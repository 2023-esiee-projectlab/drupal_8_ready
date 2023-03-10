<?php

namespace Drupal\kaltura\Plugin\media\Source;

use Drupal\Component\Utility\Crypt;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\Display\EntityFormDisplayInterface;
use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Field\FieldTypePluginManagerInterface;
use Drupal\Core\File\Exception\FileException;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Url;
use Drupal\media\IFrameUrlHelper;
use Drupal\kaltura\Kaltura\Resource;
use Drupal\kaltura\Kaltura\ResourceException;
use Drupal\media\MediaSourceBase;
use Drupal\media\MediaInterface;
use Drupal\media\MediaTypeInterface;
use Drupal\kaltura\Kaltura\ResourceFetcherInterface;
use Drupal\kaltura\Kaltura\UrlResolverInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\kaltura\Plugin\media\Source\KalturaInterface;
/**
 * Provides a media source plugin for oEmbed resources.
 *
 * For security reasons, the oEmbed source (and, therefore, anything that
 * extends it) obeys a hard-coded list of allowed third-party oEmbed providers
 * set in its plugin definition's providers array. This array is a set of
 * provider names, exactly as they appear in the canonical oEmbed provider
 * database at https://oembed.com/providers.json.
 *
 * You can implement support for additional providers by defining a new plugin
 * that uses this class. This can be done in hook_media_source_info_alter().
 * For example:
 * @code
 * <?php
 *
 * function example_media_source_info_alter(array &$sources) {
 *   $sources['artwork'] = [
 *     'id' => 'artwork',
 *     'label' => t('Artwork'),
 *     'description' => t('Use artwork from Flickr and DeviantArt.'),
 *     'allowed_field_types' => ['string'],
 *     'default_thumbnail_filename' => 'no-thumbnail.png',
 *     'providers' => ['Deviantart.com', 'Flickr'],
 *     'class' => 'Drupal\media\Plugin\media\Source\Kaltura',
 *   ];
 * }
 * @endcode
 * The "Deviantart.com" and "Flickr" provider names are specified in
 * https://oembed.com/providers.json. The
 * \Drupal\media\Plugin\media\Source\Kaltura class already knows how to handle
 * standard interactions with third-party oEmbed APIs, so there is no need to
 * define a new class which extends it. With the code above, you will able to
 * create media types which use the "Artwork" source plugin, and use those media
 * types to link to assets on Deviantart and Flickr.
 *
 * @MediaSource(
 *   id = "kaltura",
 *   label = @Translation("Kaltura source"),
 *   description = @Translation("Use kaltura URL for reusable media."),
 *   allowed_field_types = {"string_long","string"},
 *   default_thumbnail_filename = "no-thumbnail.png",
 *   deriver = "Drupal\kaltura\Plugin\media\Source\KalturaDeriver",
 *   providers = {},
 * )
 */
class Kaltura extends MediaSourceBase implements KalturaInterface {

  /**
   * The logger channel for media.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * The HTTP client.
   *
   * @var \GuzzleHttp\Client
   */
  protected $httpClient;

  /**
   * The oEmbed resource fetcher service.
   *
   * @var \Drupal\kaltura\Kaltura\ResourceFetcherInterface
   */
  protected $resourceFetcher;

  /**
   * The Kaltura manager service.
   *
   * @var \Drupal\kaltura\Kaltura\UrlResolverInterface
   */
  protected $urlResolver;

  /**
   * The iFrame URL helper service.
   *
   * @var \Drupal\media\IFrameUrlHelper
   */
  protected $iFrameUrlHelper;

  /**
   * The file system.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * Constructs a new Kaltura instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   *   The entity field manager service.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory service.
   * @param \Drupal\Core\Field\FieldTypePluginManagerInterface $field_type_manager
   *   The field type plugin manager service.
   * @param \Psr\Log\LoggerInterface $logger
   *   The logger channel for media.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   * @param \GuzzleHttp\ClientInterface $http_client
   *   The HTTP client.
   * @param \Drupal\kaltura\Kaltura\ResourceFetcherInterface $resource_fetcher
   *   The oEmbed resource fetcher service.
   * @param \Drupal\kaltura\Kaltura\UrlResolverInterface $url_resolver
   *   The oEmbed URL resolver service.
   * @param \Drupal\media\IFrameUrlHelper $iframe_url_helper
   *   The iFrame URL helper service.
   * @param \Drupal\Core\File\FileSystemInterface $file_system
   *   The file system.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, EntityFieldManagerInterface $entity_field_manager, ConfigFactoryInterface $config_factory, FieldTypePluginManagerInterface $field_type_manager, LoggerInterface $logger, MessengerInterface $messenger, ClientInterface $http_client, ResourceFetcherInterface $resource_fetcher, UrlResolverInterface $url_resolver, IFrameUrlHelper $iframe_url_helper, FileSystemInterface $file_system = NULL) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $entity_type_manager, $entity_field_manager, $field_type_manager, $config_factory);
    $this->logger = $logger;
    $this->messenger = $messenger;
    $this->configFactory = $config_factory;
    $this->httpClient = $http_client;
    $this->resourceFetcher = $resource_fetcher;
    $this->urlResolver = $url_resolver;
    $this->iFrameUrlHelper = $iframe_url_helper;
    if (!$file_system) {
      @trigger_error('The file_system service must be passed to Kaltura::__construct(), it is required before Drupal 9.0.0. See https://www.drupal.org/node/3006851.', E_USER_DEPRECATED);
      $file_system = \Drupal::service('file_system');
    }
    $this->fileSystem = $file_system;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('entity_field.manager'),
      $container->get('config.factory'),
      $container->get('plugin.manager.field.field_type'),
      $container->get('logger.factory')->get('media'),
      $container->get('messenger'),
      $container->get('http_client'),
      $container->get('kaltura.kaltura.resource_fetcher'),
      $container->get('kaltura.kaltura.url_resolver'),
      $container->get('media.oembed.iframe_url_helper'),
      $container->get('file_system')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getMetadataAttributes() {
    return [
      'type' => $this->t('Resource type'),
      'title' => $this->t('Resource title'),
      'author_name' => $this->t('The name of the author/owner'),
      'author_url' => $this->t('The URL of the author/owner'),
      'provider_name' => $this->t("The name of the provider"),
      'provider_url' => $this->t('The URL of the provider'),
      'cache_age' => $this->t('Suggested cache lifetime'),
      'default_name' => $this->t('Default name of the media item'),
      'thumbnail_uri' => $this->t('Local URI of the thumbnail'),
      'thumbnail_width' => $this->t('Thumbnail width'),
      'thumbnail_height' => $this->t('Thumbnail height'),
      'url' => $this->t('The source URL of the resource'),
      'width' => $this->t('The width of the resource'),
      'height' => $this->t('The height of the resource'),
      'html' => $this->t('The HTML representation of the resource'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getMetadata(MediaInterface $media, $name) {
    $media_url = $this->getSourceFieldValue($media);
    $config = $this->getConfiguration();
    try {
      $resource_url = $this->urlResolver->getResourceUrl($media_url);
      $resource = $this->resourceFetcher->fetchResource($resource_url);
    }
    catch (ResourceException $e) {
      $this->messenger->addError($e->getMessage());
      return NULL;
    }

    switch ($name) {
      case 'default_name':
        if ($title = $this->getMetadata($media, 'title')) {
          return $title;
        }
        elseif ($url = $this->getMetadata($media, 'url')) {
          return $url;
        }
        return parent::getMetadata($media, 'default_name');

      case 'thumbnail_uri':
        return $this->getLocalThumbnailUri($resource) ?: parent::getMetadata($media, 'thumbnail_uri');

      case 'type':
        return $resource->getType();

      case 'title':
        return $resource->getTitle();

      case 'author_name':
        return $resource->getAuthorName();

      case 'author_url':
        return $resource->getAuthorUrl();

      case 'provider_name':
        $provider = $resource->getProvider();
        return $provider ? $provider->getName() : '';

      case 'provider_url':
        $provider = $resource->getProvider();
        return $provider ? $provider->getUrl() : NULL;

      case 'cache_age':
        return $resource->getCacheMaxAge();

      case 'thumbnail_width':
        return $resource->getThumbnailWidth();

      case 'thumbnail_height':
        return $resource->getThumbnailHeight();

      case 'url':
        $url = $resource->getUrl();
        return $url ? $url->toString() : NULL;

      case 'width':
        return $resource->getWidth();

      case 'height':
        return $resource->getHeight();

      case 'html':
        return $resource->getHtml();

      default:
        break;
    }
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    $domain = $this->configFactory->get('kaltura.settings')->get('iframe_domain');
    if (!$this->iFrameUrlHelper->isSecure($domain)) {
      array_unshift($form, [
        '#markup' => '<p>' . $this->t('It is potentially insecure to display oEmbed content in a frame that is served from the same domain as your main Drupal site, as this may allow execution of third-party code. <a href=":url" target="_blank">You can specify a different domain for serving oEmbed content here</a> (opens in a new window).', [
          ':url' => Url::fromRoute('kaltura.settings')->setAbsolute()->toString(),
        ]) . '</p>',
      ]);
    }

    $form['thumbnails_directory'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Thumbnails location'),
      '#default_value' => $this->configuration['thumbnails_directory'],
      '#description' => $this->t('Thumbnails will be fetched from the provider for local usage. This is the URI of the directory where they will be placed.'),
      '#required' => TRUE,
    ];
    $form['username'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Email'),
      '#default_value' => $this->configuration['username'],
      '#description' => $this->t('The email assocated with your kaltura account'),
      '#required' => TRUE,
    ];
    $form['password'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Secret key'),
      '#default_value' => $this->configuration['password'],
      '#description' => $this->t('The secret key associated with your kaltura account'),
      '#required' => TRUE,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $form_state->setFormState(['providers'=>["kaltura"]]);
    parent::submitConfigurationForm($form, $form_state);
    $configuration = $this->getConfiguration();
    $this->configFactory->getEditable('kaltura.settings')->set('username', $form['username']['#value'])->save(TRUE);
    $this->configFactory->getEditable('kaltura.settings')->set('password', $form['password']['#value'])->save(TRUE);
    $configuration['providers'] = array_filter(array_values($configuration['providers']));
    $this->setConfiguration($configuration);
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
    $thumbnails_directory = $form_state->getValue('thumbnails_directory');
    if (!file_valid_uri($thumbnails_directory)) {
      $form_state->setErrorByName('thumbnails_directory', $this->t('@path is not a valid path.', [
        '@path' => $thumbnails_directory,
      ]));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'thumbnails_directory' => 'public://oembed_thumbnails',
      'providers' => [],
      'username' => '',
      'password' => '',
    ] + parent::defaultConfiguration();
  }

  /**
   * Returns the local URI for a resource thumbnail.
   *
   * If the thumbnail is not already locally stored, this method will attempt
   * to download it.
   *
   * @param \Drupal\kaltura\Kaltura\Resource $resource
   *   The oEmbed resource.
   *
   * @return string|null
   *   The local thumbnail URI, or NULL if it could not be downloaded, or if the
   *   resource has no thumbnail at all.
   *
   * @todo Determine whether or not oEmbed media thumbnails should be stored
   * locally at all, and if so, whether that functionality should be
   * toggle-able. See https://www.drupal.org/project/drupal/issues/2962751 for
   * more information.
   */
   protected function getLocalThumbnailUri(Resource $resource) {
     // If there is no remote thumbnail, there's nothing for us to fetch here.
     $remote_thumbnail_url = $resource->getThumbnailUrl();
     if (!$remote_thumbnail_url) {
       return NULL;
     }
     $remote_thumbnail_url = $remote_thumbnail_url->toString();

     // Compute the local thumbnail URI, regardless of whether or not it exists.
     $configuration = $this->getConfiguration();
     $directory = $configuration['thumbnails_directory'];
     $local_thumbnail_uri = "$directory/" . Crypt::hashBase64($remote_thumbnail_url) . '.' . pathinfo($remote_thumbnail_url, PATHINFO_EXTENSION);

     // If the local thumbnail already exists, return its URI.
     if (file_exists($local_thumbnail_uri)) {
       return $local_thumbnail_uri;
     }

     // The local thumbnail doesn't exist yet, so try to download it. First,
     // ensure that the destination directory is writable, and if it's not,
     // log an error and bail out.
     if (!$this->fileSystem->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS)) {
       $this->logger->warning('Could not prepare thumbnail destination directory @dir for oEmbed media.', [
         '@dir' => $directory,
       ]);
       return NULL;
     }

     try {
       $response = $this->httpClient->get($remote_thumbnail_url);
       if ($response->getStatusCode() === 200) {
         $this->fileSystem->saveData((string) $response->getBody(), $local_thumbnail_uri, FileSystemInterface::EXISTS_REPLACE);
         return $local_thumbnail_uri;
       }
     }
     catch (RequestException $e) {
       $this->logger->warning($e->getMessage());
     }
     catch (FileException $e) {
       $this->logger->warning('Could not download remote thumbnail from {url}.', [
         'url' => $remote_thumbnail_url,
       ]);
     }
     return NULL;
   }

  /**
   * {@inheritdoc}
   */
  public function attachConstraints(MediaInterface $media) {
    parent::attachConstraints($media);

    if (isset($this->configuration['source_field'])) {
      $source_field_name = $this->configuration['source_field'];
      if ($media->hasField($source_field_name)) {
        foreach ($media->get($source_field_name) as &$embed_code) {
          /** @var \Drupal\Core\TypedData\DataDefinitionInterface $typed_data */
          $typed_data = $embed_code->getDataDefinition();
          $typed_data->addConstraint('KalturaResource');
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getSourceFieldConstraints() {
    return [
      'kaltura_resource' => [],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function prepareViewDisplay(MediaTypeInterface $type, EntityViewDisplayInterface $display) {
    $display->setComponent($this->getSourceFieldDefinition($type)->getName(), [
      'type' => 'kaltura',
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function prepareFormDisplay(MediaTypeInterface $type, EntityFormDisplayInterface $display) {
    parent::prepareFormDisplay($type, $display);
    $source_field = $this->getSourceFieldDefinition($type)->getName();

    $display->setComponent($source_field, [
      'type' => 'oembed_textfield',
      'weight' => $display->getComponent($source_field)['weight'],
    ]);
    $display->removeComponent('name');
  }

  /**
   * {@inheritdoc}
   */
  public function getProviders() {
    $configuration = $this->getConfiguration();
    return $configuration['providers'] ?: $this->getPluginDefinition()['providers'];
  }

  /**
   * {@inheritdoc}
   */
  public function createSourceField(MediaTypeInterface $type) {
    $plugin_definition = $this->getPluginDefinition();

    $label = (string) $this->t('@type iframe embed', [
      '@type' => $plugin_definition['label'],
    ]);
    return parent::createSourceField($type)->set('label', $label);
  }

}

<?php

namespace Drupal\helloworld\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\helloworld\Service\HelloWorldService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ImporterController
 *
 * @package Drupal\importer\Controller
 */
class HelloWorldController extends ControllerBase {

  protected $helloWorld;

  public function __construct(HelloWorld $helloWorld) {
    $this->helloWorld = $helloWorld;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('helloworld')
    );
  }

  /**
   * Controller callback.
   *
   * @return array
   */
  public function show() {
    $hooks = $this->helloWorld->getDefinitions();
    $token = \Drupal::moduleHandler()->invokeAll('token_info');

    return [
      '#markup' => 'Hello world!',
    ];
  }

}
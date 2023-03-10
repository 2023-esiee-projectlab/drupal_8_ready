<?php

namespace Drupal\helloworld\Controller;

use Drupal\Core\Controller\ControllerBase;
//use Drupal\helloworld\Service\HelloWorldService;
use Drupal\notification_plugin\Plugin\NotificationPlugin\HelloWorld;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ImporterController
 *
 * @package Drupal\importer\Controller
 */
class helloWorldController extends ControllerBase {

  protected $helloWorld;

  public function __construct(HelloWorld $helloWorld) {
    $this->helloWorld = $helloWorld;
  }
  public function hello(): array
  {
    return array(
      '#title' => 'Hello World!',
      '#markup' => 'Content for Hello World.'
    );
  }
//  /**
//   * {@inheritdoc}
//   */
//  public static function create(ContainerInterface $container): helloWorldController
//  {
//    return new static(
//      $container->get('helloworld')
//    );
//  }

//  /**
//   * Controller callback.
//   *
//   * @return array
//   */
//  public function show() {
//    $hooks = $this->helloWorld->getDefinitions();
//    $token = \Drupal::moduleHandler()->invokeAll('token_info');
//
//    return [
//      '#markup' => 'Hello world!',
//    ];
//  }

}

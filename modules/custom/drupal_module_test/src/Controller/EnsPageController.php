<?php

namespace Drupal\drupal_module_test\Controller;

use Drupal\Core\Controller\ControllerBase;

class EnsPageController extends ControllerBase {

  public function helloWord(): array
  {

//    $val = [
//      'name' => 'World',
//    ];

    return [
      '#theme' => 'main_template',
      '#name' => 'world',
      '#title' => 'First test with the template.twig',
    ];
  }

}

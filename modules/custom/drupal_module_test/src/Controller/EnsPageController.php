<?php

namespace Drupal\drupal_module_test\Controller;

use Drupal\Core\Controller\ControllerBase;

class EnsPageController extends ControllerBase {

  public function helloWord(): array
  {
    return [
      '#markup' => 'Hello, world!',
    ];
  }

}

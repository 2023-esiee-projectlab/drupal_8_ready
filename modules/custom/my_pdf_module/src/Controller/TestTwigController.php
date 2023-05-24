<?php

namespace Drupal\my_pdf_module\Controller;

use Drupal\Core\Controller\ControllerBase;

class TestTwigController extends ControllerBase {
  public function content(): array {

    return [
      '#theme' => 'node__article_custom',
      '#test_var' => $this->t('Test Value'),
    ];

  }
}

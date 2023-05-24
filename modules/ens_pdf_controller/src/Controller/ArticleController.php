<?php

namespace Drupal\ens_pdf_controller\Controller;

use Drupal\Core\Controller\ControllerBase;

class ArticleController extends ControllerBase{


  public function page(): array
  {
      $items = [
          ['name'=>'Article 1'],
          ['name'=>'Article 2'],
          ['name'=>'Article 3'],
          ['name'=>'Article 4'],
      ];

      return [
        '#theme' => 'article_list',
        '#items' => $items,
        '#title' => 'our article list',
      ];

  }

}

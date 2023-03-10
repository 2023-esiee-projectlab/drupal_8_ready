<?php

namespace Drupal\ens_pdf_controller\Controller;

class ArticleController{


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
        '#title' => 'our aticle list',
      ];

  }

}

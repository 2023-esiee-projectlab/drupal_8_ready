<?php
namespace Drupal\pdf_manu\Controller;

use Drupal\Core\Controller\ControllerBase;

class Test extends ControllerBase{
    public function hi(){
        return array(
            '#type' => 'markup',
            '#markup' => 'Le generateur arrive bientot !'
        );
    }
}





?>
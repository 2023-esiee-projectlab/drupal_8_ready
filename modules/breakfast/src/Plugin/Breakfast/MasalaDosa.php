<?php

/**
 * @file
 * Contains \Drupal\breakfast\Plugin\Breakfast\MasalaDosa.
 */

namespace Drupal\breakfast\Plugin\Breakfast;

use Drupal\Core\Annotation\Translation;
use Drupal\Component\Annotation\Plugin;
use Drupal\breakfast\BreakfastBase;

/**
 * Adds Masala Dosa to your Breakfast menu.
 *
 *
 * @Breakfast(
 *   id = "masala_dosa",
 *   label = @Translation("Masala Dosa"),
 *   image = "https://upload.wikimedia.org/wikipedia/commons/3/34/Paper_Masala_Dosa.jpg",
 *   ingredients = {
 *     "Rice Batter",
 *     "Black lentils",
 *     "Potatoes",
 *     "Onions"
 *   }
 * )
 */
class MasalaDosa extends BreakfastBase {
  public function servedWith() {
    return array("Sambar", "Coriander Chutney");
  }
}

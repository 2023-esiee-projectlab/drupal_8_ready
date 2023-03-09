<?php

/**
 * @file
 * Contains \Drupal\breakfast\Plugin\Breakfast\Uppuma.
 */

namespace Drupal\breakfast\Plugin\Breakfast;

use Drupal\Core\Annotation\Translation;
use Drupal\Component\Annotation\Plugin;
use Drupal\breakfast\BreakfastBase;

/**
 * Uppuma anyone?
 *
 *
 * @Breakfast(
 *   id = "uppuma",
 *   label = @Translation("Uppuma"),
 *   image = "https://upload.wikimedia.org/wikipedia/commons/c/c1/Chowchowbath.jpg",
 *   ingredients = {
 *     "Semolina",
 *     "Cumin",
 *     "Green chillies",
 *     "Onions"
 *   }
 * )
 */
class Uppuma extends BreakfastBase {
  public function servedWith() {
    return array("Lemon pickle", "Coconut Chutney");
  }
}

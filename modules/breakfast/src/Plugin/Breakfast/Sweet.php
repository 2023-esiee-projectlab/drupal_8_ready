<?php

/**
 * @file
 * Contains \Drupal\breakfast\Plugin\Breakfast\Sweet.
 */

namespace Drupal\breakfast\Plugin\Breakfast;

use Drupal\Core\Annotation\Translation;
use Drupal\Component\Annotation\Plugin;
use Drupal\breakfast\BreakfastBase;

/**
 * A dessert or two whould be great!
 *
 *
 * @Breakfast(
 *   id = "sweet",
 *   deriver = "Drupal\breakfast\Plugin\Derivative\Sweets"
 * )
 */
class Sweet extends BreakfastBase {
  public function servedWith() {
    return array();
  }
}

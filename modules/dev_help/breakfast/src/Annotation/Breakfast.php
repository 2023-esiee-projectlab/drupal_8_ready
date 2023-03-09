<?php

/**
 * @file
 * Contains \Drupal\breakfast\Annotation\Breakfast.
 */

namespace Drupal\breakfast\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a custom "breakfast" annotation object.
 *
 * @Annotation
 */
class Breakfast extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The human-readable name of the breakfast.
   *
   * @ingroup plugin_translatable
   *
   * @var \Drupal\Core\Annotation\Translation
   */
  public $label;

  /**
   * A glimspe of how your breakfast looks.
   *
   * This is not provided manually, it will be added by the discovery mechanism.
   *
   * @var string
   */
  public $image;

  /**
   * An array of igredients used to make it.
   *
   * @var array
   */
  public $ingredients = array();
}

<?php

/**
 * @file
 * Provides Drupal\breakfast\BreakfastInterface
 */

namespace Drupal\breakfast;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * An interface for all Breakfast type plugins
 */
interface BreakfastInterface extends PluginInspectionInterface {

  /**
   * Return the name of yout breakfast.
   *
   * @return string
   */
  public function getName();

  /**
   * Shows a photo of the breakfast.
   *
   * @return string
   */
  public function getImage();

  /**
   * What to serve with.
   *
   * @return array
   */
  public function servedWith();
}

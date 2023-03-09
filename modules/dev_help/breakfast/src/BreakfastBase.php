<?php
/**
 * @file
 * Provides Drupal\breakfast\FlavorBase.
 */

namespace Drupal\breakfast;

use Drupal\Component\Plugin\PluginBase;

abstract class BreakfastBase extends PluginBase implements BreakfastInterface {

  public function getName() {
    return $this->pluginDefinition['label'];
  }

  public function getImage() {
    return $this->pluginDefinition['image'];
  }
}

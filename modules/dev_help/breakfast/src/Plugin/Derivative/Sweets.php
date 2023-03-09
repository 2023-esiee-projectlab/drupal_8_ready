<?php

/**
 * @file
 * Contains \Drupal\breakfast\Plugin\Derivative\Sweets.
 */

namespace Drupal\breakfast\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Component\Serialization\Yaml;

/**
 * Sweets are dynamic plugin definitions.
 */
class Sweets extends DeriverBase {

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $sweets_list = drupal_get_path('module', 'breakfast') . '/sweets.yml';
    $sweets = Yaml::decode(file_get_contents($sweets_list));

    foreach ($sweets as $key => $sweet) {
      $this->derivatives[$key] = $base_plugin_definition;
      $this->derivatives[$key] += array(
        'label' => $sweet['label'],
        'image' => $sweet['image'],
        'ingredients' => $sweet['ingredients'],
      );
    }

    return $this->derivatives;
  }
}

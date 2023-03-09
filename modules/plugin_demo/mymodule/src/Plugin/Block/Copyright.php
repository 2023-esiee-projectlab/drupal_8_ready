<?php

/**
 * @file
 * Contains \Drupal\mymodule\Plugin\Block\Copyright.
 */

namespace Drupal\mymodule\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * @Block(
 *   id = "copyright_block",
 *   admin_label = @Translation("Copyright"),
 *   category = @Translation("Custom")
 * )
 */
class Copyright extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $date = new \DateTime();

    $unit_manager = \Drupal::service('plugin.manager.unit');

    $plugins = $unit_manager->getDefinitions();
    drupal_set_message(print_r($plugins, TRUE));

    // Create a class instance through the manager.
    $feet_instance = $unit_manager->createInstance('meters');

    // Convert 12ft into meters.
    $labelValue = $feet_instance->getLabel();
    $unitValue =  $feet_instance->getUnit();

    return [
      '#markup' => t('label @labelValue ,unitValue @unitValue', [
          '@labelValue' => $labelValue,
          '@unitValue' => $unitValue,
      ]),
    ];
  }
}

<?php

/**
 * @file
 * Contains \Drupal\breakfast\BreakfastPluginManager.
 */

namespace Drupal\breakfast;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Language\LanguageManager;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * A Plugin to manage your breakfast.
 */
class BreakfastPluginManager extends DefaultPluginManager {

  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, LanguageManager $language_manager, ModuleHandlerInterface $module_handler) {
    $subdir = 'Plugin/Breakfast';

    // The name of the annotation class that contains the plugin definition.
    $plugin_definition_annotation_name = 'Drupal\breakfast\Annotation\Breakfast';

    parent::__construct($subdir, $namespaces, $module_handler, 'Drupal\breakfast\BreakfastInterface', $plugin_definition_annotation_name);

    $this->alterInfo('breakfast_info');

    $this->setCacheBackend($cache_backend, 'breakfast_choice');
  }

}

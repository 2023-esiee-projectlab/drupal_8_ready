<?php

namespace Drupal\travel_planner;

/**
 * A base class to help developers implement their own reservatin type plugins.
 *
 * This is a helper class which makes it easier for other developers to
 * implement sandwich plugins in their own modules. In SandwichBase we provide
 * some generic methods for handling tasks that are common to pretty much all
 * sandwich plugins. Thereby reducing the amount of boilerplate code required to
 * implement a sandwich plugin.
 *
 *
 * @see \Drupal\travel_planner\Annotation\ReservationType
 * @see \Drupal\travel_planner\ReservationTypeInterface
 */
use Drupal\Component\Plugin\PluginBase;

abstract class ReservationTypeBase extends PluginBase implements ReservationTypeInterface {

  public function description() {
    return $this->pluginDefinition['description'];
  }

  abstract public function displayFormFields();
}

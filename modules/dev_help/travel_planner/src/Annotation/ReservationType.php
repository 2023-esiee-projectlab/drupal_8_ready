<?php

namespace Drupal\travel_planner\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Reservation type item annotation object.
 *
 * @see \Drupal\travel_planner\Plugin\ReservationTypeManager
 * @see plugin_api
 *
 * @Annotation
 */
class ReservationType extends Plugin {
   /**
     * The description of the Reservation Type.
     *
     * @var \Drupal\Core\Annotation\Translation
     *
     * @ingroup plugin_translatable
     */
    public $description;
}

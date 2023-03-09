<?php
namespace Drupal\travel_planner;

/**
 * Defines an interface for Reservation type plugins.
 */
interface ReservationTypeInterface {
  /**
   * Provide a description of the reservation type.
   *
   * @return string
   *   A string description of the reservation type.
   */
  public function description();

  /**
   * Display the associated content types form fields.
   *
   * @return string
   *   List of associated content types form fields.
   */
  public function displayFormFields();
}

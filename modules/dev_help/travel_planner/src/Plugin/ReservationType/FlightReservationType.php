<?php

namespace Drupal\travel_planner\Plugin\ReservationType;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\travel_planner\ReservationTypeBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the flight reservation type pane.
 *
 * @ReservationType(
 *   id = "flight_reservation_type",
 *   description = @Translation("This is a flight Reservation Type.")
 * )
 */
class FlightReservationType extends ReservationTypeBase implements ContainerFactoryPluginInterface {
  // Use Drupal\Core\StringTranslation\StringTranslationTrait to define
  // $this->t() for string translations in our plugin.
  use StringTranslationTrait;
  /**
   * The day recommended to book flight reservations.
   *
   * This is the string representation of the day of the week you get from
   * date('D').
   *
   * @var string
   */
  protected $day;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $reservation = new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('string_translation')
    );
    return $reservation;
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, TranslationInterface $translation) {
    // Store the translation service.
    $this->setStringTranslation($translation);
    // Store the day so we can generate a special description on Tuesdays.
    $this->day = date('D');
    // Pass the other parameters up to the parent constructor.
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public function description() {
    // We override the description() method in order to change the description
    // text based on the date. On Sunday we only have day old bread.
    if ($this->day == 'Tues') {
      return $this->t('This is the best day to book flight reservations. '.$this->displayFormFields());
    }else {
      return $this->t('This is a booked flight reservation. '.$this->displayFormFields());
    }

    //return parent::description();
  }

  /**
   * Display form fields for associated content type.
   *
   * @return string
   *   A description of the sandwich ordered.
   */
  public function displayFormFields() {
      $formFieldDefintions = \Drupal::service('entity_field.manager')->getFieldDefinitions('node','flight_reservation');

    return $this->t(serialize($formFieldDefintions));
    //return "Display Form Fields for the Flight Reservation Type.";
  }
}

<?php
namespace Drupal\travel_planner\Controller;

use DOMDocument;
use DOMXPath;

use Drupal\Core\Controller\ControllerBase;
use Drupal\travel_planner\AcceptEmailService;
use Drupal\travel_planner\ReservationTypeManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\Component\Utility\Html;
use Drupal\Core\Url;

/**
 * Class ReservationPageController.
 */
class ReservationPageController extends ControllerBase {

  protected $tempStoreFactory;
  protected $reservationTypeManager;

  /**
  * Inject services.
  */
  public function __construct(PrivateTempStoreFactory $tempStoreFactory, ReservationTypeManager $reservationManager) {
      $this->tempStoreFactory = $tempStoreFactory;
      $this->reservationTypeManager = $reservationManager;
  }

  /**
  * Return Reservation page title
  */
  public function pageTitle($reservation) {
    return $reservation;
  }

  /**
  * Returns array index that has the specified keyword
  */
  static protected function arraySearchPartial($arr, $keyword) {
      foreach($arr as $index => $string) {
          if (strpos($string, $keyword) !== FALSE)
              return $index;
      }
  }

  /**
  * Displays the processed HTML with reservation information
  */
  public function acceptEmail() {
    $rawEmail = " ";
    $filePath = \Drupal::service('travel_planner.acceptEmail')->getEmail();
    $tempstore = $this->tempStoreFactory->get('travel_planner');

    // Create an email object
    $handle = fopen($filePath, "r");
    if ($handle) {
        while (!feof($handle)) {
            $rawEmail .= fgets($handle, 4096);
        }
        fclose($handle);
    }
    $tempstore->set('$rawEmail', $rawEmail);

    $rawStringContent = $tempstore->get('$rawEmail');

    $infoBlocks = [];
    $itineraryBlock = [];
    $flightLegs = [];

    // Retrieve DOM object
    $dom = new DOMDocument();

    // Parse the email object via HTML elements
    $dom->loadHTML($rawStringContent);
    $html = $dom->getElementsByTagName('table');
    $current = '';

    // Grab table blocks of information
    foreach ($html as $tables) {
      $infoBlocks[] = $dom->saveHTML($tables);
    }

    //Retrieve specific table with word "itinerary" within
    $flightInfo = static::arraySearchPartial($infoBlocks, 'itinerary');

    $itineraryDOM = new DOMDocument();
    $itineraryDOM->loadHTML($infoBlocks[$flightInfo]);
    $xpath = new DOMXPath($itineraryDOM);
    $legs = $itineraryDOM->getElementsByTagName('table');

    // Retrieve only the tables that have the word "flight" within it
    $count = 0;
    $nb = $legs->length;
    for($pos=1; $pos<$nb-1; $pos++) {
      $itineraryBlock[] = $itineraryDOM->saveHTML($legs[$pos]);
    }

  /*  foreach($legs as $leg){
       // Look into each leg for the flight details
       //if ($count !== 0) {
         $flightDOM = new DOMDocument();
         $flightDOM->loadHTML(serialize($leg));
         $xpath2 = new DOMXPath($flightDOM);
         $flightDetail = $xpath->query('//*[contains(text(),"Flight")]/ancestor::table');

        if ($flightDetail->length>0) {
            $count += 1;
            $itineraryBlock[] = $itineraryDOM->saveHTML($leg);
        }
       //}
    }*/

    /*$flightBlockDOM = new DOMDocument();

    foreach($flightInfoBlocks as $flightBlock){
       $flightBlockDOM->loadHTML($itineraryBlocks[$flightBlock]);
       $flightLegs[] = $flightBlockDOM->saveHTML($flightBlockDOM);
    }*/

    // Save parsed email content for content page generation
    /*$tempstore->set('processed_html', $doc);

    $processed_html = $tempstore->get('processed_hmtl');*/

    return array(
        '#type' => 'markup',
        '#markup' => t(serialize($itineraryBlock)),
      );
  }

  public function description() {
    $build = [];

    $build['intro'] = [
      '#markup' => $this->t("This page lists the reservation plugins we've created."),
    ];

    $reservation_plugin_definitions = $this->reservationTypeManager->getDefinitions();

    // Let's output a list of the plugin definitions we now have.
    $items = [];
    foreach ($reservation_plugin_definitions as $reservation_plugin_definition) {
      $items[] = $this->t("@id (description: @description)", [
        '@id' => $reservation_plugin_definition['id'],
        '@description' => $reservation_plugin_definition['description'],
      ]);
    }

    // Add our list to the render array.
    $build['plugin_definitions'] = [
      '#theme' => 'item_list',
      '#title' => 'Reservation Type plugin definitions',
      '#items' => $items,
    ];

    $flight_reservation_plugin_definition = $this->reservationTypeManager->getDefinition('flight_reservation_type');

    $items = [];
    foreach ($reservation_plugin_definitions as $plugin_id => $reservation_plugin_definition) {
      $plugin = $this->reservationTypeManager->createInstance($plugin_id, ['of' => 'configuration values']);
      $items[] = $plugin->description();
    }

    $build['plugins'] = [
      '#theme' => 'item_list',
      '#title' => 'Reservation Type plugins',
      '#items' => $items,
    ];

    return $build;
  }

  /**
   * {@inheritdoc}
   *
   * Override the parent method so that we can inject our Reservation Type plugin
   * manager service into the controller.
   *
   * @see container
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('tempstore.private'),
      $container->get('plugin.manager.reservation')
    );
  }
}

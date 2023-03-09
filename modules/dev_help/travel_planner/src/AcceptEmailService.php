<?php
namespace Drupal\travel_planner;

class AcceptEmailService {

  /**
   * Returns an email file's path
   *
   * @return string
   *   The string.
   */
  public function getEmail(){
    // Return file path of email text file
    return \Drupal::service('extension.list.module')->getPath('travel_planner'). '/src/sample-reservation-email.txt';
  }
}

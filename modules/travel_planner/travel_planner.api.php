<?php
/**
 * This hook is invoked by ReservationTypeManager::__construct().
 *
 * @param array $reservation_plugin_info
 *   This is the array of plugin definitions.
 */
function hook_reservation_info_alter(array &$reservation_plugin_info) {
  foreach ($reservation_plugin_info as $plugin_id => $plugin_definition) {
    $reservation_plugin_info[$plugin_id]['reservations'] = t('Find latest reservation plugin info.');
  }
}

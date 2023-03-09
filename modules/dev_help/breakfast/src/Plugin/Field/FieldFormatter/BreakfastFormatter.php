<?php

/**
 * @file
 * Contains Drupal\breakfast\Plugin\Field\FieldFormatter\BreakfastFormatter.
 */

namespace Drupal\breakfast\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the breakfast formatter.
 *
 * @FieldFormatter(
 *   id = "breakfast_formatter",
 *   module = "breakfast",
 *   label = @Translation("Simple breakfast formatter"),
 *   field_types = {
 *     "breakfast_choice"
 *   }
 * )
 */
class BreakfastFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items) {
    $elements = array();

    foreach ($items as $delta => $item) {
      $breakfast_item = \Drupal::service('plugin.manager.breakfast')->createInstance($item->value);
      $markup = '<h1>'. $breakfast_item->getName() . '</h1>';
      $markup .= '<img src="'. $breakfast_item->getImage() .'" width="300"/>';
      $markup .= '<h2>Goes well with:</h2>'. implode(", ", $breakfast_item->servedWith());
      $elements[$delta] = array(
        '#markup' => $markup,
      );
    }

    return $elements;
  }

}

<?php

/**
 * @file
 * Contains \Drupal\breakfast\Plugin\Field\FieldWidget\BreakfastWidget.
 */

namespace Drupal\breakfast\Plugin\Field\FieldWidget;

use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Component\Utility\String;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'field_breakfast' widget.
 *
 * @FieldWidget(
 *   id = "breakfast_select",
 *   module = "breakfast",
 *   label = @Translation("Breakfast Choice"),
 *   field_types = {
 *     "breakfast_choice"
 *   }
 * )
 */
class BreakfastWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $value = isset($items[$delta]->value) ? $items[$delta]->value : '';
    $options = array();
    $breakfast_items = \Drupal::service('plugin.manager.breakfast')->getDefinitions();
    foreach($breakfast_items as $plugin_id => $breakfast_item) {
      $options[$plugin_id] = $breakfast_item['label'];
    }

    $element = array(
      '#type' => 'select',
      '#options' => $options,
      '#default_value' => $value,
      '#multiple' => FALSE,
    );

    return array('value' => $element);
  }

  public function validate($element, FormStateInterface $form_state) {
    $value = $element['#value'];
    $form_state->setValueForElement($element, $value);
  }
}

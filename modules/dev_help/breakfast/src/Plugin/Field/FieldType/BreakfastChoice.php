<?php

/**
 * @file
 * Contains Drupal\breakfast\Plugin\Field\FieldType\BreakfastChoice.
 */

namespace Drupal\breakfast\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'breakfast_choice' field type.
 *
 * @FieldType(
 *   id = "breakfast_choice",
 *   label = @Translation("Breakfast Choice"),
 *   module = "breakfast",
 *   description = @Translation("Select what you want for breakfast."),
 *   default_widget = "breakfast_select",
 *   default_formatter = "breakfast_formatter"
 * )
 */
class BreakfastChoice extends FieldItemBase {
  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return array(
      'columns' => array(
        'value' => array(
          'type' => 'text',
          'size' => 'tiny',
          'not null' => FALSE,
        ),
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('value')->getValue();
    return $value === NULL || $value === '';
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['value'] = DataDefinition::create('string')
      ->setLabel(t('Breakfast Choice'));

    return $properties;
  }
}

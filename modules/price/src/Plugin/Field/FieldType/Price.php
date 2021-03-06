<?php

/**
 * @file
 * Contains \Drupal\commerce_price\Plugin\Field\FieldType\Price.
 */

namespace Drupal\commerce_price\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the commerce price field type.
 *
 * @FieldType(
 *   id = "price",
 *   label = @Translation("Price"),
 *   description = @Translation("Stores a decimal amount and a three letter currency code."),
 *   default_widget = "price_simple",
 *   default_formatter = "price",
 * )
 */
class Price extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $fieldDefinition) {
    $properties['amount'] = DataDefinition::create('float')
      ->setLabel(t('Amount'))
      ->setRequired(FALSE);

    $properties['currency_code'] = DataDefinition::create('string')
      ->setLabel(t('Currency code'))
      ->setRequired(FALSE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $fieldDefinition) {
    return array(
      'columns' => array(
        'amount' => array(
          'description' => 'The price amount.',
          'type' => 'numeric',
          'precision' => 19,
          'scale' => 6,
        ),
        'currency_code' => array(
          'description' => 'The currency code for the price.',
          'type' => 'varchar',
          'length' => 3,
        ),
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('amount')->getValue();
    return $value === NULL || $value === '';
  }

}

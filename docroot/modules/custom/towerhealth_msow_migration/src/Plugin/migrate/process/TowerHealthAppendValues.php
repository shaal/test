<?php

namespace Drupal\towerhealth_msow_migration\Plugin\migrate\process;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;
use Drupal\node\Entity\Node;

/**
 * Append this value onto the existing value of the field.
 *
 * @code
 * field_text:
 *   plugin: towerhealth_append_values
 *   source: text
 * @endcode
 *
 * @MigrateProcessPlugin(
 *   id = "towerhealth_append_values",
 * )
 */
class TowerHealthAppendValues extends ProcessPluginBase {
  /**
   * Current migration.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $migration;

  /**
   * The entity type for the query.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $entityType;

  /**
   * The field holding the value.
   *
   * @var string
   */
  protected $fieldName;

  /**
   * The field holding the value.
   *
   * @var string
   */
  protected $lookupField;

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    // Throw an error if value and reverse value are the same.

    $lookup_field = $this->configuration['lookup_field'];
    $field_name = $this->configuration['field'];

    $id = $row->getDestinationProperty($lookup_field);

    $query = \Drupal::entityQuery('node');
    $result = $query
      ->condition($lookup_field . '.0.value', $id, '=')
      ->execute();

    $existing = [];
    if ($node = Node::load(reset($result))) {
      $existing_values = $node->$field_name->getValue();

      foreach ($existing_values as $item) {
        $existing[] = $item['value'];
      }
    }
    if (!is_array($value)) {
      $value = [$value];
    }
    // Append the new values to existing.
    $existing = array_unique(array_merge($existing, $value));

    return $existing;
  }

  /*
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $source = $this->configuration['source'];
    $properties = is_string($source) ? [$source] : $source;

    $return = [];
    foreach ($properties as $property) {
      if ($property || (string) $property === '0') {
        $return[] = $row->get($property);
      }
      else {
        $return[] = $value;
      }
    }

    if (is_string($source)) {
      $this->multiple = is_array($return[0]);
      return $return[0];
    }
    return $return;
    // Get the identifier from migration row.
    /*$example_identifier = $row->getDestinationProperty('example_identifier');

    $existing = [];
    // Get the existing entity, if it exists.
    if ($node = Node::load($example_identifier)) {
      $existing = $node->field_example_field->getValue();
    }
    if (!is_array($newValues)) {
      $newValues = [$newValues];
    }
    // Append the new values to existing.
    $existing = array_unique(array_merge($existing, $newValues));
    return $existing;
  }*/
}
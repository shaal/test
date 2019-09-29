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
  protected $nid;

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    // Throw an error if value and reverse value are the same.
    $field_name = $this->configuration['field'];

    $id = $row->getDestinationProperty('nid');

    $existing = [];
    if ($node = Node::load($id)) {
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

}

<?php

namespace Drupal\towerhealth_msow_migration\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * Append this value onto the existing value of the field.
 *
 * @code
 * field_text:
 *   plugin: towerhealth_lookup_node
 *   lookup_field: string of field to lookup against
 *   source: value to lookup
 * @endcode
 *
 * @MigrateProcessPlugin(
 *   id = "towerhealth_lookup_node",
 * )
 */
class TowerHealthLookupNode extends ProcessPluginBase {
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
  protected $lookupField;

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    if (isset($this->configuration['lookup_field'])) {
      $lookup_field = $this->configuration['lookup_field'];
      $id = $value;

      if (!empty($id)) {
        $query = \Drupal::entityQuery('node');
        $result = $query
          ->condition($lookup_field . '.0.value', $id, '=')
          ->execute();

        return reset($result);
      }

      return NULL;
    }
  }

}

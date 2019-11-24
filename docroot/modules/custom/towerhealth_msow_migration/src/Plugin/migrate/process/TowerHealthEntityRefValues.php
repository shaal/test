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
 *   plugin: towerhealth_entity_ref_values
 *   source: text
 * @endcode
 *
 * @MigrateProcessPlugin(
 *   id = "towerhealth_entity_ref_values",
 * )
 */
class TowerHealthEntityRefValues extends ProcessPluginBase {
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
    $value = ['target_id' => $value];

    return $value;
  }

}

<?php

namespace Drupal\towerhealth_msow_migration\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * Return all the rows as a value.
 *
 * @MigrateProcessPlugin(
 *   id = "towerhealth_row_to_value",
 * )
 */
class TowerHealthRowToValue extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    return $row->getSource();
  }

}

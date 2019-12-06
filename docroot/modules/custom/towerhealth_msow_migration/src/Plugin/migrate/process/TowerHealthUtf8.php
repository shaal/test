<?php

namespace Drupal\towerhealth_msow_migration\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * Return value converted to UTF-8 encoding.
 *
 * @MigrateProcessPlugin(
 *   id = "towerhealth_utf_8",
 * )
 */
class TowerHealthUtf8 extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    return mb_convert_encoding($value, 'UTF-8', 'UTF-8');
  }

}

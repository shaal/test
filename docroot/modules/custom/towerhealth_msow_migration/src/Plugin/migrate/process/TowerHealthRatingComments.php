<?php

namespace Drupal\towerhealth_msow_migration\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * Transforms odd comment array into array needed for paragraphs.
 *
 * @see \Drupal\migrate\Plugin\MigrateProcessInterface
 *
 * @MigrateProcessPlugin(
 *   id = "towerhealth_rating_comments",
 *   handle_multiples = TRUE
 * )
 */
class TowerHealthRatingComments extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $new_values = [];

    foreach ($value as $item) {
      $new_values[] = $item['comment'];
    }

    return $new_values;
  }

}

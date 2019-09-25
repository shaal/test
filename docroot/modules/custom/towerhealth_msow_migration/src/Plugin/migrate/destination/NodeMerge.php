<?php

namespace Drupal\towerhealth_msow_migration\Plugin\migrate\destination;

use Drupal\migrate\Plugin\migrate\destination\EntityContentBase;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\Row;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides node destination, updating only the non-empty fields.
 *
 * Usage:
 *
 * destination:
 *   plugin: 'node_merge'
 *   lookup_field: 'field_location_id'
 * process:
 *   ...
 *   field_program_id: [some source value]
 *
 * Where 'field_location_id' is the drupal field name you are using to look up
 * the node, and a destination value called 'field_location_id' has the lookup
 * value you need.
 *
 * @MigrateDestination(
 *   id = "node_merge",
 * )
 *
 * @copyright Copyright (c) 2019 Palantir.net
 */
class NodeMerge extends EntityContentBase {

  /**
   * Entity type.
   *
   * @var string
   */
  public static $entityType = 'node';

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration = NULL) {
    return parent::create($container, $configuration, 'entity:' . static::$entityType, $plugin_definition, $migration);
  }

  /**
   * Gets the entity ID of the row.
   *
   * @param \Drupal\migrate\Row $row
   *   The row object.
   *
   * @return int
   *   The first matching nid, or FALSE if not found.
   */
  protected function getEntityId(Row $row) {
    if (isset($this->configuration['lookup_field'])) {
      $lookup_field = $this->configuration['lookup_field'];
      $id = $row->getDestinationProperty($lookup_field);

      $query = \Drupal::entityQuery('node');
      $result = $query
        ->condition($lookup_field . '.0.value', $id, '=')
        ->execute();

      return reset($result);
    }
  }

}

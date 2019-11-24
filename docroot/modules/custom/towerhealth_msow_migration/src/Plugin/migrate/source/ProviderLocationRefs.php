<?php

namespace Drupal\towerhealth_msow_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\towerhealth_msow_migration\Plugin\migrate\source\CSVtoJSON;
use Drupal\migrate\Row;

/**
 * Migrate source plugin for Insurance.
 *
 * @MigrateSource(
 *   id = "provider_location_refs"
 * )
 */
class ProviderLocationRefs extends CSVtoJSON {
  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration);
  }
  /**
   * List of available source fields.
   *
   * Keys are the field machine names as used in field mappings, values are
   * descriptions.
   *
   * @var array
   */
  public $fields = [
    'practioner_id' => 'Pracitioner ID',
    'location_ids' => 'Location ID',
  ];

  /**
   * List of ids.
   *
   * @var array
   */
  public $ids = [
    'practioner_id' => [
      'type' => 'string',
      'max_length' => 64,
    ],
  ];

  /**
   * Process the JSON file and convert flattened data to single record per TIN.
   */
  public function parseJson($json) {
    $data = json_decode($json);
    // Remove the header from the data file.
    unset($data[0]);

    $processed_data = [];
    foreach ($data as $row) {
      $pracitioner_id = $row[0];
      $location_id = $row[2];
      if (!isset($processed_data[$pracitioner_id])) {
        $processed_data[$pracitioner_id] = [
          'practioner_id' => $pracitioner_id,
          'location_ids' => [],
        ];
      }
      if (!in_array($location_id, $processed_data[$pracitioner_id]['location_ids'])) {
        $processed_data[$pracitioner_id]['location_ids'][] = $location_id;
      }
    }
    // Remove keys since this is confusing the migration references.
    $processed_data = array_values($processed_data);
    foreach ($processed_data as &$pracitioner_id) {
      $pracitioner_id['location_ids'] = array_values($pracitioner_id['location_ids']);
    }

    return $processed_data;
  }
}
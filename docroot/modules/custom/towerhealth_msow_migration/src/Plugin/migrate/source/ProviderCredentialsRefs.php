<?php

namespace Drupal\towerhealth_msow_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\towerhealth_msow_migration\Plugin\migrate\source\CSVtoJSON;
use Drupal\migrate\Row;

/**
 * Migrate source plugin for Insurance.
 *
 * @MigrateSource(
 *   id = "provider_credential_refs"
 * )
 */
class ProviderCredentialsRefs extends CSVtoJSON {
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
    'credentials' => 'Credentials',
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
      $id = $row[1];
      $credential_type = $row[7];
      if (!isset($processed_data[$pracitioner_id])) {
        $processed_data[$pracitioner_id] = [
          'practioner_id' => $pracitioner_id,
          'credentials' => [],
        ];
      }
      if (!in_array($id, $processed_data[$pracitioner_id]['credentials'])) {
        $processed_data[$pracitioner_id]['credentials'][] = [
          'id' => $id,
          'credential_type' => $credential_type,
        ];
      }
    }

    // Remove keys since this is confusing the migration references.
    $processed_data = array_values($processed_data);

    return $processed_data;
  }
}
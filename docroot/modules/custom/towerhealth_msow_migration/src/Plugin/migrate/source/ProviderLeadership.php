<?php

namespace Drupal\towerhealth_msow_migration\Plugin\migrate\source;

/**
 * Migrate source plugin for provider leadership titles.
 *
 * @MigrateSource(
 *   id = "provider_leadership"
 * )
 */
class ProviderLeadership extends CSVtoJSON {

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
    'job_titles' => 'Job titles',
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
   * Process the JSON file and convert flattened data.
   */
  public function parseJson($json) {
    $data = json_decode($json);
    // Remove the header from the data file.
    unset($data[0]);

    $processed_data = [];
    foreach ($data as $row) {
      $pracitioner_id = $row[0];
      $leadership_name = $row[2];
      if (!isset($processed_data[$pracitioner_id])) {
        $processed_data[$pracitioner_id] = [
          'practioner_id' => $pracitioner_id,
          'job_titles' => [],
        ];
      }
      if (!in_array($leadership_name, $processed_data[$pracitioner_id]['job_titles'])) {
        $processed_data[$pracitioner_id]['job_titles'][] = $leadership_name;
      }
    }

    // Remove keys since this is confusing the migration references.
    $processed_data = array_values($processed_data);

    return $processed_data;
  }

}

<?php

namespace Drupal\towerhealth_msow_migration\Plugin\migrate\source;

/**
 * Migrate source plugin for clinical area of interests.
 *
 * @MigrateSource(
 *   id = "provider_interests_term"
 * )
 */
class ProviderInterestsTerm extends CSVtoJSON {

  /**
   * List of available source fields.
   *
   * Keys are the field machine names as used in field mappings, values are
   * descriptions.
   *
   * @var array
   */
  public $fields = [
    'id' => 'Special interest ID',
    'interest' => 'Special Interest',
  ];

  /**
   * List of ids.
   *
   * @var array
   */
  public $ids = [
    'id' => [
      'type' => 'string',
      'max_length' => 64,
    ],
  ];

  /**
   * Process the JSON file and convert flattened data.
   */
  public function parseJson($json, $secondary_json = NULL, $third_json = NULL) {
    $data = json_decode($json);
    // Remove the header from the data file.
    unset($data[0]);

    $processed_data = [];
    foreach ($data as $row) {
      $id = $row[0];
      $interest_term = $row[1];
      if (!isset($processed_data[$id])) {
        $processed_data[$id] = [
          'interest' => $interest_term,
        ];
      }
    }

    // Remove keys since this is confusing the migration references.
    $processed_data = array_values($processed_data);

    return $processed_data;
  }

}

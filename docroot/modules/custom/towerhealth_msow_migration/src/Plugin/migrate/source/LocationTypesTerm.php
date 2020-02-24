<?php

namespace Drupal\towerhealth_msow_migration\Plugin\migrate\source;

/**
 * Migrate source plugin for hospital affiliations.
 *
 * @MigrateSource(
 *   id = "location_types_term"
 * )
 */
class LocationTypesTerm extends CSVtoJSON {

  /**
   * List of available source fields.
   *
   * Keys are the field machine names as used in field mappings, values are
   * descriptions.
   *
   * @var array
   */
  public $fields = [
    'name' => 'Name',
  ];

  /**
   * List of ids.
   *
   * @var array
   */
  public $ids = [
    'name' => [
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
      $location_type = $row[12];
      if (!isset($processed_data[$location_type]) && !empty($location_type)) {
        $processed_data[$location_type] = [
          'name' => $location_type,
        ];
      }
    }

    // Remove keys since this is confusing the migration references.
    $processed_data = array_values($processed_data);

    return $processed_data;
  }

}

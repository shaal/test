<?php

namespace Drupal\towerhealth_msow_migration\Plugin\migrate\source;

/**
 * Migrate source plugin for hospital affiliations.
 *
 * @MigrateSource(
 *   id = "hospital_affiliation_term"
 * )
 */
class HospitalAffiliationsTerm extends CSVtoJSON {

  /**
   * List of available source fields.
   *
   * Keys are the field machine names as used in field mappings, values are
   * descriptions.
   *
   * @var array
   */
  public $fields = [
    'fac_code' => 'Fac code',
    'name' => 'Name',
    'location_id' => 'Location Id',
  ];

  /**
   * List of ids.
   *
   * @var array
   */
  public $ids = [
    'fac_code' => [
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

    $processed_data = [];
    foreach ($data as $row) {
      $name = $row[0];
      $fac_code = $row[1];
      $location_id = $row[2];
      if (!isset($processed_data[$fac_code])) {
        $processed_data[$fac_code] = [
          'fac_code' => $fac_code,
          'name' => $name,
          'location_id' => $location_id,
        ];
      }
    }

    // Remove keys since this is confusing the migration references.
    $processed_data = array_values($processed_data);

    return $processed_data;
  }

}

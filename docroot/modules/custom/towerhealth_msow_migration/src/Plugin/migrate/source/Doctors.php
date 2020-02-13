<?php

namespace Drupal\towerhealth_msow_migration\Plugin\migrate\source;

/**
 * Migrate source plugin for provider location hours.
 *
 * @MigrateSource(
 *   id = "doctors"
 * )
 */
class Doctors extends CSVtoJSON {

  /**
   * List of available source fields.
   *
   * Keys are the field machine names as used in field mappings, values are
   * descriptions.
   *
   * @var array
   */
  public $fields = [
    'practioner_id' => 'Practioner ID',
    'last_name' => 'Last name',
    'first_name' => 'First name',
    'middle_name' => 'Middle name',
    'professional_title' => 'Professional Title',
    'gender' => 'Gender',
    'app' => 'APP',
    'employment_status' => 'Employment Status',
    'npi_id' => 'NPI ID',
    'epic_id' => 'Epic_Provider_Number',
    'open_scheduling' => 'Open scheduling',
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
  public function parseJson($json, $secondary_json = NULL, $third_json = NULL) {
    $data = json_decode($json);
    // Remove the header from the data file.
    unset($data[0]);

    $processed_data = [];
    foreach ($data as $row) {
      $pracitioner_id = $row[0];

      if (!isset($processed_data[$pracitioner_id])) {
        $processed_data[$pracitioner_id] = [
          'practioner_id' => $pracitioner_id,
          'last_name' => $row[1],
          'first_name' => $row[2],
          'middle_name' => $row[3],
          'professional_title' => $row[4],
          'gender' => $row[5],
          'app' => $row[6],
          'employment_status' => $row[7],
          'npi_id' => $row[8],
          'epic_id' => $row[9],
          'open_scheduling' => $row[10],
        ];
      }
    }

    // Remove keys since this is confusing the migration references.
    $processed_data = array_values($processed_data);

    return $processed_data;
  }

}

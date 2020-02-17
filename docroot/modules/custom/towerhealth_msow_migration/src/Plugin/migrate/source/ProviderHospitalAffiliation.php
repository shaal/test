<?php

namespace Drupal\towerhealth_msow_migration\Plugin\migrate\source;

/**
 * Migrate source plugin for provider leadership titles.
 *
 * @MigrateSource(
 *   id = "provider_hospital_aff"
 * )
 */
class ProviderHospitalAffiliation extends CSVtoJSON {

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
    'fac_code' => 'Facility Code',
    'status' => 'Status',
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
      $fac_code = $row[1];
      $status = $row[2];
      if ($status == 'Active') {
        if (!isset($processed_data[$pracitioner_id])) {
          $processed_data[$pracitioner_id] = [
            'practioner_id' => $pracitioner_id,
            'fac_codes' => [],
          ];
        }
        if (!in_array($fac_code, $processed_data[$pracitioner_id]['fac_codes'])) {
          $processed_data[$pracitioner_id]['fac_codes'][] = $fac_code;
        }
      }
    }

    // Remove keys since this is confusing the migration references.
    $processed_data = array_values($processed_data);

    return $processed_data;
  }

}

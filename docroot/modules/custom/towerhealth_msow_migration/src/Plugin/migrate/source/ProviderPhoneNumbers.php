<?php

namespace Drupal\towerhealth_msow_migration\Plugin\migrate\source;

/**
 * Migrate source plugin for provider leadership titles.
 *
 * @MigrateSource(
 *   id = "provider_phone_numbers"
 * )
 */
class ProviderPhoneNumbers extends CSVtoJSON {

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
    'phone_number' => 'Phone number'
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
  public function parseJson($json, $secondary_json = NULL) {
    $data = json_decode($json);
    $office_data = json_decode($secondary_json);

    // Remove the header from the data file.
    unset($data[0]);
    unset($office_data[0]);

    $processed_data = [];
    $processed_office_data = [];

    // Sort through the offices to find the primary location for a provider.
    foreach ($office_data as $office_row) {
      $id = $office_row[1];
      $desigination = $office_row[2];

      if ($desigination === 'Primary' && !isset($processed_office_data[$id])) {
        $processed_office_data[$id] = [
          'office_id' => $id,
        ];
      }
    }

    // Find the phone number for the primary location.
    foreach ($data as $row) {
      $pracitioner_id = $row[0];
      $pracitioner_officer_id = $row[1];
      $phone_number = $row[3];

      if (!isset($processed_office_data[$pracitioner_officer_id]) && !isset($processed_office_data[$pracitioner_id]) && !empty($phone_number)) {
        $processed_data[$pracitioner_id] = [
          'practioner_id' => $pracitioner_id,
          'phone_number' => $phone_number,
        ];
      }
    }

    // Remove keys since this is confusing the migration references.
    $processed_data = array_values($processed_data);

    return $processed_data;
  }

}

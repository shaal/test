<?php

namespace Drupal\towerhealth_msow_migration\Plugin\migrate\source;

/**
 * Migrate source plugin for provider leadership titles.
 *
 * @MigrateSource(
 *   id = "provider_insurance_provider_group"
 * )
 */
class ProviderInsuranceProviderGroup extends CSVtoJSON {

  /**
   * List of available source fields.
   *
   * Keys are the field machine names as used in field mappings, values are
   * descriptions.
   *
   * @var array
   */
  public $fields = [
    'practioner_id' => 'Practioner Id',
    'insurance_groups' => 'Insurance Groups',
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
      $insurance_name = trim($row[2]);
      $insurance_group = 'DIV1';

      if (strpos($insurance_name, 'DIV') === 0) {
        $insurance_group = substr($insurance_name, 0, '4');
      }

      if (!isset($processed_data[$pracitioner_id])) {
        $processed_data[$pracitioner_id] = [
          'practioner_id' => $pracitioner_id,
          'insurance_groups' => [],
        ];
      }

      if (!in_array($insurance_group, $processed_data[$pracitioner_id]['insurance_groups'])) {
        $processed_data[$pracitioner_id]['insurance_groups'][] = $insurance_group;
      }
    }
    // Remove keys since this is confusing the migration references.
    $processed_data = array_values($processed_data);
    foreach ($processed_data as &$pracitioner_id) {
      $pracitioner_id['insurance_groups'] = array_values($pracitioner_id['insurance_groups']);
    }

    return $processed_data;
  }

}

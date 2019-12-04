<?php

namespace Drupal\towerhealth_msow_migration\Plugin\migrate\source;

/**
 * Migrate source plugin for provider leadership titles.
 *
 * @MigrateSource(
 *   id = "provider_insurance_group_terms"
 * )
 */
class ProviderInsuranceGroupTerms extends CSVtoJSON {

  /**
   * List of available source fields.
   *
   * Keys are the field machine names as used in field mappings, values are
   * descriptions.
   *
   * @var array
   */
  public $fields = [
    'insurance_group' => 'Insurance Groups',
    'insurance_companies' => 'Insurance Companies'
  ];

  /**
   * List of ids.
   *
   * @var array
   */
  public $ids = [
    'insurance_group' => [
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
      $insurance_name = trim($row[2]);
      $insurance_group = 'DIV1';
      $insurance_company = '';

      if (strpos($insurance_name, 'DIV') === 0) {
        $insurance_group = substr($insurance_name, 0, '4');
        $insurance_company = substr($insurance_name, 7);
      }
      else {
        $insurance_company = $insurance_name;
      }

      if (!isset($processed_data[$insurance_group])) {
        $processed_data[$insurance_group] = [
          'insurance_group' => $insurance_group,
          'insurance_companies' => [],
        ];
      }

      if (!in_array($insurance_company, $processed_data[$insurance_group]['insurance_companies'])) {
        $processed_data[$insurance_group]['insurance_companies'][] = $insurance_company;
      }
    }
    // Remove keys since this is confusing the migration references.
    $processed_data = array_values($processed_data);
    foreach ($processed_data as &$insurance_group) {
      $insurance_group['insurance_companies'] = array_values($insurance_group['insurance_companies']);
    }

    return $processed_data;
  }

}

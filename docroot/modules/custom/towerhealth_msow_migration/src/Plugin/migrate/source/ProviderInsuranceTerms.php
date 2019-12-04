<?php

namespace Drupal\towerhealth_msow_migration\Plugin\migrate\source;

/**
 * Migrate source plugin for provider leadership titles.
 *
 * @MigrateSource(
 *   id = "provider_insurance_terms"
 * )
 */
class ProviderInsuranceTerms extends CSVtoJSON {

  /**
   * List of available source fields.
   *
   * Keys are the field machine names as used in field mappings, values are
   * descriptions.
   *
   * @var array
   */
  public $fields = [
    'insurance_company' => 'Insurance Company',
  ];

  /**
   * List of ids.
   *
   * @var array
   */
  public $ids = [
    'insurance_company' => [
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
      $insurance_company = trim($row[2]);
      if (strpos($insurance_company, 'DIV') === 0) {
        $insurance_company = substr($insurance_company, 7);
      }
      if (!isset($processed_data[$insurance_company])) {
        $processed_data[$insurance_company] = [
          'insurance_company' => $insurance_company,
        ];
      }
    }

    // Remove keys since this is confusing the migration references.
    $processed_data = array_values($processed_data);

    return $processed_data;
  }

}

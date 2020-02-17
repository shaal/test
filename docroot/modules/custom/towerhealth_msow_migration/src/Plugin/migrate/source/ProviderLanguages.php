<?php

namespace Drupal\towerhealth_msow_migration\Plugin\migrate\source;

/**
 * Migrate source plugin for languages.
 *
 * @MigrateSource(
 *   id = "provider_languages"
 * )
 */
class ProviderLanguages extends CSVtoJSON {

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
    'languages' => 'Languages',
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
      $language = $row[1];
      if (!empty($language) && strtolower($language) !== 'english') {
        if (!isset($processed_data[$pracitioner_id])) {
          $processed_data[$pracitioner_id] = [
            'practioner_id' => $pracitioner_id,
            'languages' => [],
          ];
        }
        if (!in_array($language, $processed_data[$pracitioner_id]['languages'])) {
          $processed_data[$pracitioner_id]['languages'][] = $language;
        }
      }
    }
    // Remove keys since this is confusing the migration references.
    $processed_data = array_values($processed_data);
    foreach ($processed_data as &$pracitioner_id) {
      $pracitioner_id['languages'] = array_values($pracitioner_id['languages']);
    }

    return $processed_data;
  }

}

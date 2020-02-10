<?php

namespace Drupal\towerhealth_msow_migration\Plugin\migrate\source;

/**
 * Migrate source plugin for provider leadership titles.
 *
 * @MigrateSource(
 *   id = "provider_specialties_refs"
 * )
 */
class ProviderSpecialtiesRefs extends CSVtoJSON {

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
    'specialty_term' => 'Specialty term',
    'board_certified' => 'Board certified',
    'board_elligble' => 'Board elligible',
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
      $specialty_term = $row[1];
      $board_name = $row[5];
      $document_name = $row[12];
      $certified_year = $row[6];

      if (!isset($processed_data[$pracitioner_id]) && ($document_name == 'Board Specialties' || $document_name == 'Board Pending')) {
        $processed_data[$pracitioner_id] = [
          'practioner_id' => $pracitioner_id,
          'specialty_terms' => [],
          'board_certified' => [],
        ];
      }
      if (is_array($processed_data[$pracitioner_id]['specialty_terms']) && !in_array($specialty_term, $processed_data[$pracitioner_id]['specialty_terms'])) {
        $processed_data[$pracitioner_id]['specialty_terms'][] = $specialty_term;

        $this->boardPendingDataAlter($processed_data, $pracitioner_id, $document_name, $board_name, $certified_year);
      }
    }

    // Remove keys since this is confusing the migration references.
    $processed_data = array_values($processed_data);

    return $processed_data;
  }

  /**
   * Add the board name to the data array.
   *
   * @param array $processed_data
   *   The data array to work on.
   * @param string $pracitioner_id
   *   The provider's id.
   * @param string $document_name
   *   Is this pending or certified.
   * @param string $board_name
   *   The name of the board.
   * @param string $certified_year
   *   The year the provider was certified.
   *
   * @return mixed
   *   THe processed array with board data added.
   */
  private function boardPendingDataAlter(array &$processed_data, $pracitioner_id, $document_name, $board_name, $certified_year) {
    $specialty_term_board = '';

    if ($document_name == 'Board Specialties') {
      $specialty_term_board = $board_name;
    }
    elseif ($document_name == 'Board Pending') {
      $certified_year = $certified_year ? ', ' . $certified_year : '';
      $specialty_term_board = $board_name . ' <span>' . t('Board Elligible') . $certified_year . '</span>';
    }

    if (!in_array($specialty_term_board, $processed_data[$pracitioner_id]['board_certified'])) {
      $processed_data[$pracitioner_id]['board_certified'][] = $specialty_term_board;
    }

    return $processed_data;
  }

}

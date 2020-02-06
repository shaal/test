<?php

namespace Drupal\towerhealth_msow_migration\Plugin\migrate\source;

/**
 * Migrate source plugin for provider leadership titles.
 *
 * @MigrateSource(
 *   id = "locations"
 * )
 */
class Locations extends CSVtoJSON {

  /**
   * List of available source fields.
   *
   * Keys are the field machine names as used in field mappings, values are
   * descriptions.
   *
   * @var array
   */
  public $fields = [
    'location_id' => 'Location ID',
    'office_name' => 'Title',
    'address_1' => 'Address 1',
    'address_2' => 'Address 2',
    'city' => 'City',
    'state' => 'State',
    'zip_code' => 'Zip_Code',
    'phone_number' => 'Phone number',
    'office_contact' => 'Office Contact',
    'handicap_access' => 'Handicap Access',
    'fax_number' => 'Fax number',
  ];

  /**
   * List of ids.
   *
   * @var array
   */
  public $ids = [
    'location_id' => [
      'type' => 'string',
      'max_length' => 64,
    ],
  ];

  /**
   * @param $practitioner_offices
   */
  public function processPractionOfficeData($practitioner_offices) {
    $processed_offices_data = [];

    if (!is_array($practitioner_offices)) {
      return $processed_offices_data;
    }

    // Sort through the offices to find the primary location for a provider.
    foreach ($practitioner_offices as $office_row) {
      $pracitioner_officer_id = $office_row[1];
      $location_id = $office_row[2];

      if (!isset($processed_offices_data[$location_id])) {
        $processed_offices_data[$location_id] = [
          'location_id' => $location_id,
          'practitioner_office_ids' => [],
        ];
        if (!in_array($pracitioner_officer_id, $processed_offices_data[$location_id]['practitioner_office_ids'])) {
          $processed_offices_data[$location_id]['practitioner_office_ids'][] = $pracitioner_officer_id;
        }
      }
    }

    return $processed_offices_data;
  }

  /**
   * @param $processed_offices_data
   * @param $practitioner_offices_desig
   */
  public function processLocationData($processed_offices_data, $processed_practioner_offices) {
    $processed_location_ids = [];

    if (!is_array($processed_offices_data) || !is_array($processed_practioner_offices)) {
      return $processed_location_ids;
    }

    /**
     * Based on the practioner office id determine the office location ids
     * that are in the practioner offices designation data.
     * If present then this location is a primary or secondary location
     * and should be migrated as a distinct location.
     */
    foreach($processed_offices_data as $proccessed_office) {
      $location_id = $proccessed_office['location_id'];
      foreach($proccessed_office['practitioner_office_ids'] as $practitioner_office_id) {
        if (in_array($practitioner_office_id, $processed_practioner_offices) && !in_array($location_id, $processed_location_ids)) {
          $processed_location_ids[] = $location_id;
        }
      }
    }

    return $processed_location_ids;
  }

  /**
   * @param $practitioner_offices_desig
   */
  public function processOfficeDesigData($practitioner_offices_desig) {
    $processed_practioner_offices = [];

    foreach($practitioner_offices_desig as $pracitioner_office) {
      $processed_practioner_offices[] = $pracitioner_office[1];
    }

    return $processed_practioner_offices;
  }

  /**
   * Process the JSON file and convert flattened data.
   */
  public function parseJson($json, $secondary_json = NULL, $third_json = NULL) {
    $offices = json_decode($json);
    $practitioner_offices = json_decode($secondary_json);
    $practitioner_offices_desig = json_decode($third_json);

    // Remove the header from the data file.
    unset($offices[0]);
    unset($practitioner_offices[0]);
    unset($practitioner_offices_desig[0]);

    $processed_data = [];
    $processed_practioner_offices = $this->processOfficeDesigData($practitioner_offices_desig);
    $processed_offices_data = $this->processPractionOfficeData($practitioner_offices);

    if (empty($processed_offices_data)) {
      return $processed_data;
    }

    $processed_location_ids = $this->processLocationData($processed_offices_data, $processed_practioner_offices);

    foreach ($offices as $office) {
      $location_id = $office[0];

      if (in_array($location_id, $processed_location_ids) && is_integer($location_id) && !array_key_exists($location_id, $processed_data)) {
        $processed_data[$location_id] = [
          'location_id' => $location_id,
          'office_name' => $office[1],
          'address_1' => $office[2],
          'address_2' => $office[3],
          'city' => $office[4],
          'state' => $office[5],
          'zip_code' => $office[6],
          'phone_number' => $office[7],
          'office_contact' => $office[8],
          'handicap_access' => $office[9],
          'fax_number' => $office[10],
        ];
      }
    }

    return $processed_data;
  }

}

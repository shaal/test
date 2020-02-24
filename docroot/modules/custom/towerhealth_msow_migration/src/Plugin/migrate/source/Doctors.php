<?php

namespace Drupal\towerhealth_msow_migration\Plugin\migrate\source;

use ArrayIterator;
use Drupal\migrate\MigrateException;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;

/**
 * Migrate source plugin for provider location hours.
 *
 * @MigrateSource(
 *   id = "doctors"
 * )
 */
class Doctors extends SourcePluginBase {

  /**
   * Data obtained from the JSON file.
   *
   * @var array[]
   *   Array of data rows, each one an array of values keyed by field names.
   */
  public $dataRows = [];

  /**
   * Encoded json files to be used to build the entire doctor profile.
   *
   * @var array[]
   *   Each array item should contain the encoded json source.
   */
  public $encodedJson = [];

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
    'msow_ids' => 'Msow Ids',
    'insurance_groups' => 'Insurance Groups',
    'specialty_term' => 'Specialty term',
    'board_certified' => 'Board certified',
    'board_elligble' => 'Board elligible',
    'job_titles' => 'Job titles',
    'languages' => 'Languages',
    'fac_code' => 'Facility Code',
    'status' => 'Status',
    'location_ids' => 'Location ID',
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
   * {@inheritdoc}
   *
   * @throws \InvalidArgumentException
   * @throws \Drupal\migrate\MigrateException
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration);

    /*// Path is required.
    if (empty($this->configuration['path'])) {
    throw new MigrateException('You must declare the "path" to the source CSV file in your source settings.');
    }*/

    $this->encodedJson['doctors'] = $this->encodeJsonCsv($this->configuration['doctors_path']);
    $this->encodedJson['credentials'] = $this->encodeJsonCsv($this->configuration['credentials_path']);
    $this->encodedJson['insurance'] = $this->encodeJsonCsv($this->configuration['insurance_path']);
    $this->encodedJson['special_interests'] = $this->encodeJsonCsv($this->configuration['special_interests_path']);
    $this->encodedJson['leadership'] = $this->encodeJsonCsv($this->configuration['leadership_path']);
    $this->encodedJson['specialties'] = $this->encodeJsonCsv($this->configuration['specialties_path']);
    $this->encodedJson['languages'] = $this->encodeJsonCsv($this->configuration['languages_path']);
    $this->encodedJson['locations'] = $this->encodeJsonCsv($this->configuration['locations_path']);
    $this->encodedJson['hospital_affiliations'] = $this->encodeJsonCsv($this->configuration['hospital_affiliation_path']);

    $this->dataRows = $this->parseJSON($this->encodedJson);
  }

  /**
   * Return a string representing the source query.
   *
   * @return string
   *   The file path.
   */
  public function __toString() {
    return $this->configuration['file'];
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\migrate\MigrateException
   * @throws \League\Csv\Exception
   */
  public function initializeIterator() {
    return new ArrayIterator($this->dataRows);
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return $this->ids;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    return $this->fields;
  }

  /**
   * Open file from CSV and encode to JSON.
   */
  public function encodeJsonCsv($path) {
    $file = fopen($path, 'r');

    // Setup a PHP array to hold our CSV rows.
    $csv_data = [];

    // Go through rows in our CSV file and add them to array.
    while (($row = fgetcsv($file, 0, "|")) !== FALSE) {
      // Cleanup non-utf8 characters.
      $csv_data[] = mb_convert_encoding($row, 'UTF-8', 'UTF-8');
    }

    $json = json_encode($csv_data);

    // If JSON encoding fails throw an exception.
    if (!$json) {
      throw new MigrateException(json_last_error_msg());
    }

    return $json;
  }

  /**
   * Process the JSON file and convert flattened data.
   */
  public function parseJson($encodedJson) {
    $processed_data = [];

    $processed_data = $this->parseDoctors($processed_data, $encodedJson['doctors']);
    $processed_data = $this->parseCredentials($processed_data, $encodedJson['credentials']);
    $processed_data = $this->parseInsurance($processed_data, $encodedJson['insurance']);
    $processed_data = $this->parseSpecialInterests($processed_data, $encodedJson['special_interests']);
    $processed_data = $this->parseSpecialties($processed_data, $encodedJson['specialties']);
    $processed_data = $this->parseLanguages($processed_data, $encodedJson['languages']);
    $processed_data = $this->parseLeadership($processed_data, $encodedJson['leadership']);
    $processed_data = $this->parseLocations($processed_data, $encodedJson['locations']);
    $processed_data = $this->parseHospitalAffiliations($processed_data, $encodedJson['hospital_affiliations']);

    return $processed_data;
  }

  /**
   * Process the Doctors file and convert flattened data.
   */
  public function parseDoctors($processed_data, $doctors) {
    if (empty($doctors)) {
      return $processed_data;
    }

    $data = json_decode($doctors);
    // Remove the header from the data file.
    unset($data[0]);

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

    return $processed_data;
  }

  /**
   * Process the Credentials file and convert flattened data.
   */
  public function parseCredentials($processed_data, $credentials) {
    if (empty($credentials)) {
      return $processed_data;
    }

    $data = json_decode($credentials);
    // Remove the header from the data file.
    unset($data[0]);

    foreach ($data as $row) {
      $pracitioner_id = $row[0];
      $id = $row[1];
      $credential_type = $row[7];

      if (isset($processed_data[$pracitioner_id])) {
        if (!array_key_exists('credentials', $processed_data[$pracitioner_id])) {
          $processed_data[$pracitioner_id]['credentials'] = [];
        }

        if (!in_array($id, $processed_data[$pracitioner_id]['credentials'])) {
          $processed_data[$pracitioner_id]['credentials'][] = [
            'id' => $id,
            'credential_type' => $credential_type,
          ];
        }
      }
    }

    return $processed_data;
  }

  /**
   * Process the Insurance file and convert flattened data.
   */
  public function parseInsurance($processed_data, $insurance) {
    if (empty($insurance)) {
      return $processed_data;
    }

    $data = json_decode($insurance);

    // Remove the header from the data file.
    unset($data[0]);

    foreach ($data as $row) {
      $pracitioner_id = $row[0];
      $insurance_name = trim($row[2]);
      $insurance_group = 'DIV1';

      if (strpos($insurance_name, 'DIV') === 0) {
        $insurance_group = substr($insurance_name, 0, '4');
      }

      if (isset($processed_data[$pracitioner_id])) {
        if (!array_key_exists('insurance_groups', $processed_data[$pracitioner_id])) {
          $processed_data[$pracitioner_id]['insurance_groups'] = [];
        }

        if (!in_array($insurance_group, $processed_data[$pracitioner_id]['insurance_groups'])) {
          $processed_data[$pracitioner_id]['insurance_groups'][] = $insurance_group;
          dump($insurance_group);
        }
      }
    }

    return $processed_data;
  }

  /**
   * Process the Special Interests file and convert flattened data.
   */
  public function parseSpecialInterests($processed_data, $special_interests) {
    if (empty($special_interests)) {
      return $processed_data;
    }

    $data = json_decode($special_interests);
    // Remove the header from the data file.
    unset($data[0]);

    foreach ($data as $row) {
      $pracitioner_id = $row[0];
      $msow_id = $row[1];

      if (isset($processed_data[$pracitioner_id])) {
        if (!array_key_exists('msow_ids', $processed_data[$pracitioner_id])) {
          $processed_data[$pracitioner_id]['msow_ids'] = [];
        }

        if (!in_array($msow_id, $processed_data[$pracitioner_id]['msow_ids'])) {
          $processed_data[$pracitioner_id]['msow_ids'][] = $msow_id;
        }
      }
    }

    return $processed_data;
  }

  /**
   * Process the JSON file and convert flattened data.
   */
  public function parseSpecialties($processed_data, $specialties) {
    if (empty($specialties)) {
      return $processed_data;
    }

    $data = json_decode($specialties);
    // Remove the header from the data file.
    unset($data[0]);

    foreach ($data as $row) {
      $pracitioner_id = $row[0];
      $specialty_term = $row[1];
      $board_name = $row[5];
      $document_name = $row[12];
      $certified_year = $row[6];

      if (isset($processed_data[$pracitioner_id])) {
        $processed_data = $this->processSpecialties($processed_data, $pracitioner_id, $specialty_term, $board_name, $document_name, $certified_year);
      }
    }

    return $processed_data;
  }

  /**
   * Process specialties.
   */
  private function processSpecialties($processed_data, $pracitioner_id, $specialty_term, $board_name, $document_name, $certified_year) {
    if ($document_name == 'Board Specialties' || $document_name == 'Board Pending') {
      if (!array_key_exists('specialty_terms', $processed_data[$pracitioner_id])) {
        $processed_data[$pracitioner_id]['specialty_terms'] = [];
      }

      if (!empty($specialty_term) && is_array($processed_data[$pracitioner_id]['specialty_terms']) && !in_array($specialty_term, $processed_data[$pracitioner_id]['specialty_terms'])) {
        $processed_data[$pracitioner_id]['specialty_terms'][] = $specialty_term;

        $this->boardPendingDataAlter($processed_data, $pracitioner_id, $document_name, $board_name, $certified_year);
      }
    }

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

    if (!array_key_exists('board_certified', $processed_data[$pracitioner_id])) {
      $processed_data[$pracitioner_id]['board_certified'] = [];
    }

    if (!in_array($specialty_term_board, $processed_data[$pracitioner_id]['board_certified'])) {
      $processed_data[$pracitioner_id]['board_certified'][] = $specialty_term_board;
    }

    return $processed_data;
  }

  /**
   * Process the Languages file and convert flattened data.
   */
  public function parseLanguages($processed_data, $languages) {
    if (empty($languages)) {
      return $processed_data;
    }

    $data = json_decode($languages);
    // Remove the header from the data file.
    unset($data[0]);

    foreach ($data as $row) {
      $pracitioner_id = $row[0];
      $language = $row[1];
      if (!empty($language) && strtolower($language) !== 'english' && isset($processed_data[$pracitioner_id])) {
        if (!array_key_exists('languages', $processed_data[$pracitioner_id])) {
          $processed_data[$pracitioner_id]['languages'] = [];
        }

        if (!in_array($language, $processed_data[$pracitioner_id]['languages'])) {
          $processed_data[$pracitioner_id]['languages'][] = $language;
        }
      }
    }

    return $processed_data;
  }

  /**
   * Process the JSON file and convert flattened data.
   */
  public function parseLeadership($processed_data, $leadership) {
    if (empty($leadership)) {
      return $processed_data;
    }

    $data = json_decode($leadership);
    // Remove the header from the data file.
    unset($data[0]);

    foreach ($data as $row) {
      $pracitioner_id = $row[0];
      $leadership_name = $row[2];
      if (isset($processed_data[$pracitioner_id])) {
        if (!array_key_exists('job_titles', $processed_data[$pracitioner_id])) {
          $processed_data[$pracitioner_id]['job_titles'] = [];
        }

        if (!in_array($leadership_name, $processed_data[$pracitioner_id]['job_titles'])) {
          $processed_data[$pracitioner_id]['job_titles'][] = $leadership_name;
        }
      }
    }

    return $processed_data;
  }

  /**
   * Process the JSON file and convert flattened data.
   */
  public function parseHospitalAffiliations($processed_data, $hospital_affiliations) {
    if (empty($hospital_affiliations)) {
      return $processed_data;
    }

    $data = json_decode($hospital_affiliations);
    // Remove the header from the data file.
    unset($data[0]);

    foreach ($data as $row) {
      $pracitioner_id = $row[0];
      $fac_code = $row[1];
      $status = $row[2];
      if ($status == 'Active' && isset($processed_data[$pracitioner_id])) {
        if (!array_key_exists('fac_codes', $processed_data[$pracitioner_id])) {
          $processed_data[$pracitioner_id]['fac_codes'] = [];
        }

        if (!in_array($fac_code, $processed_data[$pracitioner_id]['fac_codes'])) {
          $processed_data[$pracitioner_id]['fac_codes'][] = $fac_code;
        }
      }
    }

    return $processed_data;
  }

  /**
   * Process the Locations file and convert flattened data.
   */
  public function parseLocations($processed_data, $locations) {
    if (empty($locations)) {
      return $processed_data;
    }

    $data = json_decode($locations);
    // Remove the header from the data file.
    unset($data[0]);

    foreach ($data as $row) {
      $pracitioner_id = $row[0];
      $location_id = $row[2];
      if (isset($processed_data[$pracitioner_id])) {
        if (!array_key_exists('location_ids', $processed_data[$pracitioner_id])) {
          $processed_data[$pracitioner_id]['location_ids'] = [];
        }

        if (!in_array($location_id, $processed_data[$pracitioner_id]['location_ids'])) {
          $processed_data[$pracitioner_id]['location_ids'][] = $location_id;
        }
      }
    }

    return $processed_data;
  }

}

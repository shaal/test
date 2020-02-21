<?php

namespace Drupal\towerhealth_msow_migration\Plugin\migrate\source;

use ArrayIterator;
use Drupal\migrate\MigrateException;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;

/**
 * Source for converting CSV files to JSON.
 *
 * @MigrateSource(
 *   id = "locations",
 *   source_module = "towerhealth_msow_migration"
 * )
 */
class Locations extends SourcePluginBase {

  /**
   * Data obtained from the JSON file.
   *
   * @var array[]
   *   Array of data rows, each one an array of values keyed by field names.
   */
  public $dataRows = [];

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
    'hours' => 'Hours',
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
   * Encoded json files to be used to build the entire doctor profile.
   *
   * @var array[]
   *   Each array item should contain the encoded json source.
   */
  public $encodedJson = [];

  /**
   * {@inheritdoc}
   *
   * @throws \InvalidArgumentException
   * @throws \Drupal\migrate\MigrateException
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration);

    $this->encodedJson['offices_json'] = $this->encodeJsonCsv($this->configuration['offices_path']);
    $this->encodedJson['practioner_offices_json'] = $this->encodeJsonCsv($this->configuration['practioner_offices_path']);
    $this->encodedJson['office_desig_json'] = $this->encodeJsonCsv($this->configuration['office_desig_path']);
    $this->encodedJson['office_hours_json'] = $this->encodeJsonCsv($this->configuration['office_hours_path']);

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
  public function parseOfficeHours($processed_data, $data) {
    if (empty($data) || !is_array($data)) {
      return $processed_data;
    }

    // Remove the header from the data file.
    unset($data[0]);

    foreach ($data as $row) {
      $office_record_no = $row[1];

      if (isset($processed_data[$office_record_no])) {
        $processed_data = $this->processOfficeHours($processed_data, $row);
      }
    }

    return $processed_data;
  }

  /**
   * Process office_hours.
   */
  public function processOfficeHours($processed_data, $row) {
    $office_hours_id = $row[0];
    $office_record_no = $row[1];
    $days_of_week = $row[4];
    $start = $row[5];
    $end = $row[6];

    if (!array_key_exists('hours', $processed_data[$office_record_no])) {
      $processed_data[$office_record_no]['hours'] = [];
    }

    if (!in_array($office_hours_id, $processed_data[$office_record_no]['hours'])) {

      if (strpos($days_of_week, '-') > 0) {
        $split = explode(' - ', $days_of_week);

        $days = [
          1 => 'Mon',
          2 => 'Tues',
          3 => 'Wed',
          4 => 'Thurs',
          5 => 'Fri',
          6 => 'Sat',
          7 => 'Sun',
        ];

        $start_day = array_search($split[0], $days);
        $end_day = array_search($split[1], $days);
      }
      else {
        $days = [
          1 => 'Monday',
          2 => 'Tuesday',
          3 => 'Wednesday',
          4 => 'Thursday',
          5 => 'Friday',
          6 => 'Saturday',
          7 => 'Sunday',
        ];

        $start_day = array_search($days_of_week, $days);
        $end_day = $start_day;
      }

      $start = substr($start, '11', 5);
      $end = substr($end, '11', 5);

      $index = $start_day;

      while ($index <= $end_day) {
        $day = $index;
        // Office hour modules weeks are sunday - saturday.
        // Office hour data is monday - sunday.
        // Reset the day to 0 to work with office hour module.
        if ($index == 7) {
          $day = 0;
        }
        $processed_data[$office_record_no]['hours'][] = [
          'office_hours_id' => $office_hours_id,
          'day' => $day,
          'starthours' => str_replace(':', '', $start),
          'endhours' => str_replace(':', '', $end),
        ];

        $index++;
      }

    }

    return $processed_data;
  }

  /**
   * Process the JSON file and convert flattened data.
   */
  public function parseJson($encodedJson) {
    $offices = json_decode($encodedJson['offices_json']);
    $office_hours = json_decode($encodedJson['office_hours_json']);

    // Remove the header from the data file.
    unset($offices[0]);
    unset($office_hours[0]);

    $processed_data = [];

    foreach ($offices as $office) {
      $location_id = intval($office[0]);

      if (is_integer($location_id) && $location_id !== 0 && !array_key_exists($location_id, $processed_data)) {
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

    $processed_data = $this->parseOfficeHours($processed_data, $office_hours);

    return $processed_data;
  }

}

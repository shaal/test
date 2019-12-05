<?php

namespace Drupal\towerhealth_msow_migration\Plugin\migrate\source;

/**
 * Migrate source plugin for provider location hours.
 *
 * @MigrateSource(
 *   id = "location_hours"
 * )
 */
class LocationHours extends CSVtoJSON {

  /**
   * List of available source fields.
   *
   * Keys are the field machine names as used in field mappings, values are
   * descriptions.
   *
   * @var array
   */
  public $fields = [
    'office_record_no' => 'Office record number',
    'hours' => 'Hours',
  ];

  /**
   * List of ids.
   *
   * @var array
   */
  public $ids = [
    'office_record_no' => [
      'type' => 'string',
      'max_length' => 64,
    ],
  ];

  /**
   * Process the JSON file and convert flattened data.
   */
  public function parseJson($json, $secondary_json = NULL) {
    $data = json_decode($json);
    // Remove the header from the data file.
    unset($data[0]);

    $processed_data = [];
    foreach ($data as $row) {
      $office_hours_id = $row[0];
      $office_record_no = $row[1];
      $days_of_week = $row[4];
      $start = $row[5];
      $end = $row[6];

      if (!isset($processed_data[$office_record_no])) {
        $processed_data[$office_record_no] = [
          'office_record_number' => $office_record_no,
          'hours' => [],
        ];
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

        $start = substr($start, '11');
        $end = substr($end, '11');

        $i = $start_day;

        while ($i <= $end_day) {
          $processed_data[$office_record_no]['hours'][] = [
            'office_hours_id' => $office_hours_id,
            'days_of_week' => $i,
            'start' => $start,
            'end' => $end,
          ];

          $i++;
        }
      }
    }

    // Remove keys since this is confusing the migration references.
    $processed_data = array_values($processed_data);
    dump($processed_data);

    return $processed_data;
  }

}

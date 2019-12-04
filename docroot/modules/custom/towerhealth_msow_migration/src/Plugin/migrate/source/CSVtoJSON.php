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
 *   id = "csv_to_json",
 *   source_module = "towerhealth_msow_migration"
 * )
 */
class CSVtoJSON extends SourcePluginBase {

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
  public $fields = [];

  /**
   * List of ids.
   *
   * @var array
   */
  public $ids = [];

  /**
   * {@inheritdoc}
   *
   * @throws \InvalidArgumentException
   * @throws \Drupal\migrate\MigrateException
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration);

    // Path is required.
    if (empty($this->configuration['path'])) {
      throw new MigrateException('You must declare the "path" to the source CSV file in your source settings.');
    }

    $file = fopen($this->configuration['path'], 'r');

    // Setup a PHP array to hold our CSV rows.
    $csv_data = [];

    // Go through rows in our CSV file and add them to array.
    while (($row = fgetcsv($file, 0, "|")) !== FALSE) {
      // Cleanup non-utf8 characters.
      $csv_data[] = mb_convert_encoding($row, 'UTF-8', 'UTF-8');
    }

    $json = json_encode($csv_data);

    // If JSON encoding fails throw an exception
    if (!$json) {
      throw new MigrateException(json_last_error_msg());
    }

    $this->dataRows = $this->parseJSON($json);
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
   * Process the JSON file and convert flattened data to single record per TIN.
   */
  public function parseJson($json) {
    return [];
  }

}

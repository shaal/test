<?php

namespace Drupal\towerhealth_msow_migration\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * Lookup a taxonomy term based on a field and value.
 *
 * @code
 * field_text:
 *   plugin: towerhealth_lookup_taxonomy_term
 *   lookup_field: string of field to lookup against
 *   source: value to lookup
 * @endcode
 *
 * @MigrateProcessPlugin(
 *   id = "towerhealth_lookup_taxonomy_term",
 * )
 */
class TowerHealthLookupTaxonomyTerm extends ProcessPluginBase {
  /**
   * Current migration.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $migration;

  /**
   * The entity type for the query.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $entityType;

  /**
   * The field holding the value.
   *
   * @var string
   */
  protected $lookupField;

  /**
   * The field holding vocabulary id.
   *
   * @var string
   */
  protected $vid;

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $result = '';

    if (isset($this->configuration['lookup_field'])) {
      $lookup_field = $this->configuration['lookup_field'];
      $vid = $this->configuration['vid'];
      $id = $value;

      if (!empty($id)) {
        $query = \Drupal::entityQuery('taxonomy_term');
        $result = $query
          ->condition($lookup_field . '.0.value', $id, '=')
          ->condition('vid', $vid)
          ->execute();

        return reset($result);
      }

      return $result;
    }
  }

}

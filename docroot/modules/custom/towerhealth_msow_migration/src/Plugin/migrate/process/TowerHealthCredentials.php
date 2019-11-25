<?php

namespace Drupal\towerhealth_msow_migration\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Creates provider credential paragraphs.
 *
 * @see \Drupal\migrate\Plugin\MigrateProcessInterface
 *
 * @MigrateProcessPlugin(
 *   id = "towerhealth_credentials",
 *   handle_multiples = TRUE
 * )
 */
class TowerHealthCredentials extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $credentials = $value;
    $paragraphs = [];

    if (empty($credentials)) {
      return $paragraphs;
    }

    foreach ($credentials as $credential_value) {
      $credential = [];

      $credential['type'] = 'education';
      $credential['bundle'] = 'education';
      $credential['field_education_type'] = $credential_value['credential_type'];
      if (!empty($credential_value['id'])) {
        $query = \Drupal::entityQuery('taxonomy_term');
        $result = $query
          ->condition('field_university_record_no.0.value', $credential_value['id'], '=')
          ->execute();

        $credential['field_education_institution_ref'] = ['target_id' => reset($result)];
      }
      $credential_paragraph = Paragraph::create($credential);
      $credential_paragraph->save();

      $target_id_dest = $credential_paragraph->id();
      $target_revision_id_dest = $credential_paragraph->getRevisionId();

      $paragraphs[] = [
        'target_id' => $target_id_dest,
        'target_revision_id' => $target_revision_id_dest,
      ];
    }

    return $paragraphs;
  }

}

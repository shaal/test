<?php

namespace Drupal\towerhealth_msow_migration\Plugin\migrate\destination;

use Drupal\migrate\Plugin\migrate\destination\EntityContentBase;
use Drupal\migrate\Row;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\TypedData\TypedDataInterface;
use Drupal\entity_reference_revisions\EntityReferenceRevisionsFieldItemList;

/**
 * Provides a node destination that recreates paragraph entities when updating.
 *
 * Usage:
 * @code
 * destination:
 *   plugin: 'node_paragraphs'
 * @endcode
 *
 * @MigrateDestination(
 *   id = "node_paragraphs",
 * )
 */
class NodeParagraphs extends EntityContentBase {

  /**
   * Always return 'node' as the entity type for this plugin.
   *
   * @param string $plugin_id
   *   The plugin ID.
   *
   * @return string
   *   The entity type.
   */
  protected static function getEntityTypeId($plugin_id) {
    return 'node';
  }

  /**
   * {@inheritdoc}
   */
  protected function updateEntity(EntityInterface $entity, Row $row) {
    // Delete existing paragraph entities.
    $destination_fields = $row->getDestination();

    foreach (array_keys($destination_fields) as $field_name) {
      // Exclude semesters_course_list for now.
      if ($field_name != 'field_semesters_course_list') {
        $item_list = $entity->$field_name;
        if ($item_list instanceof TypedDataInterface) {
          if ($item_list instanceof EntityReferenceRevisionsFieldItemList) {
            foreach ($item_list as $item) {
              $paragraph_entity = $item->entity;
              if ($paragraph_entity) {
                $item->entity->delete();
              }
            }
          }
        }
      }
    }

    parent::updateEntity($entity, $row);
  }

  /**
   * Gets the entity ID of the row.
   *
   * @param \Drupal\migrate\Row $row
   *   The row object.
   *
   * @return int
   *   The first matching nid, or FALSE if not found.
   */
  protected function getEntityId(Row $row) {
    if (isset($this->configuration['lookup_field'])) {
      $lookup_field = $this->configuration['lookup_field'];
      $id = $row->getDestinationProperty($lookup_field);

      $query = \Drupal::entityQuery('node');
      $result = $query
        ->condition($lookup_field . '.0.value', $id, '=')
        ->execute();

      return reset($result);
    }
  }

}

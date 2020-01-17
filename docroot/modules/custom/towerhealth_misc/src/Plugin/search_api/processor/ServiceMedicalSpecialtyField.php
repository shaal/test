<?php

namespace Drupal\towerhealth_misc\Plugin\search_api\processor;

use Drupal\Core\Entity\EntityInterface;
use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;

/**
 * Adds a custom type filter to the indexed data.
 *
 * @SearchApiProcessor(
 *   id = "service_medical_specialty_field",
 *   label = @Translation("Service medical specialty field"),
 *   description = @Translation("Add a field that includes medical specialties from a services condition."),
 *   stages = {
 *     "add_properties" = 0,
 *   },
 *   locked = false,
 *   hidden = false,
 * )
 */
class ServiceMedicalSpecialtyField extends ProcessorPluginBase {

  /**
   * Machine name of the processor.
   *
   * @var string
   */
  protected $processorId = 'service_medical_specialty_field';

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(DatasourceInterface $datasource = NULL) {
    $properties = [];

    if (!$datasource) {
      $definition = [
        'label' => $this->t('Service medical specialty field'),
        'description' => $this->t('Add a field that includes medical specialties from a services condition.'),
        'type' => 'string',
        'processor_id' => $this->getPluginId(),
      ];
      $properties[$this->processorId] = new ProcessorProperty($definition);
    }

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function addFieldValues(ItemInterface $item) {
    $entity = $item->getOriginalObject()->getValue();
    $keys = [];

    if ($entity instanceof EntityInterface && $entity->hasField('field_medical_specialties_ref')) {

      $terms = $entity->get('field_medical_specialties_ref')->getValue();

      foreach ($terms as $term) {
        $tid = $term['target_id'];

        $specialty_term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($tid);

        if ($specialty_term instanceof EntityInterface) {
          if ($specialty_term->hasField('field_related_conditions_ref')  && !$specialty_term->get('field_related_conditions_ref')->isEmpty()) {
            $conditions = $specialty_term->get('field_related_conditions_ref')->getValue();
            foreach ($conditions as $condition_term) {
              $query = \Drupal::service('entity.query')
                ->get('node')
                ->condition('type', 'service')
                ->condition('field_conditions_ref', $condition_term['target_id']);
              $entity_ids = $query->execute();

              if (!empty($entity_ids)) {
                foreach ($entity_ids as $id) {
                  $service = \Drupal::entityTypeManager()->getStorage('node')->load($id);

                  $keys[] = $service->getTitle();
                }
              }
            }
          }
        }
      }
    }

    $fields = $this->getFieldsHelper()
      ->filterForPropertyPath($item->getFields(), NULL, $this->processorId);

    sort($keys);
    $keys = array_unique($keys);

    foreach ($fields as $field) {
      foreach ($keys as $key) {
        $field->addValue($key);
      }
    }
  }

}

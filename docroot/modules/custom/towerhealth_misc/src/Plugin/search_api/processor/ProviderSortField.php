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
 *   id = "provider_sort_field",
 *   label = @Translation("Provider default sort field"),
 *   description = @Translation("Add a provider sort field to search index"),
 *   stages = {
 *     "add_properties" = 0,
 *   },
 *   locked = false,
 *   hidden = false,
 * )
 */
class ProviderSortField extends ProcessorPluginBase {

  /**
   * Machine name of the processor.
   *
   * @var string
   */
  protected $processorId = 'provider_sort_field';

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(DatasourceInterface $datasource = NULL) {
    $properties = [];

    if (!$datasource) {
      $definition = [
        'label' => $this->t('Provider Sort Field'),
        'description' => $this->t('Allows custom sorting by provider type'),
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
    $key = '';

    if ($entity instanceof EntityInterface && $entity->hasField('field_profile_type')) {
      $provider_types = [
        'tower_health_employed',
        'app_employed',
        'independent',
        'contracted',
      ];
      $type = $entity->get('field_profile_type')->getValue();
      $value = reset($type)['value'];
      $key = array_search($value, $provider_types);
    }

    $fields = $this->getFieldsHelper()
      ->filterForPropertyPath($item->getFields(), NULL, $this->processorId);

    foreach ($fields as $field) {
      $field->addValue($key);
    }

  }

}

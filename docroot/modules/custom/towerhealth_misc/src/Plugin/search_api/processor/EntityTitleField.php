<?php

namespace Drupal\towerhealth_misc\Plugin\search_api\processor;

use Drupal\Core\Entity\EntityInterface;
use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;

/**
 * Adds an entity title filter to the indexed data.
 *
 * @SearchApiProcessor(
 *   id = "entity_title_field",
 *   label = @Translation("Entity title field"),
 *   description = @Translation("Add a entity agnostic title field to the search index"),
 *   stages = {
 *     "add_properties" = 0,
 *   },
 *   locked = false,
 *   hidden = false,
 * )
 */
class EntityTitleField extends ProcessorPluginBase {

  /**
   * Machine name of the processor.
   *
   * @var string
   */
  protected $processorId = 'entity_title_field';

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(DatasourceInterface $datasource = NULL) {
    $properties = [];

    if (!$datasource) {
      $definition = [
        'label' => $this->t('Entity Title Field'),
        'description' => $this->t('Add a entity agnostic title field to the search index'),
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
    $title = '';

    if ($entity instanceof EntityInterface) {
      $entity->bundle();

      if ($entity->bundle() == 'video') {
        $title = $entity->label();
      }
      else {
        $title = $entity->label();
      }
    }

    $fields = $this->getFieldsHelper()
      ->filterForPropertyPath($item->getFields(), NULL, $this->processorId);

    foreach ($fields as $field) {
      $field->addValue($title);
    }

  }

}

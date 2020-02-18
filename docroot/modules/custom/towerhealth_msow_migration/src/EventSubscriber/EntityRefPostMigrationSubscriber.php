<?php

namespace Drupal\towerhealth_msow_migration\EventSubscriber;

use Drupal\Core\Entity\EntityInterface;
use Drupal\migrate\Event\MigrateEvents;
use Drupal\migrate\Event\MigrateRollbackEvent;
use Drupal\migrate\Event\MigrateImportEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Class DoctorPostMigrationSubscriber.
 *
 * Run our user flagging after the last node migration is run.
 *
 * @package Drupal\towerhealth_msow_migration
 */
class EntityRefPostMigrationSubscriber implements EventSubscriberInterface {

  /**
   * The event dispatcher.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $dispatcher;

  /**
   * The migration in question.
   */
  protected $migration_name;

  /**
   * The field on the node being imported on.
   */
  protected $field_names;

  /**
   * MigrationImportSync constructor.
   *
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
   *   The event dispatcher.
   */
  public function __construct(EventDispatcherInterface $dispatcher) {
    $this->dispatcher = $dispatcher;
  }

  /**
   * Get subscribed events.
   *
   * @inheritdoc
   */
  public static function getSubscribedEvents() {
    $events[MigrateEvents::PRE_IMPORT][] = ['onPreImport'];
    return $events;
  }

  /**
   * Event callback to sync source and destination.
   *
   * @param \Drupal\migrate\Event\MigrateImportEvent $event
   *   The migration import event.
   */
  public function onPreImport(MigrateImportEvent $event) {
    $migration = $event->getMigration();
    $field_names = $this->field_names;

    if ($migration->id() === $this->migration_name) {
      $id_map = $migration->getIdMap();
      $id_map->prepareUpdate();
      $source = $migration->getSourcePlugin();
      $source->rewind();
      $source_id_values = [];

      while ($source->valid()) {
        $source_id_values[] = $source->current()->getSourceIdValues();
        $source->next();
      }
      $id_map->rewind();
      while ($id_map->valid()) {
        $map_source_id = $id_map->currentSource();
        if (!in_array($map_source_id, $source_id_values, TRUE)) {
          $destination_ids = $id_map->currentDestination();
          if (isset($destination_ids['nid'])) {
            $node = Node::load($destination_ids['nid']);

            // Remove all items from the given fields.
            foreach ($field_names as $field) {
              if ($node instanceof EntityInterface) {
                if (isset($field['is_paragraph_ref']) && $field['is_paragraph_ref'] == TRUE) {
                  $paragraphs = $node->get($field['field_name'])->getValue();

                  foreach ($paragraphs as $paragraph_value) {
                    $paragraph = Paragraph::load($paragraph_value['target_id']);
                    $paragraph->delete();
                  }

                }
                $node->set($field['field_name'], $field['value']);
                $node->save();
              }
            }
            $id_map->delete($map_source_id);
          }
        }
        $id_map->next();
      }
      $this->dispatcher->dispatch(MigrateEvents::POST_ROLLBACK, new MigrateRollbackEvent($migration));
    }
  }

}

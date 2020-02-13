<?php
namespace Drupal\towerhealth_msow_migration\EventSubscriber;

use Drupal\Core\Entity\EntityInterface;
use Drupal\migrate\Event\MigrateEvents;
use Drupal\migrate\Event\MigrateRollbackEvent;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate_plus\Event\MigrateEvents as MigratePlusEvents;
use Drupal\migrate\Event\MigrateImportEvent;
use Drupal\migrate\Event\MigrateRowDeleteEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\node\Entity\Node;

/**
* Class DoctorPostMigrationSubscriber.
*
* Run our user flagging after the last node migration is run.
*
* @package Drupal\towerhealth_msow_migration
*/
class DoctorPostMigrationSubscriber implements EventSubscriberInterface {

  /**
   * The event dispatcher.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $dispatcher;

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
    $destination = $migration->getDestinationPlugin();
    while ($id_map->valid()) {
      $map_source_id = $id_map->currentSource();
      if (!in_array($map_source_id, $source_id_values, TRUE)) {
        $destination_ids = $id_map->currentDestination();
        $node = Node::load($destination_ids['nid']);

        if ($node instanceof EntityInterface) {
          $node->set('moderation_state', 'archived');
          $node->save();
          //$destination->rollback($destination_ids);
          $id_map->delete($map_source_id);
        }
      }
      /**
       * /*$this->dispatchRowDeleteEvent(MigrateEvents::PRE_ROW_DELETE, $migration, $destination_ids);
      $this->dispatchRowDeleteEvent(MigratePlusEvents::MISSING_SOURCE_ITEM, $migration, $destination_ids);
      $this->dispatchRowDeleteEvent(MigrateEvents::POST_ROW_DELETE, $migration, $destination_ids);
       */
      $id_map->next();
    }
    $this->dispatcher->dispatch(MigrateEvents::POST_ROLLBACK, new MigrateRollbackEvent($migration));
  }

  /**
   * Dispatches MigrateRowDeleteEvent event.
   *
   * @param string $event_name
   *   The event name to dispatch.
   * @param \Drupal\migrate\Plugin\MigrationInterface $migration
   *   The active migration.
   * @param array $destination_ids
   *   The destination identifier values of the record.
   */
  protected function dispatchRowDeleteEvent($event_name, MigrationInterface $migration, array $destination_ids) {
    // Symfony changing dispatcher so implementation could change.
    //$this->dispatcher->dispatch($event_name, new MigrateRowDeleteEvent($migration, $destination_ids));
  }
}
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
use Drupal\taxonomy\Entity\Term;
use Drupal\migrate\Row;

/**
* Class DoctorPostMigrationSubscriber.
*
* Run our user flagging after the last node migration is run.
*
* @package Drupal\towerhealth_msow_migration
*/
class SpecialInterestPostMigrationSubscriber implements EventSubscriberInterface {

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
    if ($migration->id() === 'towerhealth_providers_special_interests') {
      $id_map = $migration->getIdMap();
      $id_map->prepareUpdate();
      $source = $migration->getSourcePlugin();
      $source->rewind();
      $source_id_values = [];
      $current_source = [];

      while ($source->valid()) {
        $source_id_values[] = $source->current()->getSourceIdValues();
        $current_source = $source->current();
        $source->next();
      }
      $id_map->rewind();
      $destination = $migration->getDestinationPlugin();
      while ($id_map->valid()) {
        $map_source_id = $id_map->currentSource();
        if (!in_array($map_source_id, $source_id_values, TRUE)) {
          $destination_ids = $id_map->currentDestination();
          $node = Node::load($destination_ids['nid']);
          // Don't remove the node from the system, only archive it.
          if ($node instanceof EntityInterface) {
            $interest_terms = $node->get('field_area_interest_ref')->getValue();

            foreach($interest_terms as $i => $term) {
              $interest_term = Term::load($term['target_id']);

              if (in_array($interest_term->get('field_area_of_interest_msow_id')->getString(), $current_source->get('msow_ids'))) {
                $node->get('field_area_interest_ref')->removeItem($i);
                $node->save();
                $id_map->delete($map_source_id);
              }
            }
          }
        }
        $id_map->next();
      }
      $this->dispatcher->dispatch(MigrateEvents::POST_ROLLBACK, new MigrateRollbackEvent($migration));
    }
  }
}
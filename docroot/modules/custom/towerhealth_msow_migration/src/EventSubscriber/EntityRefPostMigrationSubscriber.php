<?php
namespace Drupal\towerhealth_msow_migration\EventSubscriber;

use Drupal\Core\Entity\EntityInterface;
use Drupal\migrate\Event\MigrateEvents;
use Drupal\migrate\Event\MigrateRollbackEvent;
use Drupal\migrate\Event\MigrateImportEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;

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
  protected $field_name;

  /**
   * The field on the term to search by.
   */
  protected $term_field_name;

  /**
   * The name for the value in the source.
   */
  protected $source_value_name;

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
    $field_name = $this->field_name;
    $term_field_name = $this->term_field_name;
    $source_value_name = $this->source_value_name;

    if ($migration->id() === $this->migration_name) {
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
          dump($node->getTitle());

          // Don't remove the node from the system, only archive it.
          if ($node instanceof EntityInterface) {
            $terms = $node->get($field_name)->getValue();
            dump($terms);

            foreach($terms as $i => $term_value) {
              $term = Term::load($term_value['target_id']);
              dump($current_source);
              dump($term->get($term_field_name)->getString());

              if (in_array($term->get($term_field_name)->getString(), $current_source->get($source_value_name))) {
                $node->get($field_name)->removeItem($i);
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
<?php
namespace Drupal\towerhealth_msow_migration\EventSubscriber;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
* Class LocationHoursPreMigrationSubscriber.
*
* Remove value from the location office hours field if removed from data.
*
* @package Drupal\towerhealth_msow_migration
*/
class LocationHoursPreMigrationSubscriber extends EntityRefPostMigrationSubscriber {

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
    $this->migration_name = 'towerhealth_location_hours';
    $this->field_names = [
      [
        'field_name' => 'field_location_hours',
        'value' => [],
      ],
    ];
  }
}
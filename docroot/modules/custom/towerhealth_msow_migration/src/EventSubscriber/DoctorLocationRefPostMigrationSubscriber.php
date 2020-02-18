<?php

namespace Drupal\towerhealth_msow_migration\EventSubscriber;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class DoctorPostMigrationSubscriber.
 *
 * Run our user flagging after the last node migration is run.
 *
 * @package Drupal\towerhealth_msow_migration
 */
class DoctorLocationRefPostMigrationSubscriber extends EntityRefPostMigrationSubscriber {

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
    $this->migration_name = 'towerhealth_providers_locations';
    $this->field_names = [
      [
        'field_name' => 'field_location_ref',
        'value' => [],
      ],
    ];
  }

}

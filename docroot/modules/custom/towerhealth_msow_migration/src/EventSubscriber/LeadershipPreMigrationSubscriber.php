<?php
namespace Drupal\towerhealth_msow_migration\EventSubscriber;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
* Class LeadershipPreMigrationSubscriber.
*
* Remove value from leadership field if removed from data.
*
* @package Drupal\towerhealth_msow_migration
*/
class LeadershipPreMigrationSubscriber extends EntityRefPostMigrationSubscriber {

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
    $this->migration_name = 'towerhealth_providers_leadership';
    $this->field_names = [
      [
        'field_name' => 'field_profile_professional_title',
        'value' => '',
      ],
    ];
  }
}
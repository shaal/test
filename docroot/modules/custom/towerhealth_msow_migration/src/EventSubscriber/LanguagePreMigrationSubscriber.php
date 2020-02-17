<?php
namespace Drupal\towerhealth_msow_migration\EventSubscriber;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
* Class LanguagePreMigrationSubscriber.
*
* Remove value from the Language field if removed from data.
*
* @package Drupal\towerhealth_msow_migration
*/
class LanguagePreMigrationSubscriber extends EntityRefPostMigrationSubscriber {

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
    $this->migration_name = 'towerhealth_providers_languages';
    $this->field_names = [
      [
        'field_name' => 'field_profile_languages',
        'value' => '',
      ],
    ];
  }
}
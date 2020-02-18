<?php

namespace Drupal\towerhealth_msow_migration\EventSubscriber;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class CredentialsPreMigrationSubscriber.
 *
 * Remove Education paragraph and data from education field if removed from data.
 *
 * @package Drupal\towerhealth_msow_migration
 */
class CredentialsPreMigrationSubscriber extends EntityRefPostMigrationSubscriber {

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
    $this->migration_name = 'towerhealth_providers_credentials';
    $this->field_names = [
      [
        'field_name' => 'field_profile_education',
        'value' => [],
        'is_paragraph_ref' => TRUE,
      ],
    ];
  }

}

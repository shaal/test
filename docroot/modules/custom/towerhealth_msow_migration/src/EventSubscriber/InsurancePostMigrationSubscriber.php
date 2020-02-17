<?php
namespace Drupal\towerhealth_msow_migration\EventSubscriber;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
* Class InsurancePostMigrationSubscriber.
*
* Run our user flagging after the last node migration is run.
*
* @package Drupal\towerhealth_msow_migration
*/
class InsurancePostMigrationSubscriber extends EntityRefPostMigrationSubscriber {

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
    $this->migration_name = 'towerhealth_providers_insurance_group_ref';
    $this->field_names = [
      [
        'field_name' => 'field_insurance_ref',
        'value' => []
      ],
    ];
  }
}
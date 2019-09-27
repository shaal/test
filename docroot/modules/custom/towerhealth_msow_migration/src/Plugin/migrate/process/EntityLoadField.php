<?php

namespace Drupal\towerhealth_msow_migration\Plugin\migrate\process;

use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\migrate\Plugin\Exception\BadPluginDefinitionException;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;
use Drupal\typed_data\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * This plugin retrieves values from existing entities.
 *
 * Adapted from: https://www.drupal.org/project/migrate_plus/issues/2926200
 *
 * @MigrateProcessPlugin(
 *   id = "entity_load_field",
 *   handle_multiples = TRUE
 * )
 *
 * Example usage for a taxonomy_term migration:
 * @code
 * process:
 *   tid:
 *    -
 *      plugin: migration_lookup
 *      source: translation_reference
 *      migration: article
 *    -
 *      plugin: entity_load_field
 *      entity_type: node
 *      field_name: field_tags
 *    -
 *      plugin: extract
 *      index:
 *        - 0
 *        - target_id
 *
 * @endcode
 */
class EntityLoadField extends ProcessPluginBase implements ContainerFactoryPluginInterface {

  /**
   * Current migration.
   *
   * @var \Drupal\migrate\Plugin\MigrationInterface
   */
  protected $migration;

  /**
   * The entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $entityStorage;

  /**
   * The entity type for the query.
   *
   * @var string
   */
  protected $entityType;

  /**
   * The field holding the value.
   *
   * @var string
   */
  protected $fieldName;

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\migrate\Plugin\Exception\BadPluginDefinitionException
   *   Thrown if some mandatory property is missing.
   * @throws \Drupal\typed_data\Exception\InvalidArgumentException
   *   Thrown if the provided entity type does not exist.
   */
  public function __construct(array $configuration, $pluginId, $pluginDefinition, MigrationInterface $migration, EntityTypeManagerInterface $entityTypeManager) {
    parent::__construct($configuration, $pluginId, $pluginDefinition);
    $this->migration = $migration;

    if (empty($this->configuration['entity_type'])) {
      throw new BadPluginDefinitionException('entity_load_field', 'entity_type');
    }
    try {
      $this->entityStorage = $entityTypeManager->getStorage($this->configuration['entity_type']);
    }
    catch (PluginNotFoundException $e) {
      throw new InvalidArgumentException('The ' . $this->configuration['entity_type'] . ' entity type does not exist.');
    }

    if (empty($this->configuration['field_name'])) {
      throw new BadPluginDefinitionException('entity_load_field', 'field_name');
    }
    $this->fieldName = $this->configuration['field_name'];
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $pluginId, $pluginDefinition, MigrationInterface $migration = NULL) {
    return new static(
      $configuration,
      $pluginId,
      $pluginDefinition,
      $migration,
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrateExecutable, Row $row, $destinationProperty) {
    if (!is_array($value)) {
      $value = [$value];
    }
    $values = [];

    /** @var \Drupal\Core\Entity\EntityInterface $entity */
    foreach ($this->entityStorage->loadMultiple($value) as $entity) {
      if ($entity && $entity->{$this->fieldName}) {
        foreach ($entity->{$this->fieldName}->getValue() as $entity_value) {
          $values[] = $entity_value;
        }
      }
    }
    return $values;
  }

}

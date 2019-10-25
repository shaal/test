<?php

/**
 * @file
 * Contains code to support the provider ratings migration.
 *
 * @copyright Copyright (c) 2019 Palantir.net
 */

/**
 * Implements hook_drush_command().
 */
function towerhealth_rating_migration_drush_command() {
  $commands['migrate-profile-ratings'] = [
    'description' => 'Migrate the profile ratings via batch',
    'aliases' => ['th-mrp'],
    'options' => [
      'reset' => 'Reset the ratings migration to 0 and re-import.',
    ],
    'examples' => [
      'drush th-mrp',
    ],
  ];

  return $commands;
}

/**
 * Drush command logic for deleting aliases.
 */
function drush_towerhealth_rating_migration_migrate_profile_ratings() {
  $state_total = 'rating_migration.state_total';

  $reset = drush_get_option('reset', FALSE);

  if ($reset == TRUE) {
    \Drupal::state()->delete($state_total);
    drush_invoke_process('@site', 'mrs towerhealth_ratings');
    drush_invoke_process('@site', 'mr towerhealth_ratings');
  }

  $total = \Drupal::state()->get($state_total, 0);

  $storage = \Drupal::entityManager()->getStorage('node');

  if ($total == 0) {
    $total = $storage->getQuery()
      ->condition('type', 'profile')
      ->condition('status', 1)
      ->condition('field_profile_npi', '', '!=')
      ->count()
      ->execute();
    \Drupal::state()->set($state_total, $total);
  }

  $rounds = ceil($total / 5);
  $processed = 0;
  $current_round = 0;

  while ($current_round <= $rounds) {
    // Reset status in case not ready due to error.
    drush_invoke_process('@site', 'mrs towerhealth_ratings');
    // Migrate the next 10 items.
    drush_invoke_process('@site', 'mim towerhealth_ratings --limit=5');

    $processed += 5;

    $current_round += 1;

    $total = $total - $processed;

    \Drupal::state()->set($state_total, $total);

    $message = $processed . ' total profiles migrated. Aproximate remaining: ' . $total;

    drush_print($message);
  }

  if ($total <= 0) {
    \Drupal::state()->delete($state_total);
  }
}

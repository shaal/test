<?php

/**
 * @file
 * Drupal settings file template for use on Acquia environments.
 */

$settings['trusted_host_patterns'][] = "^{$_ENV['AH_SITE_ENVIRONMENT']}dev.prod.acquia-sites.com";
$settings['trusted_host_patterns'][] = "^{$_ENV['AH_SITE_ENVIRONMENT']}stg.prod.acquia-sites.com";

$settings['file_public_path'] = '${drupal.site.settings.file_public_path}';
$settings['file_private_path'] = "/mnt/gfs/{$_ENV['AH_SITE_GROUP']}.{$_ENV['AH_SITE_ENVIRONMENT']}/files-private";
$settings['file_temp_path'] = $_ENV['TEMP'];

$settings['config_sync_directory'] = '${drupal.site.config_sync_directory}';

// Enable/disable config_split configurations.
if (isset($_ENV['AH_PRODUCTION']) && $_ENV['AH_PRODUCTION']) {
  $config['config_split.config_split.production']['status'] = TRUE;
}
elseif (isset($_ENV['AH_NON_PRODUCTION']) && $_ENV['AH_NON_PRODUCTION']) {
  $config['config_split.config_split.staging']['status'] = TRUE;
}

//// Add an htaccess prompt on dev and staging environments.
//// @see https://docs.acquia.com/articles/password-protect-your-non-production-environments-acquia-hosting#phpfpm

// Make sure Drush keeps working.
// Modified from function drush_verify_cli()
$cli = (php_sapi_name() == 'cli');

$settings['rating_api_id'] = '386708867350450';
$settings['rating_api_secret'] = '60eebb76-506b-4429-957d-5dea47fe8132';

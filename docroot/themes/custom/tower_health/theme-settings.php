<?php

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Theme\ThemeSettings;
use Drupal\system\Form\ThemeSettingsForm;
use Drupal\Core\Form;

function tower_health_form_system_theme_settings_alter(&$form, Drupal\Core\Form\FormStateInterface $form_state) {
  $form['tower_health_settings']['contact'] = array(
    '#type' => 'fieldset',
    '#title' => t('Footer Contact Information'),
  );
  $form['tower_health_settings']['contact']['address1'] = array(
    '#type' => 'textfield',
    '#title' => t('Address 1'),
    '#default_value' => theme_get_setting('address1', 'tower_health'),
  );
  $form['tower_health_settings']['contact']['address2'] = array(
    '#type' => 'textfield',
    '#title' => t('Address 2'),
    '#default_value' => theme_get_setting('address2', 'tower_health'),
  );
  $form['tower_health_settings']['contact']['telephone'] = array(
    '#type' => 'textfield',
    '#title' => t('Telephone Number'),
    '#default_value' => theme_get_setting('telephone', 'tower_health'),
  );
  $form['tower_health_settings']['details'] = array(
    '#type' => 'fieldset',
    '#title' => t('Footer Bar Details'),
  );
  $form['tower_health_settings']['details']['legal'] = array(
    '#type' => 'textfield',
    '#title' => t('Copyright Disclaimer'),
    '#default_value' => theme_get_setting('legal', 'tower_health'),
  );
}

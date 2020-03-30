<?php

function tower_health_form_system_theme_settings_alter(&$form, &$form_state, $form_id = NULL) {

  $form['global_data'] = array(
    '#type'          => 'fieldset',
    '#title'         => t('Global Data'),
    '#description'   => t("Global data used by multiple page types")
  );
  $form['global_data']['appointment_phone_number'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Appointment Phone Number'),
    '#default_value' => theme_get_setting('appointment_phone_number'),
    '#description'   => t("List the global phone number for scheduling appointments (should contain digits 0-9 only, i.e. 555-555-5555).")
  );
  $form['global_data']['appointment_phone_label'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Appointment Phone Number Label'),
    '#default_value' => theme_get_setting('appointment_phone_label'),
    '#description'   => t("List the label to display for the phone number above (can include digits and letters, i.e. 555-555-HELP).")
  );
  $form['global_data']['appointment_button_link'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Appointment Button Link'),
    '#default_value' => theme_get_setting('appointment_button_link'),
    '#description'   => t("Link to use for 'Request an Appointment' button for doctors who do no have Epic ID for open scheduling widget")
  );
  $form['global_data']['reviews_link'] = array(
    '#type'          => 'textfield',
    '#title'         => t('About Reviews Link'),
    '#default_value' => theme_get_setting('reviews_link'),
    '#description'   => t("Link to use for 'About Reviews' link on doctor profiles with ratings from Binary Fountain. This should link to the generic about reviews page.")
  );
}

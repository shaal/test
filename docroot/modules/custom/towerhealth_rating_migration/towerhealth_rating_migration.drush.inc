<?php

/**
 * @file
 * Contains code to support the provider ratings migration.
 *
 * @copyright Copyright (c) 2019 Palantir.net
 */

use Drupal\Core\Controller\ControllerBase;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Site\Settings;
use Drupal\Component\Serialization\Json;
use Drupal\views\Views;

/**
 * Implements hook_drush_command().
 */
function towerhealth_rating_migration_drush_command() {
  $commands['create-ratings-json'] = [
    'description' => 'Create the ratings json file',
    'aliases' => ['crj'],
    'examples' => [
      'drush crj',
    ],
  ];

  return $commands;
}

/**
 * Drush command logic for deleting aliases
 *
 */
function drush_towerhealth_rating_migration_create_ratings_json() {
  $rating_api_id = Settings::get('rating_api_id');
  $rating_api_secret = Settings::get('rating_api_secret');
  $access_token = '';

  // Firstly, get the view in question.
  $view = Views::getView('ratings_npi_id');

  // Execute the view.
  $view->execute();
  $view_results = $view->result;

  $npi_ids = [];

  foreach($view_results as $result) {
    $result = $result->_entity->toArray();
    if (!empty($result['field_profile_npi'])) {
      $npi_ids[] = ['personId' => $result['field_profile_npi'][0]['value']];
    }
  }

  $client = new Client(["base_uri" => "https://api.binaryfountain.com/api/"]);

  $response = $client->request('post', 'service/v1/token/create', [
    'form_params' => [
      'appId' => $rating_api_id,
      'appSecret' => $rating_api_secret
    ]
  ]);


  $response_body = Json::decode($response->getBody());

  $response_data = [
    'status' => $response_body['status'],
  ];

  if ($response_data['status']['code'] === 200) {

    $access_token = $response_body['accessToken'];

    $provider_response = $client->request('post', 'service/bsr/comments', [
      'headers' => [
        'accessToken' => $access_token,
      ],
      'form_params' => [['personId' => '1205820826'],['personId' => '1578507232'],['personId' => '1568609121']]
    ]);

    /*$test = [['personId' => '1205820826'], ['personId' => '1578507232'], ['personId' => '1568609121']];

    dump(http_build_query($test, '', '&'));*/

    //echo $provider_response->getBody();
  //}
  }
}
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

  $client = new Client(["base_uri" => "https://api.binaryfountain.com/api/"]);

  $response = $client->request('post', 'service/v1/token/create', [
    'form_params' => [
      'appId' => $rating_api_id,
      'appSecret' => $rating_api_secret,
    ]
  ]);


  $response_body = Json::decode($response->getBody());
  dump($response_body);

  $response_data = [
    'status' => $response_body['status'],
  ];

  if ($response_data['status']['code'] === 200) {
    $access_token = $response_body['accessToken'];

  }


 /*$response = $client->request('GET', 'service/bsr/comments', [
    'headers' => [
      'accessToken' => 'Uz5QFd0ArenyxkwlQRtP_1570549028046',
    ],
    'query' => ['personId' => '1598729055']
  ]);*/
}
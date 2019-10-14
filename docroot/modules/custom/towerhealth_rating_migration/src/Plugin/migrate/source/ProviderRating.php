<?php

namespace Drupal\towerhealth_rating_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;
use Drupal\Core\State\StateInterface;
use GuzzleHttp\Client;
use Drupal\migrate\MigrateException;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\Core\Site\Settings;
use Drupal\Component\Serialization\Json;

/**
 * Source plugin for beer content.
 *
 * @MigrateSource(
 *   id = "provider_rating",
 *   source_module = "towerhealth_rating_migration"
 * )
 */
class ProviderRating extends SqlBase {

  /**
   * Access token.
   *
   * @var string
   *   Generated access token to binary fountain.
   */
  public $accessToken;

  /**
   * Guzzle client.
   *
   * @var object
   *   Guzzle client object to pull ratings from.
   */
  public $client;

  /**
   * Data obtained from the JSON file.
   *
   * @var array[]
   *   Array of data rows, each one an array of values keyed by field names.
   */
  public $dataRows = [];

  /**
   * List of available source fields.
   *
   * Keys are the field machine names as used in field mappings, values are
   * descriptions.
   *
   * @var array
   */
  public $fields = [];

  /**
   * List of ids.
   *
   * @var array
   */
  public $ids = [];

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration, StateInterface $state) {
    $this->state = $state;

    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration, $state);

    $rating_api_id = Settings::get('rating_api_id');
    $rating_api_secret = Settings::get('rating_api_secret');

    $this->client = new Client(["base_uri" => "https://api.binaryfountain.com/api/"]);

    $response = $this->client->request('post', 'service/v1/token/create', [
      'form_params' => [
        'appId' => $rating_api_id,
        'appSecret' => $rating_api_secret,
      ],
    ]);

    $response_body = Json::decode($response->getBody());

    if ($response_body['status']['code'] === 200) {
      $this->access_token = $response_body['accessToken'];
    }
    else {
      throw new MigrateException('Unable to authorize with Binary Fountain Api');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    // An important point to note is that your query *must* return a single row
    // for each item to be imported. Here we might be tempted to add a join to
    // migrate_example_beer_topic_node in our query, to pull in the
    // relationships to our categories. Doing this would cause the query to
    // return multiple rows for a given node, once per related value, thus
    // processing the same node multiple times, each time with only one of the
    // multiple values that should be imported. To avoid that, we simply query
    // the base node data here, and pull in the relationships in prepareRow()
    // below.
    $fields = [
      'field_profile_npi_value',
    ];

    $query = $this->select('node__field_profile_npi', 'n')
      ->fields('n', $fields)
      ->condition('field_profile_npi_value', '', '!=');

    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'field_profile_npi_value' => $this->t('Npi ID Query'),
      // Note that these fields are not part of the query above.
      // They are populated by prepareRow() below.
      'field_profile_npi' => $this->t('Npi ID'),
      'total_rating_count' => $this->t('Rating Count'),
      'total_rating_comment' => $this->t('Comment Count'),
      'overall_rating' => $this->t('Overall Rating'),
      'comments' => $this->t('Comments'),
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'field_profile_npi_value' => [
        'type' => 'string',
        'alias' => 'n',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    $npi_id = $row->getSourceProperty('field_profile_npi_value');

    // Set the npi_id to be used for lookup.
    $row->setSourceProperty('field_profile_npi', $npi_id);

    $promise = $this->client->requestAsync('get', '/service/bsr/comments', [
      'headers' => [
        'accessToken' => $this->access_token,
      ],
      'query' => ['personId' => $npi_id],
    ]);

    $provider_response = $promise->wait();

    // Decode the body into JSON.
    $response_body = Json::decode($provider_response->getBody());

    $response_data = [
      'success' => $response_body['status'],
    ];

    if ($response_data['success']['code'] == 200) {
      $rating_entity = $response_body['data']['entities'][0];

      $row->setSourceProperty('total_rating_count', $rating_entity['totalRatingCount']);
      $row->setSourceProperty('total_comment_count', $rating_entity['totalCommentCount']);
      $row->setSourceProperty('comments', $rating_entity['comments']);

      if (isset($rating_entity['overallRating']['value'])) {
        $row->setSourceProperty('overall_rating', $rating_entity['overallRating']['value']);
      }
    }

    return parent::prepareRow($row);
  }

}

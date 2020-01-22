<?php

namespace Drupal\towerhealth_autocomplete\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Defines a route controller for watches autocomplete form elements.
 */
class FindServicesController extends FindCareController {
  /**
   * The node storage.
   *
   * @var \Drupal\node\NodeStorage
   */
  protected $nodeStorage;

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->nodeStorage = $entity_type_manager->getStorage('node');
    $this->queryParam = 'services';
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * Handler for autocomplete request.
   */
  public function handleAutocomplete(Request $request) {
    $results = [];

    return new JsonResponse($results);
  }

}

<?php

namespace Drupal\towerhealth_autocomplete\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Entity\Element\EntityAutocomplete;
use Drupal\views\Views;

/**
 * Defines a route controller for watches autocomplete form elements.
 */
class FindDoctorController extends ControllerBase {
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
    $this->nodeStroage = $entity_type_manager->getStorage('node');
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
    $input = $request->query->get('q');
    // Get the typed string from the URL, if it exists.
    if (!$input) {
      return new JsonResponse($results);
    }
    $input = Xss::filter($input);

    // Firstly, get the view in question.
    $view = Views::getView('auto_medical_specialty');

    // Pass any input.
    $view->setExposedInput(['name' => $input]);

    // Execute the view.
    $view->execute();
    $view_result = $view->result;

    // This should pull from the view and shouldf not need to pull from the entity
    // Access field data from the view results.
    foreach ($view_result as $data) {
      $term = $data->_object->getEntity();
      $results[] = [
        'value' => $term->getName(),
        'label' => $term->getName(),
      ];
    }
    
    return new JsonResponse($results);
  }
}
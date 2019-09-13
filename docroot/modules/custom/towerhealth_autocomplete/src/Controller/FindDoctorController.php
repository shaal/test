<?php

namespace Drupal\towerhealth_autocomplete\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Component\Utility\Xss;
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

    $results = $this->taxonomySuggestedTerms('auto_medical_specialty', $input, t('Medical Specialties'), $results);
    $results = $this->taxonomySuggestedTerms('auto_condition', $input, t('Conditions'), $results);
    $results = $this->nodeSuggestedTerms('auto_provider', $input, t('Providers'), $results);

    return new JsonResponse($results);
  }

  /**
   * Return suggestions from taxonomy term source.
   */
  private function taxonomySuggestedTerms($view_id, $input, $label, $results) {

    // Firstly, get the view in question.
    $view = Views::getView($view_id);

    // Pass any input.
    $view->setExposedInput(['name' => $input]);

    // Execute the view.
    $view->execute();
    $view_result = $view->result;

    // Return the results with out addititions if empty.
    if (count($view_result) === 0) {
      return $results;
    }

    // This should pull from the view and shouldn't need to pull from the entity
    // access field data from the view results.
    $results[] = [
      'grouping' => TRUE,
      'label' => $label,
    ];

    foreach ($view_result as $data) {
      $values = $data->_item->getField('name')->getValues();
      $term_name = reset($values)->toText();

      $results[] = [
        'value' => $term_name,
        'label' => $term_name,
      ];
    }

    return $results;
  }

  /**
   * Return suggestions from node source.
   */
  private function nodeSuggestedTerms($view_id, $input, $label, $results) {

    // Firstly, get the view in question.
    $view = Views::getView($view_id);

    // Pass any input.
    $view->setExposedInput(['title' => $input]);

    // Execute the view.
    $view->execute();
    $view_result = $view->result;

    // Return the results with out addititions if empty.
    if (count($view_result) === 0) {
      return $results;
    }

    $results[] = [
      'grouping' => TRUE,
      'label' => $label,
    ];

    foreach ($view_result as $data) {
      $entity = $data->_object->getEntity();

      if ($entity instanceof EntityInterface) {
        $url = $entity->toUrl();

        $values = $data->_item->getField('title')->getValues();
        $node_title = reset($values)->toText();

        $results[] = [
          'value' => $node_title,
          'label' => $node_title,
          'url' => $url->toString(),
        ];
      }
    }

    return $results;
  }

}

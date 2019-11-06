<?php

namespace Drupal\towerhealth_autocomplete\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Component\Utility\Xss;

/**
 * Defines a route controller for watches autocomplete form elements.
 */
class FindProviderController extends FindCareController {
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
    $this->queryParam = 'providers';
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
    // @todo add a childResults and merge with auto_condition results under 'Condition' label
    $input = $request->query->get('q');
    // Get the typed string from the URL, if it exists.
    if (!$input) {
      return new JsonResponse($results);
    }
    $input = Xss::filter($input);

    $results = $this->taxonomySuggestedTerms('auto_medical_specialty', $input, t('Medical Specialties'), $results, 'name', 'view.find_a_provider.find_doctor', 'specialties');

    $condition_results = $this->taxonomySuggestedTerms('auto_condition', $input, t('Conditions'), [], 'name', NULL, NULL);

    $synonym_label = '';
    if (empty($condition_results)) {
      $synonym_label = t('Conditions');
    }
    $synonym_results = $this->taxonomySuggestedTerms('auto_synonym', $input, $synonym_label ? $synonym_label : '', [], 'synonym', NULL, NULL);

    $condition_results = array_merge($condition_results, $synonym_results);

    $results = array_merge($results, $condition_results);

    $results = $this->nodeSuggestedTerms('auto_provider', $input, t('Providers'), $results, NULL, NULL);

    return new JsonResponse($results);
  }

}

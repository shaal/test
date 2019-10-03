<?php

namespace Drupal\towerhealth_autocomplete\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\views\Views;

/**
 * Defines a route controller for watches autocomplete form elements.
 */
class FindCareController extends ControllerBase {
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
   * Return suggestions from taxonomy term source.
   */
  public function taxonomySuggestedTerms($view_id, $input, $label, $results, $field = null) {

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
      $values = $data->_item->getField($field)->getValues();
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
  public function nodeSuggestedTerms($view_id, $input, $label, $results) {

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

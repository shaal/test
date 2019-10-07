<?php

namespace Drupal\towerhealth_autocomplete\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\views\Views;
use Drupal\search_api\Item\Item;

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
   *
   * @param $view_id
   * @param $input
   * @param $label
   * @param $results
   * @param string $field
   * @param $child_results
   * @return array
   */
  public function taxonomySuggestedTerms($view_id, $input, $label, $results, $field, $child_results = NULL) {

    // Firstly, get the view in question.
    $view = Views::getView($view_id);
    // Pass any input.
    $view->setExposedInput(['name' => $input]);

    // Execute the view.
    $view->execute();
    $view_result = $view->result;

    // Create new results array
    $new_results = [];

    // If the parent grouping has no results still add the parent label to group.
    if (count($view_result) === 0 && is_array($child_results) && count($child_results) >= 1) {
      $new_results[] = [
        'grouping' => TRUE,
        'label' => $label,
      ];

      $child_results = array_merge($new_results, $child_results);

      $results = array_merge($results, $child_results);

      return $results;
    }
    elseif (count($view_result) === 0) {
      return $results;
    }

    // Add the label if one is provided.
    if (!empty($label)) {
      $new_results[] = [
        'grouping' => TRUE,
        'label' => $label,
      ];
    }

    // Pull the search index field and add it as a value.
    foreach ($view_result as $data) {
      $item = $data->_item;

      if ($item instanceof Item) {
        $values = $data->_item->getField($field)->getValues();
        foreach ($values as $value) {
          $text = $value->toText();

          // Confirm that this field matches the input.
          if (strpos(strtolower($text), $input) !== FALSE && $this->duplicate_value($text, $new_results) === FALSE) {
            $new_results[] = [
              'value' => $text,
              'label' => $text,
            ];
          }
        }
      }
    }

    // Add the child results to the new results.
    if (!empty($child_results) && is_array($child_results)) {
      $results = array_merge($new_results, $child_results);
    }

    // Add new results to existing results.
    $results = array_merge($results, $new_results);

    return $results;
  }

  /**
   * Return suggestions from node source.
   *
   * @param $view_id
   * @param $input
   * @param $label
   * @param $results
   * @return array
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
          'lbael' => $node_title,
          'url' => $url->toString(),
        ];
      }
    }

    return $results;
  }

  /**
   * Determine if the value is already in the array.
   *
   * @param $value
   * @param $array
   * @return bool
   */
  private function duplicate_value($value, $array) {
    foreach ($array as $item) {
      if (array_key_exists('value', $item) && $item['value'] === $value) {
        return TRUE;
      }
    }
    return FALSE;
  }

}

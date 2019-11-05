<?php

namespace Drupal\towerhealth_autocomplete\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\views\Views;
use Drupal\core\Url;

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
   * Search type.
   *
   * @var string
   */
  public $searchType = '';

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->nodeStorage = $entity_type_manager->getStorage('node');
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
   * @param string $view_id
   *   The ID of the view.
   * @param string $input
   *   The string the user input.
   * @param mixed $label
   *   The label to group this set of results.
   * @param array $results
   *   The existing results.
   * @param string $field
   *   The search field to pull from the result item.
   *
   * @return array
   *   Array of suggested items.
   */
  public function taxonomySuggestedTerms($view_id, $input, $label, array $results, $field, $route, $facet) {

    // Firstly, get the view in question.
    $view = Views::getView($view_id);

    // Pass any input.
    $view->setExposedInput(['name' => $input]);

    // Execute the view.
    $view->execute();
    $view_result = $view->result;

    // Create new results array.
    $new_results = [];

    if (count($view_result) === 0) {
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

      $values = $data->_item->getField($field)->getValues();
      foreach ($values as $value) {
        $query_param = $this->searchType;

        $url = '';
        if (is_object($value)) {
          $text = $value->toText();
        }
        else {
          $text = $value;
        }

        if ($this->searchType == 'locations') {
          $query_param = 'f';
        }

        if ($route && $facet) {
          $query = $facet . ':' . $value;
          $url = Url::fromRoute($route,[], ['attributes' => ['rel' => 'nofollow'], 'query' => [$query_param => [$query]]])->toString();
        }

        // Confirm that this field matches the input.
        if (strpos(strtolower($text), strtolower($input)) !== FALSE && $this->duplicateValue($text, $new_results) === FALSE) {
          $text = ucwords($text);

          $result = [
            'value' => $text,
            'label' => $text,
          ];

          if ($url) {
            $result['url'] = $url;
          }
          $new_results[] = $result;
        }
      }
    }

    // Add new results to existing results.
    $results = array_merge($results, $new_results);

    return $results;
  }

  /**
   * Return suggestions from node source.
   *
   * @param string $view_id
   *   The ID of the view.
   * @param string $input
   *   The string the user input.
   * @param string $label
   *   The label to group this set of results.
   * @param array $results
   *   The existing results.
   * @param bool $redirect_url
   *   Redirect to the node or return the plain result.
   *
   * @return array
   *   Array of suggested items.
   */
  public function nodeSuggestedTerms($view_id, $input, $label, array $results, $route, $facet, $redirect_url = TRUE) {

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
      // Store the entity object to build the URL.
      $entity = $data->_object->getEntity();

      if ($entity instanceof EntityInterface) {
        $query_param = $this->searchType;

        $url = '';

        $values = $data->_item->getField('title')->getValues();

        $value = reset($values);

        if ($this->searchType == 'locations') {
          $query_param = 'f';
        }

        if ($redirect_url === TRUE) {
          $url_object = $entity->toUrl();
          $url = $url_object->toString();
        }
        else if ($route && $facet) {
          $query = $facet . ':' . $value;
          $url = Url::fromRoute($route,[], ['attributes' => ['rel' => 'nofollow'], 'query' => [$query_param => [$query]]])->toString();
        }

        if (is_object($value)) {
          $node_title = ucwords(reset($values)->toText());
        }
        else {
          $node_title = ucwords($value);
        }

        $results[] = [
          'value' => $node_title,
          'lbael' => $node_title,
          'url' => $url,
        ];
      }
    }

    return $results;
  }

  /**
   * Determine if the value is already in the array.
   *
   * @param string $value
   *   The suggested value to search for.
   * @param array $array
   *   The array of values to search.
   *
   * @return bool
   *   Returns true if this is a duplicate.
   */
  private function duplicateValue($value, array $array) {
    $value = strtolower($value);

    foreach ($array as $item) {
      if (array_key_exists('value', $item) && strtolower($item['value']) === $value) {
        return TRUE;
      }
    }

    return FALSE;
  }

}

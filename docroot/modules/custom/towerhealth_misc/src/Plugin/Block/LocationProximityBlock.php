<?php

namespace Drupal\towerhealth_misc\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\views\Views;
use Drupal\Core\Url;
use Drupal\Component\Utility\Xss;

/**
 * Provides a 'DynamicFilterBlock' block.
 *
 * @Block(
 *  id = "location_proximity_filter",
 *  admin_label = @Translation("Location proximity filter"),
 *  category = "Views"
 * )
 */
class LocationProximityBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // We don't have much context during build, so we look at the current route
    // and try to decide if we're on a Views page.
    /** @var \Symfony\Component\HttpFoundation\ParameterBag $currentRouteParameters */

    $view_id = 'find_a_location';
    $view_display_id = 'find_location';

    /** @var \Drupal\views\ViewExecutable $view */
    $view = Views::getView($view_id);
    $view->setDisplay($view_display_id);

    $view->initHandlers();

    $display = $view->getDisplay();

    /** @var \Drupal\views\Plugin\views\exposed_form\ExposedFormPluginInterface $exposed_form */
    $exposed_form = $display->getPlugin('exposed_form');

    $form = $exposed_form->renderExposedForm(TRUE);

    $form['find_location_search']['#type'] = 'hidden';
    $search_term_id = $form['find_location_search']['#id'];
    $form['find_location_search']['#id'] = $search_term_id . '-proximity';
    $form['find_location_search']['#attributes']['data-id'] = 'location-proximity-search-input';

    $form['#id'] = 'views-exposed-form-find-a-location-find-location-proximity';

    // Explicitly add locations terms to proximity input.
    $locations = \Drupal::request()->get('location_latlon');
    $form['location_latlon']['value']['#attributes']['data-id'] = 'location-proximity-value-input';
    if (!empty($locations) && is_array($locations) && empty($form['location_latlon']['value']['#attributes']['value'])) {
      $form['location_latlon']['value']['#attributes']['value'] = Xss::filter($locations['value']);
      $form['location_latlon']['distance']['from']['#attributes']['value'] = Xss::filter($locations['distance']['from']);
    }

    // Explicitly add search term to search input.
    $search_term = Xss::filter(\Drupal::request()->get('find_location_search'));
    if (!empty($search_term) && empty($form['find_location_search']['#attributes']['value'])) {
      $form['find_location_search']['#attributes']['value'] = $search_term;
    }

    $classes = [];
    if (isset($form['#attributes']['class'])) {
      $classes = $form['#attributes']['class'];
    }
    $classes[] = 'form-autocomplete';
    $classes[] = 'form-section__item--group';
    $form['#attributes']['class'] = $classes;

    $build['exposed_form'] = $form;

    $url = Url::fromRoute('view.' . $view_id . '.' . $view_display_id);
    $location_link = [
      '#title' => t('Use my location'),
      '#type' => 'link',
      '#attributes' => [
        'class' => [
          'js-use-my-location',
          'link',
          'link--italic',
        ],
        'data-view' => 'views-exposed-form-find-a-location-find-location',
      ],
      '#url' => $url,
    ];
    $build['exposed_form']['location_latlon']['value']['location_link'] = $location_link;
    $build['#cache'] = ['max-age' => 0];

    return $build;
  }

}

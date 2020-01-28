<?php

namespace Drupal\towerhealth_misc\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\views\Views;
use Drupal\Component\Utility\Xss;

/**
 * Provides a 'Location search block for hero region' block.
 *
 * @Block(
 *  id = "location_hero_block",
 *  admin_label = @Translation("Location hero block"),
 *  category = "Views"
 * )
 */
class LocationHeroBlock extends BlockBase {

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

    unset($form['location_latlon']);
    unset($form['facets']);
    $form['find_location_search']['#attributes']['data-id'] = 'location-hero-block';
    $form['find_location_search']['#attributes']['class'][] = 'listing-search__input';
    $form['#info']['filter-search_api_fulltext']['label'] = t('Search Locations');
    $form['#id'] = 'views-exposed-form-find-a-location-find-location-hero';

    // Explicitly add search term to search input.
    $search_term = Xss::filter(\Drupal::request()->get('find_location_search'));
    if (!empty($search_term) && empty($form['find_location_search']['#attributes']['value'])) {
      $form['find_location_search']['#attributes']['value'] = $search_term;
    }

    // Set classes.
    $classes = [];
    if (isset($form['#attributes']['class'])) {
      $classes = $form['#attributes']['class'];
    }
    $classes[] = 'form-autocomplete';
    $form['#attributes']['class'] = $classes;

    $build['exposed_form'] = $form;

    $build['exposed_form']['actions']['#attributes']['class'][] = 'listing-search__actions';

    $build['exposed_form']['#attributes']['class'][] = 'listing-search';
    $build['#cache'] = ['max-age' => 0];

    return $build;
  }

}

<?php

namespace Drupal\towerhealth_misc\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\views\Views;
use Drupal\Component\Utility\Xss;

/**
 * Provides a 'Search block for hero region' block.
 *
 * @Block(
 *  id = "search_hero_block",
 *  admin_label = @Translation("Search hero block"),
 *  category = "Views"
 * )
 */
class SearchHeroBlock extends BlockBase {

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

    $view_id = 'search';
    $view_display_id = 'search_page';

    /** @var \Drupal\views\ViewExecutable $view */
    $view = Views::getView($view_id);
    $view->setDisplay($view_display_id);

    $view->initHandlers();

    $display = $view->getDisplay();

    /** @var \Drupal\views\Plugin\views\exposed_form\ExposedFormPluginInterface $exposed_form */
    $exposed_form = $display->getPlugin('exposed_form');

    // Set classes.
    $classes = [];
    if (isset($form['#attributes']['class'])) {
      $classes = $form['#attributes']['class'];
    }
    $form['#attributes']['class'] = $classes;

    $form = $exposed_form->renderExposedForm(TRUE);

    unset($form['search_api_datasource']);
    unset($form['type']);
    $form['site_search']['#attributes']['class'][] = 'listing-search__input';
    $form['site_search']['#attributes']['data-id'] = 'search-hero-block';
    $form['site_search']['#attributes']['placeholder'] = t('Search by Keyword');
    $form['#id'] = 'views-exposed-form-search-search-page-hero';

    // Explicitly add search term to search input.
    $search_term = Xss::filter(\Drupal::request()->get('site_search'));
    if (!empty($search_term) && empty($form['site_search']['#attributes']['value'])) {
      $form['find_services_search']['#attributes']['value'] = $search_term;
    }

    $build['exposed_form'] = $form;

    $build['exposed_form']['actions']['#attributes']['class'][] = 'listing-search__actions';

    $build['exposed_form']['#attributes']['class'][] = 'listing-search';
    $build['#cache'] = ['max-age' => 0];

    return $build;
  }

}

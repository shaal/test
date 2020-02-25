<?php

namespace Drupal\towerhealth_misc\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\views\Views;

/**
 * Provides a 'Search filter block' block.
 *
 * @Block(
 *  id = "search_filter_block",
 *  admin_label = @Translation("Search Filter BLock"),
 *  category = "Views"
 * )
 */
class SearchFilterBlock extends BlockBase {

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

    $form = $exposed_form->renderExposedForm(TRUE);

    $form['site_search']['#type'] = 'hidden';
    $search_term_id = $form['site_search']['#id'];
    $form['site_search']['#id'] = $search_term_id . '-filter';
    $form['site_search']['#attributes']['data-id'] = 'general-search-filter-input';

    $form['type']['#prefix'] = '<ul class="form-item--checkboxes form-item--inline">';
    $form['type']['#attributes']['data-id'] = 'general_search_filter__checkboxes';
    $form['type']['article']['#attributes']['data-id'] = 'general-search-filter--checkbox';
    $form['type']['location']['#attributes']['data-id'] = 'general-search-filter--checkbox';
    $form['type']['profile']['#attributes']['data-id'] = 'general-search-filter--checkbox';
    $form['type']['service']['#attributes']['data-id'] = 'general-search-filter--checkbox';
    $form['type']['all'] = [
      '#type' => 'checkbox',
      '#title' => 'All',
      '#attributes' => [
        'data-id' => 'general-search-filter--checkbox',
        'class' => ['js-search-all'],
      ],
      '#return_value' => '',
      '#default_value' => null,
      '#id' => 'edit-type-all',
    ];

    // Remove basic page as an option
    unset($form['type']['page']);

    $form['search_api_datasource']['entity:media']['#attributes']['data-id'] = 'general-search-filter--checkbox';
    $form['search_api_datasource']['#attributes']['data-id'] = 'general_search_filter__checkboxes';
    $form['search_api_datasource']['#suffix'] = '</ul>';

    $form['#id'] = 'views-exposed-form-general-search-filters';

    $classes = [];
    if (isset($form['#attributes']['class'])) {
      $classes = $form['#attributes']['class'];
    }
    $classes[] = 'form-autocomplete';
    $form['#attributes']['class'] = $classes;

    $build['exposed_form'] = $form;

    $build['exposed_form']['#attributes']['class'] = [
      'form-section__item',
      'form-section__item--full',
    ];
    $build['#cache'] = ['max-age' => 0];

    return $build;
  }

}

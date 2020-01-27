<?php

namespace Drupal\towerhealth_misc\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\views\Views;
use Drupal\Core\Url;
use Drupal\Component\Utility\Xss;

/**
 * Provides a 'Provider proximity filter' block.
 *
 * @Block(
 *  id = "provider_proximity_filter",
 *  admin_label = @Translation("Provider proximity filter"),
 *  category = "Views"
 * )
 */
class ProviderProximityBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // We don't have much context during build, so we look at the current route
    // and try to decide if we're on a Views page.
    /** @var \Symfony\Component\HttpFoundation\ParameterBag $currentRouteParameters */

    $view_id = 'find_a_provider';
    $view_display_id = 'find_doctor';

    /** @var \Drupal\views\ViewExecutable $view */
    $view = Views::getView($view_id);
    $view->setDisplay($view_display_id);

    $view->initHandlers();

    $display = $view->getDisplay();

    /** @var \Drupal\views\Plugin\views\exposed_form\ExposedFormPluginInterface $exposed_form */
    $exposed_form = $display->getPlugin('exposed_form');

    $form = $exposed_form->renderExposedForm(TRUE);

    $form['find_doctor_search']['#type'] = 'hidden';
    $search_term_id = $form['find_doctor_search']['#id'];
    $form['find_doctor_search']['#id'] = $search_term_id . '-proximity';
    $form['find_doctor_search']['#attributes']['data-id'] = 'provider-proximity-search-input';

    $form['#id'] = 'views-exposed-form-find-a-provider-find-doctor-proximity';

    $classes = [];
    if (isset($form['#attributes']['class'])) {
      $classes = $form['#attributes']['class'];
    }
    $classes[] = 'form-autocomplete';
    $form['#attributes']['class'] = $classes;

    // Explicitly add locations terms to proximity input.
    $locations = \Drupal::request()->get('provider_location_latlong');
    if (!empty($locations) && is_array($locations) && empty($form['provider_location_latlong']['value']['#attributes']['value'])) {
      $form['provider_location_latlong']['value']['#attributes']['value'] = Xss::filter($locations['value']);
      $form['provider_location_latlong']['distance']['from']['#attributes']['value']  = Xss::filter($locations['distance']['from']);
    }

    // Explicitly add search term to search input.
    $search_term = Xss::filter(\Drupal::request()->get('find_doctor_search'));
    if (!empty($search_term) && empty($form['find_doctor_search']['#attributes']['value'])) {
      $form['find_doctor_search']['#attributes']['value'] = $search_term;
    }

    $build['exposed_form'] = $form;

    $build['exposed_form']['#attributes']['class'] = [
      'form-section__item--group',
      'form-section__item--oneSixth-twoColumns',
      'form-section__item',
    ];

    $url = Url::fromRoute('view.' . $view_id . '.' . $view_display_id);

    $location_link = [
      '#title' => t('Use my location'),
      '#type' => 'link',
      '#attributes' => [
        'class' => [
          'js-use-my-location',
          'link',
          'link--small',
        ],
        'data-view' => 'views-exposed-form-find-a-provider-find-doctor-proximity',
      ],
      '#url' => $url,
    ];
    $build['exposed_form']['actions']['location_link'] = $location_link;

    return $build;
  }

}

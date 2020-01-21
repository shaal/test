<?php

namespace Drupal\towerhealth_misc\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\views\Views;
use Drupal\Core\Url;

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

    unset($form['find_location_search']);

    $form['#id'] = 'views-exposed-form-find-a-location-find-location-proximity';

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
          'link--small',
        ],
        'data-view' => 'views-exposed-form-find-a-location-find-location',
      ],
      '#url' => $url,
    ];
    $build['exposed_form']['actions']['location_link'] = $location_link;

    return $build;
  }

}

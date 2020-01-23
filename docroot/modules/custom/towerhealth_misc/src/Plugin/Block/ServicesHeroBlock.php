<?php

namespace Drupal\towerhealth_misc\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\views\Views;

/**
 * Provides a 'Services search block for hero region' block.
 *
 * @Block(
 *  id = "service_hero_block",
 *  admin_label = @Translation("Services hero block"),
 *  category = "Views"
 * )
 */
class ServicesHeroBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // We don't have much context during build, so we look at the current route
    // and try to decide if we're on a Views page.
    /** @var \Symfony\Component\HttpFoundation\ParameterBag $currentRouteParameters */

    $view_id = 'find_a_service';
    $view_display_id = 'find_service';

    /** @var \Drupal\views\ViewExecutable $view */
    $view = Views::getView($view_id);
    $view->setDisplay($view_display_id);

    $view->initHandlers();

    $display = $view->getDisplay();

    /** @var \Drupal\views\Plugin\views\exposed_form\ExposedFormPluginInterface $exposed_form */
    $exposed_form = $display->getPlugin('exposed_form');

    $classes = [];
    if (isset($form['#attributes']['class'])) {
      $classes = $form['#attributes']['class'];
    }
    $classes[] = 'form-autocomplete';
    $form['#attributes']['class'] = $classes;

    $form = $exposed_form->renderExposedForm(TRUE);

    $form['find_services_search']['#attributes']['class'][] = 'listing-search__input';
    $form['find_services_search']['#attributes']['data-id'] = 'services-hero-block';

    $form['#id'] = 'views-exposed-form-find-a-service-find-service-hero';

    $build['exposed_form'] = $form;

    $build['exposed_form']['actions']['#attributes']['class'][] = 'listing-search__actions';

    $build['exposed_form']['#attributes']['class'][] = 'listing-search';

    return $build;
  }

}

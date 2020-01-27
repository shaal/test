<?php

namespace Drupal\towerhealth_misc\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\views\Views;

/**
 * Provides a 'Provider search block for header region' block.
 *
 * @Block(
 *  id = "provider_home_hero_block",
 *  admin_label = @Translation("Provider home hero block"),
 *  category = "Views"
 * )
 */
class ProviderHomeHeroBlock extends BlockBase {

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

    unset($form['provider_location_latlong']);
    unset($form['facets']);
    $form['#id'] = 'views-exposed-form-find-a-provider-find-provider-home-hero';

    $classes = [];
    if (isset($form['#attributes']['class'])) {
      $classes = $form['#attributes']['class'];
    }
    $classes[] = 'form-autocomplete';
    $form['#attributes']['class'] = $classes;

    $form['actions']['#attributes']['data-id'] = 'provider-home-hero-block';
    // Set the submit button classes.
    if (isset($form['actions']['submit']['#attributes']['class'])) {
      $classes = $form['actions']['submit']['#attributes']['class'];
    }
    else {
      $classes = [];
    }

    $form['actions']['submit']['#attributes']['class'] = array_merge($classes, [
      'button',
      'button--primary',
    ]);

    $build['exposed_form'] = $form;

    return $build;
  }

}

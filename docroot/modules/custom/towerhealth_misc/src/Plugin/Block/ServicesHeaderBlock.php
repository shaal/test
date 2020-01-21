<?php

namespace Drupal\towerhealth_misc\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\views\Views;
use Drupal\Core\Url;

/**
 * Provides a 'Location search block for header region' block.
 *
 * @Block(
 *  id = "services_header_block",
 *  admin_label = @Translation("Services header block"),
 *  category = "Views"
 * )
 */
class ServicesHeaderBlock extends BlockBase {

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

    $form = $exposed_form->renderExposedForm(TRUE);

    $form['#id'] = 'views-exposed-form-find-a-service-find-service-header';

    $classes = [];
    if (isset($form['#attributes']['class'])) {
      $classes = $form['#attributes']['class'];
    }
    $classes[] = 'form-autocomplete';
    $classes[] = 'main-menu__form';
    $form['#attributes']['class'] = $classes;

    $form['actions']['#attributes']['data-id'] = 'services_header_block';
    // Set the submit button classes
    if (isset($form['actions']['submit']['#attributes']['class'])) {
      $classes = $form['actions']['submit']['#attributes']['class'];
    }
    else {
      $classes = [];
    }

    $form['actions']['submit']['#attributes']['class'] = array_merge($classes,[
      'button',
      'button--small',
      'button--primary',
    ]);

    $build['exposed_form'] = $form;

    return $build;
  }

}

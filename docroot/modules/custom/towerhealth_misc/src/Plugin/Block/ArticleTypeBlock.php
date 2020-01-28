<?php

namespace Drupal\towerhealth_misc\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\views\Views;
use Drupal\Core\Url;

/**
 * Provides an 'Article proximity filter' block.
 *
 * @Block(
 *  id = "article_type_filter",
 *  admin_label = @Translation("Article type filter"),
 *  category = "Views"
 * )
 */
class ArticleTypeBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // We don't have much context during build, so we look at the current route
    // and try to decide if we're on a Views page.
    /** @var \Symfony\Component\HttpFoundation\ParameterBag $currentRouteParameters */

    $view_id = 'article_listing';
    $view_display_id = 'article_listing_page';

    /** @var \Drupal\views\ViewExecutable $view */
    $view = Views::getView($view_id);
    $view->setDisplay($view_display_id);

    $view->initHandlers();

    $display = $view->getDisplay();

    /** @var \Drupal\views\Plugin\views\exposed_form\ExposedFormPluginInterface $exposed_form */
    $exposed_form = $display->getPlugin('exposed_form');

    $form = $exposed_form->renderExposedForm(TRUE);

    $form['#id'] = 'views-exposed-form-article-listing-article-listing-page-type';

    $classes = [];
    if (isset($form['#attributes']['class'])) {
      $classes = $form['#attributes']['class'];
    }
    $form['#attributes']['class'] = $classes;

    $build['exposed_form'] = $form;

    $build['exposed_form']['#attributes']['class'] = [
      'form-section__item--group',
      'form-section__item',
    ];

    return $build;
  }

}

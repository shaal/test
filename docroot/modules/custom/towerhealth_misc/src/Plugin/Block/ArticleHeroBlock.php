<?php

namespace Drupal\towerhealth_misc\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\views\Views;

/**
 * Provides an 'Article search block for hero region' block.
 *
 * @Block(
 *  id = "article_hero_block",
 *  admin_label = @Translation("Article hero block"),
 *  category = "Views"
 * )
 */
class ArticleHeroBlock extends BlockBase {

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

    $build['exposed_form'] = null;


    return $build;
  }

}

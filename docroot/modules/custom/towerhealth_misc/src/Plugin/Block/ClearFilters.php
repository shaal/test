<?php

namespace Drupal\towerhealth_misc\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Provides a 'Clear Filters' block.
 *
 * @Block(
 *  id = "clear_filters",
 *  admin_label = @Translation("Clear filters"),
 *  category = "Views"
 * )
 */
class ClearFilters extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // We don't have much context during build, so we look at the current route
    // and try to decide if we're on a Views page.
    /** @var \Symfony\Component\HttpFoundation\ParameterBag $currentRouteParameters */
    $currentRouteParameters = \Drupal::routeMatch()->getParameters();

    $view_id = $currentRouteParameters->get('view_id', FALSE);
    $view_display_id = $currentRouteParameters->get('display_id', FALSE);

    // We don't want to build the block if this is not a Views page.
    if (!($view_id && $view_display_id)) {
      return [];
    }

    $build['content']['title'] = [
      '#type' => 'markup',
      '#markup' => '<h2>' . t('Filter By:') . '</h2>',
    ];

    $route = 'view.' . $view_id . '.' . $view_display_id;

    $build['content']['link'] = Link::createFromRoute('Clear all Filters', $route)->toRenderable();

    return $build;
  }

}

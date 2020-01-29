<?php

namespace Drupal\towerhealth_misc\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Component\Utility\Xss;

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
    $currentRouteParameters = \Drupal::routeMatch()->getParameters();

    $view_id = $currentRouteParameters->get('view_id', FALSE);
    $view_display_id = $currentRouteParameters->get('display_id', FALSE);

    // We don't want to build the block if this is not a Views page.
    if (!($view_id && $view_display_id)) {
      return [];
    }

    $key = '';

    if ($view_id == 'find_a_location') {
      $key = 'find_location_search';
    }
    $search_term = Xss::filter(\Drupal::request()->get($key));

    $build['content']['title'] = [
      '#type' => 'markup',
      '#markup' => '<span>' . t('Filter') . '</span>',
    ];

    $route = 'view.' . $view_id . '.' . $view_display_id;

    $build['content']['link'] = Link::createFromRoute('Clear all Filters', $route, [$key => $search_term])->toRenderable();

    return $build;
  }

}

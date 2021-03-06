<?php

namespace Drupal\towerhealth_misc\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an 'Simple Listing header block for hero region' block.
 *
 * @Block(
 *  id = "listing_hero_block",
 *  admin_label = @Translation("Listing hero block"),
 *  category = "Views"
 * )
 */
class ListingHeroBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $route = \Drupal::routeMatch()->getCurrentRouteMatch()->getRouteObject();
    $title = $route->getDefault('_title');

    $build['#label'] = $title;

    return $build;
  }

}

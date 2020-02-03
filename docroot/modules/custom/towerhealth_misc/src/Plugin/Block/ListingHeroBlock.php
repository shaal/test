<?php

namespace Drupal\towerhealth_misc\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\views\Views;

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
    $build = '';
    dump('Het');

    return $build;
  }

}

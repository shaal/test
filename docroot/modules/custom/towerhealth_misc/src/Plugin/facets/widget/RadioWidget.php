<?php

namespace Drupal\towerhealth_misc\Plugin\facets\widget;

use Drupal\facets\Plugin\facets\widget\LinksWidget;

/**
 * The radios widget.
 *
 * @FacetsWidget(
 *   id = "radio_widget",
 *   label = @Translation("List of radios"),
 *   description = @Translation("A configurable widget that shows a list of radio options"),
 * )
 */
class RadioWidget extends LinksWidget {

  /**
   * {@inheritdoc}
   */
  protected function appendWidgetLibrary(array &$build) {
    $build['#attributes']['class'][] = 'js-facets-radio-links';
    $build['#attached']['library'][] = 'towerhealth_misc/drupal.towerhealth_misc.radio-widget';
  }

}

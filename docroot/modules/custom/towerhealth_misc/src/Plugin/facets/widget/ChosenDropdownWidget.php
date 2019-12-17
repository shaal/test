<?php

namespace Drupal\towerhealth_misc\Plugin\facets\widget;

use Drupal\facets\Plugin\facets\widget\LinksWidget;

/**
 * The radios widget.
 *
 * @FacetsWidget(
 *   id = "choden_dropdown_widget",
 *   label = @Translation("Dropdown widget that use chosen."),
 *   description = @Translation("A configurable widget that shows a select list using chosen."),
 * )
 */
class ChosenDropdownWidget extends LinksWidget {

  /**
   * {@inheritdoc}
   */
  protected function appendWidgetLibrary(array &$build) {
    $build['#attributes']['class'][] = 'js-facets-chosen-links';
    $build['#attached']['library'][] = 'towerhealth_misc/drupal.towerhealth_misc.chosen-dropdown-widget';
  }

}

<?php

namespace Drupal\towerhealth_misc\Plugin\facets\widget;

use Drupal\facets\Plugin\facets\widget\LinksWidget;
use Drupal\Core\Form\FormStateInterface;
use Drupal\facets\FacetInterface;

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
  public function defaultConfiguration() {
    return [
      'default_option_label' => 'Choose',
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function build(FacetInterface $facet) {
    $build = parent::build($facet);
    $build['#attributes']['class'][] = 'js-facets-chosen-links';
    $build['#attached']['drupalSettings']['facets']['dropdown_widget'][$facet->id()]['facet-default-option-label'] = $this->getConfiguration()['default_option_label'];
    $build['#attached']['library'][] = 'towerhealth_misc/drupal.towerhealth_misc.chosen-dropdown-widget';
    $build['#attached']['library'][] = 'facets/drupal.facets.general';

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state, FacetInterface $facet) {
    $config = $this->getConfiguration();

    $form += parent::buildConfigurationForm($form, $form_state, $facet);

    $form['default_option_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Default option label'),
      '#default_value' => $config['default_option_label'],
    ];

    return $form;
  }

}

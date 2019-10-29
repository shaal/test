<?php

namespace Drupal\towerhealth_misc\Plugin\facets\widget;

use Drupal\facets\Plugin\facets\widget\LinksWidget;
use Drupal\Core\Url;
use Drupal\facets\FacetInterface;
use Drupal\facets\Result\Result;

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
  public function build(FacetInterface $facet) {
    $build = parent::build($facet);

    $results = $build['#facet']->getResults();

    foreach($results as $result) {
      dump($result->getFacetSourceId());
    }

    $urlProcessorManager = \Drupal::service('plugin.manager.facets.url_processor');
    $url_processor = $urlProcessorManager->createInstance($facet->getFacetSourceConfig()->getUrlProcessorName(), ['facet' => $facet]);
    $active_filters = $url_processor->getActiveFilters();

    unset($active_filters[$facet->id()]);

    // Only if there are still active filters, use url generator.
    if ($active_filters) {
      $url = \Drupal::service('facets.utility.url_generator')
        ->getUrl($active_filters, FALSE);
    }
    else {
      $request = \Drupal::request();
      $url = Url::createFromRequest($request);
      $params = $request->query->all();
      unset($params[$url_processor->getFilterKey()]);
      $url->setOption('query', $params);
    }
    kint($url_processor);

    $items = $build['#items'];
    foreach ($items as $id => $item) {
      dump($item['#url']);
      dump($item['#url']->toString());
    }

    $result_item = new Result($facet, 'reset_all', t('All Providers'), 2);
    $result_item->setActiveState(FALSE);
    $result_item->setUrl($url);

    $item = $this->prepareLink($result_item);

    // Put reset facet link on first place.
    array_unshift($build['#items'], $item);

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  protected function appendWidgetLibrary(array &$build) {

    /*$item = [
      '#type' => 'link'
    ]*/
    $build['#attributes']['class'][] = 'js-facets-radio-links';
    $build['#attached']['library'][] = 'towerhealth_misc/drupal.towerhealth_misc.radio-widget';
  }

}

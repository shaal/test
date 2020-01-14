/**
 * @file
 * Transforms links into checkboxes.
 */

(function ($, Drupal) {

  'use strict';

  Drupal.facets = Drupal.facets || {};
  Drupal.behaviors.facetsRadioWidget = {
    attach: function (context) {
      Drupal.facets.makeRadioButtons(context);
    }
  };

  /**
   * Turns all facet links into radio buttons.
   */
  Drupal.facets.makeRadioButtons = function (context) {
    // Find all checkbox facet links and give them a checkbox.
    var $radioWidgets = $('.js-facets-radio-links', context)
      .once('facets-radio-transform');

    if ($radioWidgets.length > 0) {
      $radioWidgets.each(function (index, widget) {
        var $widget = $(widget);
        var $widgetLinks = $widget.find('li > a');

        // Add correct CSS selector for the widget. The Facets JS API will
        // register handlers on that element.
        $widget.addClass('form-item--radios form-item--inline');

        // Transform links to radio buttons.
        $widgetLinks.each(Drupal.facets.makeRadioButton);
      });

      // We have to trigger attaching of behaviours, so that Facets JS API can
      // register handlers on checkbox widgets.
      Drupal.attachBehaviors(context, Drupal.settings);
    }

    // Set indeterminate value on parents having an active trail.
    $('.facet-item--expanded.facet-item--active-trail > input').prop('indeterminate', true);
  };

  /**
   * Replace a link with a checked checkbox.
   */
  Drupal.facets.makeRadioButton = function () {
    console.log($(this));
    var $link = $(this);
    var active = $link.hasClass('is-active');
    var description = $link.html();
    var href = $link.attr('href');
    var id = $link.data('drupal-facet-item-id');

    var radio = $('<input type="radio" class="radio-checkbox">')
      .attr('id', id)
      .data($link.data())
      .data('facetsredir', href);
    var label = $('<label for="' + id + '">' + description + '</label>');

    radio.on('change.facets', function (e) {
      e.preventDefault();

      var $widget = $(this).closest('.js-facets-widget');

      $(this).parents('.facet-item').siblings().find('.radio-checkbox').attr('checked', false);

      Drupal.facets.disableFacet($widget);
      $widget.trigger('facets_filter', [href]);
    });

    if (active) {
      radio.attr('checked', true);
      label.find('.js-facet-deactivate').remove();
    }

    $link.before(label).before(radio).hide();

  };

  /**
   * Disable all facet checkboxes in the facet and apply a 'disabled' class.
   *
   * @param {object} $facet
   *   jQuery object of the facet.
   */
  Drupal.facets.disableFacet = function ($facet) {
    $facet.addClass('facets-disabled');
    $('input.facets-radio', $facet).click(Drupal.facets.preventDefault);
    $('input.facets-radio', $facet).attr('disabled', true);
  };

  /**
   * Event listener for easy prevention of event propagation.
   *
   * @param {object} e
   *   Event.
   */
  Drupal.facets.preventDefault = function (e) {
    e.preventDefault();
  };

})(jQuery, Drupal);

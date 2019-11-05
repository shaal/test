/**
 * @file
 * Expands the behaviour of the default autocompletion.
 */

(function ($, Drupal, drupalSettings) {

  'use strict';

  // As a safety precaution, bail if the Drupal Core autocomplete framework is
  // not present.
  if (!Drupal.autocomplete) {
    return;
  }

  var autocomplete = {};


  /**
   * Attaches our custom autocomplete settings to all affected fields.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Attaches the autocomplete behaviors.
   */
  Drupal.behaviors.towerHealthAutocomplete = {
    attach: function (context, settings) {
      // Find all our fields with autocomplete settings
      $(context)
        .find('.find-care-autocomplete')
        .once('tower-health-autocomplete')
        .each(function (i) {
          var uiAutocomplete = $(this).data('ui-autocomplete');
          if (!uiAutocomplete) {
            return;
          }
          var $element = uiAutocomplete.menu.element;
          $element.addClass('autocomplete-search');

          // Add labels to the dropdown
          uiAutocomplete._renderItem = function (ul, item) {
            if (item.grouping) {
              return $('<li>').addClass('autocomplete-search__label').html(item.label).appendTo(ul);
            }

            return $('<li>').append($('<a>').html(item.label)).appendTo(ul);
          };

          // Override the "select" callback of the jQuery UI autocomplete.
          var oldSelect = uiAutocomplete.options.select;
          uiAutocomplete.options.select = function (event, ui) {
            // If this is a label and not a real suggestion then return false.
            if (ui.item.grouping) {
              return false;
            }

            // If this is a URL suggestion, instead of autocompleting we
            // redirect the user to that URL.
            if (ui.item.url) {
              location.href = ui.item.url;
              return false;
            }

            $(this).val(ui.item.value);

            $(this).parents('form').submit();
          };
        });
    }
  };

  Drupal.SearchApiAutocomplete = autocomplete;

})(jQuery, Drupal, drupalSettings);

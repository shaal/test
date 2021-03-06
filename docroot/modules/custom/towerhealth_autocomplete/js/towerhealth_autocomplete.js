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

  $.fn.filterByData = function (prop, val) {
    var $self = this;
    if (typeof val === 'undefined') {
      return $self.filter(
        function () { return typeof $(this).data(prop) !== 'undefined'; }
      );
    }
    return $self.filter(
      function () { return $(this).data(prop) == val; }
    );
  };


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
          // Set a data attribute to append dropdown too.
          $(this).data('autocomplete-id', 'id-' + i);
          // Get the width of the input.
          var width = $(this).width();
          var $element = uiAutocomplete.menu.element;
          // Temporarily remove autocomplete class to fix protoype conflict.
          $element.addClass('autocomplete-search drupal');
          $element.css('width', width);

          // Add labels to the dropdown
          uiAutocomplete._renderItem = function (ul, item) {
            if (item.grouping) {
              return $('<li>').addClass('autocomplete-search__label').html(item.label).appendTo(ul);
            }

            return $('<li>').addClass('autocomplete-search__item').append($('<a>').html(item.label)).appendTo(ul);
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
      $('.autocomplete-search.ui-autocomplete').each(function (i) {
        var id = 'id-' + i;
        var input = $('.find-care-autocomplete').filterByData('autocomplete-id', id).parent();
        $(this).data('autocomplete-id', id);
        $(this).appendTo(input);
      });
    }
  };

  Drupal.SearchApiAutocomplete = autocomplete;

})(jQuery, Drupal, drupalSettings);

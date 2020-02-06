/**
 * @file
 * Expands the behaviour of the default autocompletion.
 */

(function ($, Drupal, drupalSettings) {

  /**
   * Attaches our custom autocomplete settings to all affected fields.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Attaches the autocomplete behaviors.
   */
  Drupal.behaviors.searchListingFilter = {
    attach: function (context, settings) {

      $('.form-checkbox').on('change', function() {
        $(this).parents('#views-exposed-form-search-search-page').submit();
      });
    }
  }
})(jQuery, Drupal, drupalSettings);

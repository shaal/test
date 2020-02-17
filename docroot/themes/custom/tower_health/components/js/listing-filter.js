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
        if ($(this).hasClass('js-search-all')) {
          $('.form-checkbox').not(this).prop('checked', false);
        }
        $(this).parents('#views-exposed-form-search-search-page').submit();
      });

      var urlParams = new URLSearchParams(window.location.search);
      var params = urlParams.toString();

      if (params.length === 0) {
        $('.js-search-all').prop('checked', true);
      }
    }
  }
})(jQuery, Drupal, drupalSettings);

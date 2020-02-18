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
        else {
          $('.form-checkbox.js-search-all').prop('checked', false);
        }
        $(this).parents('#views-exposed-form-search-search-page').submit();
      });

      var urlParams = new URLSearchParams(window.location.search);
      var search_terms = urlParams.get('site_search');
      var keys = urlParams.keys();
      var key_count = 0;

      for(key of keys) {
        ++key_count;
      }

      if (key_count === 0 || (search_terms.length !== 0 && key_count === 1)) {
        $('.js-search-all').prop('checked', true);
      }
    }
  }
})(jQuery, Drupal, drupalSettings);

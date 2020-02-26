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
      var params = urlParams.toString();
      var keys_object = urlParams.keys();
      var keys = [];

      for(var key of keys_object) {
        keys.push(key);
      }

      if (keys.length === 0 || params === '=' || (keys.includes('site_search') && keys.length === 1) || (keys.includes('page') && keys.length === 1) || (keys.includes('site_search') && keys.includes('page') && keys.length === 2)) {
        $('.js-search-all').prop('checked', true);
      }
    }
  }
})(jQuery, Drupal, drupalSettings);

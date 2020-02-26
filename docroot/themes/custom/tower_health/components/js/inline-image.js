/**
 * @file
 * Expands the behaviour of the default autocompletion.
 */

(function ($, Drupal, drupalSettings) {

  /**
   * Attaches inline image class to figure element.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Attaches the autocomplete behaviors.
   */
  Drupal.behaviors.searchListingFilter = {
    attach: function (context, settings) {

      $('figure.figure--inline').find('.figure--inline-mobile-full-width').each(function () {
        $(this).removeClass('figure--inline-mobile-full-width');
        $(this).parent().addClass('figure--inline-mobile-full-width');
      });
    }
  }
})(jQuery, Drupal, drupalSettings);

/**
 * @file
 * A JavaScript file containing the main menu functionality (small/large screen)
 *
 */

// JavaScript should be made compatible with libraries other than jQuery by
// wrapping it with an "anonymous closure". See:
// - https://drupal.org/node/1446420
// - http://www.adequatelygood.com/2010/3/JavaScript-Module-Pattern-In-Depth


// (function ($, Drupal) { // UNCOMMENT IF DRUPAL.
//
//   Drupal.behaviors.mainMenu = {
//     attach: function (context) {

(function ($) { // REMOVE IF DRUPAL.

  'use strict';

  // toggle
  var searchToggle = $('#search-toggle');
  // search wrapper
  var search = $('.header__search');
  // search drawer
  var searchDrawer = $('#search-drawer');

  var menu_toggle = $('#toggle-expand');
  var menu_drawer = $('.main-nav__container');

  function closeNav() {
    menu_drawer.removeClass('is-active');
    menu_toggle.removeClass('is-active');

    if ( $(window).width() < 900 ) {
      menu_drawer.attr("aria-expanded", "false");
    }
  }

  // Hide or show Search
  function openSearch() {
    search.addClass('is-active');
    searchToggle.addClass('is-active');
    searchDrawer.attr("aria-expanded", "true");
    closeNav();
  }

  function closeSearch() {
    search.removeClass('is-active');
    searchToggle.removeClass('is-active');
    searchDrawer.attr("aria-expanded", "false");
  }

})(jQuery); // REMOVE IF DRUPAL.

// })(jQuery, Drupal); // UNCOMMENT IF DRUPAL.

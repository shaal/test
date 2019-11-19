/**
 * @file
 * A JavaScript file containing the main menu functionality (small/large screen)
 *
 */

// JavaScript should be made compatible with libraries other than jQuery by
// wrapping it with an "anonymous closure". See:
// - https://drupal.org/node/1446420
// - http://www.adequatelygood.com/2010/3/JavaScript-Module-Pattern-In-Depth


// (function (Drupal) { // UNCOMMENT IF DRUPAL.
//
//   Drupal.behaviors.mainMenu = {
//     attach: function (context) {

(function ($) { // REMOVE IF DRUPAL.

  // Use context instead of document IF DRUPAL.
  var menu = $('.section-nav li');
  var menu_drawer = $('.section-nav--sub');

  // Mobile Menu Show/Hide.
  function openNav() {
    menu_drawer.addClass('is-active');
  }

  function closeNav() {
    menu_drawer.removeClass('is-active');
  }

  menu.on('click', function(e) {
    if ( $(menu_drawer).hasClass('is-active') ) {
      closeNav();
    } else {
      openNav();
    }
  });

})(jQuery); // REMOVE IF DRUPAL.

// })(Drupal); // UNCOMMENT IF DRUPAL.

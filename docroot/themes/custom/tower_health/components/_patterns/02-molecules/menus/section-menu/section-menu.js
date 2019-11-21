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

  var menu_toggle = $('#section-toggle-expand');
  var menu_drawer = $('.section-nav > ul');
  var child_toggle = $('.section-menu .expand-sub');

  // Mobile Menu Show/Hide.
  function openNav() {
    menu_drawer.addClass('is-active');
    menu_toggle.addClass('is-active');
  }

  function closeNav() {
    menu_drawer.removeClass('is-active');
    menu_toggle.removeClass('is-active');
  }

  menu_toggle.on('click', function() {
    if ( $(menu_drawer).hasClass('is-active') ) {
      closeNav();
    } else {
      openNav();
    }
  });

  // open and close sub drawers
  child_toggle.on('click', function() {
    if ( $(this).siblings('.section-menu--sub').hasClass('is-active') ) {
      $(this).siblings('.section-menu--sub').removeClass('is-active');
      $(this).parents('.section-menu__item--with-sub').removeClass('is-active');
      $(this).removeClass('is-active');
    } else {
      $('.section-menu--sub').removeClass('is-active');
      $('.section-menu .expand-sub').removeClass('is-active');
      $(this).siblings('.section-menu--sub').addClass('is-active');
      $(this).parents('.section-menu__item--with-sub').addClass('is-active');
      $(this).addClass('is-active');
    }
  });

})(jQuery); // REMOVE IF DRUPAL.

// })(Drupal); // UNCOMMENT IF DRUPAL.

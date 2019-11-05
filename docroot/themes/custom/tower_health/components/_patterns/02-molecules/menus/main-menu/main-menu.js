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
  var menu_toggle = $('#toggle-expand');
  var menu_drawer = $('.main-nav__container');
  var utility = $('#utility');
  var search = $('.header__search');

  // Mobile Menu Show/Hide.
  function openNav() {
    menu_drawer.addClass('is-active');
    menu_toggle.addClass('is-active');
    search.removeClass('is-active');
  }
  
  function closeNav() {
    menu_drawer.removeClass('is-active');
    menu_toggle.removeClass('is-active');
    search.removeClass('is-active');
  }
    
  $(menu_toggle).click(function () {
    if ( $(menu_drawer).hasClass('is-active') ) {
      closeNav();
    } else {
      openNav();
    }
  });
  
  $(document).keydown(function(event) { 
    // close menu on esc press
    if (event.keyCode == 27) { 
      closeNav();
    } 
  });
  

})(jQuery); // REMOVE IF DRUPAL.

// })(Drupal); // UNCOMMENT IF DRUPAL.

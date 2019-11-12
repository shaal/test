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
  var search = $('.header__search');
  
  var child_toggle = $('.expand-sub');

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
    
  menu_toggle.on('click', function() {
    if ( $(menu_drawer).hasClass('is-active') ) {
      closeNav();
    } else {
      openNav();
    }
  });
  
  // open and close sub drawers
  child_toggle.on('click', function() {
    if ( $(this).parents('.main-menu__item--with-sub').hasClass('is-active') ) {
      $(this).parents('.main-menu__item--with-sub').removeClass('is-active');
    } else {
      $(this).parents('.main-menu__item--with-sub').addClass('is-active');
      $(this).parents('.main-menu__item--with-sub').siblings().removeClass('is-active');
    }
  });
  
  // close the nav drawer if focus button is clicked
  $('.close-nav--mobile').on('click', function() {
    closeNav();
  });
  
  $('.close-nav--child').on('click', function() {
    $(this).parents().parents('.main-menu__item--with-sub').removeClass('is-active');
  });
  
  $(document).keydown(function(event) { 
    // close menu on esc press
    if (event.keyCode == 27) { 
      closeNav();
    } 
  });
  

})(jQuery); // REMOVE IF DRUPAL.

// })(Drupal); // UNCOMMENT IF DRUPAL.

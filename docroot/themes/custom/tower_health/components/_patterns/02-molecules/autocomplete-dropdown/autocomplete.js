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
  var child_toggle = $('.expand-sub');
  
  $('.form-autocomplete').on('click', function() {
      $(this).siblings(".autocomplete-search").toggleClass('is-active');
  });
  
  child_toggle.on('click', function() {
    if ( $(this).parents('.main-menu__item--with-sub').hasClass('is-active') ) {
      
    } else {
      $(this).siblings().children().find(".autocomplete-search").removeClass('is-active');
    }
  });
  

})(jQuery); // REMOVE IF DRUPAL.

// })(Drupal); // UNCOMMENT IF DRUPAL.

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
  var filter_toggle = $('.filter__toggle');
  var filters = $('.filter');
  var filter_drawer = $('.filter-grid-wrapper');

  // Mobile Menu Show/Hide.
  function openFilters() {
    filters.addClass('is-active');
    filter_drawer.attr("aria-expanded", "true");
  }
  
  function closeFilters() {
    filters.removeClass('is-active');
    filter_drawer.attr("aria-expanded", "false");
  }
  
  $(window).on('load', function() {
    if ( $(window).width() < 900 ) {
      filter_drawer.attr("aria-expanded", "false");
    } else {
      filter_drawer.attr("aria-expanded", "true");
    }
  });
    
  filter_toggle.on('click', function() {
    if ( filters.hasClass('is-active') ) {
      closeFilters();
    } else {
      openFilters();
    }
  });
  
  $(document).keydown(function(event) { 
    // close menu on esc press
    if (event.keyCode == 27) { 
      closeFilters();
    } 
  });
  

})(jQuery); // REMOVE IF DRUPAL.

// })(Drupal); // UNCOMMENT IF DRUPAL.

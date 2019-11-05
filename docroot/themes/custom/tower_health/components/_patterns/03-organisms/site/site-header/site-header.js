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
  var searchForm = $('#header__search-form');

  $(searchToggle).click(function(){
    if (search.hasClass('is-active')){
      search.removeClass('is-active');
    } else {
      search.addClass('is-active');
      $(this).blur();
    }
  });
        
  
  // close overlay and menus if a user clicks esx
  $(document).keydown(function(event) { 
    // close menu on esc press
    if (event.keyCode == 27) { 
      search.removeClass('is-active');
    } 
  });

})(jQuery); // REMOVE IF DRUPAL.

// })(jQuery, Drupal); // UNCOMMENT IF DRUPAL.

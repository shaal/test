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
  
    var handleFocusIn = function(evt) {
      $(evt.currentTarget).addClass("is-active");
      $(evt.currentTarget).children(".toggle-tip__drawer").attr("aria-expanded", "true");
    }

    var handleFocusOut = function(evt) {
     $(evt.currentTarget).removeClass("is-active");
     $(evt.currentTarget).children(".toggle-tip__drawer").attr("aria-expanded", "false");
    };

    var handleClick = function(evt) {
      evt.preventDefault();
      $(evt.currentTarget).toggleClass("is-active");
      
      if ( $(evt.currentTarget).children(".toggle-tip__drawer").attr('aria-expanded') == 'true' ) {
        $(evt.currentTarget).children(".toggle-tip__drawer").attr("aria-expanded", "false");
      } else {
        $(evt.currentTarget).children(".toggle-tip__drawer").attr("aria-expanded", "true");
      }
    }

    $('.toggle-tip').on({
      mouseenter : handleFocusIn,
      mouseleave : handleFocusOut,
      focusin    : handleFocusIn,
      focusout   : handleFocusOut,
      touchstart : handleClick
    });
  

})(jQuery); // REMOVE IF DRUPAL.

// })(Drupal); // UNCOMMENT IF DRUPAL.

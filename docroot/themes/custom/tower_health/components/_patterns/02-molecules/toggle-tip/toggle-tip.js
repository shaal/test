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
  
    function position_tooltip(e) {
      var tooltip = e.parents('.toggle-tip');
      var tooltip_drawer = e.siblings('.toggle-tip__drawer');
      var ww = $(window).outerWidth(); //window width
      var tl = tooltip.offset().left; //Tooltip left pos
      var left = tl + 200;
      var right = tl - 200;
      console.log(left);
      console.log(right);

      if ( left > ww && right >= 0 ) {
        tooltip_drawer.css({ 'left': 'auto', 'right': 0 });
      } else if ( left > ww && right < 0 ) {
        tooltip_drawer.css({ 'left': 'auto', 'right': 'auto' });
      } else {
        tooltip_drawer.css({ 'left': 0, 'right': 'auto' });
      }
    };
    

  
    var handleFocusIn = function(evt) {
      $(evt.currentTarget).parents('.toggle-tip').addClass("is-active");
      $(evt.currentTarget).siblings(".toggle-tip__drawer").attr("aria-expanded", "true");
      position_tooltip($(evt.currentTarget));
    };

    var handleFocusOut = function(evt) {
     $(evt.currentTarget).parents('.toggle-tip').removeClass("is-active");
     $(evt.currentTarget).siblings(".toggle-tip__drawer").attr("aria-expanded", "false");
     position_tooltip($(evt.currentTarget));
    };

    var handleClick = function(evt) {
      evt.preventDefault();
      $(evt.currentTarget).parents('.toggle-tip').toggleClass("is-active");
      position_tooltip($(evt.currentTarget));
      
      if ( $(evt.currentTarget).siblings(".toggle-tip__drawer").attr('aria-expanded') == 'true' ) {
        $(evt.currentTarget).siblings(".toggle-tip__drawer").attr("aria-expanded", "false");
      } else {
        $(evt.currentTarget).siblings(".toggle-tip__drawer").attr("aria-expanded", "true");
      }
    }

    $('.toggle-tip__toggle').on({
      mouseenter : handleFocusIn,
      mouseleave : handleFocusOut,
      focusin    : handleFocusIn,
      focusout   : handleFocusOut,
      touchstart : handleClick,
      click : handleClick
    });
  

})(jQuery); // REMOVE IF DRUPAL.

// })(Drupal); // UNCOMMENT IF DRUPAL.

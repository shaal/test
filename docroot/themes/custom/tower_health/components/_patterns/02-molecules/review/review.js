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
  var review_toggle = $('.review-listing__read-more');
  var review = $('.review');
  var review_drawer = $('.review__drawer');

  // Mobile Review Show/Hide.
  function openReviews() {
    review.addClass('is-active');
    review_drawer.attr("aria-expanded", "true");
  }
  
  function closeReviews() {
    review.removeClass('is-active');
    review_drawer.attr("aria-expanded", "false");
  }
    
  review_toggle.on('click', function() {
    if ( review.hasClass('is-active') ) {
      closeReviews();
    } else {
      openReviews();
    }
  });

})(jQuery); // REMOVE IF DRUPAL.

// })(Drupal); // UNCOMMENT IF DRUPAL.

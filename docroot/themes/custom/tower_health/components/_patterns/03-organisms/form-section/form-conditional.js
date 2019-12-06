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
  
  // unique form conditions
  $('.form-section__item[data-cond="appt-for-condition"]').change(function(){
    if ($(this).children().find('#radio1-appt').is(':checked')) {
      $(this).attr('data-cond-met', 'true');
      console.log('it is checked!');
    } else {
      $(this).attr('data-cond-met', 'false');
      console.log('it is not checked!');
    }
  });
  
  // for each item with data-cond-value 
  $('.form-section__item[data-cond-met]').each(function(){
    $(this).change(function() {
      var identifier = $(this).attr('data-cond');
      var currentItem = $(this);
      // if data-cond is true
      if ($(this).attr('data-cond-met') === 'true') {
        // find items with data-cond-by that matches this.data-cond-value
        // and remove class .conditional on items with data-cond-by that match
        $(this).parents('.form-section').find("[data-cond-by='" + identifier + "']").removeClass('form-section__item--conditional');
      } else if ($(this).attr('data-cond-met') === 'false') {
      // else if data-cond is false
        // find items with data-cond-by that matches this.data-cond-value
        // and add class to .condition on items with data-cond-by that match
        $(this).parents('.form-section').find("[data-cond-by='" + identifier + "']").addClass('form-section__item--conditional');
      }
    })
  });

})(jQuery); // REMOVE IF DRUPAL.

// })(Drupal); // UNCOMMENT IF DRUPAL.

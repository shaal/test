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

  // 'use strict';
  //
  // // toggle
  // var searchToggle = $('#search-toggle');
  // // search wrapper
  // var search = $('.header__search');
  //
  // var menu_toggle = $('#toggle-expand');
  // var menu_drawer = $('.main-nav__container');
  //
  // function closeNav() {
  //   menu_drawer.removeClass('is-active');
  //   menu_toggle.removeClass('is-active');
  // }
  //
  // // Hide or show Search
  // function openSearch() {
  //   search.addClass('is-active');
  //   searchToggle.addClass('is-active');
  //   closeNav();
  // }
  //
  // function closeSearch() {
  //   search.removeClass('is-active');
  //   searchToggle.removeClass('is-active');
  // }
  //
  // searchToggle.on('click', function() {
  //   if (search.hasClass('is-active')){
  //     closeSearch();
  //   } else {
  //     openSearch();
  //   }
  // });
  //
  // // close overlay and menus if a user clicks esx
  // $(document).keydown(function(event) {
  //   // close menu on esc press
  //   if (event.keyCode == 27) {
  //     closeSearch();
  //   }
  // });
  //
  // $('.close-search').on('click', function() {
  //   closeSearch();
  // });
  //
  // // measure height of header so mobile nav can take up available space.
  // // and so that
  // $(window).on('resize load reload', function() {
  //   if ( $(window).width() < 900 ) {
  //     var $headerHeight = $('.header__container').innerHeight();
  //     var $windowheight = $(window).height();
  //     var $math = $windowheight - $headerHeight;
  //     $('.main-nav__container').css('height', $math);
  //     $('.header__search-drawer').css('max-height', $math);
  //   } else {
  //     $('.main-nav__container').css('height', '');
  //     $('.header__search-drawer').css('max-height', '');
  //   }
  // });
  //
  // // position search drawer at desktop
  // $(window).on('resize load reload', function() {
  //   if ( $(window).width() > 900 && $(window).width() < 1200 ) {
  //     var $logo = $('.header-logo').innerHeight();
  //     $('.header__search-drawer').css('top', $logo);
  //   } else if ( $(window).width() > 1200 ) {
  //     var $offset = $('.main-menu').offset();
  //     var $top = $offset.top + 'px';
  //     $('.header__search-drawer').css('top', $top);
  //   } else {
  //     $('.header__search-drawer').css('top', ' ');
  //   }
  // });

})(jQuery); // REMOVE IF DRUPAL.

// })(jQuery, Drupal); // UNCOMMENT IF DRUPAL.

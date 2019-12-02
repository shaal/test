/**
 * @file
 * A JavaScript file for the site.
 *
 * @copyright Copyright 2019 Palantir.net
 */

// JavaScript should be made compatible with libraries other than jQuery by
// wrapping it with an "anonymous closure". See:
// - https://drupal.org/node/1446420
// - http://www.adequatelygood.com/2010/3/JavaScript-Module-Pattern-In-Depth

(function ($) {

  Drupal.behaviors.scrollAnimations = {
    attach: function (context, settings) {

      var $animation_elements = $('.js-section-animation');
      var $window = $(window);

      // For on scroll animations
      function check_if_in_view() {
        var window_height = $window.height();
        var window_top_position = $window.scrollTop();
        var window_bottom_position = (window_top_position + window_height);

        $.each($animation_elements, function() {
          var $element = $(this);
          var element_height = $element.outerHeight();
          var element_top_position = $element.offset().top;
          var element_bottom_position = (element_top_position + element_height);
          var element_middle_position = (element_top_position + ( element_height / 2 ));

          //check to see if this current container is within viewport
          if ((element_bottom_position >= window_top_position) &&
              (element_top_position <= window_bottom_position)) {
            $element.addClass('in-view');
          }
          
          if ((element_bottom_position >= window_top_position) &&
              (element_middle_position <= window_bottom_position)) {
            $element.addClass('in-mid-view');
          }
        });
      }

      $window.on('scroll resize', check_if_in_view);
      $window.trigger('scroll');

    }
  };

})(jQuery);

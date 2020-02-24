/**
 * @file
 * Expands the behaviour of the default autocompletion.
 */

(function ($, Drupal, drupalSettings) {

  /**
   * Attaches our custom autocomplete settings to all affected fields.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Attaches the autocomplete behaviors.
   */
  Drupal.behaviors.proximityMyLocation = {
    attach: function (context, settings) {
      let locationButton = $('.js-use-my-location');

      let setLocation = function (coordinates) {
        $('.js-set-my-location').val(coordinates);
      };

      locationButton.click(function(e){
        e.preventDefault();

        navigator.geolocation.getCurrentPosition(function(position) {
          var lat = position.coords.latitude;
          var long = position.coords.longitude;
          var point = new google.maps.LatLng(lat, long);
          new google.maps.Geocoder().geocode(
            {'latLng': point},
            function (res, status) {
              var address = res[0].address_components;
              var zip = '';
              $.each(address, function() {
                if (this.types[0] === 'postal_code') {
                  zip = this.short_name;
                }
              });
              setLocation(zip);
            }
          );
        });
      });
    }
  };

  /**
   * Turns all facet links into radio buttons.
   */
  Drupal.behaviors.proximitySubmitFilter = {
    attach: function (context, settings) {
      // Find all checkbox facet links and give them a checkbox.
      var locationInput = $('.js-set-my-location', context);
      var distanceInput = $('.js-proximity-distance', context);

      if (locationInput.length > 0) {
        locationInput.keyup(function() {
          if (($(this).val().length === 5)) {
            $(this).parents('form').submit();
          }
        });
      }

      distanceInput.on('change', function() {
        var optionSelected = $("option:selected", this);
        if (locationInput.val().length === 5) {
          $(this).val(optionSelected.val());
          $(this).parents('form').submit();
        }
      });
    }
  };

})(jQuery, Drupal, drupalSettings);

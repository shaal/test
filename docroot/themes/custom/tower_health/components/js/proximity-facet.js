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

})(jQuery, Drupal, drupalSettings);

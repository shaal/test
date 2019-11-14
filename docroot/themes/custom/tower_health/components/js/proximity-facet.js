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
  Drupal.behaviors.proximitySubmit = {
    attach: function (context, settings) {
      // Find all our fields with autocomplete settings
      let proximitySubmit = document.getElementById('find-a-location-proximity');
      let view = document.getElementById('views-exposed-form-find-a-location-find-location');

      let setLocationAndSubmitForm = function (coordinates, viewID) {
        $('#edit-proximity-value').val(coordinates);
        $('#views-exposed-form-' + viewID).submit();
      };


      proximitySubmit.onclick = function(e) {
        //view.submit();
        let radius = document.getElementById('edit-location-latlon-distance-from');
        let address = document.getElementById('edit-location-latlon-value');
        console.log(radius.options[radius.selectedIndex].value);
      };
    }
  };

})(jQuery, Drupal, drupalSettings);

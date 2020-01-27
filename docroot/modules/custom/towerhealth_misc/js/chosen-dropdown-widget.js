/**
 * @file
 * Transforms links into a dropdown list.
 */

(function ($) {

  'use strict';

  Drupal.facets = Drupal.facets || {};
  Drupal.behaviors.facetsChosenDropdownWidget = {
    attach: function (context, settings) {
      Drupal.facets.makeChosenDropdown(context, settings);
    }
  };

  /**
   * Turns all facet links into a dropdown with options for every link.
   *
   * @param {object} context
   *   Context.
   * @param {object} settings
   *   Settings.
   */
  Drupal.facets.makeChosenDropdown = function (context, settings) {
    // Find all dropdown facet links and turn them into an option.
    $('.js-facets-chosen-links').once('facets-dropdown-transform').each(function () {
      var $ul = $(this);
      var $links = $ul.find('.facet-item a');
      var $dropdown = $('<select multiple data-placeholder="Any">');

      // Preserve all attributes of the list.
      $ul.each(function() {
        $.each(this.attributes,function(idx, elem) {
            $dropdown.attr(elem.name, elem.value);
        });
      });
      // Remove the class which we are using for .once().
      $dropdown.removeClass('js-facets-chosen-links');
      $dropdown.addClass('facets-dropdown');
      $dropdown.addClass('js-facets-widget');
      $dropdown.addClass('js-facets-chosen-dropdown');
      $dropdown.addClass('form-item__select');

      var id = $(this).data('drupal-facet-id');
      // Add aria-labelledby attribute to reference label.
      $dropdown.attr('aria-labelledby', "facet_"+id+"_label");
      var default_option_label = settings.facets.dropdown_widget[id]['facet-default-option-label'];

      // Add empty text option first.
      var $default_option = $('<option />')
       .attr('value', '')
       .text(default_option_label);
       $dropdown.append($default_option);

      $ul.prepend('<li class="default-option element-invisible"><a href="" class="element-invisible">' + default_option_label + '</a></li>');

      var has_active = false;
      $links.each(function () {
        var $link = $(this);
        var active = $link.hasClass('is-active');
        var $option = $('<option />')
          .attr('value', $link.attr('href'))
          .data($link.data());
        if (active) {
          has_active = true;
          // Set empty text value to this link to unselect facet.
          $ul.find('.default-option a').attr("href", $link.attr('href'));
          $option.attr('selected', 'selected');
          $link.find('.js-facet-deactivate').remove();
        }
        $option.text($link.text());
        $dropdown.append($option);
      });

      // Go to the selected option when it's clicked.
      $dropdown.on('change.facets', function () {
        var anchor = $($ul).find('[data-drupal-facet-item-id="' + $(this).find(':selected').data('drupalFacetItemId') + '"]');
        var $linkElement = (anchor.length > 0) ? $(anchor) : $ul.find('.default-option a');
        var url = $linkElement.attr('href');

        $(this).trigger('facets_filter', [ url ]);
      });

      // Replace links with dropdown.
      $ul.after($dropdown).hide();
      Drupal.attachBehaviors($dropdown.parent()[0], Drupal.settings);

      $dropdown.chosen({
        width: "100%",
        placeholder_text_multiple: "Any",
        placeholder_text_single: "Any",
        search_contains: true,
      });
      console.log($dropdown.parents('form-section__item'));

      $dropdown.parents('.facets-widget-chosen-dropdown').addClass('js-complete');
    });
  };

})(jQuery);

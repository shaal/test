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

    var showChar = 350;
    var ellipsestext = "...";
    var moretext = "Read More";
    var lesstext = "Read Less";
    var morelink = ".review-more-link";
    var morecon = ".more-content";
    var conlink = ".review__drawer";
    var ellipse = ".more-ellipses";
    var target = ".review-expand-header";
    $('.more').each(function() {
      var content = $(this).html();
      var c = content.substr(0, showChar);
      var h = content.substr(showChar, content.length - showChar);
      var html = c + '<span class="more-ellipses">&nbsp;'+ ellipsestext +'</span><span class="more-content"><span>'+ h +'</span></span><div class="review-expand-header"><div class="review-expand-content-fade"></div><a href="" class="review-more-link">'+ moretext +'</a></div>';
      $(this).html(html);

    });

    $(morelink).click(function(){
      //when the BUTTON is clicked or open, this is activated
      if($(this).parent().hasClass("less")) {
        //Removes "less" from href class="more-link less"
        $(this).parent().removeClass("less");
        //Toggle fade
        $(this).prev().toggle();
        //Changes text back to "Read More"
        $(this).html(moretext);
        $(this).parent().parent().attr("aria-expanded", "false");
      } else {
        //Adds "less" to link review-expand-header
        $(this).parent().addClass("less");
        //Toggle fade
        $(this).prev().toggle();
        //Toggle display text
        $(this).html(lesstext);
        $(this).parent().parent().attr("aria-expanded", "true");
      }
      //This triggers the ellipses show/hide
      $(this).parent().prev().prev().toggle();
      //This triggers the content overflow show/hide
      $(this).parent().prev().children("span").toggle();
      return false;
    });

    $(conlink).click(function(){
      //when the review DIV is clicked or open, this is activated
      if($(this).find(target).hasClass("less")) {
        $(this).find(target).removeClass("less");
        //Toggle fade
        $(this).find(target).children("div").toggle();
        $(this).find(morelink).html(moretext);
        $(this).attr("aria-expanded", "false");
      } else {
        $(this).find(target).addClass("less");
        //Toggle fade
        $(this).find(target).children("div").toggle();
        $(this).find(morelink).html(lesstext);
        $(this).attr("aria-expanded", "true");
      }
      //This triggers the ellipses show/hide
      $(this).children(ellipse).toggle();
      //This triggers the content overflow show/hide
      $(this).children(morecon).children("span").toggle();
      return false;
    });

})(jQuery); // REMOVE IF DRUPAL.

// })(Drupal); // UNCOMMENT IF DRUPAL.

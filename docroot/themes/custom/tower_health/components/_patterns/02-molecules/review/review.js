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

    var showChar = 250;
    var ellipsestext = "...";
    var moretext = "Read More";
    var lesstext = "Read Less";
    var morelink = ".review-more-link";
    $('.more').each(function() {
      var content = $(this).html();
      var c = content.substr(0, showChar);
      var h = content.substr(showChar-1, content.length - showChar);
      var html = c + '&nbsp;<span class="more-ellipses">'+ellipsestext+'</span>&nbsp;<span class="more-content"><span>' + h + '</span><a href="" class="review-more-link">'+moretext+'</a></span>';

      $(this).html(html);

    });

    $(morelink).click(function(){
      //when the BUTTON is clicked or open, this is activated
      if($(this).hasClass("less")) {
        //Removes "less" from href class="more-link less"
        $(this).removeClass("less");
        //Changes text back to "Read More"
        $(this).html(moretext);
        $(this).parent().parent().attr("aria-expanded", "false");
      } else {
        //Adds class to href class="more-link less"
        $(this).addClass("less");
        $(this).html(lesstext);
        $(this).parent().parent().attr("aria-expanded", "true");
      }
      //This triggers the ellipses swap
      $(this).parent().prev().toggle();
      $(this).prev().toggle();
      return false;
    });

    $(".review__drawer").click(function(){
      if($(this).find(morelink).hasClass("less")) {
        $(this).find(morelink).removeClass("less");
        $(this).find(morelink).html(moretext);
        $(this).attr("aria-expanded", "false");
      } else {
        $(this).find(morelink).addClass("less");
        $(this).find(morelink).html(lesstext);
        $(this).attr("aria-expanded", "true");
      }
      $(this).children(".more-ellipses").toggle();
      $(this).children(".more-content").children("span").toggle();
      return false;
    });

})(jQuery); // REMOVE IF DRUPAL.

// })(Drupal); // UNCOMMENT IF DRUPAL.

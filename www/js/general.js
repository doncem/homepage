/**
 * Scroll window to match top with with a top of element.<br />
 * Given 'topGap' is a required additional spacing from the top
 * @param {jQuery} e
 * @param {int} topGap
 */
function scrollToTop(e, topGap) {
    $(window).scrollTop(e.scrollTop() - topGap);
    //e.children(".experiment-container").slideDown("slow");
}

/**
 * Set wrapper to window size with required gaps
 * @param {int} topLeftGap
 * @param {int} bottomRightGap
 * @returns {Object} jQuery selected object
 */
$.fn.sizeWrapper = function(topLeftGap, bottomRightGap) {
    var options = {};
    
    if (topLeftGap > 0) {
        options = $.extend({
            paddingTop: topLeftGap,
            paddingLeft: topLeftGap
        }, options);
    } else {
        topLeftGap = parseInt($(this).css("padding-top"));
    }
    
    if (bottomRightGap > 0) {
        options = $.extend({
            paddingBottom: bottomRightGap,
            paddingRight: bottomRightGap
        }, options);
    } else {
        bottomRightGap = parseInt($(this).css("padding-bottom"));
    }
    
    $(this).css($.extend({
        width: $(document).width() - topLeftGap - bottomRightGap,
        height: $(document).height() - topLeftGap - bottomRightGap
    }, options));
    
    return $(this);
};

$(function() {
    //depricated
    //if ($.browser.msie) {
        //$("#ie-sucks").slideDown("slow");
    //}

    var menuFontSize = $("#menu a").first().css("font-size");
    $("#menu a").hover(
        function() {
            $(this).css("font-size", parseInt(menuFontSize) + parseInt(menuFontSize) * 0.2);
        },
        function() {
            $(this).stop();
            $(this).animate({"font-size":menuFontSize}, "slow", function() {$(this).removeAttr("style");});
        }
    );
    
    $(window).resize(function() {
        $(".wrapper").sizeWrapper();
    });
});
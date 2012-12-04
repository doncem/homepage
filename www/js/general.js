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

$(function() {
    if ($.browser.msie) {
        $("#ie-sucks").slideDown("slow");
    }

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
});
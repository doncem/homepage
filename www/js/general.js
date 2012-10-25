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
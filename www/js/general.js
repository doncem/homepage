/**
 * Remove certain elements from the array
 * @param {mixed} v
 * @returns {Array.prototype}
 */
Array.prototype.clean = function(v) {
    for (var i = 0; i < this.length; i++) {
        if (this[i] === v) {
            this.splice(i, 1);
            i--;
        }
    }

    return this;
};

/**
 * Scroll window to match top with with a top of element.<br />
 * Given 'topGap' is a required additional spacing from the top
 * @param {jQuery} e
 * @param {int} topGap
 */
function scrollToTop(e, topGap) {
    $(window).scrollTop(e.scrollTop() - topGap);
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

    $(this).click(function(e) {
        if ($(e.target).hasClass($(this).attr("class"))) {
            $(this).children().each(function(i, e) { $(e).hide(); });
            $(this).hide("fast");
        }
    });
    return $(this);
};

/**
 * Enable/disable scrollbar in the browser
 * @param {Boolean} enable
 * @returns {void}
 */
$.fn.toggleBrowserScrollbar = function(enable) {
    var scrollPosition = [
        self.pageXOffset || document.documentElement.scrollLeft || document.body.scrollLeft,
        self.pageYOffset || document.documentElement.scrollTop  || document.body.scrollTop
    ];
    var html = $("body");

    if (enable) {
        scrollPosition = html.data("scroll-position");
        html.css("overflow", html.data("previous-overflow"));
        window.scrollTo(scrollPosition[0], scrollPosition[1]);
    } else {
        html.data("scroll-position", scrollPosition);
        html.data("previous-overflow", html.css("overflow"));
        html.css("overflow", "hidden");
        window.scrollTo(scrollPosition[0], scrollPosition[1]);
    }
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

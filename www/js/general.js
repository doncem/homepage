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
var scrollToTop = function(e, topGap) {
    $(window).scrollTop(e.scrollTop() - topGap);
};

/**
 * Pad number with 'count' amount of zeroes from the beginning
 * @param {Integer} num
 * @param {Integer} count
 * @returns {zeroPad.numZeropad|String}
 */
function zeroPad(num, count) { 
    var numZeropad = num + '';

    while(numZeropad.length < count) {
        numZeropad = "0" + numZeropad; 
    }

    return numZeropad;
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

(function($) {
    //depricated
    //if ($.browser.msie) {
        //$("#ie-sucks").slideDown("slow");
    //}

    $(window).resize(function() {
        $(".wrapper").sizeWrapper();
    });
})(jQuery);

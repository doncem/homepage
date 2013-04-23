/**
 * Remove last carachter in the search input
 * @return {void}
 */
var backspace = function() {
    var arr = $("#search-q").val().split("");
    if (arr.length > 0) {
        arr.pop();
        $("#search-q").val(arr.join(""));
    }
};
/**
 * Switch first line between letters and numbers
 * @return {void}
 */
var shift = function() {
    var letters = ["Q", "W", "E", "R", "T", "Y", "U", "I", "O", "P"];
    var abc = $("#shift").html() === "ABC";
    $("#input-1 .letter").each(function(i, e){
        if (abc) {
            $(e).html(letters[i]);
        } else {
            $(e).html(i);
        }
    });
    if (abc) {
        $("#shift").html("1-2-3");
    } else {
        $("#shift").html("ABC");
    }
};
/**
 * Add single space
 * @return {void}
 */
var space = function() {
    var arr = $("#search-q").val().split("");
    arr.push(" ");
    $("#search-q").val(arr.join(""));
};
/**
 * Resize window metrics
 * @returns {void}
 */
var resizeMe = function() {
    var w = $(window).width();
    var h = $(window).height();
    $("#page").width(w);
    $("#page").height(h);
};

$(function() {
    //our display must be bounded in wXh rect
    resizeMe();

    $(window).resize(function() {
        resizeMe();
    });
    //keyboard clicks
    //var timeout = false;
    $("#keyboard button").click(function() {
        var id = $(this).attr("id");

        if ((id !== undefined) && (id !== false)) {
            window[id]();
        } else {
            $("#search-q").val($("#search-q").val() + $(this).html());
        }
        
        if ($("#search-q").val().length > 2) {
            //if ((timeout !== undefined) && (timeout !== false)) {
            //    clearTimeout(timeout);
            //}
            //timeout = setTimeout("doSearch()", 500);
        }
    });
});

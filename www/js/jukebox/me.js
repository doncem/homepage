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
 * Do the search
 * @returns {void}
 */
var doSearch = function() {
    if ($("#search-q").val().length > 0) {
        $.getJSON(
            "/jukebox/get-songs/" + encodeURI($("#search-q").val())
            , function(json) {
                if (json.error !== undefined) {
                    $("#results").html("we received an error: " + json.error);
                    return;
                }
            }
        ).error(
            function(){
                $("#results").html("something went wrong - could complete search");
            }
        );
        $.post("/jukebox/get-songs/", $("#search-q").serialize(),
            function(response){
                $("#results").html(response);
            }
        );
    }
};
/**
 * Resize window metrics
 * @returns {void}
 */
var resizeMe = function() {
    var w = $(window).width();
    var h = $(window).height();
    $("#page").width(w);
    $("#page").height(h - $("#results-container > ul").height());
    $("#keyboard").width(w);
    $("#results").css({marginTop:$("#results-container > ul").height()});
    $("#results").height(h - $("#results-container > ul").height());
};

$(function() {
    //our display must be bounded in wXh rect
    resizeMe();

    $(window).resize(function() {
        resizeMe();
    });

    $("#search-clear").click(function() {
        $("#search-q").val("");
    });

    $("#search-container").on("focus", "input", function() {
        if ($("#keyboard-container").is(":hidden")) {
            $("#keyboard-container").slideDown("slow");
        }
    });

    $(window).click(function() {
        if ($("#search-container input:focus,#keyboard button:focus").length === 0) {
            $("#keyboard-container").slideUp("slow");
        }
    });

    $("#results-container").on("scroll", "#results", function() {
        $("#keyboard-container").slideUp("slow");
    });
    //keyboard clicks
    var timeout = false;
    $("#keyboard button").click(function() {
        var id = $(this).attr("id");

        if ((id !== undefined) && (id !== false)) {
            window[id]();
        } else {
            $("#search-q").val($("#search-q").val() + $(this).html());
        }

        if ($("#search-q").val().length > 2) {
            if ((timeout !== undefined) && (timeout !== false)) {
                clearTimeout(timeout);
            }

            timeout = setTimeout("doSearch()", 500);
        }
    });
});

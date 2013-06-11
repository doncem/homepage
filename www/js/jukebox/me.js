/**
 * Collection of artist models
 * @type @exp;Backbone@pro;Collection@call;extend
 */
window.artists = null;
/**
 * Collection of song models
 * @type @exp;Backbone@pro;Collection@call;extend
 */
window.songs = null;
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
    $("#page").height(h - $("#results-container > ul").height());
    $("#keyboard").width(w);
    $("#results").css({marginTop:$("#results-container > ul").height()});
    $("#results").height(h - $("#results-container > ul").height());
};
/**
 * Initialize our data
 * @returns {void}
 */
var initData = function() {
    (function (root, factory) {
        if (typeof define === "function" && define.amd) {
           // AMD. Register as an anonymous module.
           define(["underscore","backbone"], function(_, Backbone) {
             // Use global variables if the locals are undefined.
             return factory(_ || root._, Backbone || root.Backbone);
           });
        } else {
           // RequireJS isn't being used. Assume underscore and backbone are loaded in <script> tags
           factory(_, Backbone);
        }
    }(this, function(_, Backbone) {
        var Artist = Backbone.Model.extend({
            idAttribute:"_id",
            name:"name"
        });
        var Artists = Backbone.Collection.extend({
            model:Artist
        });
        window.artists = new Artists();
        window.artists.add(data.artists);
        var Song = Backbone.Model.extend({
            idAttribute:"_id",
            artist_id:"artist_id",
            name:"name",
            filename:"filename",
            counter:"counter"
        });
        var Songs = Backbone.Collection.extend({
            model:Song
        });
        window.songs = new Songs();
        window.songs.add(data.songs);
     }));
};
/**
 * Do the search
 * @returns {void}
 */
var doSearch = function() {
    if ($("#search-q").val().length > 0) {
        r = _.filter(window.songs.models, function(e) {
            return e.get("name").toLowerCase().indexOf($("#search-q").val().toLowerCase()) !== -1;
        });
        $("#results").html(r.length);
        console.log(r);
    }
};

$(function() {
    var data_ready = false;
    $.getJSON(
        "/jukebox/get-songs"
        , function(json) {
            if (json.error !== undefined) {
                $("#results").html("unable to load songs library: " + json.error);
                return;
            }

            data = json.data;
            initData();
            data_ready = true;
        }
    ).error(
        function(){
            data_ready = false;
            $("#results").html("something went wrong on server side - we can't search for songs :(");
        }
    );
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

        if ((id !== undefined) && (id !== false) && (id !== "search-clear")) {
            window[id]();
        } else if (id !== "search-clear") {
            $("#search-q").val($("#search-q").val() + $(this).html());
        }

        if ($("#search-q").val().length > 2) {
            if (data_ready) {
                if ((timeout !== undefined) && (timeout !== false)) {
                    clearTimeout(timeout);
                }

                timeout = setTimeout("doSearch()", 500);
            }
        }
    });
});

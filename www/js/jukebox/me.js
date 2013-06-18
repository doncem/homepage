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
        _.templateSettings = {
            interpolate : /\{\{(.+?)\}\}/g
        };
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
        window.Row = Backbone.View.extend({
            el:"#results",
            template: _.template($("#search-result-row").html()),
            className: "search-result-row found",
            artist: null,
            initialize: function(init) {
                this.model = init.model;
                this.id = init.id;
                this.artist = window.artists.findWhere({_id:this.model.get("artist_id")});
                this.render();
            },
            render: function() {
                this.$el.append(this.template({
                    el_id:this.id,
                    el_class:this.className,
                    song_id: this.model.get("_id"),
                    song: this.model.get("name"),
                    artist: this.artist.get("name")
                }));
            }
        });
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
        _.each(r, function(e, i, l) {
            if ($("#search-result-row-" + e.get("_id")).length === 0) {
                var row = new window.Row({
                    model: e,
                    id: "search-result-row-" + e.get("_id")
                });
            } else {
                $("#search-result-row-" + e.get("_id")).addClass("found");
            }
        });
        $(".search-result-row").each(function(i, e) {
            if ($(e).hasClass("found")) {
                $(e).removeClass("found").fadeIn("fast");
            } else {
                $(e).fadeOut("slow");
            }
        });
    }
};
/**
 * Filter results
 * @param {string} by
 * @returns {void}
 */
var doFilter = function(by) {
    if (by === "") {
        // it also shows previously hidden
        $(".search-result-row").each(function(i, e) {
            $(e).fadeIn("fast");
        });
    } else {
        var arr = by.split("-");
        var filter = false;
        switch (arr[1]) {
            case "artists":
                filter = 1;
                break;
            case "songs":
                filter = 0;
                break;
        }
        if (filter !== false) {
            var s_q = $("#search-q").val();
            $(".search-result-row").filter(function(i) {
                var found = $(this).children("li").filter(function(i2) {
                    return i2 === filter;
                }).html().toLowerCase().indexOf(s_q.toLowerCase());
                if (found === -1) {
                    return true;
                } else {
                    $(this).fadeIn("fast");
                    return false;
                }
            }).each(function(i, e) {
                $(e).fadeOut("slow");
            });
        }
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

    // top filter
    var previous_active_filter = "";
    $("#results-container > ul button[disabled!='disabled']").click(function() {
        if (previous_active_filter !== "" && previous_active_filter !== $(this).attr("id")) {
            $("#" + previous_active_filter).toggleClass("active");
        }

        $(this).toggleClass("active");
        previous_active_filter = $(this).hasClass("active") ? $(this).attr("id") : "";
        doFilter(previous_active_filter);
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

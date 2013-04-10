function zeroPad(num, count) { 
    var numZeropad = num + '';
    while(numZeropad.length < count) {
        numZeropad = "0" + numZeropad; 
    }
    return numZeropad;
}

var colours = [3, 2, 4];

function drawPlot(p, d) {
    source = [];
    for (i in d.graphs) {
        source.push({data:d.graphs[i].data,label:d.graphs[i].label,yaxis:d.graphs[i].y,bars:{show:d.graphs[i].bars},lines:{show:d.graphs[i].lines},color:colours[i]});
    }
    $.plot($("#" + p), source,
        {
            legend:{position:d.legend.position,backgroundOpacity:0.6},
            grid:{clickable:d.grid.clickable,hoverable:true},
            xaxis:{tickSize:1,ticks:d.x.data,labelAngle:d.x.angle},
            yaxes:[{ticks:10},{alignTicksWithAxis: d.y.align,position:d.y.position}]
        }
    );
}

function showTooltip(x, y, contents, c, showFixed) {
    $("<div id=\"tooltip\">"+(showFixed?contents.toFixed(3):contents)+"</div>").css(
        {
            top:y-30,
            left:x,
            backgroundColor:c,
            borderColor:c
        }
    ).appendTo("body").fadeIn(200);
}

function formatStringForID(str) {
    return str.replace(/[^a-zA-Z 0-9]+/g, '');
};

function checkJqxhr(jqxhr, elemID) {
    if (typeof jqxhr === "object") {
        jqxhr.done(function() {
            if (elemID !== undefined) {
                $("#" + elemID).show();
            }
        }).fail(function() {
            $("#page-content .wrapper").append("<div class=\"error ajax-movies-data\">Something went wrong here :( - couldn't get what you requested</div>");
        }).always(function() {
            $("#spinning-squares").addClass("hidden").removeAttr("id");
        });
    }
};

function wrongParamsInRouter(error) {
    $("#page-content .wrapper").append("<div class=\"error ajax-movies-data\">Something went wrong here :( - couldn't get what you requested. We have an error saying: " + error + "</div>");
};

$(function() {
    var jqxhr;
    var formatted;
    var Router = Backbone.Router.extend({
        routes: {
            "by-year/:year": "moviesYear",
            "by-genre/:genre" : "moviesGenre",
            "by-director-count/:directors" : "moviesDirectors",
            "by-country/:country/:type": "moviesCountry"
        },
        moviesYear: function(year) {
            formatted = formatStringForID(year);
            if ($("#p_year_" + formatted).length > 0) {
                $("#page-content .wrapper").sizeWrapper().show("fast");
                $("#p_year_" + formatted).show();
            } else {
                $("#page-content .wrapper").sizeWrapper().show("fast").children(".loading").removeClass("hidden").attr("id", "spinning-squares");
                jqxhr = $.getJSON(
                    "/ajax-movies-by-year/" + year
                    , function(json) {
                        if (json.error !== undefined) {
                            wrongParamsInRouter(json.error);
                            return;
                        }
                        var contents = $("<ul />").attr("id", "p_year_" + formatted).addClass("ajax-movies-data");
                        $.each(json.movies, function(i, e) {
                            contents.append(
                                $("<li />").append(
                                    $("<a />").attr({
                                        "href": e.link,
                                        "target": "_blank"
                                    }).html(e.title + (e.title_en !== null ? " (" + e.title_en + ")" : ""))
                                ).append(
                                    $("<ul />").append(
                                        $("<li />").html("<strong>Genres: </strong>" + $.map(json.genres[i], function(g, i_g) { return g.genre; }).join(", "))
                                    ).append(
                                        $("<li />").html("<strong>Countries: </strong>" + $.map(json.countries[i], function(c, i_c) { return c.country; }).join(", "))
                                    ).append(
                                        $("<li />").html("<strong>Directors: </strong>" + $.map(json.directors[i], function(d, i_d) { return d.director; }).join(", "))
                                    )
                                )
                            );
                        });
                        $("#page-content .wrapper").append(contents);
                    }
                );
                checkJqxhr(jqxhr);
            }
        },
        moviesGenre: function(genre) {
            formatted = formatStringForID(genre);
            if ($("#p_genre_" + formatted).length > 0) {
                $("#page-content .wrapper").sizeWrapper().show("fast");
                $("#p_genre_" + formatted).show();
            } else {
                $("#page-content .wrapper").sizeWrapper().show("fast").children(".loading").removeClass("hidden").attr("id", "spinning-squares");
                jqxhr = $.getJSON(
                    "/ajax-movies-and-series-by-genre/" + genre
                    , function(json) {
                        if (json.error !== undefined) {
                            wrongParamsInRouter(json.error);
                            return;
                        }
                        var contents = $("<ul />").attr("id", "p_genre_" + formatted).addClass("ajax-movies-data")
                            .append($("<li />").html("<strong>MOVIES:</strong>"));
                        $.each(json.movies, function(i, e) {
                            contents.append(
                                $("<li />").append(
                                    $("<a />").attr({
                                        "href": e.link,
                                        "target": "_blank"
                                    }).html(e.title + (e.title_en !== null ? " (" + e.title_en + ")" : "") + " [" + e.year + "]")
                                ).append(
                                    $("<ul />").append(
                                        $("<li />").html("<strong>Genres: </strong>" + $.map(json.genres[i], function(g, i_g) { return g.genre; }).join(", "))
                                    ).append(
                                        $("<li />").html("<strong>Countries: </strong>" + $.map(json.countries[i], function(c, i_c) { return c.country; }).join(", "))
                                    ).append(
                                        $("<li />").html("<strong>Directors: </strong>" + $.map(json.directors[i], function(d, i_d) { return d.director; }).join(", "))
                                    )
                                )
                            );
                        });
                        contents.append($("<li />").html("<strong>SERIES:</strong>"));
                        $.each(json.series, function(i, e) {
                            contents.append(
                                $("<li />").append(
                                    $("<a />").attr({
                                        "href": e.link,
                                        "target": "_blank"
                                    }).html(e.title + (e.title_en !== null ? " (" + e.title_en + ")" : "") + " [" + e.year_from + " - " + e.year_until + "]")
                                ).append(
                                    $("<ul />").append(
                                        $("<li />").html("<strong>Genres: </strong>" + $.map(json.series_genres[i], function(genre, i_genre) { return genre.genre; }).join(", "))
                                    ).append(
                                        $("<li />").html("<strong>Countries: </strong>" + $.map(json.series_countries[i], function(country, i_country) { return country.country; }).join(", "))
                                    )
                                )
                            );
                        });
                        $("#page-content .wrapper").append(contents);
                    }
                );
                checkJqxhr(jqxhr);
            }
        },
        moviesDirectors: function(directors) {
            formatted = formatStringForID(directors);
            if ($("#p_directed_" + formatted).length > 0) {
                $("#page-content .wrapper").sizeWrapper().show("fast");
                $("#p_directed_" + formatted).show();
            } else {
                $("#page-content .wrapper").sizeWrapper().show("fast").children(".loading").removeClass("hidden").attr("id", "spinning-squares");
                jqxhr = $.getJSON(
                    "/ajax-movies-by-director-count/" + directors
                    , function(json) {
                        if (json.error !== undefined) {
                            wrongParamsInRouter(json.error);
                            return;
                        }
                        var contents = $("<ul />").attr("id", "p_directed_" + formatted).addClass("ajax-movies-data");
                        $.each(json.directors, function(i, e) {
                            contents.append(
                                $("<li />").html("<strong>" + e.director + "</strong>").append(
                                    $("<ul />").append(
                                        $.map(json["movies_" + i], function(movie, movie_i) {
                                            return $("<li />").append(
                                                $("<a />").attr({
                                                    "href": movie.link,
                                                    "target": "_blank"
                                                }).html(movie.title + (movie.title_en !== null ? " (" + movie.title_en + ")" : "") + " [" + movie.year + "]")
                                            ).append(
                                                $("<ul />").append(
                                                    $("<li />").html("<strong>Genres: </strong>" + $.map(json["genres_" + i][movie_i], function(g, i_g) { return g.genre; }).join(", "))
                                                ).append(
                                                    $("<li />").html("<strong>Countries: </strong>" + $.map(json["countries_" + i][movie_i], function(c, i_c) { return c.country; }).join(", "))
                                                ).append(
                                                    (json["directors_" + i][movie_i].length > 1 ? $("<li />").html("<strong>Other directors: </strong>" + $.map(json["directors_" + i][movie_i], function(d, i_d) { return d.director !== e.director ? d.director : ""; }).clean().join(", ")) : "")
                                                )
                                            ).html();
                                        }).join()
                                    )
                                )
                            );
                        });
                        $("#page-content .wrapper").append(contents);
                    }
                );
                checkJqxhr(jqxhr);
            }
        },
        moviesCountry: function(country, type) {
            formatted = formatStringForID(country);
            if ($("#p_" + type + "_" + formatted).length > 0) {
                $("#page-content .wrapper").sizeWrapper().show("fast");
                $("#p_" + type + "_" + formatted).show();
            } else {
                $("#page-content .wrapper").sizeWrapper().show("fast").children(".loading").removeClass("hidden").attr("id", "spinning-squares");
                jqxhr = $.getJSON(
                    "/ajax-movies-and-series-by-country/" + country
                    , function(json) {
                        if (json.error !== undefined) {
                            wrongParamsInRouter(json.error);
                            return;
                        }
                        var contents = $("<ul />").attr("id", "p_movies_" + formatted).addClass("ajax-movies-data hidden");
                        $.each(json.movies, function(i, e) {
                            contents.append(
                                $("<li />").append(
                                    $("<a />").attr({
                                        "href": e.link,
                                        "target": "_blank"
                                    }).html(e.title + (e.title_en !== null ? " (" + e.title_en + ")" : "") + " [" + e.year + "]")
                                ).append(
                                    $("<ul />").append(
                                        $("<li />").html("<strong>Genres: </strong>" + $.map(json.genres[i], function(g, i_g) { return g.genre; }).join(", "))
                                    ).append(
                                        $("<li />").html("<strong>Countries: </strong>" + $.map(json.countries[i], function(c, i_c) { return c.country; }).join(", "))
                                    ).append(
                                        $("<li />").html("<strong>Directors: </strong>" + $.map(json.directors[i], function(d, i_d) { return d.director; }).join(", "))
                                    )
                                )
                            );
                        });
                        $("#page-content .wrapper").append(contents);
                        contents = $("<ul />").attr("id", "p_series_" + formatted).addClass("ajax-movies-data hidden");
                        $.each(json.series, function(i, e) {
                            contents.append(
                                $("<li />").append(
                                    $("<a />").attr({
                                        "href": e.link,
                                        "target": "_blank"
                                    }).html(e.title + (e.title_en !== null ? " (" + e.title_en + ")" : "") + " [" + e.year_from + " - " + e.year_until + "]")
                                ).append(
                                    $("<ul />").append(
                                        $("<li />").html("<strong>Genres: </strong>" + $.map(json.series_genres[i], function(g, i_g) { return g.genre; }).join(", "))
                                    ).append(
                                        $("<li />").html("<strong>Countries: </strong>" + $.map(json.series_countries[i], function(c, i_c) { return c.country; }).join(", "))
                                    )
                                )
                            );
                        });
                        $("#page-content .wrapper").append(contents);
                    }
                );
                checkJqxhr(jqxhr, "p_" + type + "_" + formatted);
            }
        }
    });
    new Router();
    Backbone.history.start({silent:true});
    g = {
        "p_year" : {"grid":{"clickable":true},"graphs" : [{"data":d_y,"label":"movies ("+Math.ceil(d_y_m[0][1]*d_y_x.length)+")","bars":true,"lines":false,"y":1},{"data":d_y_m,"label":"mean","bars":false,"lines":true,"y":1},{"data":d_y_s,"label":"standard deviation","bars":false,"lines":true,"y":1}],"legend":{"position":"nw"},"x":{"data":d_y_x,"angle":270},"y":{"align":null,"position":"left"}},
        "p_decade" : {"grid":{"clickable":false},"graphs" : [{"data":d_yd,"label":"movies","bars":true,"lines":false,"y":1},{"data":d_yd_m,"label":"mean","bars":false,"lines":true,"y":1},{"data":d_yd_s,"label":"standard deviation","bars":false,"lines":true,"y":1}],"legend":{"position":"nw"},"x":{"data":d_yd_x,"angle":0},"y":{"align":null,"position":"left"}},
        "p_genre" : {"grid":{"clickable":true},"graphs":[{"data":d_g_m,"label":"movies","bars":true,"lines":false,"y":1},{"data":d_g_s,"label":"tv shows","bars":true,"lines":false,"y":1},{"data":d_g_c,"label":"correlation","bars":false,"lines":true,"y":2}],"legend":{"position":"nw"},"x":{"data":d_g_x,"angle":270},"y":{"align":1,"position":"right"}},
        "p_directed" : {"grid":{"clickable":true},"graphs":[{"data":d_d,"label":"#movies by directors","bars":true,"lines":false,"y":1}],"legend":{"position":"ne"},"x":{"data":d_d_x,"angle":0},"y":{"align":null,"position":"left"}}
    };
    
    for (i in g) {
        drawPlot(i, g[i]);
    }
    
    var previousPoint = null;
    var previousLabel = null;
    $(".placeholder").bind("plothover", function(event, pos, item) {
        if (item) {
            $(this).css("cursor", "pointer");
            if ((previousPoint !== item.dataIndex) || (previousLabel !== item.series.label)) {
                previousPoint = item.dataIndex;
                previousLabel = item.series.label;
                $("#tooltip").remove();
                showTooltip(item.pageX, item.pageY, item.datapoint[1], item.series.color, !(($(this).attr("id") === "p_year") || ($(this).attr("id") === "p_decade") || (item.series.label === "correlation")));
            }
        } else {
            $(this).css("cursor", "default");
            $("#tooltip").remove();
            previousPoint = null;
        }
    });
    
    $(".placeholder").bind("plotclick", function(event, pos, item) {
        if (item) {
            if (item.series.bars.show) {
                var label = encodeURI(item.series.xaxis.ticks[item.dataIndex].label);
                switch (event.currentTarget.id) {
                    case "p_year":
                        window.location.hash = "#by-year/" + label;
                        break;
                    case "p_genre":
                        window.location.hash = "#by-genre/" + label;
                        break;
                    case "p_directed":
                        window.location.hash = "#by-director-count/" + label;
                        break;
                    default:
                        alert("Nothing's available here :/");
                        break;
                }
            }
        }
    });
});

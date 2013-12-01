function formatStringForID(str) {
    return str.replace(/[^a-zA-Z 0-9]+/g, '');
};

var colours = {
    flot: [3, 2, 4],
    raphael: [.6, .9]
};

var drawFlot = function(p, d) {
    var source = [];
    for (i in d.graphs) {
        source.push({
            data:d.graphs[i].data,
            label:d.graphs[i].label,
            yaxis:d.graphs[i].y,
            bars:{show:d.graphs[i].bars},
            lines:{show:d.graphs[i].lines},
            color:colours.flot[i]
        });
    }
    $.plot($("#flot-" + p + " .placeholder"), source,
        {
            legend:{position:d.legend.position,backgroundOpacity:0.6},
            grid:{clickable:d.grid.clickable,hoverable:true},
            xaxis:{tickSize:1,ticks:d.x.data,labelAngle:d.x.angle},
            yaxes:[{ticks:10},{alignTicksWithAxis: d.y.align,position:d.y.position}]
        }
    );
};

var showFlotTooltip = function(x, y, contents, c, showFixed) {
    $("<div id=\"tooltip\">"+(showFixed?contents.toFixed(3):contents)+"</div>").css(
        {
            top:y-30,
            left:x,
            backgroundColor:c,
            borderColor:c
        }
    ).appendTo("body").fadeIn(200);
};

var checkJqxhr = function(jqxhr, elemID) {
    if (typeof jqxhr === "object") {
        jqxhr.done(function() {
            if (elemID !== undefined) {
                $("#" + elemID).show();
            }
        }).fail(function() {
            wrongParamsInRouter("AJAX failed");
        }).always(function() {
            $("#modal-movies-list .progress").fadeOut("fast");
        });
    }
};

var wrongParamsInRouter = function(error) {
    $("#modal-movies-list .modal-body .alert span").html(error);
    $("#modal-movies-list .modal-body .alert").show();
};

(function($) {
    var jqxhr,
        formatted,
        lists = $("#modal-movies-list"),
        container = $("#modal-movies-list .modal-body"),
        Router = Backbone.Router.extend({
            routes: {
                "by-year/:year": "moviesYear",
                "by-genre/:genre" : "moviesGenre",
                "by-director-count/:directors" : "moviesDirectors",
                "by-country/:country/:type": "moviesCountry"
            },
            moviesYear: function(year) {
                formatted = formatStringForID(year);
                if (container.find("#p-year-" + formatted).length > 0) {
                    container.find("#p-year-" + formatted).show();
                } else {
                    $("#modal-movies-list .progress").show();
                    jqxhr = $.getJSON(
                        "/ajax-movies-by-year/" + year
                        , function(json) {
                            if (json.error !== undefined) {
                                wrongParamsInRouter(json.error);
                                return;
                            }
                            var contents = $("<ul />").attr("id", "p-year-" + formatted).addClass("ajax-movies-data");
                            $.each(json.movies, function(i, e) {
                                contents.append(
                                    $("<li />").append(
                                        $("<a />").attr({
                                            "href": e.link,
                                            "target": "_blank"
                                        }).html(e.title + (e.title_en !== null ? " (" + e.title_en + ")" : ""))
                                    )
                                );
                            });
                            container.append(contents);
                        }
                    );
                    checkJqxhr(jqxhr);
                }
                lists.modal("show");
            },
            moviesGenre: function(genre) {
                formatted = formatStringForID(genre);
                if (container.find("#p-genre-" + formatted).length > 0) {
                    container.find("#p-genre-" + formatted).show();
                } else {
                    $("#modal-movies-list .progress").show();
                    jqxhr = $.getJSON(
                        "/ajax-movies-and-series-by-genre/" + genre
                        , function(json) {
                            if (json.error !== undefined) {
                                wrongParamsInRouter(json.error);
                                return;
                            }
                            var contents = $("<ul />").attr("id", "p-genre-" + formatted).addClass("ajax-movies-data")
                                .append($("<li />").html("<strong>MOVIES:</strong>"));
                            $.each(json.movies, function(i, e) {
                                contents.append(
                                    $("<li />").append(
                                        $("<a />").attr({
                                            "href": e.link,
                                            "target": "_blank"
                                        }).html(e.title + (e.title_en !== null ? " (" + e.title_en + ")" : "") + " [" + e.year + "]")
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
                                    )
                                );
                            });
                            container.append(contents);
                        }
                    );
                    checkJqxhr(jqxhr);
                }
                lists.modal("show");
            },
            moviesDirectors: function(directors) {
                formatted = formatStringForID(directors);
                if (container.find("#p-directed-" + formatted).length > 0) {
                    container.find("#p-directed-" + formatted).show();
                } else {
                    $("#modal-movies-list .progress").show();
                    jqxhr = $.getJSON(
                        "/ajax-movies-by-director-count/" + directors
                        , function(json) {
                            if (json.error !== undefined) {
                                wrongParamsInRouter(json.error);
                                return;
                            }
                            var contents = $("<ul />").attr("id", "p-directed-" + formatted).addClass("ajax-movies-data");
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
                                                ).html();
                                            }).join()
                                        )
                                    )
                                );
                            });
                            container.append(contents);
                        }
                    );
                    checkJqxhr(jqxhr);
                }
                lists.modal("show");
            },
            moviesCountry: function(country, type) {
                formatted = formatStringForID(country);
                if (container.find("#p-" + type + "-" + formatted).length > 0) {
                    container.find("#p-" + type + "-" + formatted).show();
                } else {
                    $("#modal-movies-list .progress").show();
                    jqxhr = $.getJSON(
                        "/ajax-movies-and-series-by-country/" + country
                        , function(json) {
                            if (json.error !== undefined) {
                                wrongParamsInRouter(json.error);
                                return;
                            }
                            var contents = $("<ul />").attr("id", "p-movies-" + formatted).addClass("ajax-movies-data");
                            $.each(json.movies, function(i, e) {
                                contents.append(
                                    $("<li />").append(
                                        $("<a />").attr({
                                            "href": e.link,
                                            "target": "_blank"
                                        }).html(e.title + (e.title_en !== null ? " (" + e.title_en + ")" : "") + " [" + e.year + "]")
                                    )
                                );
                            });
                            container.append(contents);
                            contents = $("<ul />").attr("id", "p-series-" + formatted).addClass("ajax-movies-data");
                            $.each(json.series, function(i, e) {
                                contents.append(
                                    $("<li />").append(
                                        $("<a />").attr({
                                            "href": e.link,
                                            "target": "_blank"
                                        }).html(e.title + (e.title_en !== null ? " (" + e.title_en + ")" : "") + " [" + e.year_from + " - " + e.year_until + "]")
                                    )
                                );
                            });
                            container.append(contents);
                        }
                    );
                    checkJqxhr(jqxhr, "p-" + type + "-" + formatted);
                }
                lists.modal("show");
            }
        });
    lists.on("hidden.bs.modal", function() {
        $(".ajax-movies-data").hide();
    });
    new Router();
    Backbone.history.start({silent:true});
    // flot before rewrite
    if ($.fn.plot !== undefined) {
        g = {
            "p-year" : {grid:{clickable:true},graphs : [{data:year_data,label:"movies",bars:true,lines:false,y:1}],legend:{position:"nw"},x:{data:year_labels,angle:270},y:{align:null,position:"left"}},
            "p-decade" : {grid:{clickable:false},graphs : [{data:decade_data,label:"movies",bars:true,lines:false,y:1}],legend:{position:"nw"},x:{data:decade_labels,angle:0},y:{align:null,position:"left"}},
            "p-genre" : {grid:{clickable:true},graphs:[{data:genre_movies_data,label:"movies",bars:true,lines:false,y:1},{data:genre_series_data,label:"tv shows",bars:true,lines:false,y:1}],legend:{position:"nw"},x:{data:genre_labels,angle:270},y:{align:1,position:"right"}},
            "p-directed" : {grid:{clickable:true},graphs:[{data:directed_data,label:"#movies by directors",bars:true,lines:false,y:1}],legend:{position:"ne"},x:{data:directed_labels,angle:0},"y":{align:null,position:"left"}}
        };

        for (i in g) {
            drawFlot(i, g[i]);
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
                    showFlotTooltip(item.pageX, item.pageY, item.datapoint[1], item.series.color, !(($(this).parent().parent().attr("id") === "flot-p-year") || ($(this).parent().parent().attr("id") === "flot-p-decade")));
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
                        case "flot-p-year":
                            window.location.hash = "#by-year/" + label;
                            break;
                        case "flot-p-genre":
                            window.location.hash = "#by-genre/" + label;
                            break;
                        case "flot-p-directed":
                            window.location.hash = "#by-director-count/" + label;
                            break;
                        default:
                            alert("Nothing's available here :/");
                            break;
                    }
                }
            }
        });
    }
    // now raphael
    if (Raphael !== undefined) {
        // popup part
        var tokenRegex = /\{([^\}]+)\}/g,
        objNotationRegex = /(?:(?:^|\.)(.+?)(?=\[|\.|$|\()|\[('|")(.+?)\2\])(\(\))?/g, // matches .xxxxx or ["xxxxx"] to run over object properties
        replacer = function (all, key, obj) {
            var res = obj;
            key.replace(objNotationRegex, function (all, name, quote, quotedName, isFunc) {
                name = name || quotedName;
                if (res) {
                    if (name in res) {
                        res = res[name];
                    }
                    typeof res == "function" && isFunc && (res = res());
                }
            });
            res = (res == null || res == obj ? all : res) + "";
            return res;
        },
        fill = function (str, obj) {
            return String(str).replace(tokenRegex, function (all, key) {
                return replacer(all, key, obj);
            });
        };
        Raphael.fn.popup = function (X, Y, set, pos, ret) {
            pos = String(pos || "top-middle").split("-");
            pos[1] = pos[1] || "middle";
            var r = 5,
                bb = set.getBBox(),
                w = Math.round(bb.width),
                h = Math.round(bb.height),
                x = Math.round(bb.x) - r,
                y = Math.round(bb.y) - r,
                gap = Math.min(h / 2, w / 2, 10),
                shapes = {
                    top: "M{x},{y}h{w4},{w4},{w4},{w4}a{r},{r},0,0,1,{r},{r}v{h4},{h4},{h4},{h4}a{r},{r},0,0,1,-{r},{r}l-{right},0-{gap},{gap}-{gap}-{gap}-{left},0a{r},{r},0,0,1-{r}-{r}v-{h4}-{h4}-{h4}-{h4}a{r},{r},0,0,1,{r}-{r}z",
                    bottom: "M{x},{y}l{left},0,{gap}-{gap},{gap},{gap},{right},0a{r},{r},0,0,1,{r},{r}v{h4},{h4},{h4},{h4}a{r},{r},0,0,1,-{r},{r}h-{w4}-{w4}-{w4}-{w4}a{r},{r},0,0,1-{r}-{r}v-{h4}-{h4}-{h4}-{h4}a{r},{r},0,0,1,{r}-{r}z",
                    right: "M{x},{y}h{w4},{w4},{w4},{w4}a{r},{r},0,0,1,{r},{r}v{h4},{h4},{h4},{h4}a{r},{r},0,0,1,-{r},{r}h-{w4}-{w4}-{w4}-{w4}a{r},{r},0,0,1-{r}-{r}l0-{bottom}-{gap}-{gap},{gap}-{gap},0-{top}a{r},{r},0,0,1,{r}-{r}z",
                    left: "M{x},{y}h{w4},{w4},{w4},{w4}a{r},{r},0,0,1,{r},{r}l0,{top},{gap},{gap}-{gap},{gap},0,{bottom}a{r},{r},0,0,1,-{r},{r}h-{w4}-{w4}-{w4}-{w4}a{r},{r},0,0,1-{r}-{r}v-{h4}-{h4}-{h4}-{h4}a{r},{r},0,0,1,{r}-{r}z"
                },
                offset = {
                    hx0: X - (x + r + w - gap * 2),
                    hx1: X - (x + r + w / 2 - gap),
                    hx2: X - (x + r + gap),
                    vhy: Y - (y + r + h + r + gap),
                    "^hy": Y - (y - gap)

                },
                mask = [{
                    x: x + r,
                    y: y,
                    w: w,
                    w4: w / 4,
                    h4: h / 4,
                    right: 0,
                    left: w - gap * 2,
                    bottom: 0,
                    top: h - gap * 2,
                    r: r,
                    h: h,
                    gap: gap
                }, {
                    x: x + r,
                    y: y,
                    w: w,
                    w4: w / 4,
                    h4: h / 4,
                    left: w / 2 - gap,
                    right: w / 2 - gap,
                    top: h / 2 - gap,
                    bottom: h / 2 - gap,
                    r: r,
                    h: h,
                    gap: gap
                }, {
                    x: x + r,
                    y: y,
                    w: w,
                    w4: w / 4,
                    h4: h / 4,
                    left: 0,
                    right: w - gap * 2,
                    top: 0,
                    bottom: h - gap * 2,
                    r: r,
                    h: h,
                    gap: gap
                }][pos[1] == "middle" ? 1 : (pos[1] == "top" || pos[1] == "left") * 2];
                var dx = 0,
                    dy = 0,
                    out = this.path(fill(shapes[pos[0]], mask)).insertBefore(set);
                switch (pos[0]) {
                    case "top":
                        dx = X - (x + r + mask.left + gap);
                        dy = Y - (y + r + h + r + gap);
                    break;
                    case "bottom":
                        dx = X - (x + r + mask.left + gap);
                        dy = Y - (y - gap);
                    break;
                    case "left":
                        dx = X - (x + r + w + r + gap);
                        dy = Y - (y + r + mask.top + gap);
                    break;
                    case "right":
                        dx = X - (x - gap);
                        dy = Y - (y + r + mask.top + gap);
                    break;
                }
                out.translate(dx, dy);
                if (ret) {
                    ret = out.attr("path");
                    out.remove();
                    return {
                        path: ret,
                        dx: dx,
                        dy: dy
                    };
                }
                set.translate(dx, dy);
                return out;
        };

        // graph part
        Raphael.fn.drawGrid = function (x, y, w, h, wv, hv, color) {
            color = color || "#000";
            var path = ["M", Math.round(x) + .5, Math.round(y) + .5, "L", Math.round(x + w) + .5, Math.round(y) + .5, Math.round(x + w) + .5, Math.round(y + h) + .5, Math.round(x) + .5, Math.round(y + h) + .5, Math.round(x) + .5, Math.round(y) + .5],
                rowHeight = h / hv,
                columnWidth = w / wv;
            for (var i = 1; i < hv; i++) {
                path = path.concat(["M", Math.round(x) + .5, Math.round(y + i * rowHeight) + .5, "H", Math.round(x + w) + .5]);
            }
            for (i = 1; i < wv; i++) {
                path = path.concat(["M", Math.round(x + i * columnWidth) + .5, Math.round(y) + .5, "V", Math.round(y + h) + .5]);
            }
            return this.path(path.join(",")).attr({stroke: color});
        };
        var getAnchors = function(p1x, p1y, p2x, p2y, p3x, p3y) {
            var l1 = (p2x - p1x) / 2,
                l2 = (p3x - p2x) / 2,
                a = Math.atan((p2x - p1x) / Math.abs(p2y - p1y)),
                b = Math.atan((p3x - p2x) / Math.abs(p2y - p3y));
            a = p1y < p2y ? Math.PI - a : a;
            b = p3y < p2y ? Math.PI - b : b;
            var alpha = Math.PI / 2 - ((a + b) % (Math.PI * 2)) / 2,
                dx1 = l1 * Math.sin(alpha + a),
                dy1 = l1 * Math.cos(alpha + a),
                dx2 = l2 * Math.sin(alpha + b),
                dy2 = l2 * Math.cos(alpha + b);
            return {
                x1: p2x - dx1,
                y1: p2y + dy1,
                x2: p2x + dx2,
                y2: p2y + dy2
            };
        };
        // generate graphs
        var generate_graphs = function(_placement, bottomgutter, bottomadditive, _labels, _data) {
            var width = 800,
                height = 400,
                leftgutter = 30,
                topgutter = 20,
                r = Raphael(document.getElementById("raphael-" + _placement).getElementsByClassName("placeholder")[0], width, height),
                X = (width - leftgutter) / _labels.d.length,
                max = [],
                Y = 0;
            r.drawGrid(leftgutter + X * .5 + .5, topgutter + .5, width - leftgutter - X, height - topgutter - bottomgutter, 10, 10, "#999999");
            var paths = r.set(),
                bgps = r.set(),
                label = r.set(),
                lx = 0, ly = 0,
                is_label_visible = false,
                leave_timer,
                blanket = r.set(),
                dot = r.set();
            $.each(_data, function(data_i, data_e) {
                $.each(data_e.d, function() {
                    max.push(this[1]);
                });
                label.push(r.text(60, 12 + 15 * data_i, data_e.label + " 0").attr({fill: "#ffffff"}));
            });
            Y = (height - bottomgutter - topgutter) / Math.max.apply(null, max);
            label.hide();
            var frame = r.popup(100, 100, label, "right").attr({fill: "#000", stroke: "#666", "stroke-width": 2, "fill-opacity": .7}).hide();

            var p, bgpp;
            $.each(_data, function(data_i, data_e) {
                var color = "hsl(" + [colours.raphael[data_i], .5, .5] + ")",
                    path = r.path().attr({stroke: color, "stroke-width": 4, "stroke-linejoin": "round"}),
                    bgp = r.path().attr({stroke: "none", opacity: .3, fill: color});
                paths.push(path);
                bgps.push(bgp);
                dot[data_i] = r.set();
                for (var i = 0, ii = _labels.d.length; i < ii; i++) {
                    var y = Math.round(height - bottomgutter - Y * data_e.d[i][1]),
                        x = Math.round(leftgutter + X * (i + .5)),
                        t = r.text(x, height - bottomgutter + bottomadditive, _labels.d[i][1]).transform("r" + _labels.a).toBack();
                    if (!i) {
                        p = ["M", x, y, "C", x, y];
                        bgpp = ["M", leftgutter + X * .5, height - bottomgutter, "L", x, y, "C", x, y];
                    }
                    if (i && i < ii - 1) {
                        var Y0 = Math.round(height - bottomgutter - Y * data_e.d[i - 1][1]),
                            X0 = Math.round(leftgutter + X * (i - .5)),
                            Y2 = Math.round(height - bottomgutter - Y * data_e.d[i + 1][1]),
                            X2 = Math.round(leftgutter + X * (i + 1.5));
                        var a = getAnchors(X0, Y0, x, y, X2, Y2);
                        p = p.concat([a.x1, a.y1, x, y, a.x2, a.y2]);
                        bgpp = bgpp.concat([a.x1, a.y1, x, y, a.x2, a.y2]);
                    }
                    dot[data_i][i] = r.circle(x, y, 4).attr({fill: "#333", stroke: color, "stroke-width": 2});
                    if (data_i === 0) {
                        blanket.push(r.rect(leftgutter + X * i, 0, X, height - bottomgutter).attr({stroke: "none", fill: "#fff", opacity: 0}));
                    }
                    var rect = blanket[blanket.length - 1];
                    (function (x, y, data_index, lbl, dot) {
                        var timer, i = 0;
                        rect.hover(function () {
                            clearTimeout(leave_timer);
                            var side = "right";
                            if (x + frame.getBBox().width > width) {
                                side = "left";
                            }
                            var ppp = r.popup(x, y, label, side, 1),
                                anim = Raphael.animation({
                                    path: ppp.path,
                                    transform: ["t", ppp.dx, ppp.dy]
                                }, 200 * is_label_visible);
                            lx = label[0].transform()[0][1] + ppp.dx;
                            ly = label[0].transform()[0][2] + ppp.dy;
                            frame.show().stop().animate(anim);
                            $.each(_data, function(data_ii, data_ee) {
                                label[data_ii].attr({text:data_ee.label + lbl + ": " + data_ee.d[data_index][1]}).show().stop().animateWith(frame, anim, {transform:["t", lx, ly]}, 200 * is_label_visible);
                                dot[data_ii][data_index].attr("r", 6);
                            });
                            is_label_visible = true;
                        }, function () {
                            $.each(_data, function(data_ii, data_ee) {
                                dot[data_ii][data_index].attr("r", 4);
                            });
                            leave_timer = setTimeout(function () {
                                frame.hide();
                                $.each(label, function() {
                                    this.hide();
                                });
                                is_label_visible = false;
                            }, 1);
                        });
                    })(x, y, i, _labels.d[i][1], dot);
                }
                p = p.concat([x, y, x, y]);
                bgpp = bgpp.concat([x, y, x, y, "L", x, height - bottomgutter, "z"]);
                path.attr({path: p});
                bgp.attr({path: bgpp});
            });
            frame.toFront();
            $.each(label, function() {
                this.toFront();
            });
            blanket.toFront();
        };

        generate_graphs("p-year", 30, 15, {d:year_labels,a:270}, [{d:year_data,label:"Year "}]);
        generate_graphs("p-decade", 20, 10, {d:decade_labels,a:0}, [{d:decade_data,label:"Decade "}]);
        generate_graphs("p-genre", 80, 35, {d:genre_labels,a:270}, [{d:genre_movies_data,label:"Movies "},{d:genre_series_data,label:"Series "}]);
        generate_graphs("p-directed", 20, 10, {d:directed_labels,a:0}, [{d:directed_data,label:"Number of movies "}]);
    }
})(jQuery);

/**
 * How much to leave from top
 */
const TOP_GAP_SCROLL = 20;

/**
 * If 'e' has class 'active' - scroll to it, it's active already<br />
 * Else if link is with hash - activate it<br />
 * Else go to that link
 * @param {jQuery} e Experiment to activate
 */
function activateExperiment(e) {
    var href = e.children(".link-container").children("a");
    
    if (e.hasClass("active")) {
        scrollToTop(e, TOP_GAP_SCROLL);
        window[getFunction(href.attr("href").substr(1))]();
    } else {
        if (href.attr("href").substr(0, 1) == "#") {
            e.addClass("active");
            //href.parent().fadeOut("slow");
            scrollToTop(e, TOP_GAP_SCROLL);
            window[getFunction(href.attr("href").substr(1))]();
        } else {
            window.location = $(this).attr("href");
        }
    }
}
/**
 * Converts element id into camel case function name
 * @param {String} id
 * @return {String} Function name
 */
function getFunction(id) {
    arr = id.split("-");
    s = arr[0];
    
    for (i = 1; i < arr.length; i++) {
        s += arr[i].substr(0, 1).toUpperCase() + arr[i].substr(1);
    }
    
    return s;
}
/**
 * Add activation links to each non-active experiment.<br />
 * Attach a click to it as well
 */
$.fn.attachExperiment = function() {
    var link = $(this).attr("id");
    
    if (!$("#" + link).hasClass("active")) {
        if (link.substr(0, 6) == "jquery") {
            $("#" + link).append("<p class=\"link-container\"><a href=\"#" + link + "\" class=\"launch-link\">Launch it</a></p>");
        } else {
            $("#" + link).append("<p class=\"link-container\"><a href=\"/" + link + "/\">Go there</a></p>");
        }
    }
    
    $("#" + link).on("click", ".launch-link", function(e) {
        e.preventDefault();
        activateExperiment($("#" + link));
    });
}

$(function() {
    $(".experiment").each(function(i, e) {
        $(e).attachExperiment();
    });
    
    $(".version-history-link").click(function(e) {
        e.preventDefault();
        $(this).next(".version-history").slideToggle("fast");
    });
    
    if ((window.location.pathname == $("#menu a.active").attr("href")) && ($(window.location.hash).length > 0)) {
        activateExperiment($(window.location.hash));
    } else if ($(".experiment.active").length > 0) {
        activateExperiment($(".experiment.active").first());
    }
    
    $(window).bind("hashchange", function() {
        if ($(window.location.hash).length > 0) {
            activateExperiment($(window.location.hash));
        }
    });
});

var jqueryWindowGrid = function() {
    var c = $("#jquery-window-grid-container");
    var element = "<div id=\"grid-element-\" class=\"grid-element\" data-x=\"\" data-y=\"\"></div>";
    var gap = 4;
    var activeColour = "#012345";
    var colour = "#543210";
    
    var calculateSize = function(wSize, k) {
        return Math.floor((wSize - gap) / k - gap - 2 * parseInt($(".grid-element").first().css("border-width")));
    }
    
    var calculateMax = function(wSize) {
        return Math.floor((wSize - gap) / (3 + gap));
    }
    
    /**
     * Add element to the grid
     * @param {string} type Where to add element: 'prepend' or 'append'
     * @param {int} w New width
     * @param {int} h New height
     * @param {int} t Position from top
     * @param {int} l Position from left
     */
    var addE = function(type, w, h, t, l) {
        if (type == "prepend") {
            c.prepend(element);
        } else {
            c.append(element);
        }

        $("#grid-element-").attr("id", "grid-element-" + xCurrent + "-" + yCurrent)
                           .attr("data-x", xCurrent)
                           .attr("data-y", yCurrent)
                           .animate({
                               width:w,
                               height:h,
                               top:t,
                               left:l,
                               backgroundColor:activeColour
                           }, "fast");
    }
    
    var close = function() {
        c.children("div").each(function(i, e) {
            setTimeout(function(e) { $(e).fadeOut("fast"); }, 400);
        }).parent().hide("slow");
    }
    
    var moveLeft = function() {
        xCurrent--;
        $("#grid-element-" + (xCurrent + 1) + "-" + yCurrent).animate({backgroundColor:colour}, "fast");
        
        if ($("#grid-element-" + xCurrent + "-" + yCurrent).length > 0) {
            $("#grid-element-" + xCurrent + "-" + yCurrent).animate({backgroundColor:activeColour}, "fast");
        } else {
            var column = $(".grid-element[data-x='" + xCurrent + "']");
            var line = $(".grid-element[data-y='" + yCurrent + "']");

            if (column.length > 0) {
                addE("prepend", line.first().width(), line.first().height(), parseInt(line.first().css("top")), parseInt(column.first().css("left")));
            } else {
                if (line.length < xMax) {
                    var newX = calculateSize($(window).width(), currentXMax + 1);
                    $(".grid-element").each(function(i, e) {
                        $(e).animate({
                            left:(parseInt($(e).attr("data-x")) - xCurrent + 1) * gap + (parseInt($(e).attr("data-x")) - xCurrent) * (2 * parseInt($(".grid-element").first().css("border-width")) + newX),
                            width:newX
                        }, "fast");
                    });
                    currentXMax++;
                    addE("prepend", newX, line.first().height(), line.first().css("top"), gap);
                } else {
                    $(line[line.length - 1]).animate({"color":activeColour}, "fast");
                    xCurrent = parseInt($(line[line.length - 1]).attr("data-x"));
                }
            }
        }
    }
    
    var moveUp = function() {
        yCurrent--;
        $("#grid-element-" + xCurrent + "-" + (yCurrent + 1)).animate({backgroundColor:colour}, "fast");
        
        if ($("#grid-element-" + xCurrent + "-" + yCurrent).length > 0) {
            $("#grid-element-" + xCurrent + "-" + yCurrent).animate({backgroundColor:activeColour}, "fast");
        } else {
            var column = $(".grid-element[data-x='" + xCurrent + "']");
            var line = $(".grid-element[data-y='" + yCurrent + "']");

            if (line.length > 0) {
                addE("prepend", line.first().width(), line.first().height(), parseInt(line.first().css("top")), parseInt(column.first().css("left")));
            } else {
                if (column.length < yMax) {
                    var newY = calculateSize($(window).height(), currentYMax + 1);
                    $(".grid-element").each(function(i, e) {
                        $(e).animate({
                            top:(parseInt($(e).attr("data-y")) - yCurrent + 1) * gap + (parseInt($(e).attr("data-y")) - yCurrent) * (2 * parseInt($(".grid-element").first().css("border-width")) + newY),
                            height:newY
                        }, "fast");
                    });
                    currentYMax++;
                    addE("prepend", column.first().width(), newY, gap, parseInt(column.first().css("left")));
                } else {
                    $(column[column.length - 1]).animate({"color":activeColour}, "fast");
                    yCurrent = parseInt($(column[column.length - 1]).attr("data-y"));
                }
            }
        }
    }
    
    var moveRight = function() {
        xCurrent++;
        $("#grid-element-" + (xCurrent - 1) + "-" + yCurrent).animate({backgroundColor:colour}, "fast");
        
        if ($("#grid-element-" + xCurrent + "-" + yCurrent).length > 0) {
            $("#grid-element-" + xCurrent + "-" + yCurrent).animate({backgroundColor:activeColour}, "fast");
        } else {
            var column = $(".grid-element[data-x='" + xCurrent + "']");
            var line = $(".grid-element[data-y='" + yCurrent + "']");

            if (column.length > 0) {
                addE("append", line.first().width(), line.first().height(), parseInt(line.first().css("top")), parseInt(column.first().css("left")));
            } else {
                if (line.length < xMax) {
                    currentXMax++;
                    var newX = calculateSize($(window).width(), currentXMax);
                    $(".grid-element").each(function(i, e) {
                        $(e).animate({
                            left:(currentXMax - xCurrent + parseInt($(e).attr("data-x"))) * gap + (currentXMax - xCurrent + parseInt($(e).attr("data-x")) - 1) * (2 * parseInt($(".grid-element").first().css("border-width")) + newX),
                            width:newX
                        }, "fast");
                    });
                    addE("append", newX, line.first().height(), line.first().css("top"), currentXMax * gap + (currentXMax - 1) * (2 * parseInt($(".grid-element").first().css("border-width")) + newX));
                } else {
                    $(line[0]).animate({"color":activeColour}, "fast");
                    xCurrent = parseInt($(line[0]).attr("data-x"));
                }
            }
        }
    }
    
    var moveDown = function() {
        yCurrent++;
        $("#grid-element-" + xCurrent + "-" + (yCurrent - 1)).animate({backgroundColor:colour}, "fast");
        
        if ($("#grid-element-" + xCurrent + "-" + yCurrent).length > 0) {
            $("#grid-element-" + xCurrent + "-" + yCurrent).animate({backgroundColor:activeColour}, "fast");
        } else {
            var column = $(".grid-element[data-x='" + xCurrent + "']");
            var line = $(".grid-element[data-y='" + yCurrent + "']");

            if (line.length > 0) {
                addE("append", line.first().width(), line.first().height(), parseInt(line.first().css("top")), parseInt(column.first().css("left")));
            } else {
                if (column.length < yMax) {
                    currentYMax++;
                    var newY = calculateSize($(window).height(), currentYMax);
                    $(".grid-element").each(function(i, e) {
                        $(e).animate({
                            top:(currentYMax - yCurrent + parseInt($(e).attr("data-y"))) * gap + (currentYMax - yCurrent + parseInt($(e).attr("data-y")) - 1) * (2 * parseInt($(".grid-element").first().css("border-width")) + newY),
                            height:newY
                        }, "fast");
                    });
                    addE("append", column.first().width(), newY, currentYMax * gap + (currentYMax - 1) * (2 * parseInt($(".grid-element").first().css("border-width")) + newY), column.first().css("left"));
                } else {
                    $(column[0]).animate({"color":activeColour}, "fast");
                    yCurrent = parseInt($(column[0]).attr("data-y"));
                }
            }
        }
    }
    
    if (c.children("div").length > 1) {
        c.show("slow").children("div").each(function(i, e) {
            setTimeout(function(e) { $(e).fadeIn("fast"); }, 400);
        });
    } else {
        var xCurrent = 1;
        var yCurrent = 1;
        var xMax = calculateMax($(window).width());
        var yMax = calculateMax($(window).height());
        var currentXMax = 1;
        var currentYMax = 1;
        c.css({
            paddingLeft: gap,
            paddingTop: gap,
            width: $(window).width()- gap,
            height: $(window).height() - gap
        }).show("slow", function() {
            $(this).children("div").css({
                left: gap,
                top: gap,
                width: $(window).width() - 2 * (gap + 1),
                height: $(window).height() - 2 * (gap + 1),
                backgroundColor: activeColour
            }).fadeIn("fast");
        });
    }
    
    $(window).keyup(function(e) {
        e.preventDefault();
        
        switch(e.which) {
            case 27:
                close();
                break;
            case 37:
                moveLeft();
                break;
            case 38:
                moveUp();
                break;
            case 39:
                moveRight();
                break;
            case 40:
                moveDown();
                break;
        }
    });
}
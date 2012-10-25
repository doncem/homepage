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
            grid:{clickable:true,hoverable:true},
            xaxis:{tickSize:1,ticks:d.x},
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

$(function() {
    g = {
        "p_year" : {"graphs" : [{"data":d_y,"label":"movies ("+Math.ceil(d_y_m[0][1]*d_y_x.length)+")","bars":true,"lines":false,"y":1},{"data":d_y_m,"label":"mean","bars":false,"lines":true,"y":1},{"data":d_y_s,"label":"standard deviation","bars":false,"lines":true,"y":1}],"legend":{"position":"nw"},"x":d_y_x,"y":{"align":null,"position":"left"}},
        "p_decade" : {"graphs" : [{"data":d_yd,"label":"movies","bars":true,"lines":false,"y":1},{"data":d_yd_m,"label":"mean","bars":false,"lines":true,"y":1},{"data":d_yd_s,"label":"standard deviation","bars":false,"lines":true,"y":1}],"legend":{"position":"nw"},"x":d_yd_x,"y":{"align":null,"position":"left"}},
        "p_genre" : {"graphs":[{"data":d_g_m,"label":"movies","bars":true,"lines":false,"y":1},{"data":d_g_s,"label":"tv shows","bars":true,"lines":false,"y":1},{"data":d_g_c,"label":"correlation","bars":false,"lines":true,"y":2}],"legend":{"position":"nw"},"x":d_g_x,"y":{"align":1,"position":"right"}},
        "p_directed" : {"graphs":[{"data":d_d,"label":"#movies by directors","bars":true,"lines":false,"y":1}],"legend":{"position":"ne"},"x":d_d_x,"y":{"align":null,"position":"left"}}
    };
    
    for (i in g) {
        drawPlot(i, g[i]);
    }
    
    var previousPoint = null;
    var previousLabel = null;
    $(".placeholder").bind("plothover", function(event, pos, item) {
        if (item) {
            $(this).css("cursor", "pointer");
            if ((previousPoint != item.dataIndex) || (previousLabel != item.series.label)) {
                previousPoint = item.dataIndex;
                previousLabel = item.series.label;
                $("#tooltip").remove();
                showTooltip(item.pageX, item.pageY, item.datapoint[1], item.series.color, !(($(this).attr("id") == "p_year") || ($(this).attr("id") == "p_decade") || (item.series.label == "correlation")));
            }
        } else {
            $(this).css("cursor", "default");
            $("#tooltip").remove();
            previousPoint = null;
        }
    });
    
    $(".placeholder").bind("plotclick", function(event, pos, item) {
        if (item) {
            alert(item.dataIndex+"\n"+item.datapoint[0]+"\n"+item.datapoint[1]+"\n"+item.series.label+"\n"+
                item.series.xaxis.options.ticks[item.dataIndex][0]+"\n"+
                item.series.xaxis.ticks[item.dataIndex].v+"\n"+
                item.series.xaxis.options.ticks[item.dataIndex][1]+"\n"+
                item.series.xaxis.ticks[item.dataIndex].label);
            //item.dataIndex = item.datapoint[0]
            //item.datapoint[1] => value
            //item.series.label => label_name
            //item.series.xaxis.options.ticks[item.dataIndex][0] = item.series.xaxis.ticks[item.dataIndex].v
            //item.series.xaxis.options.ticks[item.dataIndex][1] = item.series.xaxis.ticks[item.dataIndex].label
            //alert(item.series.xaxis.options.ticks[item.dataIndex][1]);
        }
    });
});

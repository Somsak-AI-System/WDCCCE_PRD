<style>
    .box-border {
        border: 1px solid #ccc;
    }
    /*.grid-stack .grid-stack-item .grid-stack-item-content {*/
        /*overflow: hidden;*/
    /*}*/
</style>

<div class="container-fluid">

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#home">Home</a></li>
        <li><a data-toggle="tab" href="#tab1">Tab 1</a></li>
        <li><a data-toggle="tab" href="#tab2">Tab 2</a></li>
        <li><a data-toggle="tab" href="#tab3">Tab 3</a></li>
    </ul>

    <div class="tab-content">
        <div id="home" class="tab-pane fade in active">
            <div class="row">
                <div class="col-md-12">
                    <div class="btn-group pull-right">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Add Widget <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:;" onclick="$.genGraph(1)">Graph 1</a></li>
                            <li><a href="#">Graph 2</a></li>
                            <li><a href="#">Graph 3</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row grid-stack">
                <div class="grid-stack-item" data-gs-x="0" data-gs-y="0" data-gs-width="4" data-gs-height="5">
                    <div class="grid-stack-item-content box-border">
                        chart 1
                        <div id="chart1" style="min-height: 350px"></div>
                    </div>
                </div>

                <div class="grid-stack-item" data-gs-x="4" data-gs-y="0" data-gs-width="4" data-gs-height="5">
                    <div class="grid-stack-item-content box-border">
                        chart 2
                        <div id="chart2" style="min-height: 350px"></div>
                    </div>
                </div>

                <div class="grid-stack-item" data-gs-x="8" data-gs-y="0" data-gs-width="4" data-gs-height="5">
                    <div class="grid-stack-item-content box-border">
                        <div id="chart3" style="min-height: 350px"></div>
                    </div>
                </div>

                <div class="grid-stack-item" data-gs-x="0" data-gs-y="0" data-gs-width="4" data-gs-height="5">
                    <div class="grid-stack-item-content box-border">
                        <div id="chart4" style="min-height: 350px"></div>
                    </div>
                </div>

                <div class="grid-stack-item" data-gs-x="4" data-gs-y="0" data-gs-width="4" data-gs-height="5">
                    <div class="grid-stack-item-content box-border">
                        <div id="chart5" style="min-height: 350px"></div>
                    </div>
                </div>

                <div class="grid-stack-item" data-gs-x="8" data-gs-y="0" data-gs-width="4" data-gs-height="5">
                    <div class="grid-stack-item-content box-border">
                        <div id="chart6" style="min-height: 350px"></div>
                    </div>
                </div>

                <div class="grid-stack-item" data-gs-x="0" data-gs-y="0" data-gs-width="4" data-gs-height="5">
                    <div class="grid-stack-item-content box-border">
                        <div id="chart7" style="min-height: 350px"></div>
                    </div>
                </div>
            </div>
        </div>

        <div id="tab1" class="tab-pane fade"></div>
        <div id="tab2" class="tab-pane fade"></div>
        <div id="tab3" class="tab-pane fade"></div>
    </div>

</div>

<script>
    $('.grid-stack').gridstack({
        resizable: {
            handles: 'e, se, s, sw, w'
        }
    });

    let standard_ms_data = {
        title: 'title',
        categories: [
            {
                "category": [
                    {
                        "label": "2012"
                    },
                    {
                        "label": "2013"
                    },
                    {
                        "label": "2014"
                    },
                    {
                        "label": "2015"
                    },
                    {
                        "label": "2016"
                    }
                ]
            }
        ],
        dataset: [
            {
                "seriesname": "iOS App Store",
                "data": [
                    {
                        "value": "1250"
                    },
                    {
                        "value": "3000"
                    },
                    {
                        "value": "4800"
                    },
                    {
                        "value": "8000"
                    },
                    {
                        "value": "1100"
                    }
                ]
            },
            {
                "seriesname": "Google Play Store",
                "data": [
                    {
                        "value": "700"
                    },
                    {
                        "value": "1500"
                    },
                    {
                        "value": "3500"
                    },
                    {
                        "value": "6000"
                    },
                    {
                        "value": "1400"
                    }
                ]
            },
            {
                "seriesname": "Amazon AppStore",
                "data": [
                    {
                        "value": "100"
                    },
                    {
                        "value": "1000"
                    },
                    {
                        "value": "3000"
                    },
                    {
                        "value": "6000"
                    },
                    {
                        "value": "9000"
                    }
                ]
            }
        ]
    }

    let standard_data =  {
        title: 'Title',
        data: [
            {
                "label": "Venezuela",
                "value": "2900"
            },
            {
                "label": "Saudi",
                "value": "2600"
            },
            {
                "label": "Canada",
                "value": "1800"
            },
            {
                "label": "Iran",
                "value": "1400"
            },
            {
                "label": "Russia",
                "value": "1150"
            },
            {
                "label": "UAE",
                "value": "1000"
            },
            {
                "label": "US",
                "value": "300"
            },
            {
                "label": "China",
                "value": "300"
            }
        ]
    }

    $.genBox = function(divid){
        let grid_item = $('<div />',{ class:'grid-stack-item' }).attr({ 'data-gs-x':'0', 'data-gs-y':'0', 'data-gs-width':'4', 'data-gs-height':'5' });
        let grid_item_content = $('<div />',{ class:'grid-stack-item-content box-border' });
        let graph = $('<div />',{ id:divid }).css({ 'min-height':'350px' });

        $(grid_item_content).append(graph);
        $(grid_item).append(grid_item_content);

        let grids = $('.grid-stack').data('gridstack');
        grids.add_widget(grid_item, 1, 1, 4, 5, true);
    }

    $.genGraph = function(){
        $.post('<?php echo site_url('Home/getData'); ?>', function(rs){
            let divid = $.now()
            $.genBox(divid);
            $('#'+divid).chartGenerate('column2d', rs);
        },'json')
    }

    $.genData = function(data){
        let sample_data = {
            "chart": {
                "caption": data.title,
                "subcaption": "",
                "xaxisname": "",
                "yaxisname": "",
                "numbersuffix": "",
                "formatNumberScale": "2",
                "placeValuesInside": "0",
                "rotateValues": "0",
                "showValues": "1",
                "valueFontColor": "#000000",
                "theme": "fint"
            },
            "data": data.data
        }
        return sample_data;
    }

    $.genMSData = function(data){
        let sample_data = {
            "chart": {
                "caption": data.title,
                "subcaption": "",
                "xaxisname": "",
                "yaxisname": "",
                "numbersuffix": "",
                "formatNumberScale": "2",
                "placeValuesInside": "0",
                "rotateValues": "0",
                "showValues": "1",
                "valueFontColor": "#000000",
                "theme": "fint"
            },
            "categories": data.categories,
            "dataset": data.dataset
        }
        return sample_data;
    }

    $.fn.chartGenerate = function(type, data_source){
        let mat = type.match(/ms/g);
        if(mat){
            data_source = $.genMSData(data_source);
        }else{
            data_source = $.genData(data_source);
        }

        let div = $(this).attr('id');
        new FusionCharts({
            type: type,
            renderAt: div,
            width: "100%",
            height: "300",
            dataFormat: "json",
            dataSource: data_source
        }).render();
    }

    $('#chart1').chartGenerate('column2d', standard_data);
    $('#chart2').chartGenerate('bar2d', standard_data);
    $('#chart3').chartGenerate('mscolumn2d', standard_ms_data);
    $('#chart4').chartGenerate('msbar2d', standard_ms_data);
    $('#chart5').chartGenerate('pie2d', standard_data);
    $('#chart6').chartGenerate('line', standard_data);
    $('#chart7').chartGenerate('msline', standard_ms_data);

    const sample_data = {
        "chart": {
            "caption": "Countries With Most Oil Reserves [2017-18]",
            "subcaption": "",
            "xaxisname": "",
            "yaxisname": "",
            "numbersuffix": "",
            "theme": "fint"
        },
        "data": [
            {
                "label": "Venezuela",
                "value": "290"
            },
            {
                "label": "Saudi",
                "value": "260"
            },
            {
                "label": "Canada",
                "value": "180"
            },
            {
                "label": "Iran",
                "value": "140"
            },
            {
                "label": "Russia",
                "value": "115"
            },
            {
                "label": "UAE",
                "value": "100"
            },
            {
                "label": "US",
                "value": "30"
            },
            {
                "label": "China",
                "value": "30"
            }
        ]
    };

    const serie_data = {
        "chart": {
            "caption": "App Publishing Trend",
            "subcaption": "2012-2016",
            "xaxisname": "Years",
            "yaxisname": "Total number of apps in store",
            "formatnumberscale": "1",
            "plottooltext": "<b>$dataValue</b> apps were available on <b>$seriesName</b> in $label",
            "theme": "fint",
            "drawcrossline": "1"
        },
        "categories": [
            {
                "category": [
                    {
                        "label": "2012"
                    },
                    {
                        "label": "2013"
                    },
                    {
                        "label": "2014"
                    },
                    {
                        "label": "2015"
                    },
                    {
                        "label": "2016"
                    }
                ]
            }
        ],
        "dataset": [
            {
                "seriesname": "iOS App Store",
                "data": [
                    {
                        "value": "125000"
                    },
                    {
                        "value": "300000"
                    },
                    {
                        "value": "480000"
                    },
                    {
                        "value": "800000"
                    },
                    {
                        "value": "1100000"
                    }
                ]
            },
            {
                "seriesname": "Google Play Store",
                "data": [
                    {
                        "value": "70000"
                    },
                    {
                        "value": "150000"
                    },
                    {
                        "value": "350000"
                    },
                    {
                        "value": "600000"
                    },
                    {
                        "value": "1400000"
                    }
                ]
            },
            {
                "seriesname": "Amazon AppStore",
                "data": [
                    {
                        "value": "10000"
                    },
                    {
                        "value": "100000"
                    },
                    {
                        "value": "300000"
                    },
                    {
                        "value": "600000"
                    },
                    {
                        "value": "900000"
                    }
                ]
            }
        ]
    };

    const pie_data = {
        "chart": {
            "caption": "Market Share of Web Servers",
            "plottooltext": "<b>$percentValue</b> of web servers run on $label servers",
            "showlegend": "1",
            "showpercentvalues": "1",
            "legendposition": "bottom",
            "usedataplotcolorforlabels": "1",
            "theme": "fint"
        },
        "data": [
            {
                "label": "Apache",
                "value": "32647479"
            },
            {
                "label": "Microsoft",
                "value": "22100932"
            },
            {
                "label": "Zeus",
                "value": "14376"
            },
            {
                "label": "Other",
                "value": "18674221"
            }
        ]
    };

    $.fn.chartColumn2d = function(data_source){
        let div = $(this).attr('id');
        new FusionCharts({
            type: "column2d",
            renderAt: div,
            width: "100%",
            height: "300",
            dataFormat: "json",
            dataSource: data_source
        }).render();
    }

    $.fn.chartBar2d = function(data_source){
        let div = $(this).attr('id');
        new FusionCharts({
            type: "bar2d",
            renderAt: div,
            width: "100%",
            height: "300",
            dataFormat: "json",
            dataSource: data_source
        }).render();
    }

    $.fn.chartLine = function(data_source){
        let div = $(this).attr('id');
        new FusionCharts({
            type: "line",
            renderAt: div,
            width: "100%",
            height: "300",
            dataFormat: "json",
            dataSource: data_source
        }).render();
    }

    $.fn.chartColumn2dMS = function(data_source){
        let div = $(this).attr('id');
        new FusionCharts({
            type: "mscolumn2d",
            renderAt: div,
            width: "100%",
            height: "300",
            dataFormat: "json",
            dataSource: data_source
        }).render();
    }

    $.fn.chartBar2dMS = function(data_source){
        let div = $(this).attr('id');
        new FusionCharts({
            type: "msbar2d",
            renderAt: div,
            width: "100%",
            height: "300",
            dataFormat: "json",
            dataSource: data_source
        }).render();
    }

    $.fn.chartLineMS = function(data_source){
        let div = $(this).attr('id');
        new FusionCharts({
            type: "msline",
            renderAt: div,
            width: "100%",
            height: "300",
            dataFormat: "json",
            dataSource: data_source
        }).render();
    }

    $.fn.pie2d = function(data_source){
        let div = $(this).attr('id');
        new FusionCharts({
            type: "pie2d",
            renderAt: div,
            width: "100%",
            height: "300",
            dataFormat: "json",
            dataSource: data_source
        }).render();
    }
</script>
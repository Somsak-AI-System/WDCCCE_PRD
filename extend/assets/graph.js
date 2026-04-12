var chart_ms_config = {
    "palettecolors":"2196f3,0d47a1,1976d2,64b5f6,fff176,ffd54f,ffb74d",
    "plotToolText": "<b>$label</b><br>$seriesName: $dataValue",
    "exportEnabled": true,
    "subcaption": "",
    "xaxisname": "",
    "yaxisname": "",
    "numbersuffix": "",
    "formatNumberScale": "2",
    "placeValuesInside": "0",
    "labelDisplay": "rotate",
    "slantLabel": "1",
    "rotateValues": "1",
    "showValues": "1",
    "valueFontColor": "#000000",
    "is2d": "0",
    "ishollow": "0",
    "usesameslantangle": "0",
    "theme": "fint"
}

var chart_config = {
    "plotToolText": "<b>$label</b> : $dataValue",
    "exportEnabled": true,
    "subcaption": "",
    "xaxisname": "",
    "yaxisname": "",
    "numberSuffix": "",
    "formatNumberScale": "1",
    "placeValuesInside": "1",
    "labelDisplay": "rotate",
    "slantLabel": "1",
    "rotateValues": "0",
    "showValues": "1",
    "is2d": "0",
    "ishollow": "0",
    "usesameslantangle": "0",
    "theme": "fint"
}

$.genData = function(data){ console.log(data.data)
    let config_data = chart_config;
    let sample_data = {
        "chart": config_data,
        "data": data.data
    }
    return sample_data;
}

$.genMSData = function(data){
    let config_data = chart_ms_config;
    if(data.tooltip_pattern != null) config_data.plotToolText = data.tooltip_pattern;
    let sample_data = {
        "chart": config_data,
        "categories": data.categories,
        "dataset": data.dataset
    }
    return sample_data;
}

$.fn.chartGenerate = function(type, data_source){ console.log(type)
    let mat = type.match(/ms/g);
    if(mat){
        data_source = $.genMSData(data_source);
    }else{
        let stacked = type.match(/stacked/g);
        if(stacked){
            data_source = $.genMSData(data_source);
        }else{
            console.log(type)
            data_source = $.genData(data_source);
        }
    }

    let div = $(this).attr('id');
    new FusionCharts({
        type: type,
        renderAt: div,
        width: "100%",
        height: "500",
        dataFormat: "json",
        dataSource: data_source
    }).render();
}
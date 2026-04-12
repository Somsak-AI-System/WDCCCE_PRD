
var json = jQuery('.data_template').val();

data_answer = jQuery('.data_answer').val();

window.survey = new Survey.Model(json);

survey
    .onComplete
    .add(function (result) {
        document
            .querySelector('#surveyResult')
            .innerHTML = "result: " + JSON.stringify(result.data);
    });

survey.data = jQuery.parseJSON(data_answer);

survey.mode = 'editor';

$("#surveyElement").Survey({model: survey});

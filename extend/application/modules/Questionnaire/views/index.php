<?php global $site_URL;  ?>
<form id="searchForm" class="easyui-form" method="POST" name="searchForm">
    <div class="row">
        <div class="col-lg-12">
            <div id="layout" class="easyui-layout" style="width:100%;height:770px">
                <div data-options="region:'north',split:true" title="Filter" style="width:100%; height:120px;">

                    <div style="clear:both; height:8px; "></div>

                    <!-- <div class="col-xs-3" style="text-align:left;">
						<label for="menu">โครงการ <span style="color: red">*</span></label>
						<input class="easyui-textbox branch" id="branchid" name="branchid" style="width:70%; height:25px;"  >
					</div> -->

                    <div class="col-xs-4">
                        <label for="menu">แบบสอบถาม <span style="color: red">*</span></label>
                        <input class="easyui-combogrid questionnairetemplateid" id="questionnairetemplateid"
                            name="questionnairetemplateid" style="width:70%; height:25px;" />
                    </div>

                    <div class="col-xs-4">
                        <label for="menu">วันที่แบบสอบถาม</label>
                        <input type="text" class="easyui-datebox" id="date_from" name="date_from"
                            value="<? echo $date_from ?>" style="height:25pt; width:60%;" />
                    </div>

                    <div class="col-xs-4">
                        <label for="menu">ถึง</label>
                        <input type="text" class="easyui-datebox" id="date_to" name="date_to"
                            value="<? echo $date_to ?>" style="height:25pt; width:60%;" />
                    </div>

                    <div style="clear:both; height:8px; "></div>

                    <div class="col-xs-12" style="text-align:center">
                        <a href="#" class="easyui-linkbutton" onclick="Filter()"><i class="fa fa-search"></i> Export</a>
                    </div>

                </div>

                <div data-options="region:'center',title:'',iconCls:'icon-ok'" id="report-result">
                    <iframe id="birt-result" style="padding:0 0 0 0;min-height:500px;" frameborder="0" width="100%"
                        height="auto">

                    </iframe>
                </div>
            </div>
            <div id="chart1div"></div>
        </div>
    </div>
</form>



<script>
$(document).ready(function() {

    // GetBranch();
	getQuestionnaireTemplate()

});

function Filter() {

    $.messager.progress();
    // var form_data = $('#searchForm').serialize();
    // var branchid = $("#branchid").combogrid('getValue');
    var questionnairetemplateid = $("#questionnairetemplateid").combogrid('getValue');
    var date_start = $("#date_from").datebox('getValue');
    var due_date = $("#date_to").datebox('getValue');

    if (questionnairetemplateid == '') {
        jQuery.messager.alert('Warning', 'กรุณาเลือกแบบสอบถาม', 'warning');
        $.messager.progress('close');
        return false;
    }

    var url = '<?php echo site_url("Questionnaire/export")?>';
    var form_data = {
        questionnairetemplateid: questionnairetemplateid,
        date_start: date_start,
        due_date: due_date,
    };
	
    $.ajax(url, {
        type: 'POST',
        dataType: 'json',
        data: form_data,
        success: function(data) {
            var obj = data;
            $.messager.progress('close');
            if (obj.status == true) {
                window.open('<?php echo site_url();?>/' + obj.url);
            } else {
                $.messager.alert('Warning', obj.error, 'warning');
            }
        },
        error: function(data) {
            $.messager.progress('close');
            $.messager.alert('Error data', data, 'error');
        }
    });

}
</script>

 <style type="text/css">
   .row{
    margin-right: 0px !important;
    margin-left: 0px !important;
   }
   .box{
    width: 30% !important;
   }
   .dashboard{
    text-align: text !important; 
    background-color: #fff !important; 
    border-radius: 3px !important; 
    height: 100% !important; 
    margin-bottom: 0px !important;
   }
   h2{
    margin-top: 10px !important;
    line-height: normal !important;
    font: bold !important;
   }
   .list{
    margin-top: 5px !important;
   }
   .col-md-12{
    /*height: 50px !important;*/
   }
   .col-md-2{
    position: relative;
    min-height: 1px;
    padding-right: 15px;
    padding-left: 15px;
   }
   .col-md-3{
    padding-right: 0px !important;
    padding-left: 5px !important;
    height: 50px !important;
   }
   .col-md-4{
    padding-right: 0px !important;
    padding-left: 0px !important;
   }
   .col-md-8{
    padding-right: 0px !important;
    padding-left: 0px !important;
   }
   .statuslist{
      margin-left: 5px !important;
      font-size: 12px !important;
   }
   p{
    text-align: center !important;
    font-size: 24px !important;
    margin: -8px 0px 0px 1px !important;
   }
   .wait{
    background-color: #BDC3C7  !important;
    color: #fff !important;
   }
   .open{
    background-color: #4483ea !important;
    color: #fff !important;
   }
   .proces{
    background-color: #fe5631 !important;
    color: #fff !important;
   }
   .closed{
    background-color: #76d33d !important;
    color: #fff !important;
   }
   .block-chart{
    margin-top: 5px !important;
    height: 400px !important;
   }
   .block-table{
    margin-top: 5px !important;
    height: 810px !important;
   }
   .b_active{
    background: #66b317 !important;
    color: #fff !important;
   }
   .btn-sm{
      margin-left: 8px !important;
      /*margin-top: 8px !important;*/
      height: 35px !important;
      font-size: 16px !important;
      font-weight: bold !important;
   }
   .box {
     width: 100% !important;
    }
	#reload:hover {
	  color: #CCC;
	}
	#datatable {
		width: 100% !important;
	}
  table th:nth-child(3), td:nth-child(3) {
    display: none;
  }
  div.dataTables_wrapper div.dataTables_paginate {
    margin: 0 !important;
    white-space: nowrap !important;
    text-align: right !important;
  }
  div.dataTables_wrapper div.dataTables_filter {
      text-align: right !important;
  }
  .form-inline .form-control {
    margin-left: 0.5em !important;
    display: inline-block !important;
    width: auto !important;
  }
  div.dataTables_wrapper div.dataTables_paginate ul.pagination {
    margin: 2px 0 !important;
    white-space: nowrap !important;
  }
  .pagination {
    display: inline-block !important;
    padding-left: 0 !important;
    border-radius: 4px !important;
  }
 </style>
 
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" >
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Inspector</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Inspector</li>
      </ol>
    </section>
    <!-- Content Header (Page header) -->
    <section class="content">

      <div id="w" >
        <div id="acc">
           
           <!-- Head Block -->
             <div class="row">
              <div class="col-md-12">
                <!-- <div class="form-group dashboard head"> -->
                 <!--  <div class="form-group"> -->
                  	
                  	
		              <select class="form-control select2" id="branchid" name="branchid" data-placeholder="Select a Project" style="width:25% !important;">
		              	 <option value=''>None</option>
		              </select>
	                  
	                  <span class="toggleWrapper">
	                    <input type="checkbox" id="dn" class="dn">
	                    <label for="dn" class="toggle"><span class="toggle__handler"></span></label>
	                  </span>
                  
                  <span style="float: right !important; margin-right: 20px !important; margin-top: 15px !important;"><i class="glyphicon glyphicon-refresh" id="reload" title="Reload" style="cursor: pointer;"></i></span>
                  	
                   <!-- </div> -->
                <!-- </div> -->
              </div>            
            </div>
            <!-- /Head Block -->

            <div class="row list">
                <div class="col-md-2">
                  <div class="form-group dashboard all">
                    <label class="statuslist">All</label>
                    <p class="all" id="all">0</p>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group dashboard wait">
                    <label class="statuslist">Wait</label>
                    <p class="wait" id="wait">0</p>
                  </div>
                </div>   
                <div class="col-md-2">
                  <div class="form-group dashboard open">
                    <label class="statuslist">Open</label>
                    <p class="open" id="open">0</p>
                  </div>
                </div>    
                 <div class="col-md-2">
                  <div class="form-group dashboard proces">
                    <label class="statuslist">Processing</label>
                    <p class="processing" id="processing">0</p>
                  </div>
                </div>    
                 <div class="col-md-2">
                  <div class="form-group dashboard closed">
                    <label class="statuslist">Closed</label>
                    <p class="closed" id="closed">0</p>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group dashboard">
                    <label class="statuslist">Worksheet</label>
                    <p class="worksheet" id="worksheet">0</p>
                  </div>
                </div>           
              </div>

            </div><!-- /row list -->

          </div>

        <div class="row">
        	<div class="col-md-4">
	        	<div class="row">
	            	<div class="col-md-12 block-chart">
	            		<div class="form-group dashboard head">
                  			<div id="chart-container"></div>
                		</div>
	            	</div>
	            	<div class="col-md-12 block-chart">
	                	<div class="form-group dashboard head">
	                  		<div id="chart-container1"></div>
	                	</div>
	            	</div>
	        	</div>
	    	</div>
	    	<div class="col-md-8">
	        	<div class="col-md-12 block-table">
	        	    <div class="form-group dashboard head">
                      <div class="box-header with-border">
                      		<label class="header-block">Delay Task</label>
                    	</div>

                    	<div class="box-body">
	                      <div class="row datatable_block">
	                        <div class="col-md-12">
	         
	                        <table id="datatable" class="table table-striped table-bordered display nowrap" cellspacing="0" style="width:100%!important">

	                          <thead>
	                            <tr>
	                                <th>No.</th>
	                                <th>Inspection No</th>
	                                <th>Inspection Date</th>
	                                <th>Time</th>
	                                <th>Project Name</th>
	                                <th>Unit No</th>
	                                <th>Inspection Type</th>
	                                <th></th>
	                            </tr>
	                          </thead>
	                          
	                          
	                          <tfoot>
	                              <tr>
	                                  <th>No.</th>
	                                  <th>Inspection No</th>
	                                  <th>Inspection Date</th>
	                                  <th>Time</th>
	                                  <th>Project Name</th>
	                                  <th>Unit No</th>
	                                  <th>Inspection Type</th>
	                                  <th></th>
	                              </tr>
	                          </tfoot>
	                        </table>

	                      </div>
	                    </div>  
	                    
	                  </div>

                   	</div>
	        	</div>
	    	</div>

       </div><!-- /acc -->
      </div><!-- /w -->
    </section>
  <!-- Content Wrapper. Contains page content -->
  </div>

<script src="<?php echo site_assets_url('js/fusioncharts/fusioncharts.js'); ?>" ></script>
<script src="<?php echo site_assets_url('js/fusioncharts/fusioncharts.theme.fusion.js'); ?>" ></script>
<script src="<?php echo site_assets_url('js/fusioncharts/fusioncharts.jqueryplugin.min.js'); ?>" ></script>



<script type="text/javascript" src="<?php echo site_assets_url('DataTables/js/jquery.dataTables.js');?>"></script>
<!-- <script type="text/javascript" src="<?php //echo site_assets_url('DataTables/js/dataTables.rowReorder.min.js');?>"></script>
<script type="text/javascript" src="<?php //echo site_assets_url('DataTables/js/dataTables.responsive.min.js');?>"></script> -->
<script type="text/javascript" src="<?php echo site_assets_url('DataTables/js/dataTables.bootstrap.js');?>"></script> 

<!-- <link rel="stylesheet" type="text/css" href="<?php //echo site_assets_url('DataTables/css/dataTables.bootstrap.css'); ?>"> 
<link rel="stylesheet" type="text/css" href="<?php //echo site_assets_url('DataTables/css/dataTables.bootstrap4.min.css'); ?>"> -->
<!-- <link rel="stylesheet" type="text/css" href="<?php //echo site_assets_url('DataTables/css/rowReorder.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php //echo site_assets_url('DataTables/css/responsive.dataTables.min.css'); ?>"> -->
<script>

$(document).ready(function() {  
   
    var branchid = "<?php echo (isset($data['branchid'])) ? $data['branchid'] : ''; ?>";
    var inspectiontype = "<?php echo (isset($data['inspection_type'])) ? $data['inspection_type'] : ''; ?>";

    get_branch(branchid,inspectiontype);
    
    $('#datatable').DataTable({
      "scrollY": "580px",
      "scrollCollapse": true,
      "bScrollCollapse": true,
      "bPaginate": true,
      "bJQueryUI": true,
      "responsive": true,
    });

	$("#branchid").select2({});

    $(document).on('click','.view',function(){
      url = "<?php echo site_url("inspection/detail")?>/"+this.id; 
    window.open(url, "_blank"); 
    });

    var switchStatus = false;
  	$("#dn").on('change', function() {
  	    get_data();
  	});

    $(document).on('change','#branchid',function(){
	    get_data();
	  });

    setInterval(function() {
      get_data();
    }, 300000);

    $(document).on('click','#reload',function(){
    	get_data();
    });
  
});

function get_data(){

	var branchid = $('#branchid').val();

  if(branchid == ''){
    swal({
      position: 'center',
      type: 'error',
      title: 'Please select project',
      showConfirmButton: false,
      timer: 2000
    });
    $('#branchid').focus();
    $('#loader').fadeOut();
    return false;
  }

  switchStatus = $("#dn").is(':checked');
  if (switchStatus === false) {
      var inspecttype = 'Customer' ;
  }else{
     var inspecttype = 'Contractor' ;
  }
  
	var data = {
      branchid : branchid,
      inspecttype : inspecttype,
    }
  console.log(data);
   var url = "<?php echo site_url('dashboard/get_data'); ?>";
      $.ajax(url, {
         type: 'POST',
         data: data,
         success: function (data){
          var result = jQuery.parseJSON(data);
          //console.log(result);
          //if(result['Type'] =='E'){
            //reload_datatable(result['data']);
          //}else{
            jQuery("#all").html(result.all_status.All);
            jQuery("#wait").html(result.all_status.Wait);
            jQuery("#open").html(result.all_status.Open);
            jQuery("#processing").html(result.all_status.Processing);
            jQuery("#closed").html(result.all_status.Closed);
            jQuery("#worksheet").html(result.all_status.worksheet);
            chart_over_all(result.overall);
            defect_category(result.defect);
            reload_datatable(result.dealy);
          //}
          $('#loader').fadeOut();
         },
         error: function (data){
          $('#loader').fadeOut();
          swal("",data.Message,"error");
         }
      });
}


function chart_over_all(data){
	$("#chart-container").insertFusionCharts({
	  type: "doughnut2d",
	  width: "100%",
	  height: "100%",
	  creditLabel: false,
	  dataFormat: "json",
	  dataSource: {
	    chart: {
	      caption: "Over all processing",
	      subcaption: "",
	      showpercentvalues: "1",
	      defaultcenterlabel: "",//"Android Distribution",
	      aligncaptionwithcanvas: "0",
	      captionpadding: "0",
	      decimals: "0",
	      plottooltext: "",//"<b>$percentValue</b> of our Android users are on <b>$label</b>",
	      centerlabel: "",//"# Users: $value",
	      theme: "fusion"
	    },
	    credits: false,
	    data : data
	  }
	  
	});

}

function defect_category(data){

	$("#chart-container1").insertFusionCharts({
      type: "doughnut3d",
      width: "100%",
      height: "100%",
      dataFormat: "json",
      creditLabel: false,
      dataSource: {
        chart: {
          //caption: "Defect Category",
          caption: "Top 10 Defect Category",
          subcaption: "",
          enablesmartlabels: "1",
          showlabels: "0",
          numbersuffix: " Defect",
          usedataplotcolorforlabels: "1",
          plottooltext: "",//"$label, <b>$value</b> MMbbl",
          theme: "fusion"
        },
        data : data
      }
    });

}

function get_branch(branchid,inspectiontype){
   $('#loader').fadeIn();
   var url = "<?php echo site_url('branch/getbranch'); ?>";
    $.ajax(url, {
       type: 'POST',
       data: '',
       success: function (data){
        var result = jQuery.parseJSON(data);
          var select = '';
          $.each(result['data'],function(index,json){
            $('#branchid').append('<option value="'+json.branchid+'" >'+json.branch_name+'</option>');
          });

       },
       error: function (data){
        $('#loader').fadeOut();
        swal("",data.Message,"error");
       }
    });
    setTimeout(function(){
      
      if(inspectiontype == 'Contractor'){
        $("#dn").prop('checked', true);
      }
    	if(branchid != ''){
    		$("#branchid").val(branchid).trigger("change"); 
    	}else{
        $('#loader').fadeOut();
      }
    	
    }, 1000);
}

function reload_datatable(data){
  var table = $('#datatable').DataTable();
  table.clear().draw();

      var data_table = $('#datatable').DataTable({
      "data" : data,
      "processing": true,
      "destroy" :true,
      "columns": [
          { "data": "inspectionid" },
          { "data": "inspection_no" },
          { "data": "inspection_date",
              render: function(d) {
                if(d == '0000-00-00' || d == null || d == ''){
                  return ''; 
                }else{
                  return moment(d).format("DD/MM/YYYY");
                }
                
              }
          },
          { "data": "inspection_time" },
          { "data": "branch_name" },
          { "data": "productname" },
          { "data": "inspection_type" },
          { "data": null ,
              render: function(data) {
                var id = data.inspectionid;
                return "<button type='button' title='View' class='btn btn-success view btn-sm' id='"+id+"'><i class='glyphicon glyphicon-search'></i></button>"; 
              }
          },
          
      ],"columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        },

        ],
        "order": [[ 0, 'DESC' ]]
  });

  //Running No. datatable
  data_table.on( 'order.dt search.dt', function () {
      data_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
      } );
  } ).draw();

  $('#loader').fadeOut();

} 

</script>


<style type="text/css">
.toggleWrapper{
  overflow: hidden:
  -webkit-transform: translate3d(-50%, -50%, 0) !important;
  transform: translate3d(-50%, -50%, 0) !important; 
}
.toggleWrapper input{
  position: absolute !important; 
  left: -99em !important; 
}
.toggle{
  top: -5% !important;
  left: 1% !important;
  cursor: pointer !important; 
  display: inline-block !important; 
  position: relative !important; 
  width: 143px !important; 
  height: 35px !important; 
  background: #66b317 !important; 
  border-radius: 5px !important; 
  -webkit-transition: all 200ms cubic-bezier(0.445, 0.05, 0.55, 0.95) !important; 
  transition: all 200ms cubic-bezier(0.445, 0.05, 0.55, 0.95) !important; 
}
.toggle:before, .toggle:after{
  position: absolute !important; 
  line-height: 35px !important; 
  font-size: 14px !important; 
  z-index: 2 !important; 
  -webkit-transition: all 200ms cubic-bezier(0.445, 0.05, 0.55, 0.95) !important; 
  transition: all 200ms cubic-bezier(0.445, 0.05, 0.55, 0.95) !important; 
}
.toggle:before{
  content: "Customer" !important; 
  left: 5px !important; 
  color: #66b317 !important; 
}
.toggle:after{
  content: "Contractor" !important; 
  right: 5px !important; 
  color: #fff !important; 
}
.toggle__handler{
  display: inline-block !important; 
  position: relative !important; 
  z-index: 1 !important; 
  background: #fff !important; 
  width: 63px !important; 
  height: 30px !important; 
  border-radius: 3px !important; 
  top: 3px !important; 
  left: 3px !important; 
  -webkit-transition: all 200ms cubic-bezier(0.445, 0.05, 0.55, 0.95) !important; 
  transition: all 200ms cubic-bezier(0.445, 0.05, 0.55, 0.95) !important; 
  -webkit-transform: translateX(0px) !important; 
  transform: translateX(0px) !important; 
}
input:checked + .toggle{
  background: #66b317 !important; 
}
input:checked + .toggle:before{
  color: #fff !important; 
}
input:checked + .toggle:after{
  color: #66b317 !important; 
}
input:checked + .toggle .toggle__handler{
  width: 70px !important; 
  left: 10px !important; 
  -webkit-transform: translateX(60px) !important; 
  transform: translateX(60px) !important; 
  border-color: #fff !important; 
}
</style>
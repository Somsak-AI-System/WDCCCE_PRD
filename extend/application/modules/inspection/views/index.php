 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Inspection
        <small>View</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-paste"></i>Inspection</a></li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <!-- Fillter -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Fillter</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Project Name</label>
                <select class="form-control select2" id="branchid" multiple="multiple" data-placeholder="Select a Project" style="width: 100%;">
                  <option value=''>None</option>
                  <?php
                    foreach($data_branch as $key => $val){
                      echo "<option value='".$val['branchid']."'>".$val['branch_name']."</option>";
                    }
                  ?>
                </select>
              </div>
              <!-- /.form-group -->
              <div class="form-group">
               <label>Inspection No</label>
               <input type="text" class="form-control" id="inspection_no" name="inspection_no" placeholder="Inspection No....">
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-3">
              <div class="form-group">
                <label>Building Name</label>
                <select class="form-control select2" id="buildingid" multiple="multiple" data-placeholder="Select a Building" style="width: 100%;">
                <option value=''>None</option>
                  <?php
                    foreach($data_building as $key => $val){
                      echo "<option value='".$val['buildingid']."'>".$val['building_name']."</option>";
                    }
                  ?>
                </select>
              </div>
              <!-- /.form-group -->
               <div class="form-group">
               <label>Status</label>
               <select class="form-control select2" id="inspection_status" multiple="multiple" data-placeholder="Select a Status" style="width: 100%;">
                <option value=''>None</option>
                  <?php
                    foreach($data_status as $key => $val){
                      echo "<option value='".$val['inspection_status']."'>".$val['inspection_status']."</option>";
                    }
                  ?>
                </select>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-md-3">
              <div class="form-group">
                <label>Unit No</label>
                <select class="form-control select2" id="productid" multiple="multiple" data-placeholder="Select a Unit" style="width: 100%;">
                <option value=''>None</option>
                  <?php
                    foreach($data_unit as $key => $val){
                      echo "<option value='".$val['productid']."'>".$val['productname']." [".$val['branch_name']."]</option>";
                    }
                  ?>
                </select>
              </div>
              <!-- /.form-group -->
               <div class="form-group">
               <!-- 5 -->
              </div>
            </div>
            <!-- /.col -->
            <div class="col-md-3">
              <div class="form-group">
                <label>Inspection Date</label>
                <div class="input-group">
                  <button type="button" class="btn btn-default pull-right" id="daterange-btn">
                    <span>
                      <i class="fa fa-calendar"></i> Select Date range <!-- <?php echo date('d/m/Y').' - '.date('d/m/Y') ;?> --><!-- Date range picker -->
                    </span>
                    <i class="fa fa-caret-down"></i>
                  </button>
                  <input type="hidden" id="inspection_date" name="inspection_date" value="<?php //echo date('d/m/Y').'-'.date('d/m/Y') ;?>">
                </div>
                  
              </div>
              <!-- /.form-group -->
               <div class="form-group">
               <!-- 6 -->
              </div>
            </div>

          </div>
          <!-- /.row -->
           <div class="form-actions" style="text-align: center;">
            <button type="button" class="btn btn-info btn-sm fillter"><i class="fa fa-search"></i> Search</button>
          </div>
        </div>
        <!-- /.box-body -->
        
      </div>
      <!-- Fillter/.box -->



      <!-- Data Table -->
      <div class="box box-default">
        <div class="box-header with-border">
          <button type="button" class="btn btn-primary btn-sm" onclick="create();"><i class="glyphicon glyphicon-plus"></i> New</button>
          <button type="button" class="btn btn-info btn-sm" onclick="export_data();"><i class="glyphicon glyphicon-export"></i> Export</button>
          <button type="button" class="btn btn-default btn-sm" onclick="view_datatable();"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
            
            </table><table id="datatable" class="table table-striped table-bordered display" style="width:100%">
              <thead>
                <tr>
                    <th>No.</th>
                    <th>Inspection No</th>
                    <th>Inspection Date</th>
                    <th>Time</th>
                    <th>Project Name</th>
                    <th>Unit No</th>
                    <th>Inspection Type</th>
                    <th>None,Pass,Not Pass,N/A,All</th>
                    <th>Status</th>
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
                    <th>None,Pass,Not Pass,N/A,All</th>
                    <th>Status</th>
                    <th></th>
                   
                </tr>
            </tfoot>
            </table>

            </div>
        
          </div>
        
        </div>  
        <!-- /.box-header -->
      </div>
      <!-- Data Table/.box -->

     

    </section>
    <!-- /.content -->
  </div>
 <script type="text/javascript" src="<?php echo site_assets_url('DataTables/js/jquery.dataTables.js');?>"></script>
 <script type="text/javascript" src="<?php echo site_assets_url('DataTables/js/dataTables.bootstrap.js');?>"></script>
 <link rel="stylesheet" type="text/css" href="<?php echo site_assets_url('DataTables/css/dataTables.bootstrap.css'); ?>">
 <link rel="stylesheet" type="text/css" href="<?php echo site_assets_url('DataTables/css/dataTables.bootstrap4.min.css '); ?>">

<style type="text/css">
  g[class^='raphael-group-'][class$='-creditgroup'] {
    display:none !important;
  }
  g[class$='creditgroup'] {
    display:none !important;
  }

</style>

<script type="text/javascript">

$(document).ready(function() {
  //$('#loader').fadeOut();
  view_datatable();

  
});

function view_datatable(){
      $('#loader').fadeIn();
      //var url ='<?php echo site_url('inspection/getinspection');?>';
      var url ='<?php echo site_url('inspection/search');?>';
      $.ajax({
        type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
        dataType    : 'JSON',
        url         :  url , // the url where we want to POST
        data        :  '',
        cache       : false,
        encoding    :"UTF-8",
        success: function (data) {
          var result = jQuery.parseJSON(JSON.stringify(data));
          console.log(result);
         
          if(result['Type'] == 'S'){
            reload_datatable(result['data']);
          }else{
            reload_datatable(result['data']);
          }
        },error: function (msg) {
            console.log(msg);
            $('#loader').fadeOut();
        }

      })
}

function reload_datatable(data){
      var data_table = $('#datatable').DataTable({
      "data" : data,
      "processing": true,
      //"retrieve": true,
      
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
            render: function(status) {

              var sum = parseInt(status.none)+parseInt(status.pass)+parseInt(status.notpass)+parseInt(status.na);
              var none = parseInt(status.none);
              var pass = parseInt(status.pass);
              var notpass = parseInt(status.notpass);
              var na = parseInt(status.na);
              return "<button type='button' title='none' style='hight:29px;padding: 5px 7px' class='btn btn-warning btn-sm'>"+none.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",")+"</button>&nbsp<button type='button' title='Pass' style='hight:29px;padding: 5px 7px' class='btn btn-success btn-sm'>"+pass.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",")+"</button>&nbsp<button type='button' title='Not Pass' style='hight:29px;padding: 5px 7px' class='btn btn-danger btn-sm'>"+notpass.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",")+"</button>&nbsp<button type='button' title='N/A' style='hight:29px;padding: 5px 7px' class='btn btn-default btn-sm'>"+na.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",")+"</button>&nbsp<button type='button' title='ALL' style='hight:29px;padding: 5px 7px;' class='btn btn-info btn-sm'>"+sum.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",")+"</button>"; 
              }
          },
          { "data": "inspection_status","className": "text-center",
              render: function(status) {
                if(status== 'Open'){
                  return "<button type='button' title='Open' class='btn btn-info btn-sm'>Open</button>"; 
                }else if(status== 'Processing'){
                  return "<button type='button' title='Processing' class='btn btn-warning btn-sm'>Processing</button>"; 
                }else if(status== 'Repeat'){
                  return "<button type='button' title='Repeat' class='btn btn-danger btn-sm'>Repeat</button>"; 
                }else if(status== 'Closed'){  
                  return "<button type='button' title='Closed' class='btn btn-success btn-sm'>Closed</button>"; 
                }else{
                  return '';
                }
                
              }//Open//Processing//Repeat//Closed
          },
           { "data": null ,//"inspection_status",
              render: function(data) {
                var id = data.inspectionid;
                var status = data.inspection_status;
                var notpass = data.notpass;
                //console.log(notpass);
                if(status== 'Closed'){
                  return "<button type='button' title='View' class='btn btn-success view btn-sm' id='"+id+"'><i class='glyphicon glyphicon-search'></i></button>&nbsp<button type='button' title='Repeat' class='btn btn-primary duplicate btn-sm' name='"+notpass+"' id='"+id+"'><i class='fa fa-copy'></i></button>&nbsp<button type='button' title='Print' class='btn btn-default print btn-sm' id='"+id+"' ><i class='glyphicon glyphicon-print'></i></button>"; 
                

                }else if(status== 'Processing'){
                  return "<button type='button' title='View' class='btn btn-success view btn-sm' id='"+id+"'><i class='glyphicon glyphicon-search'></i></button>";

                }else{
                  return "<button type='button' title='View' class='btn btn-success view btn-sm' id='"+id+"'><i class='glyphicon glyphicon-search'></i></button>&nbsp<button type='button' id='"+id+"' title='Edit' class='btn btn-warning edit btn-sm'><i class='glyphicon glyphicon-edit'></i></button>&nbsp<button type='button' id='"+id+"' title='Deleted' class='btn btn-danger deleted btn-sm'><i class='glyphicon glyphicon-trash'></i></button>";
                }
                
              }//Open//Processing/Repeat//Closed
          },
          /*{
            "targets": -1,
            "data": null,
            "defaultContent": "<button type='button' title='Edit' class='btn btn-warning edit btn-sm'><i class='glyphicon glyphicon-edit'></i></button>&nbsp<button type='button' title='Deleted' class='btn btn-danger deleted btn-sm'><i class='glyphicon glyphicon-trash'></i></button>"
          } */
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

  //Event click button edit
  //$(document).on( 'click', '.edit', function () {
  //var data = data_table.row( $(this).parents('tr') ).data();
  data_table.on( 'click', '.edit', function () {
    //console.log(this.id);  
    window.location.assign("<?php echo site_url("inspection/mnt")?>/"+this.id); 
    //console.log(data);
  });

  data_table.on( 'click', '.deleted', function () {
  
    crmid = this.id;
    
    swal({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      type: 'warning',
      inputAttributes: {
        autocapitalize: 'off'
      },
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes',
      showLoaderOnConfirm: true,
      preConfirm: () => {
        $('#loader').fadeIn();
        var url = "<?php echo site_url('inspection/deleted'); ?>";  
        var data = {crmid:crmid};
        $.ajax(url, {
           type: 'POST',
           dataType : 'json',
           data: data,
           cache: false,
           encoding:"UTF-8",
           success: function (data){
            var result = jQuery.parseJSON(JSON.stringify(data));
            console.log(result);
            if(result['Type'] =='E'){
              $('#loader').fadeOut();
              swal("",result['message'],"error");
            }else{
              swal({
                position: 'center',//'top-end',
                type: 'success',
                title: 'Deleted Complete',
                showConfirmButton: false,
                timer: 2000
              });
              view_datatable();
            }
            
           },
           error: function (data){
            $('#loader').fadeOut();
            swal("",data.Message,"error");
           }
        });
            
      }
    })
    
  });

  /*$(document).on( 'click', '.print', function () {
    var data = data_table.row( $(this).parents('tr') ).data();*/
  data_table.on( 'click', '.print', function () {
    //console.log(this.id);
     var URL = 'http://aisystem.dyndns.biz:8100/birt-viewer/frameset?__showtitle=false&__report=nogd/print_preview.rptdesign&crmid='+this.id+'&__format=pdf';
    window.open(URL, '_blank');
    //console.log(data);
  });

  /*$(document).on( 'click', '.view', function () {
    var data = data_table.row( $(this).parents('tr') ).data();*/
  data_table.on( 'click', '.view', function () {
    //console.log(this.id);  
    window.location.assign("<?php echo site_url("inspection/detail")?>/"+this.id); 
  });

  $('#loader').fadeOut();

} 

$(document).on('click','.fillter',function(){
  
   var branchid = $('#branchid').val(); 
   var buildingid = $('#buildingid').val(); 
   var productid = $('#productid').val();
   var inspection_no = $('#inspection_no').val(); 
   var inspection_status = $('#inspection_status').val(); 
   var inspection_date = $('#inspection_date').val();//$('#daterange-btn').val(); 

  $('#loader').fadeIn();
    var data = {
      branchid : branchid,
      buildingid : buildingid,
      productid : productid,
      inspection_date : inspection_date,
      inspection_no : inspection_no,
      inspection_status : inspection_status,
    }

   var url = "<?php echo site_url('inspection/search'); ?>";  
      $.ajax(url, {
         type: 'POST',
         dataType : 'json',
         data: data,
         cache: false,
         encoding:"UTF-8",
         success: function (data){
          var result = jQuery.parseJSON(JSON.stringify(data));
          console.log(result);

          if(result['Type'] =='E'){
            reload_datatable(result['data']);
          }else{
            reload_datatable(result['data']);
          }
          
         },
         error: function (data){
          $('#loader').fadeOut();
          swal("",data.Message,"error");
         }
      });

});


$(document).on('click','.duplicate',function(){
  
   var crmid = this.id;
   var notpass = this.name;
   var data = {
    crmid : crmid,
    notpass : notpass,
   }
    
    if(notpass == 0 ){
        swal({
          title: 'Are you sure?',
          text: "This inspection is already checked defect list. Do you want to repeat it?",
          type: 'warning',
          inputAttributes: {
            autocapitalize: 'off'
          },
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          showLoaderOnConfirm: true,

          preConfirm: () => {
          
            //duplicate_notpass(crmid,notpass);
            //$('#loader').fadeIn();
            //var url = "<?php echo site_url('inspection/duplicate'); ?>";  
            /*$.ajax(url, {
               type: 'POST',
               dataType : 'json',
               data: data,
               cache: false,
               encoding:"UTF-8",
               success: function (data){
                var result = jQuery.parseJSON(JSON.stringify(data));
                
                if(result['Type'] == 'S'){
                  $('#loader').fadeOut();
                  swal({
                    position: 'center',//'top-end',
                    type: 'success',
                    title: 'Duplicate Complete',
                    showConfirmButton: false,
                    timer: 2000
                  });
                  setTimeout(function () { 
                    document.location.href = "<?php echo site_url('inspection/detail')?>/"+result['data']['crmid'];
                  }, 2500);
              }else{
               $('#loader').fadeOut();
                swal("",result['message'],"error"); 
              }
               },
               error: function (data){
                $('#loader').fadeOut();
                swal("",data.Message,"error");
               }
            });*/
                
          }
        }).then(function(result) {
           console.log(result);
           if(result.value) {
            duplicate_notpass(crmid,notpass);
           }
        });
    }else{
      /*$('#loader').fadeIn();
      var url = "<?php echo site_url('inspection/duplicate'); ?>";  
      $.ajax(url, {
         type: 'POST',
         dataType : 'json',
         data: data,
         cache: false,
         encoding:"UTF-8",
         success: function (data){
          var result = jQuery.parseJSON(JSON.stringify(data));
          console.log(result);
          
          if(result['Type'] == 'S'){
            swal({
              position: 'center',//'top-end',
              type: 'success',
              title: 'Duplicate Complete',
              showConfirmButton: false,
              timer: 2000
            });
            setTimeout(function () { 
              document.location.href = "<?php echo site_url('inspection/detail')?>/"+result['data']['crmid'];
            }, 2500);
          }else{
           $('#loader').fadeOut();
            swal("",result['message'],"error"); 
          }
         },
         error: function (data){
          $('#loader').fadeOut();
          swal("",data.Message,"error");
         }
      });*/
      swal({
        title: 'Select Date-Time',
        html: '<div class="input-group date" data-provide="datepicker"><input type="text" class="form-control" id="dup_date"><div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div></div><br><div class="bootstrap-timepicker"><div class="input-group"><input type="text" class="form-control timepicker" id="time_send" name="time_send" required="true"><div class="input-group-addon"><i class="fa fa-clock-o"></i></div></div>',
        showConfirmButton: true,
        buttons: true,
        showCancelButton: true,
        cancelButtonColor: '#d33',
        dangerMode: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        closeOnConfirm: false,
        showLoaderOnConfirm: true,
        customClass: 'swal2-overflow',
        onOpen: function() {
          $('#dup_date').datepicker({
              autoclose: true
          });
          $("#dup_date").datepicker( "setDate" , new Date() );
          $(".timepicker").timepicker({
              showInputs: false,
              showMeridian: false,    
          });
        },
      }).then(function(result) {
          //console.log(result);
          if(result.value) {
            var data = {
              crmid : crmid,
              notpass : notpass,
              inspection_date : $('#dup_date').val(),
              inspection_time : $('#time_send').val(),
            }
            $('#loader').fadeIn();
            var url = "<?php echo site_url('inspection/duplicate'); ?>";  
            $.ajax(url, {
               type: 'POST',
               dataType : 'json',
               data: data,
               cache: false,
               encoding:"UTF-8",
               success: function (data){
                var result = jQuery.parseJSON(JSON.stringify(data));
                console.log(result);
                
                if(result['Type'] == 'S'){
                  swal({
                    position: 'center',//'top-end',
                    type: 'success',
                    title: 'Duplicate Complete',
                    showConfirmButton: false,
                    timer: 2000
                  });
                  setTimeout(function () { 
                    document.location.href = "<?php echo site_url('inspection/detail')?>/"+result['data']['crmid'];
                  }, 2500);
                }else{
                 $('#loader').fadeOut();
                  swal("",result['message'],"error"); 
                }
               },
               error: function (data){
                $('#loader').fadeOut();
                swal("",data.Message,"error");
               }
            });
          }else{

          }


        });


    }


});

function export_data(){

   var branchid = $('#branchid').val(); 
   var buildingid = $('#buildingid').val(); 
   var productid = $('#productid').val();
   var inspection_no = $('#inspection_no').val(); 
   var inspection_status = $('#inspection_status').val(); 
   var inspection_date = $('#inspection_date').val();//$('#daterange-btn').val(); 

  $('#loader').fadeIn();
    var data = {
      branchid : branchid,
      buildingid : buildingid,
      productid : productid,
      inspection_date : inspection_date,
      inspection_no : inspection_no,
      inspection_status : inspection_status,
  }

  var url = "<?php echo site_url('inspection/export'); ?>";  
  $.ajax(url, {
     type: 'POST',
     dataType : 'json',
     data: data,
     cache: false,
     encoding:"UTF-8",
     success: function (data){
      var result = jQuery.parseJSON(JSON.stringify(data));
      console.log(result);
      if(result['Type'] == 'S'){
        var URL = window.location.origin+'/smartinspection/'+result["path"]; 
        window.open(URL, '_blank');
       }else{
        swal("",result['message'],"error");
      }
      $('#loader').fadeOut();
     },
     error: function (data){
      $('#loader').fadeOut();
      swal("",data.Message,"error");
     }
  });

}

function duplicate_notpass(crmid,notpass){
    
   swal({
      title: 'Select Date-Time',
      html: '<div class="input-group date" data-provide="datepicker"><input type="text" class="form-control" id="dup_date"><div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div></div><br><div class="bootstrap-timepicker"><div class="input-group"><input type="text" class="form-control timepicker" id="time_send" name="time_send" required="true"><div class="input-group-addon"><i class="fa fa-clock-o"></i></div></div>',
    showConfirmButton: true,
    buttons: true,
    showCancelButton: true,
    cancelButtonColor: '#d33',
    dangerMode: true,
    allowOutsideClick: false,
    allowEscapeKey: false,
    closeOnConfirm: false,
    showLoaderOnConfirm: true,
    customClass: 'swal2-overflow',
    onOpen: function() {
      $('#dup_date').datepicker({
          autoclose: true
      });
      $("#dup_date").datepicker( "setDate" , new Date() );
      $(".timepicker").timepicker({
          showInputs: false,
          showMeridian: false,    
      });
    },
  }).then(function(result) {
      //console.log(result);
      if(result.value) {
        var data = {
          crmid : crmid,
          notpass : notpass,
          inspection_date : $('#dup_date').val(),
          inspection_time : $('#time_send').val(),
         }
         //console.log(data);
        $('#loader').fadeIn();
        var url = "<?php echo site_url('inspection/duplicate'); ?>";  
        $.ajax(url, {
           type: 'POST',
           dataType : 'json',
           data: data,
           cache: false,
           encoding:"UTF-8",
           success: function (data){
            var result = jQuery.parseJSON(JSON.stringify(data));
            console.log(result);
            
            if(result['Type'] == 'S'){
              swal({
                position: 'center',//'top-end',
                type: 'success',
                title: 'Duplicate Complete',
                showConfirmButton: false,
                timer: 2000
              });
              setTimeout(function () { 
                document.location.href = "<?php echo site_url('inspection/detail')?>/"+result['data']['crmid'];
              }, 2500);
            }else{
             $('#loader').fadeOut();
              swal("",result['message'],"error"); 
            }
           },
           error: function (data){
            $('#loader').fadeOut();
            swal("",data.Message,"error");
           }
        });
      }else{

      }
    });

}
function create(){
  window.location.assign("<?php echo site_url("inspection/mnt")?>"); 
}

</script>
<style type="text/css">
  .swal2-overflow {
  overflow-x: visible;
  overflow-y: visible;
}
</style>
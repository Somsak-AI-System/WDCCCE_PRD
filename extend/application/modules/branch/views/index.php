 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Projects
        <small>View</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-university"></i>Projects</a></li>
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
            <div class="col-md-6">
              <div class="form-group">
                <label>Project Name</label>
                <input type="text" class="form-control" id="branch_name" placeholder="Project No....">
              </div>
              <!-- /.form-group -->
              <div class="form-group">
               
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-6">
              <div class="form-group">
                <label>Status</label>
                <select class="form-control select2" id="pj_status" name="pj_status" style="width: 100%;">
                  <option value="">All</option>
                  <option value="Active">Active</option>
                  <option value="Inactive">Inactive</option>
                  <!--<option disabled="disabled">California (disabled)</option>-->
                </select>
              </div>
              
            </div> 
            <!-- /.col -->
          </div>
          <!-- /.row -->
          <div class="form-actions" style="text-align: center;">
            <button type="button" class="btn btn-info btn-sm search fillter"><i class="fa fa-search"></i> ค้นหา</button>
          </div>
        </div>
        <!-- /.box-body -->
        
      </div>
      <!-- Fillter/.box -->

      <!-- Data Table -->
      <div class="box box-default">
        <div class="box-header with-border">
          <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createModal" data-name="" data-whatever="Create Project" data-action="add" data-crmid="" ><i class="glyphicon glyphicon-plus"></i> New</button>
          <button type="button" class="btn btn-default btn-sm" onclick="view_datatable();"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">

            <table id="datatable" class="table table-striped table-bordered display" style="width:100%">
              <thead>
                <tr>
                    <th>No.</th>
                    <th>Project Name</th>
                    <th>Project Type</th>
                    <th>Status</th>
                    <th>Date Create</th>
                    <th></th>
                   
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th>No.</th>
                    <th>Project Name</th>
                    <th>Project Type</th>
                    <th>Status</th>
                    <th>Date Create</th>
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


 <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">New message</h4>
      </div>
      <div class="modal-body">
        <form>
          <input type="hidden" class="form-control" id="crmid" >
          <input type="hidden" class="form-control" id="action" >
          <div class="form-group">
            <label for="recipient-name" class="control-label">Project Name:</label>
            <input type="text" class="form-control" id="modal_branch_name" required="true" >
          </div>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Project Type:</label>
            <select class="form-control select2" id="modal_pj_project_type" name="modal_pj_project_type" data-placeholder="Select a Project Type" style="width: 100%;">
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary create">Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

 <script type="text/javascript" src="<?php echo site_assets_url('DataTables/js/jquery.dataTables.js');?>"></script>
 <script type="text/javascript" src="<?php echo site_assets_url('DataTables/js/dataTables.bootstrap.js');?>"></script>
 <link rel="stylesheet" type="text/css" href="<?php echo site_assets_url('DataTables/css/dataTables.bootstrap.css'); ?>">
 <link rel="stylesheet" type="text/css" href="<?php echo site_assets_url('DataTables/css/dataTables.bootstrap4.min.css '); ?>">
 
<script type="text/javascript">

$(document).ready(function() {
  get_projecttype();
  view_datatable();
});

function get_projecttype(){
   var url = "<?php echo site_url('branch/get_projecttype'); ?>";
    $.ajax(url, {
       type: 'POST',
       data: '',
       success: function (data){
        var result = jQuery.parseJSON(data);
          var select = '';
          //console.log(result);
          $.each(result['data'][0]['value_default'],function(index,json){
            $('#modal_pj_project_type').append('<option value="'+json+'" >'+json+'</option>');
          });
          $("#modal_pj_project_type").select2({
            dropdownParent: $("#createModal")
          });
       },
       error: function (data){
        $('#loader').fadeOut();
        swal("",data.Message,"error");
       }
    });
}

$('#createModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
  var crmid = button.data('crmid');
  var action = button.data('action');
  var name = button.data('name');
  var project_type = button.data('project_type');
  var modal = $(this)
  modal.find('.modal-title').text(recipient);
  modal.find('#crmid').val(crmid);
  modal.find('#action').val(action);
  modal.find('#modal_branch_name').val(name);
  //modal.find('#modal_pj_project_type').val(project_type);
  if(project_type != ''){
    //$("#modal_pj_project_type").select2();
    $("#modal_pj_project_type").val(project_type).trigger("change");
  }else{
    //$("#modal_pj_project_type").select2();
    $("#modal_pj_project_type").val('แนวราบ').trigger("change");
  }

  $("#modal_branch_name").removeAttr("style");
})


function view_datatable(){
      $('#loader').fadeIn();
      var url ='<?php echo site_url('branch/getbranch');?>';
      $.ajax({
        type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
        url         :  url , // the url where we want to POST
        success: function (data) {
          var result = jQuery.parseJSON(data);
          if(result['Type'] == 'S'){
            reload_datatable(result['data']);
          }else{
            reload_datatable(result['data']);
            //$('#loader').fadeOut();
          }
        },error: function (msg) {
            console.log(msg);
          }

       })
      
}

function reload_datatable(data){
      console.log(data);
      var data_table = $('#datatable').DataTable({
        "data" : data,
        "processing": true,
        //"retrieve": true,
        "destroy" :true,
        "columns": [
            { "data": "branchid" },
            { "data": "branch_name" },
            { "data": "pj_project_type" },
            { "data": "pj_status" },
            { "data": "createdtime" ,
            "render": function(data){
                  return moment(data).format("DD/MM/YYYY HH:mm");
              }
            },
            { "data": null ,
              render: function(data) {
                var id = data.branchid;
                var name = data.branch_name;
                var project_type = data.pj_project_type;
                //return "<button type='button' id='"+id+"' title='Edit' class='btn btn-warning edit btn-sm'><i class='glyphicon glyphicon-edit'></i></button>";
                return "<button type='button' data-toggle='modal' data-target='#createModal' data-whatever='Edit Project' data-action='edit' data-name='"+name+"' data-project_type='"+project_type+"' data-crmid='"+id+"'  title='Edit' class='btn btn-warning edit btn-sm'><i class='glyphicon glyphicon-edit'></i></button>";
              }//
            },           
           /* {
              "targets": -1,
              "data": null,
              "defaultContent": "<button type='button' title='Edit' class='btn btn-warning edit btn-sm'><i class='glyphicon glyphicon-edit'></i></button>"
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
  $(document).on( 'click', '.edit', function () {
    var data = data_table.row( $(this).parents('tr') ).data();
    console.log(data);
  });

  $('#loader').fadeOut();
} 



$(document).on('click','.fillter',function(){
  
   var branch_name = $('#branch_name').val(); 
   var pj_status = $('#pj_status').val();//$('#daterange-btn').val(); 

  $('#loader').fadeIn();
    var data = {
      branch_name : branch_name,
      pj_status : pj_status,
  }

   var url = "<?php echo site_url('branch/getbranch'); ?>";  
      $.ajax(url, {
         type: 'POST',
         data: data,
         success: function (data){
          var result = jQuery.parseJSON(data);
          console.log(result);

          if(result['Type'] =='E'){
            $('#loader').fadeOut();
            swal("",result['message'],"error");
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


$(document).on('click','.create',function(){
  
  var branch_name = $('#modal_branch_name').val(); 
  var branch_type = $('#modal_pj_project_type').val(); 
  var crmid = $('#crmid').val(); 
  var action = $('#action').val(); 
  
  if(branch_name == ''  ){
    $('#modal_branch_name').focus();
    $("#modal_branch_name").css("border-color","red");
    return false;
  }
  if(branch_type == null){
    $('#modal_pj_project_type').focus();
    $("#modal_pj_project_type").select2({ containerCssClass : "select_border_red" });
    return false;
  }
  
  $('#loader').fadeIn();
    
  var data = {
    crmid : crmid,
    action : action,
    branch_name : branch_name,
    branch_type : branch_type,
  }
   var url = "<?php echo site_url('branch/create_branch'); ?>";  
      $.ajax(url, {
         type: 'POST',
         data: data,
         success: function (data){
          var result = jQuery.parseJSON(data);
          console.log(result);

            if(result['Type'] =='S'){
              $('#loader').fadeOut();
              $('#createModal').modal('toggle');

               swal({
                  position: 'center',//'top-end',
                  type: 'success',
                  title: result['Message'],
                  showConfirmButton: false,
                  timer: 2000
                });
                
              setTimeout(function () { 
                view_datatable();
              }, 2500);

            }else{
              $('#loader').fadeOut();
              swal("",result['Message'],"error");   
            }
          
         },
         error: function (data){
          $('#loader').fadeOut();
          swal("",data.Message,"error");
         }
      });

});

</script>

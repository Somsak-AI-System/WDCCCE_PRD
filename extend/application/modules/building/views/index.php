 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Building
        <small>View</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-building-o"></i>Building</a></li>
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
                <select class="form-control select2" id="branchid" name="branchid" multiple="multiple" data-placeholder="Select a Project" style="width: 100%;">
                  <option value=''>None</option>
                </select>
              </div>
              <!-- /.form-group -->
              <div class="form-group">
               
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-6">
              <div class="form-group">
                <label>Building name</label>
                <input type="text" class="form-control" id="building_name" name="building_name" placeholder="Building Name....">
              </div>
              <!-- /.form-group -->
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
          <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createModal" data-branchid="" data-building_name="" data-address1="" data-address2=""data-building_subdistrict=""data-building_district="" data-building_province="" data-zipcode="" data-bd_status="Active" data-whatever="Create Building" data-action="add" data-crmid="" ><i class="glyphicon glyphicon-plus"></i> New</button>
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
                    <th>Project Name</th>
                    <th>Building Name</th>
                    <th>Status</th>
                    <th>Create Date</th>
                    <th></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>No.</th>
                    <th>Project Name</th>
                    <th>Building Name</th>
                    <th>Status</th>
                    <th>Create Date</th>
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
            <label for="recipient-name" class="control-label">Project Name :</label>
            <select class="form-control select2" id="modal_branchid" name="modal_branchid" data-placeholder="Select a Project" style="width: 100%;">
              <option value=''>None</option>
            </select>
          </div>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Building Name :</label>
            <input type="text" class="form-control" id="modal_building_name" required="true" >
          </div>
          <div class="form-group">
          <label for="recipient-name" class="control-label">Status :</label>
          <select class="form-control select2" id="modal_bd_status" name="modal_bd_status" style="width: 100%;">
              <option value='Active'>Active</option>
              <option value='Inactive'>Inactive</option>
            </select>
          </div>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Address 1 :</label>
            <input type="text" class="form-control" id="modal_address1" >
          </div>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Address 2 :</label>
            <input type="text" class="form-control" id="modal_address2" >
          </div>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Sub District :</label>
            <input type="text" class="form-control" id="modal_building_subdistrict">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="control-label">District :</label>
            <input type="text" class="form-control" id="modal_building_district">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Province :</label>
            <input type="text" class="form-control" id="modal_building_province">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Zip Code :</label>
            <input type="text" class="form-control" id="modal_zipcode" >
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
 


<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
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
            <label for="recipient-name" class="control-label">Project Name :</label>
           <span id="modal_branch_name"></span>
          </div><hr>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Building Name : </label>
            <span id="modal_building_name"></span>
          </div><hr>
          <div class="form-group">
          <label for="recipient-name" class="control-label">Status :</label>
          <span id="modal_bd_status"></span>
          </div><hr>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Address 1 :</label>
            <span id="modal_address1"></span>
          </div><hr>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Address 2 :</label>
            <span id="modal_address2"></span>
          </div><hr>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Sub District :</label>
            <span id="modal_building_subdistrict"></span>
          </div><hr>
          <div class="form-group">
            <label for="recipient-name" class="control-label">District :</label>
            <span id="modal_building_district"></span>
          </div><hr>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Province :</label>
            <span id="modal_building_province"></span>
          </div><hr>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Zip Code :</label>
            <span id="modal_zipcode"></span>
          </div>

        </form>
      </div>
      <div class="modal-footer">
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
  get_branch();
  view_datatable();

  

});

$('#detailModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
  var crmid = button.data('crmid');
  var action = button.data('action');
  var branchid = button.data('branchid');
  var building_name = button.data('building_name');
  var address1 = button.data('address1');
  var address2 = button.data('address2');
  var building_subdistrict = button.data('building_subdistrict');
  var building_district = button.data('building_district');
  var building_province = button.data('building_province');
  var bd_status = button.data('bd_status');
  var zipcode = button.data('zipcode');
  var branch_name = button.data('branch_name');
  var modal = $(this)

  modal.find('#modal_branchid').val();
  modal.find('.modal-title').text(recipient);
  modal.find('#crmid').val(crmid)
  modal.find('#action').val(action)

  modal.find('#modal_branch_name').text(branch_name);
  modal.find('#modal_building_name').text(building_name);
  modal.find('#modal_address1').text(address1)
  modal.find('#modal_address2').text(address2)
  modal.find('#modal_building_subdistrict').text(building_subdistrict)
  modal.find('#modal_building_district').text(building_district)
  modal.find('#modal_building_province').text(building_province)
  modal.find('#modal_zipcode').text(zipcode)
  modal.find('#modal_bd_status').text(bd_status)

});

$('#createModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
  var crmid = button.data('crmid');
  var action = button.data('action');
  var branchid = button.data('branchid');
  var building_name = button.data('building_name');
  var address1 = button.data('address1');
  var address2 = button.data('address2');
  var building_subdistrict = button.data('building_subdistrict');
  var building_district = button.data('building_district');
  var building_province = button.data('building_province');
  var bd_status = button.data('bd_status');
  var zipcode = button.data('zipcode');
  var modal = $(this)

  modal.find('#modal_branchid').val();
  modal.find('.modal-title').text(recipient);
  modal.find('#crmid').val(crmid)
  modal.find('#action').val(action)

  if(branchid != ''){
    //$("#modal_branchid").select2();
    $("#modal_branchid").val(branchid).trigger("change");
  }else{
    //$("#modal_branchid").select2();
    $("#modal_branchid").val('').trigger("change");
  }

  if(bd_status != ''){
    //$("#modal_bd_status").select2();
    $("#modal_bd_status").val(bd_status).trigger("change");
  }else{
    //$("#modal_bd_status").select2();
    $("#modal_bd_status").val('').trigger("change");
  }

  $("#modal_bd_status").select2({
    dropdownParent: $("#createModal")
  });
  
  modal.find('#modal_building_name').val(building_name);
  modal.find('#modal_address1').val(address1)
  modal.find('#modal_address2').val(address2)
  modal.find('#modal_building_subdistrict').val(building_subdistrict)
  modal.find('#modal_building_district').val(building_district)
  modal.find('#modal_building_province').val(building_province)
  modal.find('#modal_zipcode').val(zipcode)
  modal.find('#modal_bd_status').val(bd_status)
  //$("#modal_branchid").removeAttr("style");
  $("#modal_building_name").removeAttr("style");
});


$(document).on('click','.create',function(){
  var building_name = $('#modal_building_name').val(); 
  var branchid = $('#modal_branchid').val();

  var crmid = $('#crmid').val(); 
  var action = $('#action').val();

  if(branchid == ''){
    /*select_border_red*/
    $('#modal_branchid').focus();
    $("#modal_branchid").select2({ containerCssClass : "select_border_red" });
    return false;
  }else if(building_name == '' ){
    $('#modal_building_name').focus();
    $("#modal_building_name").css("border-color", "red");
    return false;
  } 

  $('#loader').fadeIn();
    
  var data = {
    crmid : crmid,
    action : action,
    branchid : branchid,
    building_name : building_name,
    address1 : $('#modal_address1').val(),
    address2 : $('#modal_address2').val(),
    building_subdistrict : $('#modal_building_subdistrict').val(),
    building_district : $('#modal_building_district').val(),
    building_province : $('#modal_building_province').val(),
    zipcode : $('#modal_zipcode').val(),
    bd_status : $('#modal_bd_status').val(),
  }
   var url = "<?php echo site_url('building/create_building'); ?>";  
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


function get_branch(){
   var url = "<?php echo site_url('branch/getbranch'); ?>";  
    $.ajax(url, {
       type: 'POST',
       data: '',
       success: function (data){
        var result = jQuery.parseJSON(data);
          var select = '';
          $.each(result['data'],function(index,json){
            $('#branchid').append('<option value="'+json.branchid+'" >'+json.branch_name+'</option>');
            $('#modal_branchid').append('<option value="'+json.branchid+'" >'+json.branch_name+'</option>'); 
          });
          $("#modal_branchid").select2({
            dropdownParent: $("#createModal")
          });     
       },
       error: function (data){
        $('#loader').fadeOut();
        swal("",data.Message,"error");
       }
    });

}


function view_datatable(){
      
      $('#loader').fadeIn();
      var url ='<?php echo site_url('building/getbuilding');?>';
      $.ajax({
        type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
        url         :  url , // the url where we want to POST
        //data        : formData, // our data object
        
        success: function (data) {
          var result = jQuery.parseJSON(data);
          if(result['Type'] == 'S'){
            reload_datatable(result['data']);
           }else{
            reload_datatable(result['data']);
          }
        },error: function (msg) {
            console.log(msg);
          }

       })

}
function reload_datatable(data){
      
      var data_table = $('#datatable').DataTable({
      "data" : data,
      "processing": true,
      "destroy" :true,
      "columns": [
          { "data": "buildingid" },
          { "data": "branch_name" },
          { "data": "building_name"},
          { "data": "bd_status"},
          { "data": "createdtime" ,
            "render": function(data){
                  return moment(data).format("DD/MM/YYYY HH:mm");
              }
            },
          {"data": null ,
              render: function(data) {
                var id = data.buildingid;
                var name = data.building_name;
                var branchid = data.branchid;
                var address1 = data.address1;
                var address2 = data.address2;
                var building_subdistrict = data.building_subdistrict;
                var building_district = data.building_district;
                var building_province = data.building_province;
                var zipcode = data.zipcode;
                var status = data.bd_status;
                var branch_name = data.branch_name;
                return "<button type='button' data-toggle='modal' data-target='#detailModal' data-whatever='Detail Building' data-action='view' data-branchid='"+branchid+"'' data-branch_name='"+branch_name+"'' data-building_name='"+name+"' data-address1='"+address1+"' data-address2='"+address2+"' data-building_subdistrict='"+building_subdistrict+"' data-building_district='"+building_district+"' data-building_province='"+building_province+"' data-zipcode='"+zipcode+"' data-bd_status='"+status+"' data-crmid='"+id+"'   title='View' class='btn btn-success view btn-sm'><i class='glyphicon glyphicon-search'></i></button>&nbsp<button type='button' data-toggle='modal' data-target='#createModal' data-whatever='Edit Building' data-action='edit' data-branchid='"+branchid+"'' data-building_name='"+name+"' data-address1='"+address1+"' data-address2='"+address2+"' data-building_subdistrict='"+building_subdistrict+"' data-building_district='"+building_district+"' data-building_province='"+building_province+"' data-zipcode='"+zipcode+"' data-bd_status='"+status+"' data-crmid='"+id+"'  title='Edit' class='btn btn-warning edit btn-sm'><i class='glyphicon glyphicon-edit'></i></button>&nbsp<button type='button' id='"+id+"' title='Delete' class='btn btn-danger deleted btn-sm'><i class='glyphicon glyphicon-trash'></i></button>";
              },
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
  
  //Event click button edit
  /*$(document).on( 'click', '.edit', function () {
    var data = data_table.row( $(this).parents('tr') ).data();
    console.log(data);
  });*/

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
        var url = "<?php echo site_url('product/deleted'); ?>";  
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

  $('#loader').fadeOut();

} 

$(document).on('click','.fillter',function(){
  
   var branchid = $('#branchid').val(); 
   var building_name = $('#building_name').val();//$('#daterange-btn').val(); 

  $('#loader').fadeIn();
    var data = {
      branchid : branchid,
      building_name : building_name,
  }

   var url = "<?php echo site_url('building/getbuilding'); ?>";  
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

</script>

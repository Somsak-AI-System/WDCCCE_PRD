 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Unit
        <small>View</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-th"></i>Unit</a></li>
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
            <div class="col-md-4">
              <div class="form-group">
                <label>Project Name</label>
                <select class="form-control select2" id="branchid" name="branchid" data-placeholder="Select a Project" style="width: 100%;">
                <option value=''>None</option>
                </select>
              </div>
              <!-- /.form-group -->
              <div class="form-group">

              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-4">
              <div class="form-group">
                <label>Building Name</label>
                 <select class="form-control select2" id="buildingid" name="buildingid" data-placeholder="Select a Building" style="width: 100%;">
                  <option value=''>None</option>
                </select>
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-4">
              <div class="form-group">
                <label>Unit No</label>
                <input type="text" class="form-control" id="productname" name="productname" placeholder="Unit No....">
              </div>
              <!-- /.form-group -->
              <div class="form-group">

              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->

          </div>
          <!-- /.row -->
          <div class="form-actions" style="text-align: center;">
            <button type="button" class="btn btn-info btn-sm search fillter"><i class="fa fa-search"></i> Search</button>
          </div>
        </div>
        <!-- /.box-body -->

      </div>
      <!-- Fillter/.box -->

      <!-- Data Table -->
      <div class="box box-default">
        <div class="box-header with-border">
          <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createModal" data-whatever="Create Unit" data-action="add" data-crmid="" data-branchid= "" data-buildingid="" data-productname="" data-floor_no="" data-house_no="" data-unit_size="" data-customer_name="" data-phone="" data-phone_other="" data-email="" ><i class="glyphicon glyphicon-plus"></i> New</button>

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
                    <th>Building Name</th>
                    <th>Floor</th>
                    <th>Unit No</th>
                    <th>House No</th>
                    <th>Room Size</th>
                    <th></th>

                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th>No.</th>
                    <th>Project Name</th>
                    <th>Project Type</th>
                    <th>Building Name</th>
                    <th>Floor</th>
                    <th>Unit No</th>
                    <th>House No</th>
                    <th>Room Size</th>
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

  <!-- Modal Create Unit -->
  <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">New message</h4>
      </div>
      <div class="modal-body">
        <form>
          <input type="hidden" class="form-control" id="crmid" >
          <input type="hidden" class="form-control" id="action" >

          <div class="box-body">
          <div class="row">

            <div class="col-md-6">
              <div class="form-group">
                <label>Project Name :</label>
                <select class="form-control select2" id="modal_branchid" name="modal_branchid" data-placeholder="Select a Project" style="width: 100%;">
                  <option value=''>None</option>
                </select>
              </div>

              <div class="form-group">
               <label>Unit No :</label>
               <input type="text" class="form-control" id="modal_productname" name="modal_productname">
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Building Name :</label>
                <select class="form-control select2" id="modal_buildingid" name="modal_buildingid" data-placeholder="Select a Building" style="width: 100%;">
                  <option value=''>None</option>
                </select>
              </div>

              <div class="form-group">
               <label>Floor :</label>
               <input type="text" class="form-control" id="modal_floor_no" name="modal_floor_no">
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>House No :</label>
                <input type="text" class="form-control" id="modal_house_no" name="modal_house_no">
              </div>

              <div class="form-group">
               <label>Customer Name :</label>
               <input type="text" class="form-control" id="modal_customer_name" name="modal_customer_name">
              </div>

            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Room Size :</label>
                <input type="text" class="form-control" id="modal_unit_size" name="modal_unit_size">
              </div>


              <div class="form-group">
                <label>Customer Email :</label>
                <input type="text" class="form-control" id="modal_email" name="modal_email">
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Customer Mobile :</label>
                <input type="text" class="form-control" id="modal_phone" name="modal_phone">
              </div>

              <div class="form-group">
                <label>Plan :</label>
                <select class="form-control select2" id="modal_roomplanid" multiple="multiple" data-placeholder="Select Plan" style="width: 100%;"></select>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Customer Mobile Others :</label>
                <input type="text" class="form-control" id="modal_phone_other" name="modal_phone_other">
              </div>
            </div>

          </div>
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
<!-- Modal Create Unit -->

<!-- Modal Detail Unit -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">New message</h4>
      </div>
      <div class="modal-body">
        <form>
          <input type="hidden" class="form-control" id="crmid">
          <input type="hidden" class="form-control" id="action">

          <div class="box-body">
          <div class="row">

            <div class="col-md-6">
              <div class="form-group">
                <label for="recipient-name" class="control-label">Project Name :</label>
                <span id="modal_branch_name"></span>
              </div>
              <hr>
              <div class="form-group">
               <label for="recipient-name" class="control-label">Unit No :</label>
               <span id="modal_productname"></span>
              </div>
              <hr>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="recipient-name" class="control-label">Building Name :</label>
                <span id="modal_building_name"></span>
              </div>
              <hr>
              <div class="form-group">
               <label for="recipient-name" class="control-label">Floor :</label>
               <span id="modal_floor_no"></span>
              </div>
              <hr>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="recipient-name" class="control-label">House No :</label>
                <span id="modal_house_no"></span>
              </div>
              <hr>
              <div class="form-group">
                <label for="recipient-name" class="control-label">Plan :</label>
                <span id="modal_room_plan"></span>
              </div>
              <hr>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="recipient-name" class="control-label">Room Size :</label>
                <span id="modal_unit_size"></span>
              </div>
              <hr>
              <div class="form-group">
                <label for="recipient-name" class="control-label">Customer Name :</label>
                <span id="modal_customer_name"></span>
              </div>
              <hr>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="recipient-name" class="control-label">Customer Mobile :</label>
                <span id="modal_phone"></span>
              </div>
              <hr>
              <div class="form-group">
                <label for="recipient-name" class="control-label">Customer Email :</label>
                <span id="modal_email"></span>
              </div>
            </div>

            <div class="col-md-6">
              <label for="recipient-name" class="control-label">Customer Mobile Others :</label>
              <span id="modal_phone_other"></span>
                <hr>
            </div>
            <hr>

          </div>
          </div>


        </form>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<!-- Modal Detail Unit -->

 <script type="text/javascript" src="<?php echo site_assets_url('DataTables/js/jquery.dataTables.js');?>"></script>
 <script type="text/javascript" src="<?php echo site_assets_url('DataTables/js/dataTables.bootstrap.js');?>"></script>
 <link rel="stylesheet" type="text/css" href="<?php echo site_assets_url('DataTables/css/dataTables.bootstrap.css'); ?>">
 <link rel="stylesheet" type="text/css" href="<?php echo site_assets_url('DataTables/css/dataTables.bootstrap4.min.css '); ?>">

<script type="text/javascript">

$(document).ready(function() {
  //$('#loader').fadeOut();
  get_branch();
  get_building();
  view_datatable();
  get_building_modal();
  get_roomplan();

  $("#modal_branchid").select2({
    dropdownParent: $("#createModal")
  });

  $("#modal_buildingid").select2({
    dropdownParent: $("#createModal")
  });

  $("#modal_roomplanid").select2({
    dropdownParent: $("#createModal")
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

function get_building(){
   var url = "<?php echo site_url('building/getbuilding'); ?>";

  /* var data = {
    'branchid' : $('#modal_branchid').val(),
    'building_name' : '',
    'bd_status' : '',
   }*/
    $.ajax(url, {
       type: 'POST',
       data: '',
       success: function (data){
        var result = jQuery.parseJSON(data);
          var select = data;
          $.each(result['data'],function(index,json){
            $('#buildingid').append('<option value="'+json.buildingid+'" >'+json.building_name+'</option>');
            //$('#modal_buildingid').append('<option value="'+json.buildingid+'" >'+json.building_name+'</option>');
          });
          $("#modal_buildingid").select2({
            dropdownParent: $("#createModal")
          });
       },
       error: function (data){
        $('#loader').fadeOut();
        swal("",data.Message,"error");
       }
    });
}

function get_building_modal(branchid){
   var url = "<?php echo site_url('building/getbuilding'); ?>";

   var data = {
    'branchid' : $('#modal_branchid').val(),
    'building_name' : '',
    'bd_status' : '',
   }
    $.ajax(url, {
       type: 'POST',
       data: data,
       success: function (data){
        var result = jQuery.parseJSON(data);
          var select = data;
          $('#modal_buildingid').html('');
          //$('#modal_buildingid').append('<option value="">None</option>');
          $.each(result['data'],function(index,json){
            //$('#buildingid').append('<option value="'+json.buildingid+'" >'+json.building_name+'</option>');
            $('#modal_buildingid').append('<option value="'+json.buildingid+'" >'+json.building_name+'</option>');
          });

          $("#modal_buildingid").select2({
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
  var branchid = button.data('branchid');
  var buildingid = button.data('buildingid');
  var productname = button.data('productname');
  var floor_no = button.data('floor_no');
  var house_no = button.data('house_no');
  var unit_size = button.data('unit_size');
  var roomplanid = button.data('roomplanid');
  var roomplan_name = button.data('roomplan_name');
  var customer_name = button.data('customer_name');
  var phone = button.data('phone');
  var phone_other = button.data('phone_other');
  var email = button.data('email');
  var modal = $(this)

  //console.log(roomplanid);
  modal.find('#modal_branchid').val();
  modal.find('#modal_buildingid').val();
  //modal.find('#modal_roomplanid').val();

  modal.find('.modal-title').text(recipient);
  modal.find('#crmid').val(crmid)
  modal.find('#action').val(action)

  if(branchid != ''){
    $("#modal_branchid").val(branchid).trigger("change");
  }else{
    $("#modal_branchid").val('').trigger("change");
  }
  if(buildingid != ''){
    $("#modal_buildingid").val(buildingid).trigger("change");
  }else{
    $("#modal_buildingid").val('').trigger("change");
  }

  if(roomplanid != '' && roomplanid != undefined){
    const string = String(roomplanid);
    var str = string.includes(',');
    //console.log(str);
    if(str === true){
      var selectedValues = new Array();
      selectedValues = roomplanid.split(", ");
      $("#modal_roomplanid").val(selectedValues).trigger("change");
    }else{
      $("#modal_roomplanid").val(string).trigger("change");
    }
    

  }else{
    //$("#modal_roomplanid").select2();
    $("#modal_roomplanid").val('').trigger("change");
  }
  
  modal.find('#modal_productname').val(productname)
  modal.find('#modal_floor_no').val(floor_no)
  modal.find('#modal_house_no').val(house_no)
  modal.find('#modal_unit_size').val(unit_size)
  //modal.find('#modal_room_type').val(room_type)
  modal.find('#modal_customer_name').val(customer_name)
  modal.find('#modal_phone').val(phone)
  modal.find('#modal_phone_other').val(phone_other)
  modal.find('#modal_email').val(email)
  //$("#modal_branchid").removeAttr("style");
  $("#modal_productname").removeAttr("style");
});

$('#detailModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
  var crmid = button.data('crmid');
  var action = button.data('action');
  var branchid = button.data('branchid');
  var buildingid = button.data('buildingid');
  var branch_name = button.data('branch_name');
  var building_name = button.data('building_name');
  var productname = button.data('productname');
  var floor_no = button.data('floor_no');
  var house_no = button.data('house_no');
  var unit_size = button.data('unit_size');
  var roomplan_name = button.data('roomplan_name');
  var customer_name = button.data('customer_name');
  var phone = button.data('phone');
  var phone_other = button.data('phone_other');
  var email = button.data('email');
  var modal = $(this)

  modal.find('.modal-title').text(recipient);
  modal.find('#crmid').val(crmid)
  modal.find('#action').val(action)
  modal.find('#modal_branch_name').text(branch_name)
  modal.find('#modal_building_name').text(building_name)
  modal.find('#modal_productname').text(productname)
  modal.find('#modal_floor_no').text(floor_no)
  modal.find('#modal_house_no').text(house_no)
  modal.find('#modal_unit_size').text(unit_size)
  modal.find('#modal_room_plan').text(roomplan_name)
  modal.find('#modal_customer_name').text(customer_name)
  modal.find('#modal_phone').text(phone)
  modal.find('#modal_phone_other').text(phone_other)
  modal.find('#modal_email').text(email)
});

$(document).on('click','.create',function(){
  var buildingid = $('#modal_buildingid').val();
  var branchid = $('#modal_branchid').val();
  var productname = $('#modal_productname').val();

  var crmid = $('#crmid').val();
  var action = $('#action').val();

  if(branchid == ''){
    $('#modal_branchid').focus();
    $("#modal_branchid").select2({ containerCssClass : "select_border_red" });
    return false;
  }else if(buildingid == '' ){
    $('#modal_buildingid').focus();
    $("#modal_buildingid").select2({ containerCssClass : "select_border_red" });
    return false;
  }else if(productname == ''){
    $('#modal_productname').focus();
    $("#modal_productname").css("border-color", "red");
    return false;
  }

  $('#loader').fadeIn();

  var data = {
    crmid : crmid,
    action : action,
    branchid : branchid,
    buildingid : buildingid,
    productname : productname,
    floor_no : $('#modal_floor_no').val(),
    house_no : $('#modal_house_no').val(),
    unit_size : $('#modal_unit_size').val(),
    roomplanid : $('#modal_roomplanid').val(),
    customer_name : $('#modal_customer_name').val(),
    phone : $('#modal_phone').val(),
    phone_other : $('#modal_phone_other').val(),
    email : $('#modal_email').val(),
  }

  //console.log(data);

   var url = "<?php echo site_url('product/create'); ?>";
      $.ajax(url, {
         type: 'POST',
         data: data,
         success: function (data){
          var result = jQuery.parseJSON(data);

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


function get_roomplan(roomplanid){
  var url = "<?php echo site_url('defect/getroomplan'); ?>";
    $.ajax(url, {
       type: 'POST',
       data: '',
       success: function (data){
        var result = jQuery.parseJSON(data);
        if(result['Type'] == 'S'){
          $('#modal_roomplanid').html('');
          //$('#modal_roomplanid').append(new Option('', ''));  
          $.each(result['data'], function( index, value ) {
            $('#modal_roomplanid').append(new Option(value['roomplan_name'], value['roomplanid']));  
          })
        }else{
          $('#modal_roomplanid').html('');
          $('#modal_roomplanid').append(new Option('', ''));
        } 
       },
       error: function (data){
        swal("",data.Message,"error");
       }
    });

}

function view_datatable(){

      $('#loader').fadeIn();
      var url ='<?php echo site_url('product/getproduct');?>';
      $.ajax({
        type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
        url         :  url , // the url where we want to POST

        success: function (data) {
          var result = jQuery.parseJSON(data);
          if(result['Type'] == 'S'){
            //console.log(result['data']);
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
      //console.log(data);
      var data_table = $('#datatable').DataTable({
      "data" : data,
      "processing": true,
      //"retrieve": true,
      "destroy" :true,
      "columns": [
          { "data": "productid" },
          { "data": "branch_name" },
          { "data": "pj_project_type" },
          { "data": "building_name" },
          { "data": "floor_no" },
          { "data": "productname" },
          { "data": "house_no" },
          { "data": "unit_size" },
          { "data": null ,
              render: function(data) {
                var id = data.productid;
                var name = data.productname;
                var branchid = data.branchid;
                var buildingid = data.buildingid;
                var floor_no = data.floor_no;
                var house_no = data.house_no;
                var unit_size = data.unit_size;
                var customer_name = data.customer_name;
                var phone = data.phone;
                var phone_other = data.phone_other;
                var email = data.email;
                var building_name = data.building_name;
                var branch_name = data.branch_name;
                var roomplanid = data.roomplanid;
                var roomplan_name = data.roomplan_name;

                return "<button type='button' data-toggle='modal' data-target='#detailModal' data-whatever='Detail Unit' data-action='view' data-branchid='"+branchid+"' data-branch_name='"+branch_name+"' data-building_name='"+building_name+"'' data-buildingid='"+buildingid+"' data-productname='"+name+"' data-floor_no='"+floor_no+"' data-house_no='"+house_no+"' data-unit_size='"+unit_size+"'  data-roomplan_name='"+roomplan_name+"' data-roomplanid='"+roomplanid+"' data-customer_name='"+customer_name+"' data-phone='"+phone+"' data-phone_other='"+phone_other+"' data-email='"+email+"' data-crmid='"+id+"' title='View' class='btn btn-success view btn-sm'><i class='glyphicon glyphicon-search'></i></button>&nbsp<button type='button' data-toggle='modal' data-target='#createModal' data-whatever='Edit Unit' data-action='edit' data-branchid='"+branchid+"' data-buildingid='"+buildingid+"' data-productname='"+name+"' data-floor_no='"+floor_no+"' data-house_no='"+house_no+"' data-unit_size='"+unit_size+"'  data-roomplan_name='"+roomplan_name+"' data-roomplanid='"+roomplanid+"' data-customer_name='"+customer_name+"' data-phone='"+phone+"' data-phone_other='"+phone_other+"' data-email='"+email+"' data-crmid='"+id+"' title='Edit' class='btn btn-warning edit btn-sm'><i class='glyphicon glyphicon-edit'></i></button>&nbsp<button type='button' id='"+id+"' class='btn btn-danger deleted btn-sm' title='Delete'><i class='glyphicon glyphicon-trash'></i></button>";
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
   var buildingid = $('#buildingid').val();
   var productname = $('#productname').val();

  $('#loader').fadeIn();
    var data = {
      branchid : branchid,
      buildingid : buildingid,
      productname : productname,
    }

   var url = "<?php echo site_url('product/getproduct'); ?>";
      $.ajax(url, {
         type: 'POST',
         data: data,
         success: function (data){
          var result = jQuery.parseJSON(data);
          console.log(result);

          if(result['Type'] =='E'){
            //$('#loader').fadeOut();
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

/*$(document).on('change','#modal_branchid',function(){
   var branchid = $('#modal_branchid').val();
   get_building_modal(branchid);


   var url = "<?php echo site_url('product/getproduct'); ?>";
      $.ajax(url, {
         type: 'POST',
         data: data,
         success: function (data){
          var result = jQuery.parseJSON(data);
          console.log(result);

          if(result['Type'] =='E'){
            //$('#loader').fadeOut();
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

});*/

</script>

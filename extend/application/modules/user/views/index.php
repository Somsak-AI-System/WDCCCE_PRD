 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        test
        <small>View</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="glyphicon glyphicon-user"></i>User</a></li>
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
                <label>User Name</label>
                <input type="text" class="form-control" id="user_name" placeholder="User Name....">
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-3">
              <div class="form-group">
                <label>First Name</label>
                <input type="text" class="form-control" id="first_name" placeholder="First Name....">
              </div>
             <!-- /.form-group -->
            </div> 
            <!-- /.col -->
            <div class="col-md-3">
              <div class="form-group">
                <label>Last Name</label>
                <input type="text" class="form-control" id="last_name" placeholder="Last Name....">
              </div>
             <!-- /.form-group -->
            </div> 
            <!-- /.col -->
            <div class="col-md-3">
              <div class="form-group">
                <label>Status</label>
                <select class="form-control select2" id="user_status" name="user_status" style="width: 100%;">
                  <option value="">All</option>
                  <option value="Active">Active</option>
                  <option value="Inactive">Inactive</option>
                </select>
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
          <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createModal" data-user_name="" data-first_name="" data-last_name="" data-phone=""data-email1="" data-status="" data-id="" data-whatever="Create User" data-action="add" data-crmid="" ><i class="glyphicon glyphicon-plus"></i> New</button>
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
                    <th>User Name</th>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Create Task On Mobile</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th>No.</th>
                    <th>User Name</th>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Create Task On Mobile</th>
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


  <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">New message</h4>
      </div>
      <div class="modal-body">
        <form>
          <input type="hidden" class="form-control" id="id" >
          <input type="hidden" class="form-control" id="action" >
          <div class="form-group">
            <label for="recipient-name" class="control-label">User Name :</label>
             <input type="text" class="form-control" id="modal_user_name" required="true" >
          </div>
          <!-- Password -->
          <div class="form-group pass_show ptxt">
            <label for="recipient-name" class="control-label">Password :</label>
            <input type="password" class="form-control" id="modal_password" required="true" >
          </div>
          <div class="form-group pass_show ptxt">
            <label for="recipient-name" class="control-label">Confirm Password :</label>
            <input type="password" class="form-control" id="modal_cf_password" required="true" >
          </div>
          <!-- Password -->
          <div class="form-group">
            <label for="recipient-name" class="control-label">First Name :</label>
            <input type="text" class="form-control" id="modal_first_name" required="true" >
          </div>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Last Name :</label>
            <input type="text" class="form-control" id="modal_last_name" required="true" >
          </div>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Mobile :</label>
            <input type="text" class="form-control" id="modal_phone_mobile" required="true" >
          </div>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Email :</label>
            <input type="text" class="form-control" id="modal_email1" required="true" >
          </div>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Status :</label>
            <select class="form-control select2" id="modal_status" name="modal_status" style="width: 100%;">
              <option value='Active'>Active</option>
              <option value='Inactive'>Inactive</option>
            </select>
          </div>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Create Task On Mobile :</label>
            <select class="form-control select2" id="modal_create_task" name="modal_create_task" style="width: 100%;">
              <option value='0'>No</option>
              <option value='1'>Yes</option>
            </select>
          </div>
          <label class="control-label" id="texterror" style="color: red"></label>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary create">Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
 
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">New message</h4>
      </div>
      <div class="modal-body">
        <form>
          <input type="hidden" class="form-control" id="edit_id" >
          <input type="hidden" class="form-control" id="edit_action" >
          <div class="form-group">
            <label for="recipient-name" class="control-label">First Name :</label>
            <input type="text" class="form-control" id="edit_first_name" required="true" >
          </div>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Last Name :</label>
            <input type="text" class="form-control" id="edit_last_name" required="true" >
          </div>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Mobile :</label>
            <input type="text" class="form-control" id="edit_phone_mobile" required="true" >
          </div>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Email :</label>
            <input type="text" class="form-control" id="edit_email1" required="true" >
          </div>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Status :</label>
            <select class="form-control select2" id="edit_status" name="edit_status" style="width: 100%;">
              <option value='Active'>Active</option>
              <option value='Inactive'>Inactive</option>
            </select>
          </div>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Create Task On Mobile :</label>
            <select class="form-control select2" id="edit_create_task" name="edit_create_task" style="width: 100%;">
              <option value='0'>No</option>
              <option value='1'>Yes</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary edit_user">Save</button>
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
            <label for="recipient-name" class="control-label">User Name :</label>
           <span id="modal_user_name"></span>
          </div><hr>
          <div class="form-group">
            <label for="recipient-name" class="control-label">First Name : </label>
            <span id="modal_first_name"></span>
          </div><hr>
          <div class="form-group">
          <label for="recipient-name" class="control-label">Last Name :</label>
          <span id="modal_last_name"></span>
          </div><hr>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Mobile :</label>
            <span id="modal_phone_mobile"></span>
          </div><hr>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Email :</label>
            <span id="modal_email1"></span>
          </div><hr>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Status :</label>
            <span id="modal_status"></span>
          </div><hr>

          <div class="form-group">
            <label for="recipient-name" class="control-label">Create Task On Mobile :</label>
            <span id="modal_create_task"></span>
          </div>

        </form>
      </div>
      <div class="modal-footer">
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

$(document).ready(function(){
  view_datatable();
});


$('#detailModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
  var id = button.data('id');
  var user_name = button.data('user_name');
  var first_name = button.data('first_name');
  var last_name = button.data('last_name');
  var phone_mobile = button.data('phone_mobile');
  var email1 = button.data('email1');
  var status = button.data('status');
  var create_task = button.data('create_task');

  var modal = $(this)
  modal.find('.modal-title').text(recipient);
  modal.find('#id').val(id)
  modal.find('#action').val(action)
 
  modal.find('#modal_user_name').text(user_name);
  modal.find('#modal_first_name').text(first_name);
  modal.find('#modal_last_name').text(last_name)
  modal.find('#modal_phone_mobile').text(phone_mobile)
  modal.find('#modal_email1').text(email1)
  modal.find('#modal_status').text(status)

  modal.find('#modal_create_task').text(create_task);
  //$("#roomplanid").val(create_task).trigger("change");

});

$('#editModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
  var id = button.data('id');
  var action = button.data('action');
  /*var user_name = button.data('user_name');*/
  var first_name = button.data('first_name');
  var last_name = button.data('last_name');
  var phone_mobile = button.data('phone_mobile');
  var email1 = button.data('email1');
  var status = button.data('status');
  var create_task_num = button.data('create_task_num');
  var modal = $(this)

  modal.find('.modal-title').text(recipient);
  modal.find('#edit_id').val(id)
  modal.find('#edit_action').val(action)

  $("#edit_status").select2();
  $("#edit_status").val(status).trigger("change");
  
  /*modal.find('#modal_user_name').val(user_name);*/
  modal.find('#edit_first_name').val(first_name);
  modal.find('#edit_last_name').val(last_name)
  modal.find('#edit_phone_mobile').val(phone_mobile)
  modal.find('#edit_email1').val(email1)
  modal.find('#edit_status').val(status)

  /*$("#modal_user_name").removeAttr("style");*/
  $("#edit_first_name").removeAttr("style");
  $("#edit_last_name").removeAttr("style");
  
  $("#edit_create_task").val(create_task_num).trigger("change");

  $("#edit_create_task").select2({
    dropdownParent: $("#editModal")
  });
  $("#edit_status").select2({
    dropdownParent: $("#editModal")
  });

});

$('#createModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
  var id = button.data('id');
  var action = button.data('action');
  var user_name = button.data('user_name');
  var first_name = button.data('first_name');
  var last_name = button.data('last_name');
  var phone_mobile = button.data('phone_mobile');
  var email1 = button.data('email1');
  var status = button.data('status');
  var modal = $(this)

  $('label[id*=texterror').html('');

  modal.find('.modal-title').text(recipient);
  modal.find('#id').val(id)
  modal.find('#action').val(action)

  /*$("#modal_status").select2();
  $("#modal_status").val('Active').trigger("change");*/
  
  modal.find('#modal_user_name').val(user_name);
  modal.find('#modal_password').val('');
  modal.find('#modal_cf_password').val('');
  modal.find('#modal_first_name').val(first_name);
  modal.find('#modal_last_name').val(last_name)
  modal.find('#modal_phone_mobile').val(phone_mobile)
  modal.find('#modal_email1').val(email1)
  //modal.find('#modal_status').val(status)

  $("#modal_user_name").removeAttr("style");
  $("#modal_first_name").removeAttr("style");
  $("#modal_last_name").removeAttr("style");

  $("#modal_status").select2({
    dropdownParent: $("#createModal")
  });
  $("#modal_create_task").select2({
    dropdownParent: $("#createModal")
  });

});

function view_datatable(){
      $('#loader').fadeIn();
      var url ='<?php echo site_url('user/getusers');?>';
      $.ajax({
        type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
        url         :  url , // the url where we want to POST
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
        //"retrieve": true,
        "destroy" :true,
        "columns": [
            { "data": "id" },
            { "data": "user_name" },
            { "data": "first_name" },
            { "data": "last_name" },
            { "data": "phone_mobile" },
            { "data": "email1" },
            { "data": "create_task" },
            { "data": "status" },
            {"data": null ,
              render: function(data) {
                var id = data.id;
                var user_name = data.user_name;
                var first_name = data.first_name;
                var last_name = data.last_name;
                var email1 = data.email1;
                var phone_mobile = data.phone_mobile;
                var create_task = data.create_task;
                var status = data.status;
                if(data.create_task == 'Yes'){var create = 1;}else{var create = 0;};
                var create_task_num = create;
                
                return "<button type='button' data-toggle='modal' data-target='#detailModal' data-whatever='Detail User' data-action='view' data-id='"+id+"' data-user_name='"+user_name+"' data-first_name='"+first_name+"' data-last_name='"+last_name+"' data-email1='"+email1+"' data-phone_mobile='"+phone_mobile+"' data-status='"+status+"' data-create_task='"+create_task+"' title='View' class='btn btn-success view btn-sm'><i class='glyphicon glyphicon-search'></i></button>&nbsp<button type='button' data-toggle='modal' data-target='#editModal' data-whatever='Edit User' data-action='edit' data-id='"+id+"' data-user_name='"+user_name+"' data-first_name='"+first_name+"' data-last_name='"+last_name+"' data-email1='"+email1+"' data-phone_mobile='"+phone_mobile+"' data-status='"+status+"' data-create_task_num='"+create_task_num+"' title='Edit' class='btn btn-warning edit btn-sm'><i class='glyphicon glyphicon-edit'></i></button>&nbsp<button type='button' class='btn btn-info btn-sm' data-toggle='modal' data-target='#passwordModal' data-pass_id='"+id+"' data-pass_action='edit' title='Change Password' ><i class='fa fa-unlock'></i></button>&nbsp<button type='button' id='"+id+"' title='Deleted' class='btn btn-danger deleted btn-sm'><i class='glyphicon glyphicon-trash'></i></button>";
              },
          }, 
        ],"columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        },/*<a href="#" data-toggle="modal" data-target="#passwordModal" data-pass_id="<?php echo USERID;?>" data-pass_action="edit" ><label for="recipient-name" style="cursor: pointer" class="control-label">Change Password</label></a>*/

        ],
        "order": [[ 1, 'asc' ]]
      });

  //Running No. datatable
  data_table.on( 'order.dt search.dt', function () {
      data_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
      } );
  } ).draw();

  data_table.on( 'click', '.deleted', function () {
    user_id = this.id;
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
        var url = "<?php echo site_url('user/deleted'); ?>";  
        var data = {user_id:user_id,action:'deleted'};
        $.ajax(url, {
           type: 'POST',
           dataType : 'json',
           data: data,
           success: function (data){
            var result = jQuery.parseJSON(JSON.stringify(data));
            console.log(result);

            if(result['Type'] =='E'){
              $('#loader').fadeOut();
              swal("",result['message'],"error");
            }else{
              //$('#loader').fadeOut();
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
  
   var user_name = $('#user_name').val(); 
   var first_name = $('#first_name').val();
   var last_name = $('#last_name').val();
   var status = $('#user_status').val();


  $('#loader').fadeIn();
    var data = {
      user_name : user_name,
      first_name : first_name,
      last_name : last_name,
      status : status,
  }

   var url = "<?php echo site_url('user/getusers'); ?>";  
      $.ajax(url, {
         type: 'POST',
         data: data,
         success: function (data){
          var result = jQuery.parseJSON(data);
          console.log(result);

          if(result['Type'] =='E'){
            //$('#loader').fadeOut();
            //swal("",result['message'],"error");
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

$(document).on('click','.create',function(){
   
   var userid = $('#id').val(); 
   var action = $('#action').val(); 
   var modal_user_name = $('#modal_user_name').val(); 
   var modal_password = $('#modal_password').val();
   var modal_cf_password = $('#modal_cf_password').val();
   var modal_first_name = $('#modal_first_name').val();
   var modal_last_name = $('#modal_last_name').val();
   var modal_phone_mobile = $('#modal_phone_mobile').val();
   var modal_email1 = $('#modal_email1').val();
   var modal_status = $('#modal_status').val();
   var modal_create_task = $('#modal_create_task').val();
   
  $('label[id*=texterror').html(''); 
  var passw = /[!@#$%^&*(),.?":{}|<>]/g;

  $("#modal_user_name").removeAttr("style");
  $("#modal_first_name").removeAttr("style");
  $("#modal_last_name").removeAttr("style");
  $("#modal_password").removeAttr("style");
  $("#modal_cf_password").removeAttr("style");

  if(modal_user_name == ''){
    $('#modal_user_name').focus();
    $("#modal_user_name").css("border-color", "red");
    return false;
  }else if(modal_password == '' ){
    $('#modal_password').focus();
    $("#modal_password").css("border-color", "red");
    return false;
  }else if(modal_password.length < 8 ){
    $('#modal_password').focus();
    $('#texterror').append('* Password verification must be more than 8 characters.');
    $("#modal_password").css("border-color", "red");
    return false;
  }else if(modal_password.match(passw)){
    $('#texterror').append('* Please use only alphanumeric or alphabetic characters');
    $("#modal_password").css("border-color", "red");
  }else if(modal_cf_password == ''){
    $('#modal_cf_password').focus();
    $("#modal_cf_password").css("border-color", "red");
    return false;
  }else if(modal_password != modal_cf_password){
    $('#modal_cf_password').focus();
    $('#texterror').append('* Passwords not match');
    $("#modal_cf_password").css("border-color", "red");
    return false;
  }else if(modal_first_name == ''){
    $('#modal_first_name').focus();
    $("#modal_first_name").css("border-color", "red");
    return false;
  }else if(modal_last_name == ''){
    $('#modal_last_name').focus();
    $("#modal_last_name").css("border-color", "red");
    return false;
  }

  $('#loader').fadeIn();
  var data = {
    userid : userid,
    action : action,
    user_name : modal_user_name,
    password : modal_password,
    first_name : modal_first_name,
    last_name : modal_last_name,
    phone_mobile : modal_phone_mobile,
    email1 : modal_email1,
    status : modal_status,
    create_task : modal_create_task
  }

  var url = "<?php echo site_url('user/create'); ?>";  
  $.ajax(url, {
     type: 'POST',
     data: data,
     success: function (data){
      var result = jQuery.parseJSON(data)

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

        if(result['reload'] == true){
          setTimeout(function () { 
            location.reload();
          }, 2100);
        }

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

$(document).on('click','.edit_user',function(){
   
   var user_id = $('#edit_id').val(); 
   var action = $('#edit_action').val(); 
   var first_name = $('#edit_first_name').val(); 
   var last_name = $('#edit_last_name').val();
   var phone_mobile = $('#edit_phone_mobile').val();
   var email1 = $('#edit_email1').val();
   var status = $('#edit_status').val();
   var edit_create_task = $('#edit_create_task').val();

   $('#loader').fadeIn();
    var data = {
      userid : user_id,
      action : action,
      first_name : first_name,
      last_name : last_name,
      phone_mobile : phone_mobile,
      email1 : email1,
      status : status,
      create_task : edit_create_task
    }
    var url = "<?php echo site_url('user/edit_profile'); ?>";  
    $.ajax(url, {
       type: 'POST',
       data: data,
       success: function (data){
        var result = jQuery.parseJSON(data)

        if(result['Type'] =='S'){
          $('#loader').fadeOut();
          $('#editModal').modal('toggle');
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

          if(result['reload'] == true){
            setTimeout(function () { 
              location.reload();
            }, 2100);
          }

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

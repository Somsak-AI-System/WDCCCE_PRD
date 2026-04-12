 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Defect
        <small>View</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-file-text-o"></i>Defect</a></li>
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
                <label>Zone</label>
                <select class="form-control select2" id="zoneid" name="zoneid" multiple="multiple" data-placeholder="Select a Zone" style="width: 100%;">
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
                <label>Defect Name</label>
                  <input type="text" class="form-control" id="defect_name" placeholder="Defect Name....">
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
           <div class="form-actions" style="text-align: center;">
            <button type="button" class="btn btn-info btn-sm fillter"><i class="fa fa-search"></i> ค้นหา</button>
          </div>
        </div>
        <!-- /.box-body -->

      </div>
      <!-- Fillter/.box -->

      <!-- Data Table -->
      <div class="box box-default">
        <div class="box-header with-border">
          <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createModal" data-whatever="Create Defect" data-action="add" data-crmid="" data-zone_name= "" data-defect_status="Active" data-defect_name=""><i class="glyphicon glyphicon-plus"></i> New</button>
          <button type="button" class="btn btn_s btn-info btn-sm btn_import"><i class="glyphicon glyphicon-import"></i> Import</button>
          <button type="button" class="btn btn-default btn-sm" onclick="view_datatable();get_zone();"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">

            </table><table id="datatable" class="table table-striped table-bordered display" style="width:100%">
              <thead>
                <tr>
                    <th>No.</th>
                    <th>Zone Name</th>
                    <th>Defect Name</th>
                    <th>Plan</th>
                    <th>Status</th>
                    <th></th>

                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th>No.</th>
                    <th>Zone Name</th>
                    <th>Defect Name</th>
                    <th>Plan</th>
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
          <input type="hidden" class="form-control" id="crmid" >
          <input type="hidden" class="form-control" id="action" >
          <div class="form-group">
            <label for="recipient-name" class="control-label">Zone Name :</label>
            <input type="text" class="form-control" id="modal_zone_name" >
          </div>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Defect Name :</label>
            <input type="text" class="form-control" id="modal_defect_name" >
          </div>
          <div class="form-group">
          <label for="recipient-name" class="control-label">Room Plan :</label>
            <select class="form-control select2" id="roomplanid" data-placeholder="Select Plan" style="width: 100%;">
            </select>
          </div>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Status :</label>
            <select class="form-control select2" id="modal_defect_status" name="modal_defect_status" style="width: 100%;">
              <option value='Active'>Active</option>
              <option value='Inactive'>Inactive</option>
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
            <label for="recipient-name" class="control-label">Zone Name :</label>
           <span id="modal_zone_name"></span>
          </div><hr>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Defect Name :</label>
            <span id="modal_defect_name"></span>
          </div><hr>
          <div class="form-group">
          <label for="recipient-name" class="control-label">Plan :</label>
          <span id="modal_roomplan_name"></span>
          </div><hr>
          <div class="form-group">
          <label for="recipient-name" class="control-label">Status :</label>
          <span id="modal_defect_status"></span>
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
  //$('#loader').fadeOut();
  get_zone();
  view_datatable();
  get_roomplan();

  $("#roomplanid").select2({
    dropdownParent: $("#createModal")
  });

  $(document).on('click', '.btn_import', function () {
    var URL = '<?php echo site_url("import/import_defect")?>';
    window.open(URL, '_blank');
   });

});

function get_zone(){
   var url = "<?php echo site_url('defect/get_zone'); ?>";
    $.ajax(url, {
       type: 'POST',
       data: '',
       success: function (data){
        var result = jQuery.parseJSON(data);
          var select = '';
          $.each(result['data'],function(index,json){
            $('#zoneid').append('<option value="'+json.zoneid+'" >'+json.zone_name+'</option>');
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
  var zone_name = button.data('zone_name');
  var defect_status = button.data('defect_status');
  var defect_name = button.data('defect_name');
  var roomplan_name = button.data('roomplan_name');
  var roomplanid = button.data('roomplanid');
  var modal = $(this)
  
  if(roomplanid != ''){
    $("#roomplanid").val(roomplanid).trigger("change");
  }else{
    $("#roomplanid").val('').trigger("change");
  }
  
  modal.find('.modal-title').text(recipient);
  modal.find('#crmid').val(crmid)
  modal.find('#action').val(action)

  $("#modal_defect_status").select2();
  $("#modal_defect_status").val(defect_status).trigger("change");

  modal.find('#modal_zone_name').val(zone_name)
  modal.find('#modal_defect_name').val(defect_name)

  /*$("#roomplanid").select2();
  $("#roomplanid").val(roomplanid).trigger('change');*/

  $("#modal_zone_name").removeAttr("style");
  $("#modal_defect_name").removeAttr("style");
  
  $("#modal_defect_status").select2({
    dropdownParent: $("#createModal")
  });
  
});

$('#detailModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
  var crmid = button.data('crmid');
  var action = button.data('action');
  var zone_name = button.data('zone_name');
  var defect_status = button.data('defect_status');
  var defect_name = button.data('defect_name');
  var roomplan_name = button.data('roomplan_name');
  var modal = $(this)

  modal.find('.modal-title').text(recipient);
  modal.find('#crmid').val(crmid)
  modal.find('#action').val(action)

  modal.find('#modal_zone_name').text(zone_name)
  modal.find('#modal_defect_name').text(defect_name)
  modal.find('#modal_defect_status').text(defect_status)
  modal.find('#modal_roomplan_name').text(roomplan_name)

});

function get_roomplan(){
  var url = "<?php echo site_url('defect/getroomplan'); ?>";
    $.ajax(url, {
       type: 'POST',
       data: '',
       success: function (data){
        var result = jQuery.parseJSON(data);
        if(result['Type'] == 'S'){
          //$('#roomplanid').html('');
          //$('#roomplanid').append(new Option('', ''));  
          $.each(result['data'], function( index, value ) {
            //console.log(value);
            $('#roomplanid').append(new Option(value['roomplan_name'], value['roomplanid']));  
          })
          
          //$("#roomplanid").select2();
          //$("#roomplanid").val(roomplanid).trigger("change");
          $("#roomplanid").select2({
            dropdownParent: $("#createModal")
          });
        }else{
          /*$('#roomplanid').html('');
          $('#roomplanid').append(new Option('', ''));*/
        } 
       },
       error: function (data){
        swal("",data.Message,"error");
       }
    });

}

function view_datatable(){

      $('#loader').fadeIn();
      var url ='<?php echo site_url('defect/getdefect');?>';
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
              $('#loader').fadeOut();
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
          { "data": "defectid" },
          { "data": "zone_name" },
          { "data": "defect_name" },
          { "data": "roomplan_name" },
          { "data": "defect_status" },

          {"data": null ,
              render: function(data) {
                var id = data.defectid;
                var name = data.defect_name;
                var zone_name = data.zone_name;
                var defect_status = data.defect_status;
                var roomplan_name = data.roomplan_name;
                var roomplanid = data.roomplanid;

                return "<button type='button' data-toggle='modal' data-target='#detailModal' data-whatever='Detail Defect' data-action='view' data-zone_name='"+zone_name+"' data-defect_status='"+defect_status+"' data-defect_name='"+name+"' data-roomplan_name='"+roomplan_name+"' data-roomplanid='"+roomplanid+"' data-crmid='"+id+"' title='View' class='btn btn-success view btn-sm'><i class='glyphicon glyphicon-search'></i></button>&nbsp<button type='button' data-toggle='modal' data-target='#createModal' data-whatever='Edit Defect' data-action='edit' data-zone_name='"+zone_name+"' data-defect_status='"+defect_status+"' data-defect_name='"+name+"'  data-roomplan_name='"+roomplan_name+"' data-roomplanid='"+roomplanid+"'  data-crmid='"+id+"'  title='Edit' class='btn btn-warning edit btn-sm'><i class='glyphicon glyphicon-edit'></i></button>&nbsp<button type='button' id='"+id+"' class='btn btn-danger deleted btn-sm'><i class='glyphicon glyphicon-trash'></i></button>";
              },
          },

      ],"columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        },

        ],
        "order": [[ 0, 'ASC' ]]

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
        var url = "<?php echo site_url('defect/deleted'); ?>";
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

$(document).on('click','.create',function(){
  var zone_name = $('#modal_zone_name').val();
  var defect_status = $('#modal_defect_status').val();
  var defect_name = $('#modal_defect_name').val();
  var roomplanid = $('#roomplanid').val();

  var crmid = $('#crmid').val();
  var action = $('#action').val();

  if(zone_name == ''){
    $('#modal_zone_name').focus();
    $("#modal_zone_name").css("border-color", "red");
    return false;
  }else if(defect_name == ''){
    $('#modal_defect_name').focus();
    $("#modal_defect_name").css("border-color", "red");
    return false;
  }/*else if(roomplan_name == ''){
    $('#modal_roomplan_name').focus();
    $("#modal_roomplan_name").css("border-color", "red");
    return false;
  }*/

  $('#loader').fadeIn();

  var data = {
    crmid : crmid,
    action : action,
    zone_name : zone_name,
    defect_name : defect_name,
    defect_status : defect_status,
    roomplanid : roomplanid,
  }
  var url = "<?php echo site_url('defect/create'); ?>";
      $.ajax(url, {
         type: 'POST',
         data: data,
         success: function (data){
          var result = jQuery.parseJSON(data);
          console.log(result);

            if(result['Type'] =='S'){
              $('#loader').fadeOut();
              $('#createModal').modal('toggle');
               $('#loader').fadeOut();
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

$(document).on('click','.fillter',function(){

   var zoneid = $('#zoneid').val();
   var defect_name = $('#defect_name').val();

  $('#loader').fadeIn();
    var data = {
      zoneid : zoneid,
      defect_name : defect_name,
    }

   var url = "<?php echo site_url('defect/getdefect'); ?>";
      $.ajax(url, {
         type: 'POST',
         data: data,
         success: function (data){
          var result = jQuery.parseJSON(data);

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

</script>

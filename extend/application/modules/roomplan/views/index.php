 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Plans
        <small>View</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-file-text-o"></i>Plans</a></li>
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
                <label>Plans</label>
                <input type="text" class="form-control" id="roomplan_name" placeholder="Plans....">
              </div>
              <!-- /.form-group -->
              <div class="form-group">

              </div>
              <!-- /.form-group -->
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
          <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createModal" data-whatever="Create Plans" data-action="add" data-crmid="" data-roomplan_name="" data-url="" data-crmid_image="" ><i class="glyphicon glyphicon-plus"></i> New</button>
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
                    <th>Plans Name</th>
                    <th class="dt-center">Image</th>
                    <th></th>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th>No.</th>
                    <th>Plans Name</th>
                    <th class="dt-center">Image</th>
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

<style type="text/css">
  th.dt-center, td.dt-center { text-align: center; }
</style>


<div class="modal fade" id="Modalimage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel"></h4>
      </div>
      <div class="modal-body">
        <form>
          <input type="hidden" class="form-control" id="crmid" >
          <input type="hidden" class="form-control" id="action" >
          <div class="form-group text-center">
            <img id="modal_url" name="modal_url" class="myImages" style="width:500px;height:500px;text-align: center;">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">New message</h4>
      </div>
      <div class="modal-body">
        
        <form method="post" id="form_post" enctype="multipart/form-data">
          <input type="hidden" class="form-control" id="crmid" name="crmid" >
          <input type="hidden" class="form-control" id="action" name="action" >
          <input type="hidden" class="form-control" id="image_url" name="image_url" >
          <div class="form-group">
            <label for="recipient-name" class="control-label">Plans :</label>
            <input type="text" class="form-control" name="modal_roomplan_name" id="modal_roomplan_name" >
          </div>
            
            <div class="avatar-upload">
                <div class="avatar-edit">
                    <input type='file' id="imageUpload" name="imageUpload" value="" accept=".png, .jpg, .jpeg" />
                    <label for="imageUpload" id="imageClick" name="imageClick"></label>
                </div>
                <div class="avatar-preview">
                    <div id="imagePreview" name="imagePreview" style="background-size: 400px 400px;background-repeat: no-repeat;"></div>
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

<style type="text/css">
  .avatar-upload {
    position: relative;
    max-width: 400px;
    margin: 0 auto;
  }
  .avatar-upload .avatar-edit {
    position: absolute;
    right: -20px;
    z-index: 1;
    top: -10px;
  }
  .avatar-upload .avatar-edit input {
    display: none;
  }
  .avatar-upload .avatar-edit input + label {
    display: inline-block;
    width: 34px;
    height: 34px;
    margin-bottom: 0;
    border-radius: 100%;
    /*background: #fff;*/
    background: #80cbf7;
    border: 1px solid transparent;
    box-shadow: 0px 2px 4px 0px rgba(0,0,0,0.12);
    cursor: pointer;
    font-weight: normal;
    transition: all 0.2s ease-in-out;
  }
  .avatar-upload .avatar-edit input + label:hover {
    background: #f1f1f1;
    border-color: #d6d6d6;
  }
  .avatar-upload .avatar-edit input + label:after {
    content: "\f040";
    font-family: 'FontAwesome';
    color: #757575;
    /*color: #FFF;*/
    position: absolute;
    top: 7px;
    left: 0;
    right: 0;
    text-align: center;
    margin: auto;
  }
  .avatar-upload .avatar-preview {
    width: 400px;
    height: 400px;
    position: relative;
    /*border-radius: 100%;*/
    border: 6px solid #f8f8f8;
    box-shadow: 0px 2px 4px 0px rgba(0,0,0,0.1);
  }
  .avatar-upload .avatar-preview > div {
    width: 100%;
    height: 100%;
    /*border-radius: 100%;*/
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
  }
}

</style>
 
 <script type="text/javascript" src="<?php echo site_assets_url('DataTables/js/jquery.dataTables.js');?>"></script>
 <script type="text/javascript" src="<?php echo site_assets_url('DataTables/js/dataTables.bootstrap.js');?>"></script>
 <link rel="stylesheet" type="text/css" href="<?php echo site_assets_url('DataTables/css/dataTables.bootstrap.css'); ?>">
 <link rel="stylesheet" type="text/css" href="<?php echo site_assets_url('DataTables/css/dataTables.bootstrap4.min.css '); ?>">

<script type="text/javascript">

$(document).ready(function() {
  view_datatable();
});

function readURL(input) {

  if (input.files && input.files[0]) {
    var file = input.files[0];
    var fileType = file["type"];
    var validImageTypes = ["image/gif", "image/jpeg", "image/png"];
    
    if ($.inArray(fileType, validImageTypes) < 0) {
        swal("",'Please insert valid image file with following extensions .jpg .gif .png',"error");
        return false;
    }
    var reader = new FileReader();
      reader.onload = function(e) {
        $('#imagePreview').css('background-image','url('+e.target.result+')');
        $('#imagePreview').hide();
        $('#imagePreview').fadeIn(650);
      }
    reader.readAsDataURL(input.files[0]);
  
  }

}

$("#imageClick").click(function() {
  $('#imageUpload').val('');
});

$("#imageUpload").change(function() {
    readURL(this);
});

$('#Modalimage').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var roomplan_name = "Plans : "+button.data('roomplan_name');
  var url = button.data('url');
  var modal = $(this)
  if(url == ''){
    url = "<?= base_url('assets/images/no-image.png') ?>";
  }
  modal.find('.modal-title').text(roomplan_name)
  modal.find('#modal_url').attr("src", url);
});


$('#createModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
  var crmid = button.data('crmid');
  var action = button.data('action');
  var roomplan_name = button.data('roomplan_name');
  var url = button.data('url');
  var modal = $(this);
  modal.find('.modal-title').text(recipient);
  modal.find('#crmid').val(crmid);
  modal.find('#action').val(action);
  modal.find('#image_url').val(url);
  if(url == ''){
    url = "<?= base_url('assets/images/upload-image.png') ?>";
  }

  var d = new Date();
  var hr = d.getHours();
  var min = d.getMinutes();
  var s = d.getSeconds();
  var version = hr+''+min+''+s;

  modal.find('#imagePreview').css('background-image','url('+url+'?version='+version+')');
  modal.find('#modal_roomplan_name').val(roomplan_name);

  $("#modal_roomplan_name").removeAttr("style");
  
  $('#imageUpload').val('');
});

function view_datatable(){
    $('#loader').fadeIn();
    var url ='<?php echo site_url('roomplan/getroomplan');?>';
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
  var d = new Date();
  var hr = d.getHours();
  var min = d.getMinutes();
  var s = d.getSeconds();
  var version = hr+''+min+''+s;
  //console.log(hr+''+min+''+s);
  var data_table = $('#datatable').DataTable({
      "data" : data,
      "processing": true,
      "destroy" :true,
      "cache" : false,
      "columns": [
          { "data": "roomplanid" , width: 100,},
          { "data": "roomplan_name" , width: 250,},
          { "className": "dt-center" ,width: 150,
            render: function (data, type, JsonResultRow, meta) {
                url = JsonResultRow.image[0];
                if(url == 'undefined' || url == undefined){
                  url= '';
                }
                roomplan_name = JsonResultRow.roomplan_name;
                if(url != null && url != '' ){
                  return '<img src="'+url+'?version='+version+'" class="myImages" id="myImg" style="width:100px;height:100px;cursor: pointer;" alt="'+url+'?version='+version+'"data-toggle="modal" data-target="#Modalimage" data-roomplan_name="'+roomplan_name+'" data-url="'+url+'?version='+version+'" >';
                }else{
                  return '<i class="glyphicon glyphicon-picture"></i>';  
                }
               //version
            }
          },
          {"data": null , width: 100,
              //render: function(data) {
              render: function (data, type, JsonResultRow, meta) {
                var id = JsonResultRow.roomplanid;
                var name = JsonResultRow.roomplan_name;
                var url = JsonResultRow.image;
                //console.log(url);
                if(url == 'undefined' || url == undefined){
                  url= '';
                }
                return "<button type='button' data-toggle='modal' data-target='#Modalimage' data-roomplan_name='"+name+"' data-url='"+url+"?version="+version+"' data-crmid='"+id+"' data-crmid_image='' title='View' class='btn btn-success view btn-sm'><i class='glyphicon glyphicon-search'></i></button>&nbsp<button type='button' data-toggle='modal' data-target='#createModal' data-whatever='Edit Plans' data-action='edit' data-roomplan_name='"+name+"' data-url='"+url+"' data-crmid='"+id+"'  title='Edit' class='btn btn-warning edit btn-sm'><i class='glyphicon glyphicon-edit'></i></button>&nbsp<button type='button' id='"+id+"' title='Delete' class='btn btn-danger deleted btn-sm' ><i class='glyphicon glyphicon-trash'></i></button>";
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

  //data_table.ajax.reload();
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
        var url = "<?php echo site_url('roomplan/deleted'); ?>";
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
 var roomplan_name = $('#modal_roomplan_name').val();
 var crmid = $('#crmid').val();
 var action = $('#action').val();
 if(roomplan_name == ''){
    $('#modal_roomplan_name').focus();
    $("#modal_roomplan_name").css("border-color", "red");
    return false;
  }
  $('#loader').fadeIn();
  $("#form_post").submit();
});

$("#form_post").on('submit',(function(e) {
    e.preventDefault();
    var url = "<?php echo site_url('roomplan/create'); ?>";
    $.ajax({
       url: url,
       type: "POST",
       data:  new FormData(this),
       contentType: false,
       cache: false,
       processData:false,
       dataType : 'json',
       encoding:"UTF-8",
       /*beforeSend : function()
       {
        $("#loader").fadeOut();
       },*/
        success: function(data){
          var result = jQuery.parseJSON(JSON.stringify(data));
          if(result['Type'] =='S'){
            $('#loader').fadeOut();
              $('#createModal').modal('toggle');

              swal({
                position: 'center',
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
        error: function(e) 
        {
           $("#loader").fadeIn();  
        }          
      });
  })
);

$(document).on('click','.fillter',function(){

   var roomplan_name = $('#roomplan_name').val();

  $('#loader').fadeIn();
    var data = {
      roomplan_name : roomplan_name,
    }

   var url = "<?php echo site_url('roomplan/getroomplan'); ?>";
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

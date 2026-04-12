
<form id="form_data" action="#" method="post" >
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Inspection
        <small><?php echo $mod.' '.($mod=='detail'?'[ '.$record.' ]':''); ?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-paste"></i>Inspection <?php echo ($mod=='detail'? ' > '.$record :''); ?></a></li>
      </ol>
    </section>
    <input type="hidden" id="crmid" name="crmid" value="<?php echo $crmid ?>">
    <input type="hidden" id="action" name="action" value="<?php echo $mod ?>">
    <!-- Main content -->
    <section class="content">

<?php foreach($block['data'] as $key => $val){ ?>
        <!-- Black -->
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title"><?php echo $val['header_name']; ?></h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
          </div>
          <!-- Field -->
          <div class="box-body">
          <div class="row">
            <?php foreach($val['form'] as $k => $v){ ?>
              
              <?php if($v['uitype'] == 19 ){ ?>
                 <div class="col-md-6">
              <?php }else{ ?>
                 <div class="col-md-4">
              <?php }?>
             
                <div class="form-group"> 
                  <label class="<?php echo $v['columnname']; ?>"><?php echo $v['fieldlabel']; ?></label>
                <?php if($v['uitype'] == 19 ){ ?>
                  <textarea class="form-control" rows="6" id="<?php echo $v['columnname']; ?>" name="<?php echo $v['columnname'];?>" disabled><?php echo $v['value']; ?></textarea>
                <?php }elseif($v['uitype'] == 5 ){ ?>
                  <input type="text" class="form-control" id="<?php echo $v['columnname']; ?>" name="<?php echo $v['columnname']; ?>" disabled placeholder="<?php echo date("d/m/Y", strtotime($v['value'])); ?>">
                 <?php }elseif($v['uitype'] == 53 ){ ?>
                  <input type="text" class="form-control" id="<?php echo $v['columnname']; ?>" name="<?php echo $v['columnname']; ?>" disabled placeholder="<?php echo $v['value_name']; ?>">
                 <?php }elseif($v['uitype'] == 56 ){ ?>
                    
                    <?php if($v['columnname'] == 'show_inspection_list'  || $v['columnname'] == 'show_defect_list'){ 
                      $get_value =  @$v['value'];
                                          
                      if($get_value == '1'){
                          $val_name = ' Show ';
                      }else{
                          $val_name = ' Do not show ';
                      }
                    ?>
                      <input type="text" class="form-control" id="<?php echo $v['columnname']; ?>" name="<?php echo $v['columnname']; ?>" disabled placeholder="<?php echo $val_name ?>">
                    <?php }else{ ?>
                      <input type="text" class="form-control" id="<?php echo $v['columnname']; ?>" name="<?php echo $v['columnname']; ?>" disabled placeholder="<?php echo $v['value']; ?>">
                    <?php } ?>

                <?php }elseif($v['uitype'] == 59 ){ ?>
                  <input type="text" class="form-control" id="<?php echo $v['columnname']; ?>" name="<?php echo $v['columnname']; ?>" disabled placeholder="<?php echo $v['value_name']; ?>">
                <?php }elseif($v['uitype'] == 70 ){ ?>
                  <input type="text" class="form-control" id="<?php echo $v['columnname']; ?>" name="<?php echo $v['columnname']; ?>" disabled placeholder="<?php echo date("d/m/Y H:i:s", strtotime($v['value'])); ?>">
                <?php }elseif($v['uitype'] == 941 ){ ?>
                  <input type="text" class="form-control" id="<?php echo $v['columnname']; ?>" name="<?php echo $v['columnname']; ?>" disabled placeholder="<?php echo $v['value_name']; ?>">
                <?php }elseif($v['uitype'] == 942 ){ ?>
                  <input type="text" class="form-control" id="<?php echo $v['columnname']; ?>" name="<?php echo $v['columnname']; ?>" disabled placeholder="<?php echo $v['value_name']; ?>">
                <?php }else{ ?>
                  <input type="text" class="form-control" id="<?php echo $v['columnname']; ?>" name="<?php echo $v['columnname']; ?>" disabled placeholder="<?php echo $v['value']; ?>" value="<?php echo $v['value']; ?>">
                <?php }?>

                </div>
              
              </div>

            <?php } ?>
          </div>
          <!-- /.row -->
          </div>
        <!-- /.box-body -->
        <!-- Field -->
        </div>
<?php } ?>
    
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">Defect List</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
          
          </table><table id="datatable" class="table table-striped table-bordered display" style="width:100%">
            <thead>
              <tr>
                  <th>No.</th>
                  <th></th>
                  <th>Defect</th>
                  <th>Status</th>
                  <th>Plan</th>
                  <th>Before Comment</th>
                  <th class="dt-center">Before</th>
                  <th>Comment</th>
                  <th class="dt-center">After</th>
              </tr>
          </thead>

          <tfoot>
              <tr>
                 
                  <th>No.</th>
                  <th></th>
                  <th>Defect</th>
                  <th>Status</th>
                  <th>Plan</th>
                  <th>Before Comment</th>
                  <th class="dt-center">Before</th>
                  <th>Comment</th>
                  <th class="dt-center">After</th>
              </tr>
          </tfoot>
          </table>

          </div>
      
        </div>
      
      </div>  
      <!-- /.box-header -->
    </div>
    <!-- Data Table/.box -->


    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">Signature</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <div class="row">
          
          <div class="col-md-6">
            <div class="form-group text-center">    
              <label>Inspector Signature</label><div style="clear:both; "></div>

              <?php if(!empty($sig_contracto)){?>
              <img id="myImg" class="myImages" src="<?php echo $sig_contracto ?>"style="width:30%;max-width:40%">
              <?php }else{ ?>
              <i class="glyphicon glyphicon-picture"></i>
              <?php } ?>
            </div>
            <div class="form-group">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group text-center"> 
              <label>Customer/Contractor Signature</label><div style="clear:both; "></div>
              <?php if(!empty($sig_contracto)){?>  
              <img id="myImg" class="myImages" src="<?php echo $sig_customer ?>" style="width:30%;max-width:40%">
              <?php }else{ ?>
              <i class="glyphicon glyphicon-picture"></i>
              <?php } ?>
            </div>
            <div class="form-group">
            </div>
          </div>
         

          <!-- </div> -->
      
        </div>
      
      </div>  
      <!-- /.box-header -->
    </div>
    <!-- Data Table/.box -->


    <div class="box box-default">
      <div class="box-header with-border">
        <div class="text-center">
          <?php if($inspection_status == "Closed"){ ?>
            <button type="button" title="Print" class="btn btn-default btn-sm print"><i class="glyphicon glyphicon-print"></i> Print</button> 
          <?php }else{ ?>
            <button type="button" title="Edit" class="btn btn-warning btn-sm edit"><i class="glyphicon glyphicon-edit"></i> Edit</button>
          <?php } ?>
            <button type="button" title="Back" class="btn btn-info btn-sm cancel"><i class="glyphicon glyphicon-chevron-left"></i> Back</button>
        </div>
      </div>
    </div>
    </section>

<!-- The Modal -->
<!-- <div id="myModal" class="modal">
  <span class="close">&times;</span>
  <img class="modal-content" id="img01">
  <div id="caption"></div>
</div> -->

 </div>
</form>

  <script type="text/javascript" src="<?php echo site_assets_url('DataTables/js/jquery.dataTables.js');?>"></script>
  <script type="text/javascript" src="<?php echo site_assets_url('DataTables/js/dataTables.bootstrap.js');?>"></script>
  <link rel="stylesheet" type="text/css" href="<?php echo site_assets_url('DataTables/css/dataTables.bootstrap.css'); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo site_assets_url('DataTables/css/dataTables.bootstrap4.min.css '); ?>">

<style type="text/css">
th.dt-center, td.dt-center { text-align: center; }
</style>


<script type="text/javascript">

$(document).ready(function() {
  $('#loader').fadeOut();
  var result = jQuery.parseJSON(JSON.stringify(<?php echo $defect_list ?>));
  reload_datatable(<?php echo $defect_list ?>);
  
  var inspec_type = jQuery("#inspection_type").val();

    if(inspec_type == 'Contractor'){
      $('.customer_name').html('Contractor Name');
      $('.phone').html('Contractor Mobile');
      $('.phone_other').html('Contractor Mobile Others');
      $('.email').html('Contractor Email');
    }else{
      $('.customer_name').html('Customer Name');
      $('.phone').html('Customer Mobile');
      $('.phone_other').html('Customer Mobile Others');
      $('.email').html('Customer Email');
    } 
  /*var modal = document.getElementById('myModal');
  // to all images -- note I'm using a class!
  var images = document.getElementsByClassName('myImages');
  // the image in the modal
  var modalImg = document.getElementById("img01");
  // and the caption in the modal
  var captionText = document.getElementById("caption");

  // Go through all of the images with our custom class
  for (var i = 0; i < images.length; i++) {
  var img = images[i];
  // and attach our click listener for this image.
    img.onclick = function(evt) {
      modal.style.display = "block";
      modalImg.src = this.src;
      captionText.innerHTML = this.alt;
    }
  }

  var span1 = document.getElementsByClassName("close")[0];

  span1.onclick = function() {
    modal.style.display = "none";
  }*/

});

$(document).on( 'click', '.edit', function () {
    document.location.href = "<?php echo site_url('inspection/mnt/'.$crmid)?>/";
});

$(document).on( 'click', '.cancel', function () {
    document.location.href = "<?php echo site_url('inspection/index')?>";
});

$(document).on( 'click', '.print', function () {
  
  var crmid= $("#crmid").val();
  var URL = 'http://aisystem.dyndns.biz:8100/birt-viewer/frameset?__showtitle=false&__report=nogd/print_preview.rptdesign&crmid='+crmid+'&__format=pdf';
  window.open(URL, '_blank');

});


function reload_datatable(data){
      var groupColumn = 1;
      var data_table = $('#datatable').DataTable({
      "data" : data,
      "processing": true,
      "destroy" :true,
      "columns": [
          { "data": "inspectiondefectlistid",width: 20},
          { "data": "zone_name"},
          { "data": "defect_name",width: 300},
          { "data": "defectlist_status",width: 20},
          
          { "className": "dt-center" ,width: 100,
            render: function (data, type, JsonResultRow, meta) {
                roomplan_url = JsonResultRow.roomplan_url;
                if(roomplan_url != null && roomplan_url != '' ){
                  return '<img src="'+roomplan_url+'" class="myImages" id="myImg" style="width:150px;height:150px;" >';
                }else{
                  return '<i class="glyphicon glyphicon-picture"></i>';  
                }
            }
          },
          
          { "data": "before_comment" ,width: 150},
          { "className": "dt-center" ,width: 100,
            render: function (data, type, JsonResultRow, meta) {
                url_brfore = JsonResultRow.before_imageurl;
                inspection_timeno = JsonResultRow.inspection_timeno;
                url = JsonResultRow.imageurl;
                //console.log(url_brfore);
                if(inspection_timeno == '1'){
                  if(url != null && url != '' ){
                    return '<img src="'+url+'" class="myImages" id="myImg" style="width:150px;height:150px;" >';
                  }else{
                    return '<i class="glyphicon glyphicon-picture"></i>';  
                  }

                }else{
                  if(url_brfore != null && url_brfore != '' ){
                    return '<img src="'+url_brfore+'" class="myImages" id="myImg" style="width:150px;height:150px;" >';
                  }else{
                    return '<i class="glyphicon glyphicon-picture"></i>';  
                  }
                }
                  

            }
          },
          { "data": "comment" ,width: 150},
          { "className": "dt-center" ,width: 100,
            render: function (data, type, JsonResultRow, meta) {
                url = JsonResultRow.imageurl;
                inspection_timeno = JsonResultRow.inspection_timeno;
                //console.log(url);

                if(url != null && url != '' && inspection_timeno != '1'){
                  return '<img src="'+url+'" class="myImages" id="myImg" style="width:150px;height:150px;" >';
                }else{
                  return '<i class="glyphicon glyphicon-picture"></i>';  
                }
            }
          }
          
      ],"columnDefs": [ {
            "visible": false,
            "searchable": false,
            "orderable": false,
            "targets": groupColumn
        },

        ],
        "order": [[ 0, 'asc' ]],
        "displayLength": 25 ,
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
 
            api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="8"><b style="font-size:16px;">'+group+'</b></td></tr>'
                    );
 
                    last = group;
                }
            } );
        }
      
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

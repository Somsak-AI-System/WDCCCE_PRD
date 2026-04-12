  <?php
    session_start();
    header('Content-Type: text/html; charset=utf-8');
    global $dbconfig;
    require_once("config.inc.php");
    require_once("library/dbconfig.php");
    include_once("library/myLibrary_mysqli.php");
    $myLibrary_mysqli = new myLibrary_mysqli();
    $myLibrary_mysqli->_dbconfig = $dbconfig;

    $pquery = "select * from aicrm_stage where presence = 1 and flag_closed != 1 order by  sequence";
    $d_stages = $myLibrary_mysqli->select($pquery);
    
    $count = count($d_stages);
    $stages = @$_REQUEST['stage'];

    $query = "SELECT * FROM aicrm_setting_module_flags WHERE module='Deal'";
    $rs = $myLibrary_mysqli->select($query);

    $flagComment = '0';
    if(!empty($rs) && isset($rs[0])){
      $flagComment = $rs[0]['flag_comment'];
    }
    //echo "<pre>"; print_r($d_stages); echo "</pre>";
  ?>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="x-frame-options" content="allowall" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- <link rel="stylesheet" href="plugin/Deal/bootstrap-select.min.css" /> -->

  <link rel="stylesheet" href="plugin/Deal/bootstrap.min.css" />

  <link rel="stylesheet" href="plugin/Deal/custom.css" />

  <link href="plugin/Deal/select2.min.css" rel="stylesheet" />

  <script src="plugin/Deal/select2.min.js"></script>


  <input type="hidden" name="dealid" id="dealid" value="<?php echo $_REQUEST['record']; ?>">
  <input type="hidden" name="flagComment" id="flagComment" value="<?php echo $flagComment; ?>">
  
  <nav>

    <div class="tabbable">
  	  <ul class="nav nav-tabs wizard">
  	 
        <?php 
          $flag_check = 0;
          

          foreach ($d_stages as $key => $value) {  ?>

          <?php if($stages == 'Closed Won'){ ?>
              <li class="dropdown dropdown-toggle arrow completed" data-status="<?php echo $value['stage'] ?>"><a href="#i9" data-toggle="tab" aria-expanded="false"><span><?php echo $value['stage'] ?></span></a>
                
              </li>
          <?php }else if($stages == 'Closed Lost'){ ?>
              <li class="dropdown dropdown-toggle arrow deallost" data-status="<?php echo $value['stage'] ?>"><a href="#i9" data-toggle="tab" aria-expanded="false"><span><?php echo $value['stage'] ?></span></a>
                
              </li>
          <?php }else{ ?>

            <!-- Other stages  -->
              <?php
                $action = '';

                if($stages == $value['stage'] ){ 
                  $action = 'active';
                  $flag_check = 1;
                }else if($flag_check != 1){
                  $action = 'completed';
                }
              ?>

              <li class="dropdown dropdown-toggle arrow <?php echo $action; ?>" data-status="<?php echo $value['stage'] ?>"><a href="#i9" data-toggle="tab" aria-expanded="false"><span><?php echo $value['stage'] ?></span></a>
                <ul class="menu-list">
                  
                    <li class="list present" data-step='1' data-status="<?php echo $value['stage'] ?>"><a href="#">สถานะปัจจุบัน</a></li>
              
                  <?php if($count != ($key+1)){ ?>
                    <li class="list scomplete" data-step='2' data-status="<?php echo $value['stage'] ?>" style="border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;"><a href="#">สถานะเสร็จสิ้น</a></li>
                  <?php } ?>
                </ul>
              </li>

          <?php } ?>
          

        <?php } ?><!--foreach $d_stages  -->

          
          

            <?php if($stages == 'Closed Won'){ ?>
              
              <li class="dropdown dropdown-toggle arrow completed" data-status="Closed"><a href="#finish" data-toggle="tab" aria-expanded="true"><span>Closed</span></a>
                
              </li>

            <?php }else if($stages == 'Closed Lost'){ ?>
              
              <li class="dropdown dropdown-toggle arrow lost" data-status="Closed"><a href="#finish" data-toggle="tab" aria-expanded="true"><span>Closed</span></a>
               
              </li>
            
            <?php }else{ ?>

              <li class="dropdown dropdown-toggle arrow" data-status="Closed"><a href="#finish" data-toggle="tab" aria-expanded="true"><span>Closed</span></a>
                <ul class="menu-list">
                  <li class="list scomplete" data-step='2' data-status="Closed" data-toggle="modal" data-target="#exampleModal" style="border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;"><a href="#">สถานะเสร็จสิ้น</a></li>
                </ul>
              </li>
            
            <?php } ?>
          

  	  </ul>
  	</div>

  </nav>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="font-size: 12px !important;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel" style="display: contents;font-weight:bold;">เหตุผล</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Closed">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <div class="modal-body">

        <form name="dealupdate" method="POST" ENCTYPE="multipart/form-data">
          
          <div class="form-group">
            <label>เหตุผล (ชนะ)</label>
            <select name="wonreason" id="wonreason" tabindex="" class="form-control" style="width: 99%">
            </select>
          </div>

          <div class="form-group">
            <label>เหตุผล (แพ้)</label>
            <select name="lostreason" id="lostreason" tabindex="" class="form-control" style="width: 99%">
            </select>
          </div>

          <div class="form-group">
            <label>เหตุผล (อื่นๆ)</label>
            <textarea class="form-control" id="remark" name="remark" style="resize: none" rows="2" cols="20"></textarea>
          </div>

          <div class="form-group">
            <label>ชื่อคู่แข่ง</label>
            <select class="js-states form-control select2" id="competitorid" name="competitorid" data-placeholder="Select" style="width: 100%;font-size: 14px !important;">
            </select>
          </div>

          <div class="form-group">
            <label>ชื่อคู่แข่งใหม่</label>
            <input type="text" class="form-control" id="newcompetitor" name="newcompetitor">
          </div>

          <div class="form-group">
            <label>รายละเอียดเพิ่มเติม</label>
            <input type="text" class="form-control" id="moredetails" name="moredetails">
          </div>


        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-success closewon" style="width: 100%">Closed Won</button><!-- data-dismiss="modal" -->
        <button type="button" class="btn btn-danger closewlost" style="width: 100%; margin-top: 5px; margin-left: 0px;">Closed Lost</button>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">

  jQuery(document).ready(function(){
    
    jQuery('nav li').hover(
      function() {
        jQuery('ul', this).stop().slideDown(200);
      },
      function() {
        jQuery('ul', this).stop().slideUp(200);
      }
    );

    jQuery('#exampleModal').on('show.bs.modal', function (event) {

      var button = jQuery(event.relatedTarget) // Button that triggered the modal
      var recipient = button.data('whatever') // Extract info from data-* attributes

      get_stage();

    })

    //jQuery(".select2").select2();
    jQuery('.select2').select2({
        dropdownParent: jQuery('#exampleModal')
    });

  });

  function get_stage(){
    var url = "plugin/Deal/dealstage.php";
      jQuery.ajax(url, {
         type: 'POST',
         data: '',
         success: function (data){
          var result = jQuery.parseJSON(data);
          
          jQuery('#lostreason').html('');
          jQuery('#lostreason').append(new Option('--None--', '--None--'));

          jQuery.each(result.lostreason, function( index, value ) {
            jQuery('#lostreason').append(new Option(value['lostreason'], value['lostreason'])); 
          })

          jQuery('#wonreason').html('');
          jQuery('#wonreason').append(new Option('--None--', '--None--'));

          jQuery.each(result.wonreason, function( index, value ) {
            jQuery('#wonreason').append(new Option(value['wonreason'], value['wonreason'])); 
          })

          jQuery('#competitorid').html('');
          jQuery('#competitorid').append(new Option('', '--None--'));
          jQuery.each(result.competitor, function(index, value) {
            // darle un option con los valores asignados a la variable select
            jQuery('#competitorid').append('<option value="' + value.competitorid+ '">' + value.competitor_name+ '</option>');
          });
          //jQuery('#competitorid').selectpicker('refresh');
         },
         error: function (data){
          
         }
      });
  }

  jQuery('.closewon').click(function(){
      
      var competitor = jQuery('#competitorid').select2('data');
      //console.log(competitor[0].text);
      //console.log(competitorid[0].id);
      
      var dealid = jQuery('#dealid').val();
      var lostreason = jQuery("#lostreason").val();
      var wonreason = jQuery("#wonreason").val();
      var remark = jQuery("#remark").val();
      var competitorid = competitor[0].id
      var newcompetitor = jQuery("#newcompetitor").val();

      var moredetails = jQuery("#moredetails").val();
      //var competitortext = competitorid[0].text;

      var stage = 'Closed Won';
      var url = "plugin/Deal/updatestage.php";

      jQuery.ajax(url, {
         type: 'POST',
         data: {dealid:dealid,stage:stage,lostreason:lostreason,remark:remark,wonreason:wonreason,competitorid:competitorid,moredetails:moredetails,newcompetitor:newcompetitor},
         success: function (data){
          var result = jQuery.parseJSON(data);
          jQuery( ".stage" ).css('color','black');
          jQuery( ".stage" ).html(stage);

          jQuery("#exampleModal").modal("hide");
          jQuery('.wizard li.arrow').removeClass("active");
          jQuery('.wizard li.arrow').addClass("completed");

          jQuery('.wizard li.arrow').removeClass("lost");
          jQuery('.wizard li.arrow').removeClass("lost");
          jQuery('.wizard li.arrow').removeClass("deallost");
          jQuery('.wizard li.arrow').removeClass("deallost");

          jQuery('.wizard li.arrow').removeClass("lost").removeClass("deallost");

          jQuery( ".lostreason" ).html(lostreason);
          jQuery( ".remark" ).html(remark);
          jQuery( ".wonreason" ).html(wonreason);
          //jQuery( ".competitorid" ).html(competitorid);
          jQuery( ".moredetails" ).html(moredetails);

          if(newcompetitor != ''){
            jQuery( ".newcompetitor" ).html(newcompetitor);
          }
          
          if(competitorid != ''){
            jQuery(".competitorid").attr("href", "index.php?module=Competitor&action=DetailView&record="+competitorid);
            jQuery('.competitorid').attr('title',competitor[0].text);
            jQuery(".competitorid").html(competitor[0].text);
          }
          jQuery( "ul" ).remove( ".menu-list" );

         },

         error: function (data){
         
         }
      });
    
  });

  jQuery('.closewlost').click(function(){

      var competitor = jQuery('#competitorid').select2('data');
    

      var dealid = jQuery('#dealid').val();
      var lostreason = jQuery("#lostreason").val();
      var wonreason = jQuery("#wonreason").val();
      var remark = jQuery("#remark").val();
      var competitorid = competitor[0].id;
      var moredetails = jQuery("#moredetails").val();
      var newcompetitor = jQuery("#newcompetitor").val();

      //var competitortext = competitor[0].text;

      var stage = 'Closed Lost';
      var url = "plugin/Deal/updatestage.php";

      jQuery.ajax(url, {
         type: 'POST',
         data: {dealid:dealid,stage:stage,lostreason:lostreason,remark:remark,wonreason:wonreason,competitorid:competitorid,moredetails:moredetails,newcompetitor:newcompetitor},
         success: function (data){
          var result = jQuery.parseJSON(data);
          jQuery( ".stage" ).css('color','black');
          jQuery( ".stage" ).html(stage);

          jQuery("#exampleModal").modal("hide");
          
          jQuery('.wizard li.arrow').removeClass("active");
          jQuery('.wizard li.arrow').removeClass("completed");
          jQuery('.wizard li.arrow').removeClass("deallost");

          jQuery('.wizard li').addClass("deallost");
          var last = jQuery(".wizard li.arrow").last();
          
          last.addClass('lost').removeClass('deallost');

          jQuery( ".lostreason" ).html(lostreason);
          jQuery( ".remark" ).html(remark);
          jQuery( ".wonreason" ).html(wonreason);
          //jQuery( ".competitorid" ).html(competitorid);
          jQuery( ".moredetails" ).html(moredetails);
          
          if(newcompetitor != ''){
            jQuery( ".newcompetitor" ).html(newcompetitor);
          }

          if(competitorid != ''){
            jQuery(".competitorid").attr("href", "index.php?module=Competitor&action=DetailView&record="+competitorid);
            jQuery('.competitorid').attr('title',competitor[0].text);
            jQuery(".competitorid").html(competitor[0].text);
          }

          jQuery( "ul" ).remove( ".menu-list" );

         },

         error: function (data){
         
         }
      });

  });

  jQuery('.wizard li').click(function(){
    /*jQuery(this).prevAll().addClass("completed");
    jQuery(this).nextAll().removeClass("completed");
    jQuery(this).removeClass("completed");*/
  });

  jQuery.saveCommentDialog = function() {
    var comments = jQuery('#comments').val();
    if (selectedElement !== null) {
      var status = jQuery(selectedElement).attr("data-status");
      var step = jQuery(selectedElement).attr("data-step");
      var a_this = jQuery(selectedElement).parent().parent();

      var dealid = jQuery('#dealid').val();
      var url = "plugin/Deal/updatestage.php";

      if (step == 1) {
        jQuery.ajax(url, {
          type: 'POST',
          data: {
            dealid: dealid,
            stage: status,
            comments
          },
          success: function(data) {
            var result = jQuery.parseJSON(data);
            jQuery(".stage").css('color', 'black');
            jQuery(".stage").html(status);

          },
          error: function(data) {

          }
        });

        a_this.nextAll().removeClass("active");
        a_this.prevAll().removeClass("active");
        a_this.addClass("active");

        a_this.prevAll().addClass("completed");
        a_this.nextAll().removeClass("completed");

        a_this.nextAll().removeClass("lost");
        a_this.prevAll().removeClass("lost");
        a_this.nextAll().removeClass("deallost");
        a_this.prevAll().removeClass("deallost");

        a_this.removeClass("lost").removeClass("deallost");

        a_this.removeClass("completed");

      } else {

        if (status !== 'Closed') {

          var next_status = a_this.next(1).attr("data-status");

          jQuery.ajax(url, {
            type: 'POST',
            data: {
              dealid: dealid,
              stage: next_status,
              comments
            },
            success: function(data) {
              var result = jQuery.parseJSON(data);
              jQuery(".stage").css('color', 'black');
              jQuery(".stage").html(next_status);

            },
            error: function(data) {

            }
          });

          a_this.nextAll().removeClass("active");
          a_this.prevAll().removeClass("active");

          a_this.next(1).addClass("active");

          a_this.prevAll().addClass("completed");
          a_this.nextAll().removeClass("completed");
          a_this.removeClass("active");

          a_this.nextAll().removeClass("lost").removeClass("deallost");
          a_this.prevAll().removeClass("lost").removeClass("deallost");

          a_this.addClass("completed");
        }

      }
    }

    jQuery('#popup-dialog').window('close')
    selectedElement = null
  }

  jQuery.closeCommentDialog = function(){
    jQuery('#popup-dialog').window('close')
  }
  var popupDialog = jQuery('<div />',{ id:'popup-dialog' })
  var selectedElement = null;

  jQuery('.wizard li ul li').click(function(){
    selectedElement = this
    var status = jQuery(this).attr("data-status");
    var step = jQuery(this).attr("data-step");
    var a_this = jQuery(this).parent().parent();
    
    var dealid = jQuery('#dealid').val();
    var url = "plugin/Deal/updatestage.php";
    var flagComment = jQuery('#flagComment').val()

    if(flagComment === '1' && status !== 'Closed'){
      var html = `<div class="container-fluid">
      <div class="row" style="padding:5px;">
        <div class="col-sm-12" style="padding:0px!important">
          <textarea class="form-control" style="padding:0px!important;width:99%;resize:none;" name="comments" id="comments" rows="3"></textarea>
        </div>

        <div class="col-sm-12 text-center" style="margin-top:10px;padding:0px!important">
          <button type="button" class="btn btn-success btn-xs" onclick="jQuery.saveCommentDialog()">บันทึก</button>
          <button type="button" class="btn btn-danger btn-xs" onclick="jQuery.closeCommentDialog()">ยกเลิก</button>
        </div>
      </div>
    </div>`;

      jQuery(popupDialog).window({
        title: 'หมายเหตุ',
        width:300,
        height:150,
        modal:true,
        maximizable: false,
        minimizable: false
      }).html(html);
      return false;
    }

    if(step == 1){
        // console.log('dddd');
        jQuery.ajax(url, {
           type: 'POST',
           data: {dealid:dealid,stage:status},
           success: function (data){
            var result = jQuery.parseJSON(data);
            //jQuery( ".stage" ).html('');
            jQuery( ".stage" ).css('color','black');
            jQuery( ".stage" ).html(status);
           
           },
           error: function (data){
           
           }
        });

        a_this.nextAll().removeClass("active");
        a_this.prevAll().removeClass("active");
        a_this.addClass("active");

        a_this.prevAll().addClass("completed");
        a_this.nextAll().removeClass("completed");
        
        a_this.nextAll().removeClass("lost");
        a_this.prevAll().removeClass("lost");
        a_this.nextAll().removeClass("deallost");
        a_this.prevAll().removeClass("deallost");

        a_this.removeClass("lost").removeClass("deallost");
         
        a_this.removeClass("completed");

    }else{
      // console.log(status);
      if(status !== 'Closed'){

        var next_status = a_this.next(1).attr("data-status");

        jQuery.ajax(url, {
           type: 'POST',
           data: {dealid:dealid,stage:next_status},
           success: function (data){
            var result = jQuery.parseJSON(data);
            jQuery( ".stage" ).css('color','black');
            jQuery( ".stage" ).html(next_status);
           
           },
           error: function (data){
           
           }
        });

        a_this.nextAll().removeClass("active");
        a_this.prevAll().removeClass("active");

        a_this.next(1).addClass("active");
        //var next_status = a_this.next(1).attr("data-status");
        //console.log(step);
        //console.log(next_status);

        a_this.prevAll().addClass("completed");
        a_this.nextAll().removeClass("completed");
        a_this.removeClass("active");
        
        a_this.nextAll().removeClass("lost").removeClass("deallost");
        a_this.prevAll().removeClass("lost").removeClass("deallost");

        a_this.addClass("completed");
      }
      
    }
    

  });

</script>

<style type="text/css">
.select2-close-mask{
    z-index: 8099 !important;
}
.select2-dropdown{
    z-index: 9051 !important;
}
.select2-container {
    z-index: 99999 !important;
}
.select2 {
  font-size: 14px !important;
}
.select2-results__options{
    font-size:14px !important;
 }
</style>

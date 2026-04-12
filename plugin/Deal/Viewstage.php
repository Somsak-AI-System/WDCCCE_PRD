<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
global $dbconfig, $current_user;
require_once("config.inc.php");
require_once("library/dbconfig.php");
include_once("library/myLibrary_mysqli.php");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

$usercomment = $current_user->column_fields['first_name'] . ' ' . $current_user->column_fields['last_name'];

$pquery = "select * from aicrm_stage where presence = 1 and flag_closed != 1 order by  sequence";
$d_stages = $myLibrary_mysqli->select($pquery);

$count = count($d_stages);
$stages = @$_REQUEST['stage'];
//echo "<pre>"; print_r($d_stages); echo "</pre>";

$query = "SELECT * FROM aicrm_setting_module_flags WHERE module='Deal'";
$rs = $myLibrary_mysqli->select($query);
// echo "<pre>"; print_r($rs); echo "</pre>";
$flagComment = '0';
$flag_require_comment = '0';
if (!empty($rs) && isset($rs[0])) {
  $flagComment = $rs[0]['flag_comment'];
  $flag_require_comment = $rs[0]['flag_require_comment'];
}

?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="x-frame-options" content="allowall" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="plugin/Deal/bootstrap.min.css" rel="stylesheet">
<link href="plugin/Deal/custom.css" rel="stylesheet">

<link href="plugin/Deal/select2.min.css" rel="stylesheet" />
<script src="plugin/Deal/select2.min.js"></script>

<!-- <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.full.js"></script> -->

<input type="hidden" name="dealid" id="dealid" value="<?php echo $_REQUEST['record']; ?>">
<input type="hidden" name="flagComment" id="flagComment" value="<?php echo $flagComment; ?>">
<input type="hidden" name="flag_require_comment" id="flag_require_comment" value="<?php echo $flag_require_comment; ?>">
<input type="hidden" name="usercomment" id="usercomment" value="<?php echo $usercomment; ?>">
<nav>

  <div class="tabbable">
    <ul class="nav nav-tabs wizard">

      <?php
      $flag_check = 0;


      foreach ($d_stages as $key => $value) {  ?>

        <?php if ($stages == 'Closed Won') { ?>
          <li class="dropdown dropdown-toggle arrow completed" data-status="<?php echo $value['stage'] ?>"><a href="#i9" data-toggle="tab" aria-expanded="false"><span><?php echo $value['stage'] ?></span></a>

          </li>
        <?php } else if ($stages == 'Closed Lost') { ?>
          <li class="dropdown dropdown-toggle arrow deallost" data-status="<?php echo $value['stage'] ?>"><a href="#i9" data-toggle="tab" aria-expanded="false"><span><?php echo $value['stage'] ?></span></a>

          </li>
        <?php } else { ?>

          <!-- Other stages  -->
          <?php
          $action = '';

          if ($stages == $value['stage']) {
            $action = 'active';
            $flag_check = 1;
          } else if ($flag_check != 1) {
            $action = 'completed';
          }
          ?>

          <li class="dropdown dropdown-toggle arrow <?php echo $action; ?>" data-status="<?php echo $value['stage'] ?>"><a href="#i9" data-toggle="tab" aria-expanded="false"><span><?php echo $value['stage'] ?></span></a>
            <ul class="menu-list">

              <li class="list present" data-step='1' data-status="<?php echo $value['stage'] ?>"><a href="#">สถานะปัจจุบัน</a></li>

              <?php if ($count != ($key + 1)) { ?>
                <li class="list scomplete" data-step='2' data-status="<?php echo $value['stage'] ?>" style="border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;"><a href="#">สถานะเสร็จสิ้น</a></li>
              <?php } ?>
            </ul>
          </li>

        <?php } ?>


      <?php } ?><!--foreach $d_stages  -->

      <?php if ($stages == 'Closed Won') { ?>

        <li class="dropdown dropdown-toggle arrow completed" data-status="Closed"><a href="#finish" data-toggle="tab" aria-expanded="true"><span>Closed(100%)</span></a>

        </li>

      <?php } else if ($stages == 'Closed Lost') { ?>

        <li class="dropdown dropdown-toggle arrow lost" data-status="Closed"><a href="#finish" data-toggle="tab" aria-expanded="true"><span>Closed(100%)</span></a>

        </li>

      <?php } else { ?>

        <li class="dropdown dropdown-toggle arrow" data-status="Closed"><a href="#finish" data-toggle="tab" aria-expanded="true"><span>Closed</span></a>
          <ul class="menu-list">
            <li class="list scomplete" data-step='2' data-status="Closed" onclick="closedStep()"="border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;"><a href="#">สถานะเสร็จสิ้น</a></li>
          </ul>
        </li>

      <?php } ?>

    </ul>
  </div>

</nav>

<div id="closed-step-dialog"></div>
<div id="closed-step" class="hidden">
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
    <select name="competitorid" id="competitorid" tabindex="" class="form-control" style="width: 99%">
    </select>
  </div>

  <div class="form-group">
    <label>รายละเอียดเพิ่มเติม</label>
    <input type="text" class="form-control" id="moredetails" name="moredetails">
  </div>

  <div>
    <button type="button" class="btn btn-success closewon" onclick="closeWon()" style="width: 100%">Closed Won</button>
    <button type="button" class="btn btn-danger closewlost" onclick="closeLost()" style="width: 100%; margin-top: 5px; margin-left: 0px;">Closed Lost</button>
  </div>
</div>

<style type="text/css">
  .requert {
    border: 1px solid red !important;
  }

  .no-request {
    border: 1px solid #ccc !important;
  }
</style>
<script type="text/javascript">
  jQuery(document).ready(function() {

    jQuery('nav li').hover(
      function() {
        jQuery('ul', this).stop().slideDown(200);
      },
      function() {
        jQuery('ul', this).stop().slideUp(200);
      }
    );

    // jQuery('#exampleModal').on('show.bs.modal', function (event) {

    //   var button = jQuery(event.relatedTarget) // Button that triggered the modal
    //   var recipient = button.data('whatever') // Extract info from data-* attributes

    //   get_stage();

    // })

  });

  function closedStep() {
    jQuery('#closed-step-dialog').window({
      title: 'เหตุผล',
      width: 600,
      height: 520,
      modal: true,
      maximizable: false,
      minimizable: false
    }).html(jQuery('#closed-step').html());

    var url = "plugin/Deal/dealstage.php";
    jQuery.ajax(url, {
      type: 'POST',
      data: '',
      success: function(data) {
        var result = jQuery.parseJSON(data);

        jQuery('#closed-step-dialog #lostreason').html('');
        jQuery('#closed-step-dialog #lostreason').append(new Option('--None--', '--None--'));
        jQuery.each(result.lostreason, function(index, value) {
          jQuery('#closed-step-dialog #lostreason').append(new Option(value['lostreason'], value['lostreason']));
        })

        jQuery('#closed-step-dialog #wonreason').html('');
        jQuery('#closed-step-dialog #wonreason').append(new Option('--None--', '--None--'));
        jQuery.each(result.wonreason, function(index, value) {
          jQuery('#closed-step-dialog #wonreason').append(new Option(value['wonreason'], value['wonreason']));
        })

        jQuery('#closed-step-dialog #competitorid').html('');
        jQuery('#closed-step-dialog #competitorid').append(new Option('', '--None--'));
        jQuery.each(result.competitor, function(index, value) {
          jQuery('#closed-step-dialog #competitorid').append(new Option(value['competitor_name'], value['competitorid']));
        });
      },
      error: function(data) {

      }
    });

  }

  function closeWon()
  {
    var dealid = jQuery('#dealid').val();
    var lostreason = jQuery("#closed-step-dialog #lostreason").val();
    var wonreason = jQuery("#closed-step-dialog #wonreason").val();
    var remark = jQuery("#closed-step-dialog #remark").val();
    var competitorid = jQuery("#closed-step-dialog #competitorid").val();
    var moredetails = jQuery("#closed-step-dialog #moredetails").val();
    // console.log(dealid, lostreason, wonreason, remark, competitorid, moredetails)
    var stage = 'Closed Won';
    var url = "plugin/Deal/updatestage.php";

    jQuery.ajax(url, {
      type: 'POST',
      data: {
        dealid: dealid,
        stage: stage,
        lostreason: lostreason,
        remark: remark,
        wonreason: wonreason,
        competitorid: competitorid,
        moredetails: moredetails
      },
      success: function(data) {
        var result = jQuery.parseJSON(data);
        jQuery(".stage").css('color', 'black');
        jQuery(".stage").html(stage);

        var usercomment = jQuery('#usercomment').val();
        var html = '<tr><td style="border-bottom:1px solid #EDEDED">' + result.date + '</td><td style="border-bottom:1px solid #EDEDED">' + stage + '</td><td style="border-bottom:1px solid #EDEDED"></td><td style="border-bottom:1px solid #EDEDED">' + usercomment + '</td></tr>';
        jQuery("#history-tb tbody").append(html);

        jQuery('.wizard li.arrow').removeClass("active");
        jQuery('.wizard li.arrow').addClass("completed");

        jQuery('.wizard li.arrow').removeClass("lost");
        jQuery('.wizard li.arrow').removeClass("lost");
        jQuery('.wizard li.arrow').removeClass("deallost");
        jQuery('.wizard li.arrow').removeClass("deallost");

        jQuery('.wizard li.arrow').removeClass("lost").removeClass("deallost");
        jQuery("ul").remove(".menu-list");
        jQuery('#closed-step-dialog').window('close')
      },

      error: function(data) {

      }
    });
  }

  function closeLost()
  {
    var dealid = jQuery('#dealid').val();
    var lostreason = jQuery("#closed-step-dialog #lostreason").val();
    var wonreason = jQuery("#closed-step-dialog #wonreason").val();
    var remark = jQuery("#closed-step-dialog #remark").val();
    var competitorid = jQuery("#closed-step-dialog #competitorid").val();
    var moredetails = jQuery("#closed-step-dialog #moredetails").val();
    // console.log(dealid, lostreason, wonreason, remark, competitorid, moredetails)
    var stage = 'Closed Lost';
    var url = "plugin/Deal/updatestage.php";

    jQuery.ajax(url, {
      type: 'POST',
      data: {
        dealid: dealid,
        stage: stage,
        lostreason: lostreason,
        remark: remark,
        wonreason: wonreason,
        competitorid: competitorid,
        moredetails: moredetails
      },
      success: function(data) {
        var result = jQuery.parseJSON(data);
        jQuery(".stage").css('color', 'black');
        jQuery(".stage").html(stage);

        var usercomment = jQuery('#usercomment').val();
        var html = '<tr><td style="border-bottom:1px solid #EDEDED">' + result.date + '</td><td style="border-bottom:1px solid #EDEDED">' + stage + '</td><td style="border-bottom:1px solid #EDEDED"></td><td style="border-bottom:1px solid #EDEDED">' + usercomment + '</td></tr>';
        jQuery("#history-tb tbody").append(html);

        jQuery('.wizard li.arrow').removeClass("active");
        jQuery('.wizard li.arrow').removeClass("completed");
        jQuery('.wizard li.arrow').removeClass("deallost");

        jQuery('.wizard li').addClass("deallost");
        var last = jQuery(".wizard li.arrow").last();

        last.addClass('lost').removeClass('deallost');
        jQuery("ul").remove(".menu-list");
        jQuery('#closed-step-dialog').window('close')
      },

      error: function(data) {

      }
    });
  }

  jQuery('.wizard li').click(function() {
    /*jQuery(this).prevAll().addClass("completed");
    jQuery(this).nextAll().removeClass("completed");
    jQuery(this).removeClass("completed");*/
  });

  jQuery.saveCommentDialog = function() {

    var comments = jQuery.trim(jQuery('#comments').val());
    var flag_require_comment = jQuery('#flag_require_comment').val()

    if (flag_require_comment === '1') {

      if (comments === '') {
        jQuery("#comments").addClass("requert");
        return false;
      }
    }

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

            var usercomment = jQuery('#usercomment').val();
            var html = '<tr><td style="border-bottom:1px solid #EDEDED">' + result.date + '</td><td style="border-bottom:1px solid #EDEDED">' + status + '</td><td style="border-bottom:1px solid #EDEDED">' + comments + '</td><td style="border-bottom:1px solid #EDEDED">' + usercomment + '</td></tr>';
            jQuery("#history-tb tbody").append(html);

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

              var usercomment = jQuery('#usercomment').val();
              var html = '<tr><td style="border-bottom:1px solid #EDEDED">' + result.date + '</td><td style="border-bottom:1px solid #EDEDED">' + next_status + '</td><td style="border-bottom:1px solid #EDEDED">' + comments + '</td><td style="border-bottom:1px solid #EDEDED">' + usercomment + '</td></tr>';
              jQuery("#history-tb tbody").append(html);

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

          /*console.log(result);
          var usercomment = jQuery('#usercomment').val();
          var html = `<tr>
            <td style="border-bottom:1px solid #EDEDED"></td>
            <td style="border-bottom:1px solid #EDEDED">'+next_status+'</td>
            <td style="border-bottom:1px solid #EDEDED">'+comments+'</td>
            <td style="border-bottom:1px solid #EDEDED">'+usercomment+'</td>
            </tr>`;
          jQuery("#history-tb tbody").append(html);*/

        }

      }
    }

    jQuery('#popup-dialog').window('close')
    selectedElement = null
  }

  jQuery.closeCommentDialog = function() {
    jQuery('#popup-dialog').window('close')
  }

  var popupDialog = jQuery('<div />', {
    id: 'popup-dialog'
  })
  var selectedElement = null;

  jQuery('.wizard li ul li').click(function() {

    selectedElement = this
    var status = jQuery(this).attr("data-status");
    var step = jQuery(this).attr("data-step");
    var a_this = jQuery(this).parent().parent();

    var dealid = jQuery('#dealid').val();
    var url = "plugin/Deal/updatestage.php";
    var flagComment = jQuery('#flagComment').val()

    if (flagComment === '1' && status !== 'Closed') {

      var html = `<div class="container-fluid">
    <div class="row" style="padding:5px;">
      <div class="col-sm-12" style="padding:0px!important">
        <textarea class="form-control" style="padding:0px!important;width:99%;resize:none;" name="comments" id="comments"  rows="5"></textarea>
      </div>

      <div class="col-sm-12 text-center" style="margin-top:10px;padding:0px!important">
        <button type="button" class="btn btn-success btn-xs" onclick="jQuery.saveCommentDialog()" style="height:25px;">บันทึก</button>
        <button type="button" class="btn btn-danger btn-xs" onclick="jQuery.closeCommentDialog()" style="height:25px;">ยกเลิก</button>
      </div>
    </div>
  </div>`;

      jQuery(popupDialog).window({
        title: 'หมายเหตุ',
        width: 400,
        height: 200,
        modal: true,
        maximizable: false,
        minimizable: false
      }).html(html);
      return false;
    }

    if (step == 1) {
      //console.log(status);
      jQuery.ajax(url, {
        type: 'POST',
        data: {
          dealid: dealid,
          stage: status
        },
        success: function(data) {
          var result = jQuery.parseJSON(data);
          //jQuery( ".stage" ).html('');
          jQuery(".stage").css('color', 'black');
          jQuery(".stage").html(status);

          var usercomment = jQuery('#usercomment').val();
          var html = '<tr><td style="border-bottom:1px solid #EDEDED">' + result.date + '</td><td style="border-bottom:1px solid #EDEDED">' + next_status + '</td><td style="border-bottom:1px solid #EDEDED">' + comments + '</td><td style="border-bottom:1px solid #EDEDED">' + usercomment + '</td></tr>';
          jQuery("#history-tb tbody").append(html);
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
            stage: next_status
          },
          success: function(data) {
            var result = jQuery.parseJSON(data);
            jQuery(".stage").css('color', 'black');
            jQuery(".stage").html(next_status);
            var usercomment = jQuery('#usercomment').val();
            var html = '<tr><td style="border-bottom:1px solid #EDEDED">' + result.date + '</td><td style="border-bottom:1px solid #EDEDED">' + next_status + '</td><td style="border-bottom:1px solid #EDEDED">' + comments + '</td><td style="border-bottom:1px solid #EDEDED">' + usercomment + '</td></tr>';
            jQuery("#history-tb tbody").append(html);
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


  });
</script>
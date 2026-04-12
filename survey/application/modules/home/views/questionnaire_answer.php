<!--////////////////ไม่ให้ย้อนกลับมาหน้าเดิมถ้าไปที่หน้า save แล้ว////////////////////////////////////-->
        <script type="text/javascript">
            function noBack(){
                window.history.forward();
            }
             
            noBack();
            window.onload = noBack;
            window.onpageshow = function(evt) { if (evt.persisted) noBack();}
            window.onunload = function() { void (0); }
        </script>
<!-- //////////////////////////////////////////////////////////////////////////////////////////////// -->

 <div class="vertical-center" >
    <div class="container div_box" >
      <div class="row">
        <div class="col-lg-12"> 
          <img src="<?php echo "http://".$_SERVER['HTTP_HOST'].'/moai/'.@$image['imgHeader']['path'].@$image['imgHeader']['attachmentsid'].'_'.@$image['imgHeader']['name'];?>" style="width:100%" alt="" class="index-tab"/>
          <h2><?php echo $TEXT['data']['title'];?></h2>
        </div>
        <form name="myForm" id="myForm" action="<?php echo "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);?>/home/questionnaire_save/" method="post"  id="FromAdd">

        <input type="hidden" value="<?php echo $accountid ?>" name="accountid"> 
	      <input type="hidden" value="<?php echo $templatename ?>" name="templatename"> 
        <input type="hidden" value="<?php echo $templateid ?>" name="templateid"> 
        <input type="hidden" value="<?php echo $smartquestionnaireid ?>" name="smartquestionnaireid"> 
                 
        <div class="col-md-10  col-md-offset-1 form-group">
          <div class="row text-left" id="rg-text">

  
          <div class="col-xs-12  col-md-12">

            <?php $i=1;?>
            
            <?php  foreach ($TEXT['data']['pages'] as $pages => $value) { ?>


              <div class="step-list">
                <div>
                <!--  <center class="tx-box"><?php print_r($value['name']);?></center> -->
                 <?php foreach ($value['elements'] as $elements => $value_elements) { ?>



<!--////////////////////เอาไว้ส่งค่าที่ต้องการ//////////////////////////-->

                <?php if(isset($value_elements['otherText'])){
                  $otherText = $value_elements['otherText'];
                 // print_r($otherText);
                }else{
                  $otherText = "null";
                  //print_r($otherText);
                }?>



                <?php if(!empty($value_elements['isRequired'])){
                  $isRequired = $value_elements['isRequired'];
                  //print_r($isRequired);
                }else{
                  $isRequired = "null";
                  //print_r($isRequired);
                }?>

                 <?php if(!empty($value_elements['choicedetailid'])){
                  $choicedetailid = $value_elements['choicedetailid'];
                  //print_r($isRequired);
                }else{
                  $choicedetailid = "null";
                  //print_r($isRequired);
                }?>

                
                 <input type="hidden" name="choiceid[]" value="<?php print_r($value_elements['choiceid'].",");?><?php print_r($value_elements['type'].",");?><?php print_r($otherText.",");?><?php print_r($isRequired.",");?><?php echo $templateid.",";?><?php echo $accountid.",";?><?php print_r($choicedetailid.",");?>">
                
            

<!--//////////////////////////////////////////////-->
                  <style type="text/css">
                    .alert-blc{color: red;}
                  </style>


                    <?php 
                    if($value_elements['type']=="radiogroup"){
                      $datack = 'other-blc'; 
                    }else if($value_elements['type']=="checkbox"){
                      $datack = 'other-blc'; 
                    }else if($value_elements['type']=="text"){
                      $datack = 'input-blc'; 
                    }else if($value_elements['type']=="dropdown"){
                      $datack = 'drop-blc'; }
                    ?> 

                    <!-- <?php echo "ข้อ ".$value_elements['choiceid'];?> -->
                    <div  class="border-text" data-check="<?php echo $datack; ?>" data-id="" <?php if($value_elements['isRequired']=="true"){ echo "data-req=\"require\""; } ?>>
                      <div class="toppic">
                        <h4>
                          <?php echo "ข้อ ".$i;?> <?php print_r($value_elements['name']);?>
                          <span style="color: red;font-weight: bolder;"><?php if($value_elements['isRequired']=="true"){echo "*";}?></span>

                        </h4>
                      </div>
                      <?php if($value_elements['type']=="radiogroup"){ ?> 

                          <table width="100%" border="0">
                              <tbody>
                                <tr>
                                  <td>
                                    <label>
                                      <span>

                                        <?php if(!empty($value_elements['choices'])) { ?>

                                        <?php $num = array();?>

                                          <?php foreach ($value_elements['choices'] as $choices => $value_choices){?>
                                            <input type="radio" name="choice<?php echo $value_elements['choiceid'];?>[]" id="choice<?php echo $value_elements['choiceid'];?>" value="<?php print_r($choices."|##|");?><?php print_r($value_choices);?>">
                                            <?php print_r($value_choices);?><br>

                                            <?php $num[$choices] = $choices;?>

                                          <?php }?>
                                        <?php }?>
                                        

                                        <?php if(isset($value_elements['otherText'])){?>
                                          <?php if($value_elements['hasOther']=="1"){?>
                                            <input type="radio" name="choice<?php echo $value_elements['choiceid'];?>[]" id="choice<?php echo $value_elements['choiceid'];?>" value="<?php print_r($value_elements['otherTextid']."|##|");?><?php echo $value_elements['otherText']."|##|";?><?php echo 'get_other';?>" data-etc="etc">
                                            <?php print_r($value_elements['otherText']);?>
                                            <br>
                                            <input type="text" name="text<?php echo $value_elements['choiceid'];?>" value="" id="text<?php echo $value_elements['choiceid'];?>">
                                          <?php } ?>
                                        <?php }?>
                                       
                                        
                                      </span>
                                      <span class="alert-blc"></span>
                                  </label>
                                 </td>
                                </tr>
                              </tbody>
                            </table>

                      <?php }elseif($value_elements['type']=="checkbox"){?>

                        <table width="100%" border="0">
                              <tbody>
                                <tr>
                                  <td>
                                    <label>
                                      <span>
                                         
                                        <?php if(!empty($value_elements['choices'])) { ?>

                                        <?php $num = array();?>

                                          <?php foreach ($value_elements['choices'] as $choices => $value_choices){?>
                                            <input type="checkbox" name="choice<?php echo $value_elements['choiceid'];?>[]" id="choice<?php echo $value_elements['choiceid'];?>" value="<?php print_r($choices."|##|");?><?php print_r($value_choices);?>">
                                            <?php print_r($value_choices);?><br>



                                            <?php $num[$choices] = $choices;?>

                                          <?php }?>
                                        <?php }?>
                                        

                                        <?php if(isset($value_elements['otherText'])){?>
                                          <?php if($value_elements['hasOther']=="1"){?>
                                            <input type="checkbox" name="choice<?php echo $value_elements['choiceid'];?>[]" id="choice<?php echo $value_elements['choiceid'];?>" value="<?php print_r($value_elements['otherTextid']."|##|");?><?php echo $value_elements['otherText']."|##|";?><?php echo 'get_other';?>" data-etc="etc">
                                            <?php print_r($value_elements['otherText']);?>
                                            <br>
                                            <input type="text" name="text<?php echo $value_elements['choiceid'];?>" value="" id="text<?php echo $value_elements['choiceid'];?>">
                                          <?php } ?>
                                        <?php }?>

                                      </span>
                                      <span class="alert-blc"></span>
                                  </label>
                                 </td>
                            </tr>
                              </tbody>
                            </table>

                      <?php }elseif($value_elements['type']=="text"){?>

                         <table width="100%" border="0">
                              <tbody>
                                  <tr>
                                    <td>
                                      <label>
                                        <span>
                                          <input type="text" name="choice<?php echo $value_elements['choiceid'];?>" id="choice<?php echo $value_elements['choiceid'];?>" value="">
                                        </span>
                                        <span class="alert-blc"></span>
                                    </label>
                                  </td>
                              </tr>
                              </tbody>
                          </table>

                       <?php }elseif($value_elements['type']=="dropdown"){?>

                          <table width="100%" border="0">
                              <tbody>
                                  <tr>
                                    <td>
                                      <label>
                                        <span>

                                        <?php if(!empty($value_elements['choices'])) { ?>

                                        <?php $num = array();?>

                                         <select onchange="myFunctiondropdown(event)" name="choice<?php echo $value_elements['choiceid'];?>[]" id="choice<?php echo $value_elements['choiceid'];?>">
                                          <option value=""></option>
                                           <?php foreach ($value_elements['choices'] as $choices => $value_choices){?>
                                            <option value="<?php print_r($choices."|##|");?><?php print_r($value_choices);?>"><?php print_r($value_choices);?></option>
                                             <?php $num[$choices] = $choices;?>
                                           <?php }?>
                                          </select>
                                        <?php }?>

                                      
                                   
                                        </span>
                                        <span class="alert-blc"></span>
                                    </label>
                                  </td>
                              </tr>
                              </tbody>
                          </table>



                      <?php }?> 
                    </div>


                   
                 <?php $i++;}?>
               </div>
              </div>
             <?php } ?>
 
            </div>
            </div>
          </div>
  
        <div class="col-xs-12  form-group"> 
              <style>.step{ display:none;}.step1{ display:none;}</style>

              <input type="button" name="" id="prevBtn"  class="btn2" value="<< ย้อนกลับ">
              <input type="button" name="" id="nextBtn" class="btn2" value="ถัดไป >>">


            <div class="target">
              <br><br>
              <input name="action" type="hidden" id="action" value="SaveData" />
              <!-- <button type="submit" class="btn2">บันทึกข้อมูล</button> -->

               <input type="submit" name="submit" id="submitBtn" class="btn2" value="บันทึกข้อมูล">
            </div>
        </div>


 

        </form>
        <img src="<?php echo "http://".$_SERVER['HTTP_HOST'].'/moai/'.@$image['imgFooter']['path'].@$image['imgFooter']['attachmentsid'].'_'.@$image['imgFooter']['name'];?>" style="width:100%" alt="" class="index-tab"/>
    </div>
  </div>

  


<script src="<?php echo "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);?>/assets/js/jquery.js"></script>

<script type="text/javascript">
  function myFunctiondropdown(e) {
    document.getElementById("text_dropdown").value = e.target.value
}
</script>



<script type="text/javascript">

  $(document).ready(function(){
      var pageLoca = window.location.href;
      var oldLoca = localStorage.getItem('iPage');
      var id = window.location.pathname.split( '/' );
      var id1 = id[1];
      var id2 = id[2];
      var templateid = id[4];
      var accountid = id[5];
      console.log(pageLoca);
      console.log(oldLoca);
        if(pageLoca !== oldLoca){
            // window.location.replace(window.location.protocol+"//"+window.location.host+'/'+id1+'/'+id2+'/questionnaire/'+templateid+'/'+accountid);
        }

          localStorage.setItem('iPage', window.location.href);
     
  });

  
</script>

<script type="text/javascript">

/* ------ User Setting ------ */
var iTag = $('.step-list');
var iTagList = $('.step-list > div');
var iNext = $('#nextBtn');
var iPrev = $('#prevBtn');
var iSubmit = $('#submitBtn');
var iCount = 1;
/* ------ End User Setting ------ */

$(document).ready(function(){
    iTag.attr('data-page', '1');
    var iStart = iTag.attr('data-page');
    stepList(iStart,iCount);
})

iNext.click(function() {
    var iPage = iTag.attr('data-page');
    iPage++;
    iTag.attr('data-page', iPage);
    var pPage = iTag.attr('data-page');
    stepList(pPage,iCount);
})

iPrev.click(function(){
    var iPage = iTag.attr('data-page');
    iPage--;
    iTag.attr('data-page',iPage);
    var pPage = iTag.attr('data-page');
    stepList(pPage,iCount);
});

function stepList(iStart,iCount) {
    var i = 1;
    var pStart = parseInt(iStart);
    var pCount = parseInt(iCount);
    var pStop = pStart + pCount;
    var pLength = iTagList.length;
    iTagList.each(function(){
        $(this).show().removeClass('hide');
        if(i < pStart ||  i >= pStop) $(this).hide().addClass('hide');
        i++;
    })
    if(iTagList.last().hasClass('hide')) {
        iNext.show();
        iSubmit.hide();
    }else{
        iNext.hide();
        iSubmit.show();
    }
    if(iTag.attr('data-page') == 1) { 
        iPrev.hide();
    }else{ 
        iPrev.show();
    }
}
</script>

<script type="text/javascript">
$(function(){
    
    $('#submitBtn').click(function(){
        $('.alert-blc').text('');
        var gotoAlert = ".parents('.border-text').find('.alert-blc')";
        $('.border-text').each(function(){
            if($(this).attr('data-check') == "input-blc" && $(this).attr('data-req') == "require") {
                var inputReq = $(this).find('input[type=text]').val();
                var idBox = $(this).attr('data-id');
                if(inputReq == ""){
                    $(this).find('.alert-blc').text(idBox+' กรุณากรอกข้อมูล');
                    localStorage.setItem('error', '1');
                }
            }
            if($(this).attr('data-check') == "other-blc") {
                var otherReq = $(this).find('input[type=text]').val();
                var idBox = $(this).attr('data-id');
                var dataEtc = $(this).find('input[data-etc=etc]').prop('checked');
                if(otherReq == "" && dataEtc == true){
                    $(this).find('.alert-blc').text(idBox+' กรุณากรอกข้อมูล');
                    localStorage.setItem('error', '1');
                }
            }
            if($(this).attr('data-check') == "other-blc" && $(this).attr('data-req') == "require") {
              var dataReq = $(this).find('input:checked').length;
              if(dataReq < 1){
                $(this).find('.alert-blc').text(idBox+' กรุณากรอกข้อมูล');
                localStorage.setItem('error', '1');
              }
            }

            if($(this).attr('data-check') == "drop-blc") {
              var dataReq = $(this).find('select').val();
              if(dataReq ==""){
                $(this).find('.alert-blc').text(' กรุณากรอกข้อมูล');
                localStorage.setItem('error', '1');
              }
            }
        })
        if(localStorage.getItem('error')){
            localStorage.removeItem("error");
            alert('กรุณากรอกข้อมูลให้ครบ');
            return false;
        }
    });
    
});
</script>
















  
 
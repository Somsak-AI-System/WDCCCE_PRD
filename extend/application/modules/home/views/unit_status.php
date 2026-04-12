 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" >
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Unit Status
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="glyphicon glyphicon-list-alt"></i> Unit Status</a></li>
        <!--<li class="active">Dashboard</li>-->
      </ol>
    </section>
    <!-- Content Header (Page header) -->
    <section class="content">
    <div class="box box-default">
    <script type="text/javascript" src="<?php echo site_assets_url('unitmatrix/js/jquery-ui-1.10.4.custom.min.js'); ?>"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo site_assets_url('unitmatrix/css/metro-blue/easyui.css'); ?>">
    <script type="text/javascript" src="<?php echo site_assets_url('unitmatrix/js/jquery.easyui.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo site_assets_url('unitmatrix/js/dylay.js'); ?>"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo site_assets_url('unitmatrix/css/custompage.css'); ?>">
    <link href="<?php echo site_assets_url('unitmatrix/css/font-awesome/css/font-awesome.css'); ?>" rel="stylesheet" />

    <style>
    .hstat {padding:1em;}
    #dylay {margin: 0 1em; padding: 0; list-style: none; width: 99%; float:left; border:none;/*height:32em; overflow-y:Scroll*/}
    div.floor {
      float:left;
      width: 99% !important;
      clear: both;
      display:inline-block; 
      height:auto;
      padding:5px;
      border-bottom:1px dotted #B5D0EA;
    }
    
    .floorplan {margin:0 0 0 0em; padding: 0; list-style: none !important;float:left;width:90%;}
    .floorplan li { float: left; border-radius:4px; color:#FFF;font-weight:700;padding: 2px 3px;font-size: 0.8em !important; width:9em; border:1px solid #ededed;font-family:arial !important;line-height:1.4em; cursor:pointer;color:#FFF; }
    .floorplan li a{color:#FFF;}

    .floorplan li i{
      float: right;
      position: absolute!important;
      bottom: -2px;
      right: -2px;
      z-index: 999;
    }

    p.labelFloor1{
      float:left;
      font-size: 1em;
      text-align:center;
      width:2.5em;
      height:2.5em;
      clear:left;
      vertical-align:middle !important; 
      line-height:2.4em;
      margin:0 1em 0 0;
      padding:0 0 0 0;
      font-family:arial;
      color:#305887;
      border-radius: 50%;
      border:1px solid #428BCA;      
    }
    p.labelFloor_footer{
      float:right;
      font-size: 1em;
      text-align:center;
      width:2.5em;
      height:2.5em;
      /*clear:left;*/
      cursor :pointer;
      vertical-align:middle !important; 
      line-height:2.4em;
      margin:0 1em 0 0;
      padding:0 0 0 0;
      font-family:arial;
      color:#305887;
      border-radius: 50%;
      border:1px solid #428BCA;      
    }

    .pLeft{float:left; text-align:left; }
    .pRight{float:right; text-align:right;}
    .pCenter{ text-align:center; clear:both; width:100%;float:left;}
     #FloorFilter { margin: 0 1em; padding: 0; list-style: none; }
     #FloorFilter li { float: left; margin: 2px; border-radius: 5px; padding: 2px 5px; text-align:center; font-size: 0.8em; border:1px solid #000; }
    .status {
            clear: both;
            color: #FFFFFF;border:none;
    }
    .area{ text-align:center ; border:none;}
    .status{ text-align:center ;}
    .stat{text-align:center;   float:left; }
    .stat{
      width: 15%;
      -webkit-transform: skew(-30deg); 
      -moz-transform: skew(-30deg);
      -o-transform: skew(-30deg);  
      transform: skew(-30deg);
    }
    .stat p { 
      margin:0 0 0 0 !important;
      line-height:2em;
      /*font-size:1.2em;*/ 
      -webkit-transform: skew(30deg) !important;
      -moz-transform: skew(30deg) !important;
      -o-transform: skew(30deg) !important;
      transform: skew(30deg) !important; 
    }
    .stat a { color:#FFF;}
    .Default , .Default a{background-color: #ECF0F1 ; color: #000000 !important; }
    .None  ,.None a {background-color: #BDC3C7 !important;Color:#454545;}
    .Closed  ,.Closed a {background: #78D33C !important;}
    .Processing ,.Processing a{background-color: #FD5532 !important;}
    .Dealy, .Dealy a {background-color: #c10000 !important;}
    .Book ,.Book a{
        background-color: #F891C8 !important;
    }
    .Contractor, .Contractor a{background-color: #ECF0F1 ; color: #000000 !important; }
    .Customer, .Customer a{background-color: #ECF0F1 ; color: #000000 !important; }
    .Open , .Open a {background-color: #4583EB !important;}
    </style>

   

    <div id="w" >

    <div class="header">
    <h1>Search</h1>

    <div class="form-input" style="width:300px;">
      <label>Projects <span style="color: red">*</span></label>
      <div class="clrBoth"></div>
      <input type="hidden" id="pj_project_type" name="pj_project_type">
      <select id="branchid" name="branchid" class="easyui-combogrid"  style="width:200px;"  >
      </select>
          <!-- Btn Quick Create-->
          <div class="btn-group"style="padding-left:10px ;width: 28px !important; height: 25.08px !important;font-size: 9.5px !important;">
            <button type="button" class="btn_s btn-info dropdown-toggle" data-toggle="dropdown" style="width: 28px !important;height: 25.08px !important; margin:0 !important;background: #5bc0de !important;">
              <i class="glyphicon glyphicon-plus"></i>
            </button>
            <ul class="dropdown-menu" style="min-width:0 !important">
              <li><a href="#" onclick="quick_create_branch('branch','Projects','add');">Quick Create</a></li>
              <li><a href="#" onclick="quick_create_branch('branch','Projects','edit');">Quick Edit</a></li>
            </ul>
          </div>
      
    </div>

    <div class="form-input" style="width:300px;">
      <label>Building </label>
      <div class="clrBoth"></div>
      <select  id="buildingid" name="buildingid" class="easyui-combogrid" style="width:200px;" >
      </select>
          <!-- Btn Quick Create-->
            <div class="btn-group"style="padding-left:10px;width: 28px !important; height: 25.08px !important;font-size: 9.5px !important;">
              <button type="button" class="btn_s btn-info dropdown-toggle" data-toggle="dropdown" style="width: 28px !important;height: 25.08px !important; margin:0 !important;background: #5bc0de !important;">
                <i class="glyphicon glyphicon-plus"></i>
              </button>&nbsp;
              <ul class="dropdown-menu" style="min-width:0 !important">
                <li><a href="#" onclick="quick_create_building('building','Building','add');">Quick Create</a></li>
                <li><a href="#" onclick="quick_create_building('building','Building','edit');">Quick Edit</a></li>
              </ul>
            </div>

    </div>

    <div class="form-input" style="width:130px;height:56.88px;">
      <label>Floor</label>
      <div class="clrBoth"></div>
      <select id="floor" name="floor" class="easyui-combogrid"  style="width:90px;"  >
      </select>
    </div>

    <div class="form-input radio-group" style="width:150px;height:56.88px;">
      
      <label class="radio">
          <input type="radio" name="inspecttype" value="Contractor" checked>
          <span>Contractor</span>
      </label>
      <label class="radio">
          <input type="radio" name="inspecttype" value="Customer">
          <span>Customer</span>
      </label>

    </div>

    <div class="form-input" style="min-width:100px;">
      <label></label>
      <div class="clrBoth"></div>
      <button id="Reload" class="btn_s btn-default_search"><i class="glyphicon glyphicon-search"></i> Search</button>
    </div>

    <div class="form-input" style="min-width:100px;">
      <label></label>
      <div class="clrBoth"></div>
    </div>

    </div> <!-- Div header-->
    <div class="clrBoth" style="height:5px;"></div>

    <div class="nav-list" >

    <div id="actionwindow" Title="Unit Management"style="display:none;"></div>

    <div  id="filters" class="hstat" style="float:left;width:80%;margin-left:1em;">
      <div class="stat Default" style="width:8% !important"><a href="#" data-filter="*"><p id="All">All</p></a></div>
      <div class="stat None"><a href="#" data-filter=".None"><p id="None">None</p></a></div>
      <div class="stat Open" ><a href="#" data-filter=".Open "><p id="Open">Open</p></a></div>
      <div class="stat Processing" ><a href="#" data-filter=".Processing "><p id="Processing">Processing</p></a></div>
      <div class="stat Closed" ><a href="#" data-filter=".Closed "><p id="Closed">Closed</p></a></div>
      <!-- <div class="stat Dealy" ><a href="#" data-filter=".Dealy "><p id="Dealy">Delay</p></a></div> -->     
    </div>

    <ul id="dylay">
      
    </ul>

    </div> <!-- nav-list-->
    <div style="clear:both; height:50px;"></div>

    </div><!-- #w-->

        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
          </div>
        </div>
      </div>
      <div >
    

    </div>
  </section>
  <!-- Content Wrapper. Contains page content -->
  </div>

<style>
.radio {
  margin: 10px 0;
  display: block;
  cursor: pointer;
}
.radio input {
  display: none;
}
.radio input + span {
  line-height: 22px;
  height: 22px;
  padding-left: 22px;
  display: block;
  position: relative;
}
.radio input + span:not(:empty) {
  padding-left: 30px;
}
.radio input + span:before, .radio input + span:after {
  content: "";
  width: 18px;
  height: 18px;
  display: block;
  border-radius: 50%;
  left: 0;
  top: 0;
  position: absolute;
}
.radio input + span:before {
  background: #d1d7e3;
  transition: background 0.2s ease, -webkit-transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 2);
  transition: background 0.2s ease, transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 2);
  transition: background 0.2s ease, transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 2), -webkit-transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 2);
}
.radio input + span:after {
  background: #fff;
  -webkit-transform: scale(0.78);
          transform: scale(0.78);
  transition: -webkit-transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.4);
  transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.4);
  transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.4), -webkit-transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.4);
}
.radio input:checked + span:before {
  -webkit-transform: scale(1.04);
          transform: scale(1.04);
  background: #5d9bfb;
}
.radio input:checked + span:after {
  -webkit-transform: scale(0.4);
          transform: scale(0.4);
  transition: -webkit-transform 0.3s ease;
  transition: transform 0.3s ease;
  transition: transform 0.3s ease, -webkit-transform 0.3s ease;
}
.radio:hover input + span:before {
  -webkit-transform: scale(0.92);
          transform: scale(0.92);
}
.radio:hover input + span:after {
  -webkit-transform: scale(0.74);
          transform: scale(0.74);
}
.radio:hover input:checked + span:after {
  -webkit-transform: scale(0.4);
          transform: scale(0.4);
}
</style>

<style type="text/css">

.dropdown:hover .dropdown-menu {
    display: block;
}

.sub_li li {
	color: #777;
	width : 7em;
	float : initial;
	border: 0px solid #fff;
}
.sub_li li:hover{
	background-color:#d3d3d3;
    color: #000;
}
.fa-clock-o{
  color: red !important;
}

</style>


<script>
  $(document).ready(function() {    
    
    getBranch();

    $( "#Reload" ).click(function() {   
      getunitmatrix();
    });

    //Reload
    setInterval(function() {
      var par_branchid= jQuery("#branchid").combogrid('getValue');
      var par_building= jQuery("#buildingid").combogrid('getValue');
      var pj_project_type=jQuery('#pj_project_type').val();
      if(par_branchid !='' && par_building !='' && pj_project_type != 'แนวราบ'){
          getunitmatrix();     
      }else if(par_branchid !='' && pj_project_type == 'แนวราบ'){
          getunitmatrix();
      }
    }, 300000);
    
    $("#inspecttype1").attr('checked', 'checked');   
    $('.dropdown-toggle').dropdown()

    $('#loader').fadeOut();

    $(document).on('click', '.btn_import', function () {
    var URL = '<?php echo site_url("import")?>';
    window.open(URL, '_blank');
   });

  });



function getBranch(){
  
  $('#branchid').combogrid({
        panelWidth:350,
        idField:'branchid',
        textField:'branch_name',
        mode:'remote', 
        fitColumns:true,           
        url: "<?php echo site_url('home/getbranch'); ?>",                         
        columns: [[
           {field:'branch_name',title:'Name',width:250},
           {field:'pj_status',title:'Status',width:100},   
        ]]
      ,"onSelect":function(index,row){
          jQuery('#pj_project_type').val(row.pj_project_type);
          Getbuilding();
     }     
     /*,"onChange":function(){  
       Getbuilding();
     }    */ 
  });

  //var branchid = '<?php //echo $_GET['branchid']; ?>';

  /*if(branchid !='')
  {
     jQuery('#branchid').combogrid('setValue',branchid);
     jQuery('#branchid').trigger('change');
  }*/

}

function  Getbuilding(){
    var branchid =   jQuery('#branchid').combogrid('getValue');
    jQuery('#buildingid').combogrid({
          panelWidth:350,
          idField:'buildingid',
          textField:'building_name',
          mode:'remote', 
          fitColumns:true,           
          url: "<?php echo site_url('home/getbuilding_group');?>",
          queryParams:{branchid:branchid},
          columns: [[
              {field:'buildingid',title:'ID',hidden:true,width:20},   
              {field:'building_name',title:'Name',width:250},
              {field:'bd_status',title:'Status',width:100},   
          ]],
        "onChange":function(){  
          GetFloor();
        } 
    });

}

function  GetFloor(){
  var branchid = jQuery('#branchid').combogrid('getValue');
  var buildingid = jQuery('#buildingid').combogrid('getValue');
  var pj_project_type = jQuery('#pj_project_type').val();
    jQuery('#floor').combogrid({
      panelWidth:100,
      idField:'floor_no',
      textField:'floor_no',
      mode:'remote', 
      fitColumns:true, 
      multiple:true,          
      url: "<?php echo site_url('home/getfloor');?>",
      queryParams:{branchid:branchid,buildingid:buildingid,pj_project_type:pj_project_type}, 
        columns: [[
          {field:'floorno',checkbox:true,title:'Select',width:60},
          {field:'floor_no',title:'Floor',width:150},   
        
        ]]
  
    });
}

function getunitmatrix()
{  
  var par_branchid= jQuery("#branchid").combogrid('getValue');
  var par_building= jQuery("#buildingid").combogrid('getValue');
  var par_floorno= jQuery("#floor").combogrid('getValues');
  var pj_project_type=jQuery('#pj_project_type').val();
  var par_inspecttype= $('input[name="inspecttype"]:checked').val();
  par_floorno = par_floorno+'';

  $('#loader').fadeIn();

  if(par_branchid=="" || (par_building =="" && pj_project_type!='แนวราบ')){
    jQuery.messager.alert('Warning','Please select project and building.','warning');
    $('#loader').fadeOut();
    return false;
  }

    var url ='<?php echo site_url('home/getunitstatus');?>';
    jQuery.ajax(url, {
          type: 'POST',
          dataType: 'json',
          data:{
            branchid  :par_branchid,
            buildingid:par_building,
            floorno   :par_floorno,
            inspecttype :par_inspecttype
          },
        success: function (result) {   
          jQuery("#All").html('All ('+result.total+')');
          jQuery("#None").html('None ('+result.totalNone+')');
          jQuery("#Closed").html('Closed ('+result.totalClosed+')');
          jQuery("#Reserved").html('Processing ('+result.totalReserved+')');
          /*jQuery("#Dealy").html('Delay ('+result.totalDealy+')');*/
          jQuery("#Open").html('Open ('+result.totalOpen+')');
          jQuery("#Processing").html('Processing ('+result.totalProcessing+')');
          
          GenUnitMatrix(result.rows);

           },
            error: function (msg) {
              console.log(msg);
              $('#loader').fadeOut();
            }
        });

}
function genFloor(floorlist)
{
   floors=[];
   var $floors = jQuery('#Floorfilter');
   jQuery.each(floorlist, function(i, item) {
     floors.push('<li class="'+item.floor+'"  data-foo="'+item.floor+'"><a  href="#" data-filter=".'+item.floor+' "> '+item.floor+'</a></li>');

   });
  $floors.append( floors.join('') );
}

function GenUnitMatrix(unitdata){
  var items = [];
  var floor_no = '';
  var $dylay = jQuery('#dylay');
  $dylay.empty();
  $( "floor" ).remove( ".floor" );
  $( "ul" ).remove( ".floorplan" );

  var max_row = unitdata.length-1;
    //console.log(unitdata);
    jQuery.each(unitdata, function(i, item) {
        
        if(floor_no=='')
        {
         items.push('<div class="floor"  ><p class="labelFloor1"  >'+item.floor_no+'</p><ul id="Floor'+item.floor_no+'" class="floorplan"  >'); 
        }
        else if(floor_no!='' && floor_no !=item.floor_no){
         items.push('</ul><p class="labelFloor_footer" onclick="add_product('+item.branchid+','+item.buildingid+','+floor_no+');" title="Create Unit Floor '+floor_no+' "><i class="glyphicon glyphicon-plus"></i></p></div> <div class="floor"  ><p class="labelFloor1"  >'+item.floor_no+'</p><ul id="Floor'+item.floor_no+'" class="floorplan "  >');
        } //<i class="glyphicon glyphicon-plus"></i>

        //Check Status
        if(item.pj_status == 'Active'){
          var shot_menu = '<ul class="dropdown-menu sub_li" style="min-width: auto;" id="menu'+item.productid+'"><li onclick="quick_unit_detail(\'view\','+item.productid+');">View Unit</li><li onclick="quick_unit_edit(\'edit\','+item.productid+');">Edit Unit</li><li onclick="quick_inspector(\'add\','+item.productid+',\''+item.inspecttype+'\');">Inspection</li></ul>';
        }else{
        	var shot_menu = '<ul class="dropdown-menu sub_li" style="min-width: auto;" id="menu'+item.productid+'"><li onclick="quick_unit_detail(\'view\','+item.productid+');">View Unit</li><li onclick="quick_unit_edit(\'edit\','+item.productid+');">Edit Unit</li></ul>';
        }

        if(item.dealy == 'Dealy'){
          items.push('<li class="'+item.unit_status+' '+item.dealy+' dropdown" data-foo="'+item.floor_no+'"><a href="#" data-toggle="dropdown" class="dropdown-toggle"><span class="pLeft" >'+item.productname+'</span> <span class="pRight" >'+item.house_no+'</span><span class="pCenter">'+item.unit_size+'</span></a>'+shot_menu+'<i class="fa fa-lg fa-clock-o"></i></li>');
        }else{
          items.push('<li class="'+item.unit_status+' dropdown" data-foo="'+item.floor_no+'"><a href="#" data-toggle="dropdown" class="dropdown-toggle"><span class="pLeft" >'+item.productname+'</span> <span class="pRight" >'+item.house_no+'</span><span class="pCenter">'+item.unit_size+'</span></a>'+shot_menu+'</li>');
        }
                
        floor_no = item.floor_no;
        if(max_row == i){
          items.push ('</ul><p class="labelFloor_footer" onclick="add_product('+item.branchid+','+item.buildingid+','+item.floor_no+');" title="Create Unit Floor '+item.floor_no+'" ><i class="glyphicon glyphicon-plus"></i></p>');
        }

    });// close each()
    //console.log(items);

    $dylay.append( items.join(''));

    jQuery.each(unitdata, function(i, item) {
      var $dylayFloor = jQuery('#Floor'+item.floor_no);
  
      $dylayFloor.dylay({
          speed: 1500,
          easing: 'easeOutElastic',
          selector: '>li'
      });
    });

    // var $dylayFloor = jQuery('.floorplan');    
    jQuery('#filters a').on('click', function () {    
      var $dylayFloor = jQuery('.floorplan');
          $dylayFloor.dylay('filter', jQuery(this).data('filter')); 
          return false;
    });

    $('#loader').fadeOut();
}


function get_unitdetail(productid,productcode){
  var URL = "unit_management.php?productid="+productid; 
   //console.log(productid);
   jQuery('#actionwindow').window({
        title: "Unit : "+productcode,
        width: '600',
        height: '370',
        closed: false,
        cache: false,
        href: URL,
        modal: true
  }); 
}


function quick_unit_edit(action,productid){
  //alert(action);
  var URL = "<?php echo site_url('components/quick_view/edit_product'); ?>/"+productid;

  jQuery('#actionwindow').window({
        title: "Product Detail",
        width: '850',
        height: '280',
        closed: false,
        cache: false,
        href: URL,
        modal: true
  }); 
}

function quick_inspector(action,productid,inspecttype){
  var URL = "<?php echo site_url('inspection/quick_mnt'); ?>/"+productid+"/"+inspecttype;
  window.open(URL, '_blank');
}

function quick_unit_detail(action,productid){
	//alert(action);
  var URL = "<?php echo site_url('components/quick_view/detail_product'); ?>/"+productid;

  jQuery('#actionwindow').window({
        title: "Product Detail",
        width: '900',
        height: '550',
        closed: false,
        cache: false,
        href: URL,
        modal: true
  }); 

}

function add_product(branchid,building,floor_no){
  //alert(branchid+"/"+building+"/"+floor_no);
  var URL = "<?php echo site_url('components/quick_view/quick_create_unit');?>/"+branchid+"/"+building+"/"+floor_no

  jQuery('#actionwindow').window({
        title: "Quick Create Unit",
        width: '540',
        height: '380',
        closed: false,
        cache: false,
        href: URL,
        modal: true
  });

}

function quick_edit(module,label){
  jQuery('#actionwindow').window({
        title: "Quick Edit : "+label,
        width: '600',
        height: '370',
        closed: false,
        cache: false,
        href: URL,
        modal: true
  }); 

}

function quick_create_branch(module,label,action){

  var URL = "<?php echo site_url('components/quick_view/quick_create_branch'); ?>";

  if(action == 'edit'){
    var par_branchid= jQuery("#branchid").combogrid('getValue');
    if(par_branchid == ""){
      jQuery.messager.alert('Warning','Please select project.','warning');
      return false;
    }
    var URL = "<?php echo site_url('components/quick_view/quick_create_branch'); ?>/"+par_branchid;
  }

  jQuery('#actionwindow').window({
    title: "Quick Create : "+label,
    width: '500',
    height: '230',
    closed: false,
    cache: false,
    href: URL,
    modal: true
  }); 
}

function quick_create_building(module,label,action){
  
  var URL = "<?php echo site_url('components/quick_view/quick_create_building/'); ?>";
  if(action == 'edit'){
    var par_building= jQuery("#buildingid").combogrid('getValue');
    var par_branchid= jQuery("#branchid").combogrid('getValue');
    
    if(par_building == ""){
      jQuery.messager.alert('Warning','Please select building.','warning');
      return false;
    }else if(par_building == "0"){
      jQuery.messager.alert('Warning',"Can't edit building",'warning');
      return false;
    }
    var URL = "<?php echo site_url('components/quick_view/quick_create_building'); ?>/"+par_building+'/'+par_branchid;
  }
  jQuery('#actionwindow').window({
    title: "Quick Create : "+label,
    width: '540',
    height: '300',
    closed: false,
    cache: false,
    href: URL,
    modal: true
  }); 
}

function quick_create(module,label){
  jQuery('#actionwindow').window({
        title: "Quick Create : "+label,
        width: '600',
        height: '370',
        closed: false,
        cache: false,
        href: URL,
        modal: true
  }); 

}

</script>
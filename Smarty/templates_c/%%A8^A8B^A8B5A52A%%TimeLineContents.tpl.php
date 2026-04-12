<?php /* Smarty version 2.6.18, created on 2026-04-10 07:52:39
         compiled from TimeLineContents.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'getTranslatedString', 'TimeLineContents.tpl', 537, false),)), $this); ?>
<!-- <script type='text/javascript' src="asset/js/jquery.mixitup.js"></script> -->
<script type='text/javascript' src="asset/js/mixitup.min.js"></script>

<script type='text/javascript' src='include/js/Mail.js'></script>
<?php if ($this->_tpl_vars['SinglePane_View'] == 'true'): ?>
	<?php $this->assign('return_modname', 'DetailView'); ?>
<?php else: ?>
	<?php $this->assign('return_modname', 'CallRelatedList'); ?>
<?php endif; ?>


<?php echo '
<script type="text/javascript">
	jQuery(function(){

	  var sortOrder = \'asc\',
	  $toggleSort = jQuery(\'.toggle-sort\');
	  $toggleSort.on(\'click\', function() {
	    switch (sortOrder) {
	      case \'asc\':
	        sortOrder = \'desc\';
	        //textbottom = \'Oldest first&nbsp;<img src="themes/softed/images/sort-up.png" class="sort-arrow" style="width: 20px; height: 20px;border-radius: 50%;vertical-align: text-bottom;">\';
	        //filter = \'default:desc\';
	        jQuery(\'.sort-desc\').css(\'display\',\'none\');
	        jQuery(\'.sort-asc\').css(\'display\',\'unset\');
	        break;
	      case \'desc\':
	        sortOrder = \'asc\';
	        //textbottom = \'Newest first&nbsp;<img src="themes/softed/images/sort-down.png" class="sort-arrow" style="width: 20px; height: 20px;border-radius: 50%;vertical-align: text-bottom;">\';
	        //filter = \'default:asc\';
	        jQuery(\'.sort-asc\').css(\'display\',\'none\');
	        jQuery(\'.sort-desc\').css(\'display\',\'unset\');
	        break;
	    }

	  });

	 	var containerEl = document.querySelector(\'.container1\');
        var mixer = mixitup(containerEl);

	  	jQuery(\'input[name="namefield"]\').click(function() {
	        var namefield;
	        $(\'.timelinetable\').css(\'display\',\'none\');
	        $.each($("input[name=\'namefield\']:checked"), function(){            
	            namefield = $(this).val();
	            $(\'.\'+namefield).css(\'display\',\'block\');
	        });
	    });

	    jQuery(\'input[name="nameuser"]\').click(function() {
	        var nameuser;
	        $(\'.timelinetable\').css(\'display\',\'none\');
	        $.each($("input[name=\'nameuser\']:checked"), function(){            
	            nameuser = $(this).val();
	            $(\'.\'+nameuser).css(\'display\',\'block\');
	        });
	    });

	    jQuery(\'.bg-popup\').click(function() {

	    	jQuery(\'.bg-popup\').css(\'display\',\'none\');
	    	var popup_filter = document.getElementById("popup_filter");
	        if (popup_filter.style.display === "block") {
	            popup_filter.style.display = "none";
	            popup_filter.style.width = "350px";
	        }
	    });
	});    

    function PopupFilter() {
                
        var popup_filter = document.getElementById("popup_filter");

        if (popup_filter.style.display === "block") {
            popup_filter.style.display = "none";
            popup_filter.style.width = "350px";
        } else {
            popup_filter.style.display = "block";
            jQuery(\'.bg-popup\').css(\'display\',\'block\');
        }


    }
    function selectallfield() {
        $(\'input[name="namefield"]\').prop(\'checked\',true);
        var namefield;
        $(\'.timelinetable\').css(\'display\',\'none\');
        $.each($("input[name=\'namefield\']:checked"), function(){            
            namefield = $(this).val();
            $(\'.\'+namefield).css(\'display\',\'block\');
        });
    }

    function unselectallfield(){
        $(\'input[name="namefield"]\').prop(\'checked\',false);
        var namefield;
        $(\'.timelinetable\').css(\'display\',\'none\');
        $.each($("input[name=\'namefield\']:checked"), function(){            
            namefield = $(this).val();
            $(\'.\'+namefield).css(\'display\',\'block\');
        });
    }

    function selectalluser() {
        $(\'input[name="nameuser"]\').prop(\'checked\',true);
        var nameuser;
        $(\'.timelinetable\').css(\'display\',\'none\');
        $.each($("input[name=\'nameuser\']:checked"), function(){            
            nameuser = $(this).val();
            $(\'.\'+nameuser).css(\'display\',\'block\');
        });
    }

    function unselectalluser(){
        $(\'input[name="nameuser"]\').prop(\'checked\',false);
        var nameuser;
        $(\'.timelinetable\').css(\'display\',\'none\');
        $.each($("input[name=\'nameuser\']:checked"), function(){            
            nameuser = $(this).val();
            $(\'.\'+nameuser).css(\'display\',\'block\');
        });
    }

    function myProducts() {
        var input, filter, div, label, span, i, txtValue;
        input = document.getElementById("productsInput");
        filter = input.value.toUpperCase();
        div = document.getElementById("fieldUL");
        label = div.getElementsByTagName("label");

        for (i = 0; i < label.length; i++) {
            span = label[i].getElementsByTagName("span")[0];
            txtValue = span.textContent || span.innerText;
            // console.log(txtValue);
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                label[i].style.display = "";
            } else {
                label[i].style.display = "none";
            }
        }
        filter_user = input.value.toUpperCase();
        div_user = document.getElementById("usersUL");
        label_user = div_user.getElementsByTagName("label");

        for (i = 0; i < label_user.length; i++) {
            span_user = label_user[i].getElementsByTagName("span")[0];
            txtValue = span_user.textContent || span_user.innerText;
            // console.log(txtValue);
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                label_user[i].style.display = "";
            } else {
                label_user[i].style.display = "none";
            }
        }
    }

    
</script>
'; ?>


<?php echo '
<style type="text/css">
	#popup_filter {
	    display: none;
	    width: 350px;
	    height: auto;
	    margin-right: 0px;
	    position: absolute;
	    padding: 0;
	    text-align: left;
	    z-index: 50;
	    box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.2);
	    background-color: #ffffff;
	    -webkit-transition: all 0.5s ease;
	    -moz-transition: all 0.5s ease;
	    -o-transition: all 0.5s ease;
	    transition: all 0.5s ease;
	    padding-bottom: 50px;
	    margin-left: calc(50px + 100px);
	    border-radius: 5px;
	    z-index: 2;
	}
	.fillter_footer {
		position: absolute;
		width: 100%;
		bottom: 0;
	    left: 0;
	    margin-bottom: 10px;
	    margin-top: 10px;
	}
	.search {
	    width: 100%;
	    position: relative;
	    display: flex;
	}
	.search input[type="text"] {
	    width: 100%;
	    box-sizing: border-box;
	    border: 1px solid #ccc;
	    border-radius: 4px;
	    font-size: 11px;
	    font-family: PromptMedium;
	    background-color: white;
	    /* background-image: url(searchicon.png); */
	    background-image: url(themes/softed/images/search-grey.png);
	    background-position: 10px 10px;
	    background-repeat: no-repeat;
	    padding: 10px 15px 10px 40px;
	    -webkit-transition: width 0.4s ease-in-out;
	    transition: width 0.4s ease-in-out;
	    background-size: 15px;
	}
	.checkbox input[type="checkbox"]:checked ~ label::before {
        color: #ffffff;
        background-color: #e97126;
    }

    .checkbox input[type="checkbox"]:checked ~ label::after {
        -webkit-transform: rotate(-45deg) scale(1);
        transform: rotate(-45deg) scale(1);
    }
	.pd-r0{
		padding-right: 0px !important;
	}
	.grey_color {
	    color: #ededed;
	}
	.container2 {
	    display: block;
	    position: relative;
	    padding-left: 25px;
	    margin-bottom: 12px;
	    cursor: pointer;
	    font-size: 11px;
	    -webkit-user-select: none;
	    -moz-user-select: none;
	    -ms-user-select: none;
	    user-select: none;
	    font-family: PromptMedium;
	    color: #2b2b2b;
	    font-weight: 100;
	}
	.container2 input {
	    position: absolute;
	    opacity: 0;
	    cursor: pointer;
	    height: 0;
	    width: 0;
	}
	.checkmark {
	    position: absolute;
	    top: 0;
	    left: 0;
	    height: 15px;
	    width: 15px;
	    background-color: #eee;
	}
	.container2 input:checked ~ .checkmark {
	    background-color: #fef0e7;
	    border: 1px solid #e97126;
	}
	.container2 .checkmark:after {
	    left: 3px;
	    top: -1px;
	    width: 4px;
	    height: 9px;
	    /* border: solid #018ffb; */
	    border: solid #e97126;
	    border-width: 0 3px 3px 0;
	    -webkit-transform: rotate(45deg);
	    -ms-transform: rotate(45deg);
	    transform: rotate(45deg);
	    box-sizing: unset;
	}

	.checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    /* Show the checkmark when checked */
    .container2 input:checked ~ .checkmark:after {
        display: block;
    }

	.row {
		padding: 10px 0px 10px 0px;
	    margin-left: 15px !important;
	    margin-right: 15px !important;
	}
	.col-sm-6 {
	    width: 50%;
	    float: left;
	    position: relative;
   	 	min-height: 1px;
    	padding-left: 15px;
    	padding-right: 15px;
	}
	.col-sm-12 {
    	width: 100%;
    	float: left;
    	position: relative;
	    min-height: 1px;
	    padding-left: 15px;
	    padding-right: 15px;
	}
	.scrollbar{
		overflow-x: hidden;
    	overflow-y: auto;
	}
	.btn {
	    display: inline-block;
	    margin-bottom: 0;
	    font-weight: normal;
	    text-align: center;
	    vertical-align: middle;
	    -ms-touch-action: manipulation;
	    touch-action: manipulation;
	    cursor: pointer;
	    background-image: none;
	    border: 1px solid transparent;
	    white-space: nowrap;
	    padding: 6px 12px;
	    font-size: 14px;
	    line-height: 1.42857143;
	    border-radius: 4px;
	    -webkit-user-select: none;
	    -moz-user-select: none;
	    -ms-user-select: none;
	    user-select: none;
	}
	.btnclear{
		color: #e97126;
	    font-family: PromptMedium;
	    background-color: #ffffff;
	    font-weight: 400;
	    font-size: 11px;
	    border: 1px #e97126 solid;
	}
	.btnshowresuits {
	    background-color: #e97126;
	    border-color: #e97126;
	    color: #ffffff;
	    font-size: 11px;
	    font-family: PromptMedium;
	}
	.textheading{
		font-size: 12px;
    	font-family: \'PromptMedium\';
	}
</style>

<style type="text/css">
	.show {
        display: block !important;
    }
	.dropdown-content {
        display: none;
        position: absolute;
        background-color: #fff;
        min-width: 160px;
        overflow: auto;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown a:hover {
        background-color: #ededed;
    }
	.toggle-sort{
		height: 35px;
		width: 130px;
		border: 0;
		background-color: #fff;
		cursor: pointer;
		border-radius: 5px;
		color: #6d6d6d;
		font-family: \'PromptMedium\';
		font-size: 14px;
		z-index: 2;
		position: sticky;
	}
	.toggle-sort:hover{
		background-color: rgb(247, 247, 247);
	}
	.toggle-sort:active{
		background-color: #EDEDED;
	}
	.filter-by{
		height: 35px;
		width: 110px;
		border: 0;
		background-color: #fff;
		cursor: pointer;
		border-radius: 5px;
		color: #6d6d6d;
		font-family: \'PromptMedium\';
		font-size: 14px;
		z-index: 2;
		position: sticky;
	}
	.filter-by:hover{
		background-color: rgb(247, 247, 247);
	}
	.filter-by:active{
		background-color: #EDEDED;
	}	
</style>
'; ?>


<div class="controls" style="margin-left: 10px">
  <button class="toggle-sort sort-desc" data-sort="default:desc">Newest first&nbsp;<img src="themes/softed/images/sort-down.png" class="sort-arrow" style="width: 20px; height: 20px;border-radius: 50%;vertical-align: text-bottom;"></button>
  <button class="toggle-sort sort-asc" data-sort="default:asc" style="display: none">Oldest first&nbsp;<img src="themes/softed/images/sort-up.png" class="sort-arrow" style="width: 20px; height: 20px;border-radius: 50%;vertical-align: text-bottom;"></button>

  <button class="filter-by" onclick="PopupFilter();">Filter by&nbsp;<img src="themes/softed/images/filter-by.png" class="sort-arrow" style="width: 20px; height: 20px;border-radius: 50%;vertical-align: text-bottom;"></button>
</div>

<div id="popup_filter" class="">
    <div id="sidebarcontents">
        <div class="" style="padding: 2px;">
            <div class="row" style="margin-left: 0px; margin-right: 0px;">
            	<div class="search">
                	<input type="text" class="searchTerm" id="productsInput" onkeyup="myProducts()" placeholder="Search">
            	</div>
        	</div>
        </div>
        
        <div class="col-sm-12 scrollbar" style="padding: 2px;padding-bottom: 25px; height: 400px; overflow-x: hidden;overflow-y: auto;">        
            <!-- Fields -->
            <div class="row">
                <div class="col-sm-6">
                    <span class="textheading">Fields</span>
                </div>
                <div class="col-sm-6 pd-r0" style="text-align: right;">
                    <span class="textheading" style="color: rgba(6, 138, 240, 0.8); font-size: 10px; cursor: pointer;" onclick="selectallfield()">Select All</span>
                    <label class="grey_color"> | </label>
                    <span class="textheading" style="color: rgba(6, 138, 240, 0.8); font-size: 10px; cursor: pointer;" onclick="unselectallfield()">Clear</span>
                </div>
            </div>
            
            <div class="row" style="margin-top: 5px;">
                <div class="col-sm-12"> 

                    <div class="col-sm-12" style="margin-left: -14px;" id="fieldUL">
                        <?php $_from = $this->_tpl_vars['FILTERBYFIELD']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['headerfield'] => $this->_tpl_vars['detailfield']):
?>

						    <label class="container2">
						        <span><?php echo $this->_tpl_vars['detailfield']['fieldlabel']; ?>
</span>
						        <input type="checkbox" name="namefield" value="<?php echo $this->_tpl_vars['detailfield']['fieldid']; ?>
" checked/>
						        <span class="checkmark"></span>
						    </label>

						<?php endforeach; endif; unset($_from); ?>
                    </div>
                    
                </div>
            </div>
            
            <div class="row" style="margin-top: 10px;">
                <div class="col-sm-12" style="border-bottom: 1px solid #ededed;">

                </div>
            </div>
            <!-- Fields -->

            <!-- Users -->
            <div class="row" style="margin-top: 10px;">
                <div class="col-sm-6" style="margin-top: 10px;margin-bottom: 5px;">
                    <span class="textheading">Users</span>
                </div>
                <div class="col-sm-6 pd-r0" style="text-align: right;margin-top: 10px">
                    <span class="textheading" style="color: rgba(6, 138, 240, 0.8); font-size: 10px; cursor: pointer;" onclick="selectalluser()">Select All</span>
                    <label class="grey_color"> | </label>
                    <span class="textheading" style="color: rgba(6, 138, 240, 0.8); font-size: 10px; cursor: pointer;" onclick="unselectalluser()">Clear</span>
                </div>
            </div>
                
            
            <div class="row" style="margin-top: 5px;">
                <div class="col-sm-12">
                    
                    <div class="col-sm-12" style="margin-left: -14px;" id="usersUL">
                		<?php $_from = $this->_tpl_vars['FILTERBYUSER']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['headeruser'] => $this->_tpl_vars['detailuser']):
?>

						    <label class="container2">
						        <span><?php echo $this->_tpl_vars['detailuser']['user_name']; ?>
</span>
						        <input type="checkbox" name="nameuser" value="<?php echo $this->_tpl_vars['detailuser']['user_name']; ?>
" checked/>
						        <span class="checkmark"></span>
						    </label>

						<?php endforeach; endif; unset($_from); ?>
                    </div>
                    
                </div>
            </div>
            <!-- Users -->                     
           </div>
            
        </div>

        <!-- <div class="fillter_footer"> 
            <div class="col-sm-12" style="background-color: #ffffff; float: right; text-align: end;">
            	<button type="button" class="btn btn-light btnclear" onclick="PopupFilter()">Cancel</button>
                <button type="button" class="btn btnshowresuits" onclick="showresuits()">Apply</button>
            </div>
        </div> -->

    </div>
</div>

<hr class="hr-header">
<div id="Container" class="container1">

<?php $_from = $this->_tpl_vars['TIMELINELIST']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['header'] => $this->_tpl_vars['detail']):
?>

<?php $this->assign('rel_mod', $this->_tpl_vars['header']); ?>
<?php $this->assign('HEADERLABEL', getTranslatedString($this->_tpl_vars['header'], $this->_tpl_vars['rel_mod'])); ?>

<table width=100% style="background-color: rgb(247, 247, 247);border-radius: 1em;margin-bottom: 10px; display: block;" class="small font-12 mix timelinetable <?php echo $this->_tpl_vars['detail']['fieldid']; ?>
 <?php echo $this->_tpl_vars['detail']['user_name']; ?>
" data-filter="<?php echo $this->_tpl_vars['header']; ?>
">
	<tr style="height:25px;line-height: 2;background-color: unset;" bgcolor=white>
		<td align="left" class="timeline_" style="padding-left: 20px;">

			<label class="format_date"><?php echo $this->_tpl_vars['detail']['format_date']; ?>
</label>
			
			<?php if ($this->_tpl_vars['detail']['profile_image'] != ''): ?>
		 		<img src="<?php echo $this->_tpl_vars['detail']['profile_image']; ?>
" style="width: 25px; height: 25px;border-radius: 50%;vertical-align: text-bottom;">
		 	<?php else: ?>
		 		<img src="themes/softed/images/profile.png" style="width: 25px; height: 25px;border-radius: 50%;vertical-align: text-bottom;">
		 	<?php endif; ?>
			
			<label class="user_name font-weight">&nbsp;<?php echo $this->_tpl_vars['detail']['user_name']; ?>
</label>

			<?php if ($this->_tpl_vars['detail']['action'] == 'create'): ?>
				<label class="create_the"> Created the</label>&nbsp;<label class="font-weight"><?php echo $this->_tpl_vars['MODULE']; ?>
</label>
			<?php else: ?>
				<label class="edit_the"> Updated the</label>&nbsp;<label class="font-weight"><?php echo $this->_tpl_vars['detail']['fieldlabel']; ?>
</label>
			<?php endif; ?>

			<?php if ($this->_tpl_vars['detail']['action'] == 'create'): ?>
				
			<?php else: ?>
				<br style="line-height: 2.5;">
				<label class="old_value">&nbsp;<?php echo $this->_tpl_vars['detail']['old_value']; ?>
</label> 
				<img src="themes/softed/images/arrow-timeline.png" style="width: 15px; height: 15px;border-radius: 50%;vertical-align: middle;">
				<label class="new_value">&nbsp;<?php echo $this->_tpl_vars['detail']['new_value']; ?>
</label>
			<?php endif; ?>
		</td>
	</tr>
</table>
<?php $this->assign('check_status', $this->_tpl_vars['detail']); ?>

<?php endforeach; endif; unset($_from); ?>
</div>

<?php echo '	
<style type="text/css">
	.hr-header{
		margin-top: 1em;
		margin-bottom: 1em;
		height: 1px;
        background-color: #e9e9e9;
        border: none;
	}
	.format_date{
		border-right: 1.5px solid rgb(185 185 185);
   		padding-right: 0.8em;
	}
	.old_value{
		color: #6d6d6d;
	}
	.font-weight{
		font-weight: 600;
	}
	.font-12{
		font-size: 12px;
	}
	.framer-zexi2n{
		align-self: stretch;
	    flex: 0 0 auto;
	    height: auto;
	    overflow: hidden;
	    position: relative;
	    width: 1px;
	}

	*{
	  -webkit-box-sizing: border-box;
	  -moz-box-sizing: border-box;
	  box-sizing: border-box;
	}
</style>
'; ?>
<script>

$(document).ready(function(){
	$(".sidebar-menu li").removeClass('active');
	$(".treeview-menu li").removeClass('active');
	var urlType = document.URL;
//	 $(this).closest('li').addClass('active');
	//alert(urlType);
	var CurId = $('li.treeview a[href*="'+urlType+'"]').parent().attr('id');
	var MyId = $('li.treeview a[href*="'+urlType+'"]').parent().parent().attr('id');
	var ParentMyId = $('ul.treeview-menu a[href*="'+urlType+'"]').parent().parent().parent().parent().attr('id');
	$("#"+CurId).addClass("active"); 
	$("#"+MyId).addClass("active"); 
	$("#"+ParentMyId).addClass("active"); 
	
	
});
</script>

<aside class="left-side sidebar-offcanvas ">
<div  style=" text-align:center; background:#FFF; padding-top:0px; padding-bottom:3px; border-bottom:1px solid #EEE; ">

           <img src="<?php   echo base_url()?>assets/images/logo_th.jpg" style="width:80%"  />
                    </div>
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
  
                	<? echo $left_menu; ?>
        
                </section>
                <!-- /.sidebar -->
            </aside>
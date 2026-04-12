<style>
.message_error{ color:#FFF; font-size:18pt; }
</style>
<header class="header">

            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation" >
                <!-- Sidebar toggle button-->
              
              <!--    <a href="#" class="logo"></a>-->
             
         
               <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                         <span class="icon-bar"></span>
                </a>  
 
                <div class="navbar-left"   >
                    <ul class="nav navbar-nav">
                    <div id="show-msg" class="message_error" ></div>
                <!--    <li ><a href="home"><i class="fa fa-dashboard"></i> Dashboard</a></li>-->
                <!--    <li ><a href="#"><i class="fa fa-cog"></i> Setting</a></li>-->
                    </ul>
                 </div>    
  			<div class="navbar-right">
            
         
                    <ul class="nav navbar-nav">
                <!--
                        <li class="dropdown notifications-menu ">
                            <a href="#" class="dropdown-toggle " data-toggle="dropdown">
                                <i class="fa fa-warning"></i>
                                <span class="label label-warning">10</span>
                            </a>
                            <ul class="dropdown-menu" >
                                <li class="header">การแจ้งเตือน</li>
                                <li>
                                 
                                    <ul class="menu" style="border:none">
                                        <li >
                                            <a href="#">
                                                <i class="fa fa-warning danger"></i> รถรอคิวนานกว่า 3 ชั่วโมง 3 คัน
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-warning danger"></i> ไม่มีช่องจ่ายสำหรับปูนอินทรีดำ-ถุง
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-warning "></i>ปริมาณสินค้าน้อยกว่าที่กำหนด
                                            </a>
                                        </li>

                                     
                                    </ul>
                                </li>
                                <li class="footer"><a href="#">ดูทั้งหมด</a></li>
                            </ul>
                        </li>-->
                 
                     <!--   <li class="dropdown tasks-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-tasks"></i>
                                <span class="label label-danger">9</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">You have 9 tasks</li>
                                <li>
                                
                                    <ul class="menu">
                                        <li>
                                            <a href="#">
                                                <h3>
                                                    Design some buttons
                                                    <small class="pull-right">20%</small>
                                                </h3>
                                                <div class="progress xs">
                                                    <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                        <span class="sr-only">20% Complete</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <h3>
                                                    Create a nice theme
                                                    <small class="pull-right">40%</small>
                                                </h3>
                                                <div class="progress xs">
                                                    <div class="progress-bar progress-bar-green" style="width: 40%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                        <span class="sr-only">40% Complete</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <h3>
                                                    Some task I need to do
                                                    <small class="pull-right">60%</small>
                                                </h3>
                                                <div class="progress xs">
                                                    <div class="progress-bar progress-bar-red" style="width: 60%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                        <span class="sr-only">60% Complete</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <h3>
                                                    Make beautiful transitions
                                                    <small class="pull-right">80%</small>
                                                </h3>
                                                <div class="progress xs">
                                                    <div class="progress-bar progress-bar-yellow" style="width: 80%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                        <span class="sr-only">80% Complete</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="footer">
                                    <a href="#">View all tasks</a>
                                </li>
                            </ul>
                        </li>
                      -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user" style="color:#EEE"></i>
                                <span><?php echo  USERFNAME ?> <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                     
                                <li class="user-header">
           <!--<img src="<?php echo site_assets_url("img/photo02.jpg")?>" class="img-circle" alt="User Image" />-->
                                    <p>
                                        <?php echo USERFNAME." ". $this->session->userdata('user.lname'); ?> 
                                        
                                      <small>  <?php echo "User Role : ".USERROLE ; ?> </small>
                                      <? echo "<br>"; ?>
                                        <small>Member since <?php echo $this->session->userdata('user.register_date');?> </small>
                                    </p>
                                </li>
                             
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="#" class="btn btn-default btn-flat" id="change_password">Change Password</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="<?php echo site_url("user/logout")?>" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

    
<!-- Modal -->
<div class="modal fade bs-example-modal-sm" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Change Password</h4>
                <label class="control-label"> User :  <?php echo $this->session->userdata('user.name'); ?> </label>

            </div>
            <div class="modal-body">

                <form id="update_password">

                    
                    <input type="hidden" class="form-control" id="user_id" value="<?php echo $this->session->userdata('user.id'); ?>">

                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Old Password</label>
                        <input type="password" class="form-control" id="old_password">
                    </div>

                    <div class="form-group">
                        <label for="recipient-name" class="control-label">New Password</label>
                        <input type="password" class="form-control" id="new_password1">
                    </div>

                    <div class="form-group">
                        <label for="recipient-name" class="control-label">New Password Again</label>
                        <input type="password" class="form-control" id="new_password2">
                    </div>
                </form>






            </div>


            <div class="modal-footer">
                <!--        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                <button type="button" id="btn_update" class="btn btn-primary">Change Password</button>
            </div>
        </div>
    </div>
</div>


<script>
function showMesage()
{
    $('#show-msg').html('').fadeOut('fast');
		$('#show-msg').fadeIn('slow').html('พบปัญหา DAS ติดต่อเบอร์ 061-4850284 หรือ 061-4850368 ตลอด 24ชม.');

	
	}
    $(document).ready(function() {
		 showMesage();
				  setInterval(function() {
					   showMesage();
					   
					   
        	}, 3000);
		
		
        $("#change_password").click(function() {

            $('#myModal').modal('toggle');

            //clear screen password
            $("#old_password").val("");
            $("#new_password1").val("");
            $("#new_password2").val("");

        });

        //button update pass
        $("#btn_update").click(function() {

            //alert( $("#old_password").val());

            if ($("#old_password").val() == "")
            {
                alert("กรุณาระบุ 'เก่า' ");
                $("#old_password").focus();
                return false;
            }

            if ($("#new_password1").val() == "")
            {
                alert("กรุณาระบุ 'password ใหม่' ");
                $("#new_password1").focus();
                return false;
            }

            if ($("#new_password2").val() == "")
            {
                alert("กรุณาระบุ 'password ใหม่อีกครั้ง' ");
                $("#new_password2").focus();
                return false;
            }

            if ($("#new_password1").val() != $("#new_password2").val())
            {
                alert("กรุณาระบุ 'password ' ใหม่ให้เหมือนกัน ");
                $("#new_password2").focus();
                return false;
            }


            var form_data = {
                user_id : $("#user_id").val(),
                old_password: $("#old_password").val(),
                new_password1: $("#new_password1").val(),
                
            };


            var url = "<?php echo site_url("user/update_password") ?>";
            $.ajax(url,
                    {
                        type: 'POST',
                        dataType: 'json',
                        data: form_data,
                        success: function(data) {
                                    alert(data[0].result);
                                    if(data[0].status == '1')
                                    {
                                         window.location.href = "<?php echo site_url("user/logout") ?>";
                                     
                                    }
                                    
                        },
                        error: function(msg) {
                            $.messager.alert('Retrieve data', JSON.stringify(msg), 'error');
                        }
                    });

            $('#myModal').modal('toggle');

        });

    });

</script>  





            
            



            
            

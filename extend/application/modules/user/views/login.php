<script type="text/javascript" src="<?php echo site_assets_url('js/jquery-3.2.0.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_assets_url('js/wow-alert.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo site_assets_url('css/wow-alert.css'); ?>" media="screen" />

<?php 
session_start();
?>

<div class="container">
  <div class="row">
    <form role="form" id="userform" class="contact-form" data-toggle="validator" method="POST" action="">
    <!-- <form action="<?php  echo site_url("user/checklogin")?>"  method="post"  id="signupForm"> -->
      <div class="col-lg-offset-4 col-lg-4" style="margin-top:120px">
   			<div class="block-unit" style="text-align:center;">
            	<img src="<?php  echo site_assets_url('images/P01.png'); ?>" alt="" id="login" style="width:100%; height:100%; margin-top: 0px;">
              <img src="<?php  echo site_assets_url('images/icons/Button_Slide.png'); ?>" alt="" id="js-cat" style="width:10%; height:10%; margin-top: -15px;">
   				<br>
   				<br>
						<fieldset>
							<p>
								<input name="username" id="username" type="text" placeholder="USERNAME">
                <div style="clear:both"></div>
								<input name="password" id="password" type="password"  placeholder="PASSWORD">
               <!--  <label style="margin-left: -80px;">
                  <input type="radio" name="radio">
                  <span class="checkmark">Remember Me</span>
                </label> -->
							</p>
                <div class="form-group">
                 <!--<input type="checkbox" value="remember-me"> Keep me signed in </br> -->
                 <!-- <label style="font-size:14px; color:#F00; font-family:Arial, Helvetica, sans-serif; "><?= $this->session->flashdata('login_message'); ?>
                 </label> -->
      		      </div>
                <div>
              </div>
              <!-- <input class="submit btn-warning" type="submit" value="Login" id="button_submit" style="border-radius:10px !important; background-image: linear-gradient(45deg, #009ffd, #2a2a72); width: 100px; height: 35px;"> -->
              <button type="submit" style="border: 0; background: transparent">
                  <img src="<?php  echo site_assets_url('images/icons/Button_Login.png'); ?>" width="100" alt="submit" />
              </button>
						</fieldset>
   			</div>
   		</div>
    </form>
  </div>
</div>


<script type="text/javascript">
  
    $(document).ready(function () {

      $('#loader').fadeOut();

      $("#userform").submit(function(event){
        $('#loader').fadeIn();
        var username  = $("#username").val();
        var password  = $("#password").val();  
        
        if( username!='' && password!=''){
          $.ajax({
            method: "POST",
	          dataType :'JSON',
            url: "<?php echo site_url('user/checklogin');?>",
            data: { username: username, password: password }
          })
          .done(function( data ) {
             $('#loader').fadeOut();
               var msg = jQuery.parseJSON(JSON.stringify(data));
               console.log(msg['Type']);
              
	           if(msg['Type'] == "S" ){            
                //document.location.href = "<?php echo site_url('home/index');?>";
                
				        if(msg['is_admin'] != 'on'){
                  document.location.href = "<?php echo site_url('home/index');?>";
                }else{
                  document.location.href = "<?php echo site_url('dashboard/index');?>";
                }
                
              } else {  
                  alert(data['Message'] ,{label: "OK", success: function () {
                        $("#username").focus();
                  }});
              }

          });
        }else{
           if(username =='' ){
              $("#username").focus();
           }else if(password == '' ){
              $("#password").focus();
           }
           $('#loader').fadeOut();
        }

      // cancels the form submission
      event.preventDefault();

    });

      $("#js-cat").click(function() {

        var isHidden = $("#login").is(":hidden");

        if (isHidden) {
          $('#login').slideDown();
          document.getElementById("js-cat").src = "<?php  echo site_assets_url('images/icons/Button_Slide.png'); ?>";
        } else {
          $('#login').slideUp();
          document.getElementById("js-cat").src = "<?php  echo site_assets_url('images/icons/Button_Slide2.png'); ?>";
        }

      });

  });

  </script>

  

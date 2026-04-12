<div class="page-wrapper" style="background: #fff;">
	<div class="container-fluid">
		<!-- <div id="myModal" class="modal">
		  <div class="modal-content">
		  	<div class="modal-header">
		        <span style="font-size: 16px; font-family: PromptMedium; color: #2B2B2B;"><b><i>Account Inquiry</i></b></span>
		        <span class="close"><img src="<?php echo site_assets_url('images/icons/close.png'); ?>" width="15" height="15"/></span>
		        <br>
		    </div>
		    <div class="modal-body" style="margin-top: 10px;">
		    	<div class="col-12">
		    		<div class="row">
		    			<div class="col-sm-11 text-center" style="font-size: 11px; color: #2B2B2B; font-family: PromptMedium;">
		    				แสดงข้อมูลลูกค้าครั้งละ 20 รายการ(จากทั้งหมด 280 รายการ)
		    			</div>
		    			<div class="col-sm text-right">
		    				<button type="button" class="btn btnadd">
		    					<i>
		    						<img src="<?php echo site_assets_url('images/icons/addcontento.png'); ?>" width="15" height="15" />
		    					</i> <b>เพิ่มข้อมูล</b></button>
		    			</div>
		    		</div>
		    	</div>
		    </div>
		  </div>
		</div> -->

	</div>
</div>

<style>

	@font-face {
      font-family: PromptMedium;
      src: url(assets/fonts/Prompt-Medium.ttf);
    }

    .btnadd {
    	font-size: 11px;
    	box-shadow: 1px 0px 10px #EDEDED;
    	font-family: PromptMedium;
    	color: #E97126;
    }
	/* The Modal (background) */
	.modal {
	  display: block; /* Hidden by default */
	  position: fixed; /* Stay in place */
	  z-index: 1; /* Sit on top */
	  padding-top: 100px; /* Location of the box */
	  left: 0;
	  top: 0;
	  width: 100%; /* Full width */
	  height: 100%; /* Full height */
	  overflow: auto; /* Enable scroll if needed */
	  background-color: rgb(0,0,0); /* Fallback color */
	  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
	}

	/* Modal Content */
	.modal-content {
	  background-color: #fefefe;
	  margin: auto;
	  padding: 20px;
	  border: 1px solid #888;
	  width: 80%;
	}

	/* The Close Button */
	.close {
	  color: #aaaaaa;
	  float: right;
	  font-size: 28px;
	  font-weight: bold;
	  opacity: none;
	}

	.close:hover,
	.close:focus {
	  color: #000;
	  text-decoration: none;
	  cursor: pointer;
	}

	.modal-header {
      padding: 2px 16px;
      /*background-color: #5cb85c;*/
      color: #2B2B2B;
      padding-bottom: 10px;
    }

    .modal-body {
        overflow-y: auto;
        max-height: calc(100vh - 200px);
    }

</style>
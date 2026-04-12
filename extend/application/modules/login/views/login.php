<div class="page-wrapper">
	<div class="container-fluid">
		<div class="card">
			<div class="card-body" style="padding: 0px;">
				<div class="col-12">
					<div class="row">
						<div class="col-6" style="padding-left: 0px; padding-right: 0px;">
							<div class="banner" style="width: 100%; height: 100%;">
								<img src="<?php echo site_assets_url('images/Photo.png'); ?>" style=" border-radius: 5px;"/>
							</div>
						</div>
						<div class="col-6" style="margin-top: 10px;">
							<div class="d-flex m-b-30 no-block">
								<div class="ml-auto">
									<div class="input-group">
										<span class="input-group-btn input-group-prepend">
											<button class="btn btn-secondary btn-outline active">EN</button>
										</span>
										<span class="input-group-btn input-group-prepend">
											<button class="btn btn-secondary btn-outline">TH</button>
										</span>
									</div>
								</div>
							</div>
							<div class="col-sm-12" style="top: 65px;">
								<div class="col-sm-10">
									<form class="floating-labels m-t-40">
										<div class="form-group m-b-10">
											<input type="text" class="form-control" id="input1" style="min-height: 39px;">
											<!-- <span class="bar"></span> -->
											<label for="input1">Username</label>
										</div>
										<div class="form-group m-b-10">
											<input type="text" class="form-control" id="input2" style="min-height: 39px;">
											<!-- <span class="bar"></span> -->
											<label for="input2">Password</label>
										</div>
									</form>
									<div class="form-group" style="margin-bottom: 5px;">
										<!-- <div class="checkbox checkbox-success">
											<input type="checkbox" name="checkbox1" id="checkbox1">
											<label for="checkbox1" style="font-size: 11px; font-family:PromptMedium; color: #A9A9A9; font-weight: 400;">Remember me</label>
											<label for="checkbox1"><span></span>Remember me</label>
										</div> -->
										<div class="checkbox">
											<input type="checkbox" id="checkbox" name="" value="">
											<label for="checkbox" style="font-size: 11px; font-family:PromptMedium; color: #A9A9A9; font-weight: 400;"><span>Remember me</span></label>
										</div>
									</div>
									<div class="col-sm-10" style="display: contents;">
										<button type="submit" class="btn btn-success" style="width: 100%;">Sign in</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<style type="text/css">

	@font-face {
      font-family: PromptMedium;
      src: url(assets/fonts/Prompt-Medium.ttf);
    }

	.page-wrapper {
		background: none;
	}

	.container-fluid {
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.card {
		width: 869px;
		height: 485px;
		border-radius: 5px;
		box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.1);
	}

	.input-group>.input-group-prepend>.btn {
		border-radius: 5px;
		font-family: PromptMedium;
		color: #A9A9A9;
		background-color: #EDEDED;
		border: 0px;
	}

	.btn-secondary:not(:disabled):not(.disabled).active {
		color: #E97126;
		background-color: #ffffff;
	}

	.floating-labels .form-control {
		background-color: #EDEDED;
		font-family: PromptMedium;
		border-radius: 5px;
		border: 0px;
		font-weight: 400px;
		padding-bottom: 0px;
		padding-left: 10px;
		padding-top: 15px;
	}

	.floating-labels label {
		color: #A9A9A9;
		left: 10px;
		font-size: 16px;
	}

	.floating-labels .form-control:focus {
		background-color: #ffffff;
		border: 1px solid #E97126;
	}

	.floating-labels .focused label {
		top: 2px;
		font-size: 11px;
		color: #a9a9a9;
	}


	.btn-success {
		background-color: #E97126;
		border-color: #E97126;
	}

	.btn-success:hover {
		background-color: #E97126;
		border-color: #E97126;
	}

	/*input[type="checkbox"] {
		display:none;
	}
	input[type="checkbox"] + label span {
		display:inline-block;
		width:19px;
		height:19px;
		margin:-2px 10px 0 0;
		vertical-align:middle;
		background:url(check_radio_sheet.png) left top no-repeat;
		background-color: #EDEDED;
		cursor:pointer;
	}

	input[type="checkbox"]:checked + label span {
		background-color: #fff;
	}*/


	.container {
    margin-top: 50px;
    margin-left: 20px;
    margin-right: 20px;
	}
	.checkbox {
		width: 100%;
		margin: 15px auto;
		position: relative;
		display: block;
	}

	.checkbox input[type="checkbox"] {
		width: auto;
		opacity: 0.00000001;
		position: absolute;
		left: 0;
		margin-left: -20px;
	}
	.checkbox label {
		position: relative;
	}
	.checkbox label:before {
		content: '';
		position: absolute;
		left: 0;
		top: 0;
		margin: 4px;
		width: 22px;
		height: 22px;
		transition: transform 0.28s ease;
		border-radius: 2px;
		/*border: 2px solid #000;*/
		background-color: #EDEDED;
	}
	.checkbox label:after {
		content: '';
		display: block;
		width: 10px;
		height: 5px;
		border-bottom: 2px solid #ffffff;
		border-left: 2px solid #ffffff;
		-webkit-transform: rotate(-45deg) scale(0);
		transform: rotate(-45deg) scale(0);
		transition: transform ease 0.25s;
		will-change: transform;
		position: absolute;
		top: 12px;
		left: 10px;
	}
	.checkbox input[type="checkbox"]:checked ~ label::before {
		color: #ffffff;
		background-color: #E97126;
	}

	.checkbox input[type="checkbox"]:checked ~ label::after {
		-webkit-transform: rotate(-45deg) scale(1);
		transform: rotate(-45deg) scale(1);
	}

	.checkbox label {
		min-height: 34px;
		display: block;
		padding-left: 40px;
		margin-bottom: 0;
		font-weight: normal;
		cursor: pointer;
		vertical-align: sub;
	}
	.checkbox label span {
		position: absolute;
		top: 50%;
		-webkit-transform: translateY(-50%);
		transform: translateY(-50%);
	}
	.checkbox input[type="checkbox"]:focus + label::before {
		outline: 0;
	}

</style>
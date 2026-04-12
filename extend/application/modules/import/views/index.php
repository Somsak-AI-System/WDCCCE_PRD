<div class="page-wrapper" style="background: #fff;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
            	<div class="card">
            		<div class="card-body">
            			<div class="col-12 p-3" style="box-shadow: 1px 0px 10px #E5E5E5;">

            				<!-- <div class="row m-t-10">
            					<div class="col-sm-1 m-t-10 text-right">
            						<label>
            							PRICE LIST
            						</label>
            					</div>
							  	<div class="col-sm-10">
							  		<input class="form-control" type="text">
							  	</div>
							  	<div class="col-sm-1">
							  		<a href="#searchpricelist" data-toggle="tab">
							  			<button type="button" class="btn d-none d-lg-block m-l-15 btnsearch"><i class="fa fa-search"></i> Search </button>
							  		</a>
							  	</div>
            				</div> -->

            				<div class="row m-t-10" style="border-radius: 10px;">
            					<div class="col-sm-2" style="background: #EDEDED; border-radius: 10px 0px 0px 10px;">
            						<div class="center">
            							<a data-toggle="tab" href="#menu1">
            								<button type="button" class="btn d-none d-lg-block btnsearch" id="buttonimport"><i class="fa fa-upload"></i> Start Import </button>
            							</a>
            						</div>
            					</div>
            					<div class="col-sm-10" style="border-radius: 10px; border: 1px solid #F9F9F9;">
            						<div class="msform">
					                    <!-- progressbar -->
					                    <!-- <ul id="progressbar">
					                        <li class="" id="account"><strong></strong></li>
					                        <li id="personal"><strong></strong></li>
					                        <li id="payment"><strong></strong></li>
					                        <li id="confirm"><strong></strong></li>
					                    </ul> -->

					                    <ul id="progressbar">
									          <li id="selectfile">Select file</li>
									          <li id="errorimport">Validate & Error</li>
									          <li>Import Approval</li>
									          <li>Finished</li>
									  	</ul>
					                </div>
            					</div>
            				</div>
            			</div>
            			<div>
            				<div class="tab-content tabcontent-border m-t-10">
            					<div id="menu1" class="tab-pane fade">
            						<div class="card" style="box-shadow: 1px 0px 10px #E5E5E5;">
            							<div class="card-body">
            								<!-- <form class="md-form" id="from_import" action="<?php echo site_url('import/import2') ?>" method="post" enctype="multipart/form-data" >
            								</form> -->
            								<form class="msform" id="from_import" method="post" enctype="multipart/form-data" >
            									<fieldset>
	            									<div class="row">
		            									<div class="col-sm-1">
		            										<input type="hidden" name="import_date" id="import_date" value="<?php echo date('Y-m-d H:i:s');?>">
		            										<input type="file" id="BSbtninfo" name="BSbtninfo" value="" onChange="validate(this.value)" required="required" accept=".xls, .xlsx" hidden>
		            										<label for="BSbtninfo" class="btn d-none d-lg-block m-l-15 m-t- 15 btnbrowse">
		            											Browse
		            										</label>
		            										
		            									</div>
		            									<div class="col-sm-10">
		            										<input class="form-control" type="text" id="file-chosen">
		            										<!-- <span class="form-control" id="file-chosen" contenteditable="false" style="font-size: 14px;"></span> -->
		            										<b class="BSbtninfo" style="color: red;float: left;display: none;">Please attach file</b>
		            									</div>
		            								</div>
									                <button type="submit" name="next" class="btn d-none d-lg-block m-l-15 btnsearch next" style="float: right; width: 100px; margin-right: 15px; margin-top: -37px;"> Import </button>
									                <!-- <div class="form-group text-left">
									                	<input type="file" id="BSbtninfo" name="BSbtninfo" value="" onChange="validate(this.value)">
									                </div> -->
            									</fieldset>
            								

	            								<fieldset>
	            									<div class="col-12">
	            										<div class="card">
	            											<div class="card-body">
	            												<h4 class="card-title text-left" style="color: #000000; ">VALIDATE MESSAGE & ERROR</h4>
	            												<!-- <textarea class="form-control" rows="10" placeholder="This file verified" style="background: #EDEDED;"></textarea> -->
	            												<div class="meter" id="myBar">
	            													<span style="width: 0%"></span>
	            												</div>
	            												<div id="grid_import"></div>
	            											</div>
	            										</div>
	            									</div>
	            									<button type="button" name="black" id="black" class="btn d-none d-lg-block m-l-15 btnimportapp black" style="float: right;" onclick="window.location.reload()"> Cancel </button>
	            									<button type="button" name="next" id="importapprove" class="btn d-none d-lg-block m-l-15 btnimportapp next" style="float: right;"> IMPORT APPROVE </button>
									                
									            </fieldset>
									            <fieldset>
									                <div class="col-12">
									                	<div class="card">
									                		<div class="card-body">
									                			<h4 class="card-title text-left" style="color: #000000;">IMPORT LOG</h4>
									                			<!-- <div class="col-12" style="height: 250px; background: #EDEDED;">
									                				<p class="m-t-10" style="float: left; color: #00E196;">Import finish</p>
									                			</div> -->
									                			<div class="meter" id="barimportsuccess">
	            													<span style="width: 0%"></span>
	            												</div>
									                			<div id="grid_import_success"></div>
									                			<!-- <textarea class="form-control" rows="10" placeholder="Import finish" style="background: #EDEDED;"></textarea> -->
									                		</div>
									                	</div>
									                </div>
									                <!-- <input type="button" name="next" class="next action-button" value="Submit" />  -->
									                <button type="button" name="next" class="btn d-none d-lg-block m-l-15 btnimportapp next" style="float: right;">Close</button>
									            </fieldset>
								                <fieldset>
								                   <div class="col-12">
									                	<div class="card">
									                		<div class="card-body">
									                			<h4 class="card-title text-left" style="color: #000000;">IMPORT FINISH</h4>
									                			<div class="col-12" style="height: 250px; background: #EDEDED;">
									                				<p class="m-t-10" style="float: left; color: #000000;">
									                				No. of Records Successfully Imported : <span id='result'></span>
									                				</p>
									                			</div>
									                			
									                		</div>
									                	</div>
									                </div>
									                <!-- <input type="button" name="next" class="next action-button" value="Submit" />  -->
									                <button type="button" name="next" class="btn d-none d-lg-block m-l-15 btnimportapp" style="float: right;" onclick="window.location.reload();">IMPORT MORE</button>
								            	</fieldset>
            								
								            </form>
            							</div>
            						</div>
            					</div>
            					<div id="searchpricelist" class="tab-pane fade">
            						<div class="card" style="box-shadow: 1px 0px 10px #E5E5E5;">
            							<div class="card-body">
            								<div class="col-12">
            									<div class="table-responsive">
            										<table id="example" class="table table-bordered">
            											<thead class="text-center">
            												<tr>
            													<th>PlantID</th>
            													<th>ชื่อแพล้นท์</th>
            													<th>ที่ตั้งแพล้นท์</th>
            													<th>lat.</th>
            													<th>long.</th>
            													<th>แบรนด์</th>
            													<th>ซีเมนต์</th>
            													<th>เบอร์เซลล์</th>
            													<th>เบอร์แพลนท์</th>
            													<th>ไลน์</th>
            													<th>บริษัท</th>
            													<th>จังหวัด</th>
            													<th>เพิ่มเติม</th>
            												</tr>
            											</thead>
            											<tbody>
            												<tr>
            													<td>500000401</td>
            													<td>QMIX โรงงานสารภี</td>
            													<td>ตำบล</td>
            													<td>18.00</td>
            													<td>99.00</td>
            													<td></td>
            													<td>SCG</td>
            													<td>0898876676</td>
            													<td>0812237765</td>
            													<td>Q</td>
            													<td>บ.</td>
            													<td>เชียงใหม่</td>
            													<td>รายละเอียด</td>
            												</tr>
            											</tbody>
            										</table>
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
        </div>
    </div>

</div>

<style type="text/css">

	.btnsearch {
		background-color: #ffffff;
		color: #000000;
		border-color: #ffffff;
		box-shadow: 1px 0px 10px #E5E5E5;
	}

	.btnsearch:hover {
		background: #fff;
		color: #e8874b;
		box-shadow: 1px 0px 10px #FBEDE7;
	}

	.btnimportapp {
		background-color: #ffffff;
		color: #000000;
		border-color: #ffffff;
		box-shadow: 1px 0px 10px #E5E5E5;
	}

	.btnimportapp:hover {
		background: #fff;
		color: #e8874b;
		box-shadow: 1px 0px 10px #FBEDE7;
	}

	.center {
	  margin: 0;
	  position: absolute;
	  top: 50%;
	  left: 50%;
	  -ms-transform: translate(-50%, -50%);
	  transform: translate(-50%, -50%);
	}

	.msform {
	    text-align: center;
	    position: relative;
	    margin-top: 20px
	}

	.msform fieldset {
	    background: white;
	    border: 0 none;
	    border-radius: 0.5rem;
	    box-sizing: border-box;
	    width: 100%;
	    margin: 0;
	    padding-bottom: 20px;
	    position: relative
	}

	.form-card {
	    text-align: left
	}

	.msform fieldset:not(:first-of-type) {
	    display: none
	}

	#progressbar {
	    margin-bottom: 30px;
	    overflow: hidden;
	    color: lightgrey;
	    counter-reset: step;
	}

	#progressbar .active {
	    color: #00E296;
	}

	#progressbar .error {
	    color: #FF4560;
	}

	#progressbar li {
	  list-style-type: none;
      width: 25%;
      float: left;
      font-size: 12px;
      position: relative;
      text-align: center;
      text-transform: uppercase;
      color: #000000;
	}

	/*#progressbar #account:before {
	    font-family: FontAwesome;
	    content: "\f13e"
	}

	#progressbar #personal:before {
	    font-family: FontAwesome;
	    content: "\f007"
	}

	#progressbar #payment:before {
	    font-family: FontAwesome;
	    content: "\f030"
	}

	#progressbar #confirm:before {
	    font-family: FontAwesome;
	    content: "\f00c"
	}*/

	#progressbar li:before {
	  width: 50px;
      height: 50px;
      content: counter(step);
      counter-increment: step;
      line-height: 30px;
      border: 2px solid #F1F1F1;
      display: block;
      text-align: center;
      margin: 0 auto 10px auto;
      border-radius: 50%;
      background-color: #EDEDED;
      color: #EDEDED;
	}

	#progressbar li:after {
	    content: '';
	    width: 100%;
	    height: 2px;
	    background: lightgray;
	    position: absolute;
	    left: -50%;
	    top: 25px;
	    z-index: -1
	}

	#progressbar li.active:before,
	#progressbar li.active:after {
	    background: #00E296;
	    color: #00E296;
	}

	#progressbar li.error:before,
	#progressbar li.error:after {
	    background: #FF4560;
	    color: #FF4560;
	}


	#progressbar li:first-child:after {
      content: none;
  	}

	/*#msform input,
	#msform textarea {
	    padding: 8px 15px 8px 15px;
	    border: 1px solid #ccc;
	    border-radius: 0px;
	    margin-bottom: 25px;
	    margin-top: 2px;
	    width: 100%;
	    box-sizing: border-box;
	    font-family: montserrat;
	    color: #2C3E50;
	    background-color: #ECEFF1;
	    font-size: 16px;
	    letter-spacing: 1px
	}

	#msform input:focus,
	#msform textarea:focus {
	    -moz-box-shadow: none !important;
	    -webkit-box-shadow: none !important;
	    box-shadow: none !important;
	    border: 1px solid #673AB7;
	    outline-width: 0
	}*/

	.msform .action-button {
	    width: 100px;
	    background: #673AB7;
	    font-weight: bold;
	    color: white;
	    border: 0 none;
	    border-radius: 0px;
	    cursor: pointer;
	    padding: 10px 5px;
	    margin: 10px 0px 10px 5px;
	    float: right
	}

	.msform .action-button:hover,
	.msform .action-button:focus {
	    background-color: #311B92
	}

	.msform .action-button-previous {
	    width: 100px;
	    background: #616161;
	    font-weight: bold;
	    color: white;
	    border: 0 none;
	    border-radius: 0px;
	    cursor: pointer;
	    padding: 10px 5px;
	    margin: 10px 5px 10px 0px;
	    float: right
	}

	.msform .action-button-previous:hover,
	.msform .action-button-previous:focus {
	    background-color: #000000
	}

	.card {
	    z-index: 0;
	    border: none;
	    position: relative
	}

	.fs-title {
	    font-size: 25px;
	    color: #673AB7;
	    margin-bottom: 15px;
	    font-weight: normal;
	    text-align: left
	}

	.purple-text {
	    color: #673AB7;
	    font-weight: normal
	}

	.steps {
	    font-size: 25px;
	    color: gray;
	    margin-bottom: 10px;
	    font-weight: normal;
	    text-align: right
	}

	.fieldlabels {
	    color: gray;
	    text-align: left
	}

	.btnbrowse {
		background: #EDEDED;
	}

	.red{

		background-color:red;
	}

	.k-filter-row .k-dropdown-operator {
        display: none;
    }

	/*.btnimportapp {
		background: #fff;
		color: #e8874b;
		width: 150px;
		border-radius: 10px;
		box-shadow: 1px 0px 10px #FBEDE7;
	}*/

	.meter { 
		height: 20px;  /* Can be anything */
		position: relative;
		background: #f1a165;
		-moz-border-radius: 25px;
		-webkit-border-radius: 25px;
		border-radius: 25px;
		padding: 10px;
		box-shadow: inset 0 -1px 1px rgba(255,255,255,0.3);
	}

	.meter > span {
		display: block;
		height: 100%;
		border-top-right-radius: 8px;
		border-bottom-right-radius: 8px;
		border-top-left-radius: 20px;
		border-bottom-left-radius: 20px;
		background-color: #f1a165;
  		background-image: linear-gradient(to bottom, #f1a165, #f36d0a);
		box-shadow: 
		inset 0 2px 9px  rgba(255,255,255,0.3),
		inset 0 -2px 6px rgba(0,0,0,0.4);
		position: relative;
		overflow: hidden;
	}

	/*.k-grid {
		display: none;
	}*/

</style>

<script src="<?php echo site_assets_url('css/node_modules/datatables.net/js/jquery.dataTables.min.js');?>"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>

<script>

	$("#buttonimport").click(function() {
	  $("#selectfile").addClass('active');
	});

	$(document).ready(function(){

		var datasource = {
			pageSize: 10,
			filter: {
				logic: "and",
				filters: [],
			},
			schema: {
				model: {
					id: "",
				},
				data: "data",
				total: "pagesize",
			},
			serverPaging: false,
			serverFiltering: false,
			serverSorting: false,
		};

		var columns = [
            {
                field: "plant_id",
                title: "Plant ID",
                width: 100,
            },
            {
                field: "zone",
                title: "Zone",
                width: 100,
            },
            {
                field: "distance",
                title: "Distance",
                width: 100,
            },
            {
                field: "mat_type",
                title: "Mat Type",
                // template: "<span id='displayCount'>0</span>",
                width: 100,
            },
            {
                field: "truck_size",
                title: "Truck Size",
                width: 100,
            },
            {
                field: "truck_type",
                title: "Truck Type",
                width: 100,
            },
            {
                field: "strength",
                title: "Strength",
                width: 100,
            },
            {
                field: "mat_master",
                title: "Mat Master",
                width: 100,
            },
            {
                field: "vendor_product_code",
                title: "Vendor Product Code",
                width: 100,
            },
            {
                field: "lp",
                title: "LP",
                width: 100,
            },
            {
                field: "lp_dise",
                title: "LP DISC",
                width: 100,
            },
            {
                field: "c_cost",
                title: "C Cost",
                width: 100,
            },
            {
                field: "c_cost_vat",
                title: "C Cost+VAT",
                width: 100,
            },
            {
                field: "c_price_vat",
                title: "C Price+VAT",
                width: 100,
            },
            {
                field: "min",
                title: "Min",
                width: 100,
            },
            {
                field: "dlv_c",
                title: "DLV_C",
                width: 100,
            },
            {
                field: "dlv_c_vat",
                title: "DLV_C+VAT",
                width: 100,
            },
            {
                field: "dlv_p_vat",
                title: "DLV_P+VAT",
                width: 100,
            },
            {
                field: "status",
                title: "Status",
                width: 100,
            },
            {
                field: "pricelist_date",
                title: "Pricelist Date",
                width: 100,
            },
            {
                field: "descrtption",
                title: "Description",
                width: 100,
            },
            {
                field: "flag",
                title: "Import Status",
                width: 100,
            },
      	];

		//$("#grid_import").genKendoGridPopupimport(datasource, columns);
		$("#grid_import").genKendoGrid(datasource, columns);
		var current_fs, next_fs, previous_fs; //fieldsets
		var opacity;
		var current = 0;
		var steps = $("fieldset").length;

		setProgressBar(current);
		
		$("#importapprove").click(function(){
			
			var import_date = $('#import_date').val();
			//var form_data = {import_date : import_date};

			moveimportsuccess();

			$(".k-grid").css("display","none");
			
			var url = "<?php echo site_url('import/import3'); ?>";
			$.ajax({
           		url: url,
				type: 'POST',
				dataType : 'json',
				data: {import_date:import_date},
				cache: false,
				encoding:"UTF-8",
				success: function (data) {
					
					console.log(data);

					var datasource_import = {
						pageSize: 10,
						filter: {
							logic: "and",
							filters: [],
						},
						schema: {
							model: {
								id: "",
							},
							data: "data",
							total: "pagesize",
						},
						serverPaging: false,
						serverFiltering: false,
						serverSorting: false,
					};

					var columns_import = [
						{
			                field: "pricelistid",
			                title: "Price List No",
			                width: 100,
			            },
			            {
			                field: "plant_id",
			                title: "Plant ID",
			                width: 100,
			            },
			            {
			                field: "zone",
			                title: "Zone",
			                width: 100,
			            },
			            {
			                field: "distance",
			                title: "Distance",
			                width: 100,
			            },
			            {
			                field: "mat_type",
			                title: "Mat Type",
			                width: 100,
			            },
			            {
			                field: "truck_size",
			                title: "Truck Size",
			                width: 100,
			            },
			            {
			                field: "truck_type",
			                title: "Truck Type",
			                width: 100,
			            },
			            {
			                field: "strength",
			                title: "Strength",
			                width: 100,
			            },
			            {
			                field: "mat_master",
			                title: "Mat Master",
			                width: 100,
			            },
			            {
			                field: "vendor_product_code",
			                title: "Vendor Product Code",
			                width: 100,
			            },
			            {
			                field: "lp",
			                title: "LP",
			                width: 100,
			            },
			            {
			                field: "lp_dise",
			                title: "LP DISC",
			                width: 100,
			            },
			            {
			                field: "c_cost",
			                title: "C Cost",
			                width: 100,
			            },
			            {
			                field: "c_cost_vat",
			                title: "C Cost+VAT",
			                width: 100,
			            },
			            {
			                field: "c_price_vat",
			                title: "C Price+VAT",
			                width: 100,
			            },
			            {
			                field: "min",
			                title: "Min",
			                width: 100,
			            },
			            {
			                field: "dlv_c",
			                title: "DLV_C",
			                width: 100,
			            },
			            {
			                field: "dlv_c_vat",
			                title: "DLV_C+VAT",
			                width: 100,
			            },
			            {
			                field: "dlv_p_vat",
			                title: "DLV_P+VAT",
			                width: 100,
			            },
			            {
			                field: "status",
			                title: "Status",
			                width: 100,
			            },
			            {
			                field: "pricelist_date",
			                title: "Pricelist Date",
			                width: 100,
			            },
			            {
			                field: "descrtption",
			                title: "Description",
			                width: 100,
			            },
			      	];

			      	$("#grid_import_success").genKendoGrid(datasource_import, columns_import);

			      	$("#grid_import_success").data("kendoGrid").dataSource.data([]);
					var grid_import = $("#grid_import_success").data("kendoGrid");

			      	$.each(data.data, function (key, value) {
						grid_import.dataSource.add({ 
							pricelistid: value.pricelistno,
							plant_id: value.plantcode,
							zone: value.zone,
							distance: value.distance,
							mat_type: value.mat_type,
							truck_size: value.truck_size,
							truck_type: value.truck_type,
							strength: value.strength,
							mat_master: value.mat_master,
							vendor_product_code: value.vendor_product_code,
							lp: value.lp,
							lp_dise: value.lp_disc,
							c_cost: value.c_cost,
							c_cost_vat: value.c_cost_vat,
							c_price_vat: value.c_price_vat,
							min: value.min,
							dlv_c: value.dlv_c,
							dlv_c_vat: value.dlv_c_vat,
							dlv_p_vat: value.dlv_p_vat,
							status: value.status,
							pricelist_date: value.pricelist_date,
							descrtption: value.description,
						});
					});

					$(".k-grid").fadeIn();
					$("#barimportsuccess").fadeOut();

					$( "#result" ).append(data.total);

				},
				error: function (error) {
					alert('error');
				}
			});
		});
		
		$(".next").click(function(){

			var BSbtninfo = $('#BSbtninfo').val();
			jQuery(".BSbtninfo").css("display", "none");
			
			if(BSbtninfo == ''){
				jQuery(".BSbtninfo").css("display", "block");
				return false;
			}
			move();

			$(".k-grid").css("display","none");

			current_fs = $(this).parent();
			next_fs = $(this).parent().next();

			//Add Class Active
			$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

			//show the next fieldset
			next_fs.show();
			//hide the current fieldset with style
			current_fs.animate({opacity: 0}, {
				step: function(now) {
					// for making fielset appear animation
					opacity = 1 - now;

					current_fs.css({
						'display': 'none',
						'position': 'relative'
					});
					next_fs.css({'opacity': opacity});
				},
				duration: 500
			});
			setProgressBar(++current);

			$('#from_import').submit(function(e) {

				e.preventDefault();
				// let form_data = new FormData($(this)[0]);
				// console.log(form_data);

				// var file_data = $('.BSbtninfo').prop('files')[0];
				let form_data = new FormData($(this)[0]);
				// console.log(form_data);

				if (form_data != undefined) {
					// console.log('t');
				} else {
					// console.log('f');
				}

				var url = "<?php echo site_url('import/import2'); ?>";

				// $('#content').hide();
				// $('#page-loader').fadeIn();
				$.ajax({
					url: url,
					type: 'POST',
					data: form_data,
					contentType: false,
					processData: false,
					success: function (data) {
						var result = jQuery.parseJSON(data);
						//console.log(result.data);
						$("#grid_import").data("kendoGrid").dataSource.data([]);
						var grid = $("#grid_import").data("kendoGrid");

						grid.bind("dataBound", grid_dataBound);

						function grid_dataBound(e) {
							// console.log("dataBound");
							var gridData = grid.dataSource.view();  
							for (var i = 0; i < gridData.length; i++) {  
								var currentUid = gridData[i].uid;  
								if (gridData[i].flag == 'No') {  
									var currentRow = grid.table.find("tr[data-uid='" + currentUid + "']");  
					                //var createUserButton = $(currentRow).find("butCreateUser");  
					                //createUserButton.hide();  
					                currentRow.css('background-color', '#E97126');  
					            }  
					        }  
						}

						// var data = grid.dataSource.data();
						// grid.tbody.find('>tr').each(function () {
						// 	var dataItem = grid.dataItem(this);
						// 	// console.log(dataItem.truck_type);
						// 	if (dataItem.flag == 1) {
						// 		$(this).css('background', 'red');
						// 	}
						// 	// if (dataItem.DateOff == null && dataItem.DateAck == null) {
						// 	// 	$(this).css('color', 'red');
						// 	// }
						// 	// if (dataItem.DateOff != null && dataItem.DateAck == null) {
						// 	// 	$(this).css('color', 'green');
						// 	// }
						// 	// if (dataItem.DateOff == null && dataItem.DateAck != null) {
						// 	// 	$(this).css('color', 'blue');
						// 	// }
						// });
						
						$.each(result.data, function (key, value) {
							grid.dataSource.add({ 
								plant_id: value.plantcode,
								zone: value.zone,
								distance: value.distance,
								mat_type: value.mat_type,
								truck_size: value.truck_size,
								truck_type: value.truck_type,
								strength: value.strength,
								mat_master: value.mat_master,
								vendor_product_code: value.vendor_product_code,
								lp: value.lp,
								lp_dise: value.lp_disc,
								c_cost: value.c_cost,
								c_cost_vat: value.c_cost_vat,
								c_price_vat: value.c_price_vat,
								min: value.min,
								dlv_c: value.dlv_c,
								dlv_c_vat: value.dlv_c_vat,
								dlv_p_vat: value.dlv_p_vat,
								status: value.status,
								pricelist_date: value.pricelist_date,
								descrtption: value.description,
								flag: value.flag,
							});
						});

						$(".k-grid").fadeIn();
						$("#myBar").fadeOut();
						// $("#myBar").css("display","none");
						//console.log(result);
						if(result['flag_import'] == false){
							$('#importapprove').prop("disabled", true);
							$('#importapprove').css("background-color", 'silver');
							$("#errorimport").addClass('error');
						}
					},
					error: function (error) {
						alert('error');
					}
				});
				// $('#page-loader').fadeOut();
				// $('#content').show();
			});

		});

		function setProgressBar(curStep){
		var percent = parseFloat(100 / steps) * curStep;
		
		}

		function move() {
			var elem = document.getElementById("myBar");   
			var width = 1;
			var id = setInterval(frame, 50);
			function frame() {
				if (width >= 100) {
					clearInterval(id);
				} else {
					width++; 
					elem.style.width = width + '%'; 
				}
			}
		}

		function moveimportsuccess() {
			var barimportsuccess = document.getElementById("barimportsuccess");
			var width = 1;
			var id = setInterval(frame, 50);
			function frame() {
				if (width >= 100) {
					clearInterval(id);
				} else {
					width++;
					barimportsuccess.style.width = width + '%';
				}
			}
		}

});

$(document).ready(function() {

	var table = $('#example').DataTable({
		    "columnDefs": [{
			    "searchable": false,
	            "orderable": false,
	            "targets": 0
		    }],
		    "order": [[2, 'asc']],
		    // "displayLength": 25,
		    "pageLength": 10,
		});

	

});

var actualBtn = document.getElementById("BSbtninfo");

var fileChosen = document.getElementById("file-chosen");

actualBtn.addEventListener("change", function () {
	fileChosen.value = this.files[0].name;
});

function validate(file) {
    var ext = file.split(".");
    ext = ext[ext.length-1].toLowerCase();      
    var arrayExtensions = ["xls","xlsx"];

    if (arrayExtensions.lastIndexOf(ext) == -1) {
      $('#errmsg').html('');  
      $('#errmsg').html('*Please select file type (xls,xlsx)');  
      $("#BSbtninfo").val("");
      return false;
    }
    return true;
}

</script>
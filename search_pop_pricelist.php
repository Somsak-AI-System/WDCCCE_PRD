<?php 
$curr_row = $_REQUEST["curr_row"];
$productid = $_REQUEST["productid"];
session_start();
header('Content-Type: text/html; charset=utf-8');
require_once("config.inc.php");
require_once("library/dbconfig.php");
include_once("library/myLibrary_mysqli.php");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

$productid = $_REQUEST["productid"];


$date = date('Y-m-d');
$sql = "SELECT
			aicrm_pricelists.pricelist_no
			,aicrm_pricelists.pricelist_name
			,aicrm_pricelists.pricelist_type
			,aicrm_inventorypricelist.pricelist_showroom
			,aicrm_inventorypricelist.selling_price
			,aicrm_inventorypricelist.pricelist_nomal
			,aicrm_inventorypricelist.pricelist_first_tier
			,aicrm_inventorypricelist.pricelist_second_tier
			,aicrm_inventorypricelist.pricelist_third_tier
		FROM
			aicrm_pricelists
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_pricelists.pricelistid
			INNER JOIN aicrm_pricelistscf ON aicrm_pricelists.pricelistid = aicrm_pricelistscf.pricelistid
			LEFT JOIN aicrm_inventorypricelist ON aicrm_inventorypricelist.id = aicrm_pricelists.pricelistid 
		WHERE
			aicrm_crmentity.deleted = 0
			AND aicrm_inventorypricelist.productid = '".$productid."'";
// echo $sql;
$data = $myLibrary_mysqli->select($sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="asset/popup/bootstrap.min.css">
    <title>Pricelist</title>

</head>

<body>


    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col">
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th scope="col">Pricelist</th>
							<th scope="col">รูปแบบรายการราคา</th>
							<th scope="col">Showroom</th>
							<th scope="col">List Price</th>
							<th scope="col">Normal</th>
							<th scope="col">Tier 1</th>
							<th scope="col">Tier 2</th>
							<th scope="col">Tier 3</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if(!empty($data) && count($data)>0){
							foreach ($data as $k => $v){
						?>
							<tr>
								<td><?php echo $v['pricelist_name'];?></td>
								<td><?php echo $v['pricelist_type'];?></td>
								<td><?php echo $v['pricelist_showroom'];?></td>
								<td><?php echo $v['selling_price'];?></td>
								<td><a href="javascript:void(0);" onclick="set_return_data('<?php echo $curr_row;?>','<?php echo $v['pricelist_showroom'];?>','<?php echo $v['pricelist_nomal'];?>','<?php echo $v['pricelist_type'];?>');"><?php echo $v['pricelist_nomal'];?></a></td>
								<td><a href="javascript:void(0);" onclick="set_return_data('<?php echo $curr_row;?>','<?php echo $v['pricelist_showroom'];?>','<?php echo $v['pricelist_first_tier'];?>','<?php echo $v['pricelist_type'];?>');"><?php echo $v['pricelist_first_tier'];?></a></td>
								<td><a href="javascript:void(0);" onclick="set_return_data('<?php echo $curr_row;?>','<?php echo $v['pricelist_showroom'];?>','<?php echo $v['pricelist_second_tier'];?>','<?php echo $v['pricelist_type'];?>');"><?php echo $v['pricelist_second_tier'];?></a></td>
								<td><a href="javascript:void(0);" onclick="set_return_data('<?php echo $curr_row;?>','<?php echo $v['pricelist_showroom'];?>','<?php echo $v['pricelist_third_tier'];?>','<?php echo $v['pricelist_type'];?>');"><?php echo $v['pricelist_third_tier'];?></a></td>
							</tr>
							<?php }?>
						<?php }?>
					</tbody>
				</table>
            </div>
        </div>
    </div>





    <script src="asset/popup/jquery-3.5.1.slim.min.js"></script>
    <script src="asset/popup/umd/popper.min.js"></script>
    <script src="asset/popup/bootstrap.min.js"></script>
    <script src="asset/popup/jquery-3.5.1.min.js"></script>
    <script src="asset/popup/bootstrap.bundle.min.js"></script>

	<script>
		function set_return_data(curr_row,showroom,tier,pricelist_type){
			window.opener.document.EditView.elements["regular_price"+curr_row].value = showroom;
			window.opener.document.EditView.elements["selling_price"+curr_row].value = tier;
			window.opener.document.EditView.elements["pricelist_type"+curr_row].value = pricelist_type;
			window.opener.calcTotal();
			window.close();
		}
	</script>
</body>

</html>
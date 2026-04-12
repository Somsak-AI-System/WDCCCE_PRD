<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	require_once("../../config.inc.php");
	require_once("../../library/dbconfig.php");
	include_once("../../library/myLibrary_mysqli.php");

	$myLibrary_mysqli = new myLibrary_mysqli();
	$myLibrary_mysqli->_dbconfig = $dbconfig;

	$sql = "SELECT aicrm_competitor.* , aicrm_crmentity.description
		FROM aicrm_competitor
		INNER JOIN aicrm_competitorcf ON aicrm_competitorcf.competitorid = aicrm_competitor.competitorid
		INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_competitor.competitorid
		WHERE aicrm_crmentity.deleted = 0
		AND aicrm_competitor.competitor_seq_no not in ('','--None--')
		ORDER BY aicrm_competitor.competitor_seq_no ASC;";
	$a_data = $myLibrary_mysqli->select($sql);

	$data = array();
	foreach ($a_data as $key => $value) {
		$data[$value['competitor_seq_no']] = $value;
	}
	//echo "<pre>"; print_r($data); echo "</pre>"; exit;
?>
<style type="text/css">
	.bg_c{
		background-color: #e6f2ff !important;
	}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<div data-options="region:'center',iconCls:'icon-ok'" style="width: 100%;" title="service"  id="divplan">
	<form method="POST" name="EditView" id="EditView" ENCTYPE="multipart/form-data">
		<table width="100%" cellpadding="5" cellspacing="0" border="1"  class="homePageMatrixHdr" style="border: 1px solid #000">
			<tbody style="border: 1px solid;">
				<tr>
					<td class="bg_c" width="20%">Competitor Analysis</td>
					<td class="bg_c" width="20%"><?= @$data[0]['competitor_name'];?></td>
					<td class="bg_c" width="20%"><?= @$data[1]['competitor_name'];?></td>
					<td class="bg_c" width="20%"><?= @$data[2]['competitor_name'];?></td>
					<td class="bg_c" width="20%"><?= @$data[3]['competitor_name'];?></td>
				</tr>
				<tr>
					<td class="bg_c" >Business Type</td>
					<td><?= @$data[0]['competitor_business_type'];?></td>
					<td><?= @$data[1]['competitor_business_type'];?></td>
					<td><?= @$data[2]['competitor_business_type'];?></td>
					<td><?= @$data[3]['competitor_business_type'];?></td>
				</tr>
				<tr>
					<td class="bg_c" >Competitor Type</td>
					<td><?= @$data[0]['competitor_type'];?></td>
					<td><?= @$data[1]['competitor_type'];?></td>
					<td><?= @$data[2]['competitor_type'];?></td>
					<td><?= @$data[3]['competitor_type'];?></td>
				</tr>
				<tr>
					<td class="bg_c" >Unique Selling Point</td>
					<td><?= @$data[0]['competitor_unique_selling_point'];?></td>
					<td><?= @$data[1]['competitor_unique_selling_point'];?></td>
					<td><?= @$data[2]['competitor_unique_selling_point'];?></td>
					<td><?= @$data[3]['competitor_unique_selling_point'];?></td>
				</tr>
				<tr>
					<td class="bg_c" >Strength</td>
					<td><?= @$data[0]['competitor_strength'];?></td>
					<td><?= @$data[1]['competitor_strength'];?></td>
					<td><?= @$data[2]['competitor_strength'];?></td>
					<td><?= @$data[3]['competitor_strength'];?></td>
				</tr>
				<tr>
					<td class="bg_c" >Weakness</td>
					<td><?= @$data[0]['competitor_weakness'];?></td>
					<td><?= @$data[1]['competitor_weakness'];?></td>
					<td><?= @$data[2]['competitor_weakness'];?></td>
					<td><?= @$data[3]['competitor_weakness'];?></td>
				</tr>
				<tr>
					<td class="bg_c" >Opportunities</td>
					<td><?= @$data[0]['competitor_opportunities'];?></td>
					<td><?= @$data[1]['competitor_opportunities'];?></td>
					<td><?= @$data[2]['competitor_opportunities'];?></td>
					<td><?= @$data[3]['competitor_opportunities'];?></td>
				</tr>
				<tr>
					<td class="bg_c" >Threat</td>
					<td><?= @$data[0]['competitor_threat'];?></td>
					<td><?= @$data[1]['competitor_threat'];?></td>
					<td><?= @$data[2]['competitor_threat'];?></td>
					<td><?= @$data[3]['competitor_threat'];?></td>
				</tr>
				<tr>
					<td class="bg_c" >Market Shared</td>
					<td><?= @$data[0]['competitor_market_shared'];?></td>
					<td><?= @$data[1]['competitor_market_shared'];?></td>
					<td><?= @$data[2]['competitor_market_shared'];?></td>
					<td><?= @$data[3]['competitor_market_shared'];?></td>
				</tr>
				<tr>
					<td class="bg_c" >Main Product/Services</td>
					<td><?= @$data[0]['main_product_services'];?></td>
					<td><?= @$data[1]['main_product_services'];?></td>
					<td><?= @$data[2]['main_product_services'];?></td>
					<td><?= @$data[3]['main_product_services'];?></td>
				</tr>
				<tr>
					<td class="bg_c" >Price Estimated</td>
					<td><?= @$data[0]['price_estimated'];?></td>
					<td><?= @$data[1]['price_estimated'];?></td>
					<td><?= @$data[2]['price_estimated'];?></td>
					<td><?= @$data[3]['price_estimated'];?></td>
				</tr>
				<tr>
					<td class="bg_c" >Sales Channel</td>
					<td><?= @$data[0]['sales_channel'];?></td>
					<td><?= @$data[1]['sales_channel'];?></td>
					<td><?= @$data[2]['sales_channel'];?></td>
					<td><?= @$data[3]['sales_channel'];?></td>
				</tr>
				<tr>
					<td class="bg_c" >Customer Persona</td>
					<td><?= @$data[0]['customer_persona'];?></td>
					<td><?= @$data[1]['customer_persona'];?></td>
					<td><?= @$data[2]['customer_persona'];?></td>
					<td><?= @$data[3]['customer_persona'];?></td>
				</tr>
				<tr>
					<td class="bg_c" >Customer Group</td>
					<td><?= @$data[0]['customer_group'];?></td>
					<td><?= @$data[1]['customer_group'];?></td>
					<td><?= @$data[2]['customer_group'];?></td>
					<td><?= @$data[3]['customer_group'];?></td>
				</tr>
				<tr>
					<td class="bg_c" >Age Range</td>
					<td><?= @$data[0]['age_range'];?></td>
					<td><?= @$data[1]['age_range'];?></td>
					<td><?= @$data[2]['age_range'];?></td>
					<td><?= @$data[3]['age_range'];?></td>
				</tr>
				<tr>
					<td class="bg_c" >Occupation Group</td>
					<td><?= @$data[0]['occupation_group'];?></td>
					<td><?= @$data[1]['occupation_group'];?></td>
					<td><?= @$data[2]['occupation_group'];?></td>
					<td><?= @$data[3]['occupation_group'];?></td>
				</tr>
				<tr>
					<td class="bg_c" >Income Range</td>
					<td><?= @$data[0]['income_range'];?></td>
					<td><?= @$data[1]['income_range'];?></td>
					<td><?= @$data[2]['income_range'];?></td>
					<td><?= @$data[3]['income_range'];?></td>
				</tr>
				<tr>
					<td class="bg_c" >Remark</td>
					<td><?= @$data[0]['description'];?></td>
					<td><?= @$data[1]['description'];?></td>
					<td><?= @$data[2]['description'];?></td>
					<td><?= @$data[3]['description'];?></td>
				</tr>
			</tbody>
		</table>
	</form>
</div>
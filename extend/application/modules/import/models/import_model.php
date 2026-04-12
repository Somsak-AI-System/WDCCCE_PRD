<?php
class import_model extends CI_Model { 

	var $my_server;  
 	
 	function insert_temp($inserdata,$flag_date){
		
		
		foreach ($inserdata as $k => $v) {

			$sql = "INSERT INTO tbt_import_pricelist(plantcode, zone, distance, mat_type, truck_size, truck_type, strength, mat_master, vendor_product_code, lp, lp_disc, c_cost, c_cost_vat, c_price_vat, min, dlv_c, dlv_c_vat, dlv_p_vat, status, pricelist_date, description, import_date) VALUES (
			'".$v['plant_id']."',
			'".$v['zone']."',
			'".$v['distance']."',
			'".$v['mat_type']."',
			'".$v['truck_size']."',
			'".$v['truck_type']."',
			'".$v['strength']."',
			'".$v['mat_master']."',
			'".$v['vendor_product_code']."',
			'".$v['lp']."',
			'".$v['lp_dise']."',
			'".$v['c_cost']."',
			'".$v['c_cost_vat']."',
			'".$v['c_price_vat']."',
			'".$v['min']."',
			'".$v['div_c']."',
			'".$v['div_c_vat']."',
			'".$v['div_p_vat']."',
			'".$v['status']."',
			'".$v['pricelist_date']."',
			'".$v['descrtption']."',
			'".$flag_date."'
			)";
			$this->db->query($sql);
		}
	
		//Update Plant id
		$sql_plant = "update tbt_import_pricelist 
		inner join aicrm_plant on aicrm_plant.plant_id = tbt_import_pricelist.plantcode 
		inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_plant.plantid
		set tbt_import_pricelist.plantid = aicrm_plant.plantid where aicrm_crmentity.deleted = 0 and tbt_import_pricelist.import_date = '".$flag_date."'";
		$this->db->query($sql_plant);

		//Update Products
		$sql_products = "update tbt_import_pricelist 
		inner join aicrm_products on aicrm_products.product_code = tbt_import_pricelist.mat_master 
		inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_products.productid
		set tbt_import_pricelist.productid = aicrm_products.productid 
		where aicrm_crmentity.deleted = 0 and tbt_import_pricelist.import_date = '".$flag_date."'";
		$this->db->query($sql_products);

		//0=add,1=error
		$sql_bra = "update tbt_import_pricelist set flag = '0' where productid != 0 and plantid != 0 and import_date = '".$flag_date."'";
		$this->db->query($sql_bra);
		
       	$sql = "select 
       			plantcode ,
				zone ,
				distance ,
				mat_type ,
				truck_size ,
				truck_type ,
				strength ,
				mat_master ,
				vendor_product_code ,
				FORMAT(lp,2) as lp ,
				FORMAT(lp_disc,2) as lp_disc ,
				FORMAT(c_cost,2) as c_cost ,
				FORMAT(c_cost_vat ,2) as c_cost_vat ,
				FORMAT(c_price_vat ,2) as c_price_vat ,
				min ,
				dlv_c ,
				dlv_c_vat ,
				dlv_p_vat ,
				status ,
				DATE_FORMAT(pricelist_date, '%d/%m/%Y' ) as pricelist_date,
				description ,
				case when flag = 0 then 'Yes' else 'No' end flag 
       	 		from tbt_import_pricelist 
       	 		where import_date = '".$flag_date."'";
		$query = $this->db->query($sql);
		$result = $query->result(0);
		return $result;
	}

	function import_data($flag_date){

	  $sql = "select * from tbt_import_pricelist where import_date = '".$flag_date."' and flag = 0 order by id ASC";
	  $a_data = $this->db->query($sql);
	  $data = $a_data->result(0);
	  
		if(!empty($data)){
			
			foreach ($data as $k => $v) {
				
			  	$entity = "SELECT MAX(id)+1 as num FROM aicrm_crmentity_seq";
			  	$q_entity = $this->db->query($entity);
			  	$entityid = $q_entity->result(0);
			  	
			  	$cd = $this->get_autorun("PR".substr((date("Y") + 543), -2), "PriceList", "4");

			  	$entity = "UPDATE aicrm_crmentity_seq SET id= '".$entityid[0]['num']."'";
			  	$this->db->query($entity);

			  	$crmentity = "INSERT INTO aicrm_crmentity (crmid, smcreatorid, smownerid, modifiedby, setype, description, createdtime, modifiedtime, viewedtime, status, version, presence, deleted) VALUES ('".$entityid[0]['num']."','".USERID."','".USERID."','".USERID."','Order','".$v['description']."','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."','','',0,1,0)";
			  	$this->db->query($crmentity);
			  	
			  	$int_pricelist = "INSERT INTO aicrm_pricelists(
			  		pricelistid, 	pricelist_no, 	pricelist_name, 	plantid, 	zone, 	distance,	 mat_type,	 truck_size, 	truck_type, 
			  		strength, 		product_id, 	vendor_product_code,lp,			lp_disc,c_cost,		c_cost_vat,	 c_price_vat,	min,dlv_c,
			  		dlv_c_vat,		dlv_p_vat,		pricelist_startdate,status_pricelist) VALUES 
			  		(
			  		'".$entityid[0]['num']."',
			  		'".$cd."',
			  		'".$v['mat_master']."',
			  		'".$v['plantid']."',
			  		'".$v['zone']."',
			  		'".$v['distance']."',
			  		'".$v['mat_type']."',
			  		'".$v['truck_size']."',
			  		'".$v['truck_type']."',
			  		'".$v['strength']."',
			  		'".$v['productid']."',
			  		'".$v['vendor_product_code']."',
			  		'".$v['lp']."',
			  		'".$v['lp_disc']."',
			  		'".$v['c_cost']."',
			  		'".$v['c_cost_vat']."',
			  		'".$v['c_price_vat']."',
			  		'".$v['min']."',
			  		'".$v['dlv_c']."',
			  		'".$v['dlv_c_vat']."',
			  		'".$v['dlv_p_vat']."',
			  		'".$v['pricelist_date']."',
			  		'".$v['status']."'
			  		)";
			  	$this->db->query($int_pricelist);

			  	$int_pricelistcf = "INSERT INTO aicrm_pricelistscf(pricelistid) VALUES ('".$entityid[0]['num']."')";
			  	$this->db->query($int_pricelistcf);

			  	$sql_update = "update tbt_import_pricelist set pricelistid = '".$entityid[0]['num']."' ,pricelistno = '".$cd."' ,update_type='S' ,update_message = 'Success' where import_date ='".$flag_date."' and id = '".$v['id']."' ";
			  	$this->db->query($sql_update);
			}

		}

	  	$sql = "select * from tbt_import_pricelist where import_date = '".$flag_date."'";
		$query = $this->db->query($sql);
		$result = $query->result(0);
		
		return $result;
	}

	function get_autorun($prefix,$module,$lenght){
		$sql="
		SELECT running
		FROM ai_running_doc
		where 1
		and module='".$module."'
		and prefix='".$prefix."' 	";

		$query = $this->db->query($sql);
		$res = $query->result(0);
		$rows = $query->num_rows();
		
		if($rows>0){
			$running = $res[0]['running'];
			$running=$running+1;
			$sql="update ai_running_doc set running='".$running."' where 1 and module='".$module."' and prefix='".$prefix."' ";
			$this->db->query($sql);
		}else{
			$running=1;
			$sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";
			$this->db->query($sql);
		}

		$cd=$prefix."-".str_pad($running,$lenght,"0", STR_PAD_LEFT);
		return $cd;
	}
	
}

?>
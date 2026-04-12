<?php
ini_set('max_execution_time', 36000);
if ( !defined('BASEPATH') )
  exit('No direct script access allowed');
class Point extends MY_Controller
{
  	public function __construct()
  	{
    	parent::__construct();
		parent::__construct();
        $this->load->library('memcached_library');
        $this->load->library('crmentity');
        $this->load->database();
        $this->load->library("common");
        $this->_format = "array";
        $this->load->library('lib_import');
  	}
    
    public function getSalesInvoice(){
        $this->db->select('tbt_import_salesinvoice.crmid , sum(tbt_import_salesinvoice.saleslineamt) as grandtotal')->from('tbt_import_salesinvoice');
        $this->db->where([
            'ifnull(tbt_import_salesinvoice.accountid,"") !=' => '',
            'ifnull(tbt_import_salesinvoice.crmid,"") !=' => '',
            'tbt_import_salesinvoice.saleslineamt >' => '0',
            'tbt_import_salesinvoice.calculate_point' => '0',
            //'tbt_import_salesinvoice.sales_order' => '2280000097'
        ]);
        $this->db->group_by('tbt_import_salesinvoice.crmid'); 
        //$this->db->limit(100);
        
        $sql = $this->db->get(); 
        //echo $this->db->last_query(); exit;
        $result = $sql->result_array();
        $count = $sql->num_rows();
       /* alert($result);
        exit;*/
        foreach($result as $row){
            $this->updateSalesOrder($row['crmid'] , $row['grandtotal']);
        }
    }
    
    public function updateSalesOrder($crmid='',$grandtotal=0){
        $this->load->library('crmentity');
        $this->load->library("common");
        if($crmid==''){
            echo 'No crmid';
            exit();
        }
        $this->db->select('tbt_import_salesinvoice.*, aicrm_account.accountname , aicrm_account.birthdate, aicrm_account.accounttype,aicrm_account.accountgrade,aicrm_salesinvoice.main_channel,aicrm_salesinvoice.sub_channel,aicrm_salesinvoice.salesinvoice_no,aicrm_account.sap_no ,aicrm_account.point_total,aicrm_account.point_used,aicrm_account.point_remaining');
        $this->db->from('tbt_import_salesinvoice');
        $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = tbt_import_salesinvoice.crmid');
        $this->db->join('aicrm_salesinvoice', 'aicrm_salesinvoice.salesinvoiceid = tbt_import_salesinvoice.crmid');
        $this->db->join('aicrm_account', 'aicrm_account.accountid = tbt_import_salesinvoice.accountid');
        $this->db->join('aicrm_accountscf', 'aicrm_accountscf.accountid = tbt_import_salesinvoice.accountid');
        
        $this->db->where([
            'tbt_import_salesinvoice.crmid' => $crmid
        ]);
        $sql = $this->db->get();
        $result = $sql->row_array();
        //alert($result);
        $this->common->_filename= "Calculate_Point_Salesinvoice";
        $this->common->set_log('Before Insert Calculate Point Salesinvoice==>',$this->db->last_query(),$result);
        
        if(!empty($result)){
            
            $salesinvoice_no = $result['salesinvoice_no'];
            $invoice_date = $result['invoice_date'];
            $accountid = $result['accountid'];
            $accounttype = $result['accounttype'];
            $accountgrade = $result['accountgrade'];
            $main_channel = $result['main_channel'];
            $sub_channel = $result['sub_channel'];
            $sap_no = $result['sap_no'];
            
            $point_total = $result['point_total'];
            $point_used = $result['point_used'];
            $point_remaining = $result['point_remaining'];

            $grandtotal = $grandtotal;
            $birthdate = explode('-', $result['birthdate']);
            $birthMonth = @$birthdate['1'];
            
            $totalPrice = '0';
            $finalPoint = 0;
            $ProductPoint = 0;
            $point_birthmonth = 0;
            $messages = [];

            /*Open Products*/
            $pointType2 = $this->calPoint2($crmid, $invoice_date);
            $k_y2 = 1;
            if(!empty($pointType2)){
                $messages[] = '1. ซื้อสินค้า';
            }
            foreach($pointType2 as $cal2){
                $finalPoint = $finalPoint+$cal2['point'];
                if(isset($cal2['msg'])) $messages[] = '1.'.$k_y2.' '.$cal2['msg']; $k_y2++;
            }
            //alert($pointType2);
            /*Closed Products*/
            
            /*Open Accounts Type*/
            $pointType3 = $this->calPoint3($crmid, $invoice_date, $accountid ,$accounttype,$grandtotal,$finalPoint);
            $k_y3 = 1;
            if(!empty($pointType3)){
                $messages[] = '2. ประเภทลูกค้า';
            }
            foreach($pointType3 as $cal3){
                
                $finalPoint = $cal3['point'];
                if(isset($cal3['msg'])) $messages[] = '2.'.$k_y3.' '.$cal3['msg']; $k_y3++;
            }
            //alert($pointType3);
            /*Closed Accounts Type*/
            
            /*Open Accounts Grade*/
            $pointType4 = $this->calPoint4($crmid, $invoice_date, $accountid ,$accountgrade,$grandtotal,$finalPoint);
            $k_y4 = 1;
            if(!empty($pointType4)){
                $messages[] = '3. เกรดลูกค้า';
            }
            foreach($pointType4 as $cal4){
                $finalPoint = $cal4['point'];
                if(isset($cal4['msg'])) $messages[] = '3.'.$k_y4.' '.$cal4['msg']; $k_y4++;
            }
            //alert($pointType4);
            /*Closed Accounts Grade*/
            
            /*Open Sales Channel*/
            //echo $finalPoint;
            $pointType5 = $this->calPoint5($crmid, $invoice_date, $accountid ,$main_channel,$sub_channel,$grandtotal,$finalPoint);
            $k_y5 = 1;
            if(!empty($pointType5)){
                $messages[] = '4. Sales Channel';
            }
            foreach($pointType5 as $cal5){
                $finalPoint = $cal5['point'];
                if(isset($cal5['msg'])) $messages[] = '4.'.$k_y5.' '.$cal5['msg']; $k_y5++;
            }
            //alert($finalPoint);
            /*Closed Sales Channel*/

            /*Open Special Day*/
            $pointType6 = $this->calPoint6($crmid, $invoice_date, $accountid ,$main_channel,$sub_channel,$grandtotal,$finalPoint);
            $k_y6 = 1;
            if(!empty($pointType6)){
                $messages[] = '5. Special Day';
            }
            foreach($pointType6 as $cal6){
                $finalPoint = $cal6['point'];
                
                if(isset($cal6['msg'])) $messages[] = '5.'.$k_y6.' '.$cal6['msg']; $k_y6++;
            }
            //alert($finalPoint);
            /*Closed Special Day*/

            /*Open Birth Month*/
            $pointType7 = $this->calPoint7($crmid, $invoice_date, $birthMonth,$finalPoint,$grandtotal,$accountid,$sap_no);
            switch($pointType7['operator']){
                case '+':
                    $finalPoint = $finalPoint + $pointType7['point'];
                    break;
                case '*':
                    $finalPoint = $finalPoint * $pointType7['point'];
                    break;
            }
            if(isset($pointType7['msg'])) $messages[] = '6. เดือนเกิด';
            if(isset($pointType7['msg'])) $messages[] = $pointType7['msg'];

            if(isset($pointType7['point_birthmonth'])) $point_birthmonth = $pointType7['point_birthmonth'];
            //alert($finalPoint);
            /*Closed Birth Month*/
            /*alert($point_birthmonth); */
            //alert($messages);
            
            if($finalPoint > 0){
                $finalPoint = floor($finalPoint);
                $description = implode('\n', $messages);
                $description .= '\n\nรวม '.$finalPoint.' คะแนน';
                
                //$sap_no
                $remain_point = ($point_remaining+$finalPoint);
                $total_point = ($point_total+$finalPoint);

                $insertData = [[
                        'point_name' => 'Add Point By '.$salesinvoice_no,
                        'point_source' => 'Sales Order', // ช่องทางการได้คะแนน
                        'salesinvoiceid' => $crmid, //Sales Invoice No.
                        'sourcestatus' => 'Add',
                        'points' => $finalPoint,//คะแนน 
                        'remain_point' => $remain_point,//คะแนนสะสม (คงเหลือ)
                        'accountid' => $accountid,//ชื่อลูกค้า
                        'used_point' => $point_used,//คะแนนที่สะสม (ใช้ไป)
                        'total_point' => $total_point,//คะแนนสะสม (ทั้งหมด)
                        'point_start_date' => date('Y-m-d'), //วันที่สร้างคะแนน
                        'point_expired_date' => date('Y', strtotime('+1 year')).'-12-31', //วันที่หมดอายุคะแนน
                        'description' => $description,
                        'smcreatorid' => 1,
                        'smownerid' => 19289,
                    ]];
                    
                    $tabName = ['aicrm_crmentity', 'aicrm_point', 'aicrm_pointcf'];
                    $tabIndex = ['aicrm_crmentity' => 'crmid', 'aicrm_point' => 'pointid', 'aicrm_pointcf' => 'pointid'];
                    list($chk, $point_crmid, $point_DocNo) = $this->crmentity->Insert_Update('Point', $pointID, 'add', $tabName, $tabIndex, $insertData);
                    
                    $params = [
                        'action' => 'add',
                        'brand' => '',
                        'channel' => 'Add Point By '.$salesinvoice_no,
                        'point' => $finalPoint,
                        'accountid' => $accountid,
                        'type' => '',
                        'pointid' => $point_crmid,
                    ];

                    $this->load->library('lib_point');
                    $data = $this->lib_point->get_adjust($params);

                    $this->common->set_log('After Insert Calculate Point Salesinvoice==>',$crmid,$point_crmid);

                //Update Point point_birthmonth
                $this->db->update('aicrm_point', ['point_birthmonth'=>$point_birthmonth], ['aicrm_point'=>$point_crmid]);
            }

            //Update tbt_import_salesinvoice set calculate_point = 1
            $this->db->update('tbt_import_salesinvoice', ['calculate_point'=>'1'], ['crmid'=>$crmid]);
            //alert($point_crmid); exit;
        }
    }
    
    public function calPoint2($crmid, $invoice_date)
    {
        $invoice_date = $invoice_date.' '.date('H:i:s');
        $sql = $this->db->get_where('aicrm_point_config_type2', ['startdate <='=>$invoice_date, 'enddate >='=>$invoice_date]);
        $result = $sql->result_array();
        $point = 0;
        $data = [];
        
        foreach($result as $index => $row){
            $this->db->select('aicrm_products.*, aicrm_productcf.* , aicrm_inventoryproductrelsalesinvoice.total')->from('aicrm_inventoryproductrelsalesinvoice');
            $this->db->join('aicrm_products', 'aicrm_products.productid = aicrm_inventoryproductrelsalesinvoice.productid');
            $this->db->join('aicrm_productcf', 'aicrm_productcf.productid = aicrm_products.productid');
            $this->db->where(['aicrm_inventoryproductrelsalesinvoice.id' => $crmid]);

            if($row['product_brand'] != ''){
                $productbrand = explode(',', $row['product_brand']);
                $this->db->where_in('aicrm_products.product_brand', $productbrand);
            }

            if($row['material_type'] != ''){
                $material_type = explode(',', $row['material_type']);
                $this->db->where_in('aicrm_products.material_type', $material_type);
            }

            if($row['producttype'] != ''){
                $producttype = explode(',', $row['producttype']);
                $this->db->where_in('aicrm_products.producttype', $producttype);
            }

            if($row['productcategory'] != ''){
                $productcategory = explode(',', $row['productcategory']);
                $this->db->where_in('aicrm_products.productcategory', $productcategory);
            }

            $sql = $this->db->get();
            $res = $sql->result_array();

            foreach($res as $arr){
                if($arr['total'] >= $row['minimum']){

                    $data[] = ['bahtperpoint' => $row['bahtperpoint'],'point' =>floor($arr['total']/$row['bahtperpoint']) ,'minimum' => $row['minimum'], 'total' => $arr['total'], 'msg' => 'ซื้อ '.$arr['productname'].' เงื่อนไข '.$row['bahtperpoint'].' บาท / 1 คะแนน ยอดซื้อขั้นต่ำ '.$row['minimum'].' บาท ('.$arr['total'].'/'.$row['bahtperpoint'].') ได้ '.floor($arr['total']/$row['bahtperpoint']).' คะแนน'];
                }
                
            }
        }
        return $data;
    }

    public function calPoint3($crmid, $invoice_date, $accountid ,$accounttype,$grandtotal,$finalPoint)
    {
        $invoice_date = $invoice_date.' '.date('H:i:s');
        $sql = $this->db->get_where('aicrm_point_config_type3', ['startdate <='=>$invoice_date, 'enddate >='=>$invoice_date]);
        $result = $sql->result_array();
        $point = 0;
        $data = [];
        $s_finalPoint = $finalPoint;
        foreach($result as $index => $row){
            
            if($accounttype == $row['accounttype']){
                $operator = $row['operator'] == '' ? '*' : $row['operator'];
                switch($operator){
                    case '+':
                        $Point = $s_finalPoint + $row['number'];
                        break;
                    case '*':
                        $Point = $s_finalPoint * $row['number'];
                        break;
                }
                if($grandtotal >= $row['minimum']){
                    $data[] = ['accounttype' => $row['accounttype'], 'point' => floor($Point) ,'minimum' => $row['minimum'], 'operator' => $operator ,'msg' => 'ลูกค้าประเภท '.$row['accounttype'].' '.$operator.$row['number'].' Point เงื่อนไข ยอดซื้อขั้นต่ำ '.$row['minimum'].' บาท ('.$s_finalPoint.' '.$operator.' '.$row['number'].') ได้ '.floor($Point).' คะแนน'];
                    $s_finalPoint = $Point;
                }
            
            }     
        }
        return $data;
    }

    public function calPoint4($crmid, $invoice_date, $accountid ,$accountgrade,$grandtotal,$finalPoint)
    {
        $invoice_date = $invoice_date.' '.date('H:i:s');
        $sql = $this->db->get_where('aicrm_point_config_type4', ['startdate <='=>$invoice_date, 'enddate >='=>$invoice_date]);
        $result = $sql->result_array();
        $point = 0;
        $data = [];
        $s_finalPoint = $finalPoint;
        foreach($result as $index => $row){
            
            if($accountgrade == $row['accountgrade']){
                $operator = $row['operator'] == '' ? '*' : $row['operator'];
                switch($operator){
                    case '+':
                        $Point = $s_finalPoint + $row['number'];
                        break;
                    case '*':
                        $Point = $s_finalPoint * $row['number'];
                        break;
                }
                if($grandtotal >= $row['minimum']){
                    $data[] = ['accountgrade' => $row['accountgrade'], 'point' => floor($Point) ,'minimum' => $row['minimum'], 'operator' => $operator ,'msg' => 'ลูกค้าเกรด '.$row['accountgrade'].' '.$operator.$row['number'].' Point เงื่อนไข ยอดซื้อขั้นต่ำ '.$row['minimum'].' บาท ('.$s_finalPoint.' '.$operator.' '.$row['number'].') ได้ '.floor($Point).' คะแนน'];
                $s_finalPoint = $Point;
                }
            
            }     
        }
        return $data;
    }

    public function calPoint5($crmid, $invoice_date, $accountid ,$main_channel,$sub_channel,$grandtotal,$finalPoint)
    {
        $invoice_date = $invoice_date.' '.date('H:i:s');
        $sql = $this->db->get_where('aicrm_point_config_type5', ['startdate <='=>$invoice_date, 'enddate >='=>$invoice_date]);
        
        $result = $sql->result_array();
        $point = 0;
        $data = [];
        
        $s_finalPoint = $finalPoint;
        //alert($result); exit;
        foreach($result as $index => $row){
            
            $m_channel = false;
            $s_channel = false;

            if($row['main_channel'] != ''){
                $a_main_channel = explode(',', $row['main_channel']);
                if(in_array($main_channel, $a_main_channel))
                {
                    $m_channel = true;
                }
            }else{
                $m_channel = true;
            }
            
            if($row['sub_channel'] != ''){
               $a_sub_channel = explode(',', $row['sub_channel']);
               if(in_array($sub_channel, $a_sub_channel))
                {
                    $s_channel = true;
                }
            }else{
                $s_channel = true;
            }

            if($m_channel == true && $s_channel == true){
                if($grandtotal >= $row['minimum']){
                    $operator = $row['operator'] == '' ? '*' : $row['operator'];
                    switch($operator){
                        case '+':
                            $Point = $s_finalPoint + $row['number'];
                            break;
                        case '*':
                            $Point = $s_finalPoint * $row['number'];
                            break;
                    }
                    $data[] = ['main_channel' => $main_channel, 'sub_channel' => $sub_channel, 'point' => floor($Point) ,'minimum' => $row['minimum'], 'operator' => $operator ,'msg' => 'ซื้อจาก Main channel '.$main_channel.' / Sub channel '.$sub_channel.' '.$operator.$row['number'].' Point เงื่อนไข ยอดซื้อขั้นต่ำ '.$row['minimum'].' บาท ('.$s_finalPoint.' '.$operator.' '.$row['number'].') ได้ '.floor($Point).' คะแนน'];
                    
                    $s_finalPoint = $Point;

                }
            } 
        }//foreach
        return $data;
    }

    public function calPoint6($crmid, $invoice_date, $accountid ,$main_channel,$sub_channel,$grandtotal,$finalPoint)
    {
        $invoice_date = $invoice_date.' '.date('H:i:s');
        $sql = $this->db->get_where('aicrm_point_config_type6', ['startdate <='=>$invoice_date, 'enddate >='=>$invoice_date]);
        $result = $sql->result_array();
        $point = 0;
        $data = [];
        $s_finalPoint = $finalPoint;
        foreach($result as $index => $row){
            $m_channel = false;
            $s_channel = false;

            if($row['main_channel'] != ''){
                $a_main_channel = explode(',', $row['main_channel']);
                if(in_array($main_channel, $a_main_channel))
                {
                    $m_channel = true;
                }
            }else{
                $m_channel = true;
            }
            
            if($row['sub_channel'] != ''){
               $a_sub_channel = explode(',', $row['sub_channel']);
               if(in_array($sub_channel, $a_sub_channel))
                {
                    $s_channel = true;
                }
            }else{
                $s_channel = true;
            }            

            if($m_channel == true && $s_channel == true){
                
                $operator = $row['operator'] == '' ? '*' : $row['operator'];
                switch($operator){
                    case '+':
                        $Point = $s_finalPoint + $row['number'];
                        break;
                    case '*':
                        $Point = $s_finalPoint * $row['number'];
                        break;
                }
                $data[] = ['main_channel' => $main_channel, 'sub_channel' => $sub_channel, 'point' => floor($Point) ,'minimum' => $row['minimum'], 'operator' => $operator ,'msg' => 'ซื้อจาก Main channel '.$main_channel.' / Sub channel '.$sub_channel.' '.$operator.$row['number'].' Point เงื่อนไข ยอดซื้อขั้นต่ำ '.$row['minimum'].' บาท ('.$s_finalPoint.' '.$operator.' '.$row['number'].') ได้ '.floor($Point).' คะแนน'];

                $s_finalPoint = $Point;
            }   
        }
        return $data;
    }

    public function calPoint7($crmid='', $invoice_date="", $birthMonth="",$finalPoint=0,$grandtotal=0,$accountid,$sap_no='')
    {
        $times = 1;
        $operator = '*';
        if($birthMonth==''){
            return false;
        }
        if($sap_no==''){
            return false;
        }
        if($birthMonth == date('m', strtotime($invoice_date))){            
            $sql = $this->db->get('aicrm_point_config');
            $result = $sql->row_array();
           
            $ordermax = @$result['type5_ordermax'];
            $minimum = @$result['type5_minimum'];
            
            if($grandtotal >= $minimum){
                
                $this->db->select('aicrm_point.*')->from('aicrm_point');
                $this->db->join('aicrm_pointcf', 'aicrm_pointcf.pointid = aicrm_point.pointid');
                $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_point.pointid');
                $this->db->join('aicrm_account', 'aicrm_account.accountid = aicrm_point.accountid');
                $this->db->where([
                    'aicrm_crmentity.deleted' => '0',
                    'Year(aicrm_point.point_start_date)' => date('Y'),
                    'aicrm_point.point_birthmonth' => '1',
                    'aicrm_account.sap_no' => $sap_no,
                ]);
                $sql = $this->db->get(); 
                //$c_result = $sql->result_array();
                $count = $sql->num_rows();//get Order point

                if($count < $ordermax){
                    $operator = $result['type5_operator'] == '' ? '*':$result['type5_operator'];
                    switch($operator){
                        case '+':
                            $Point = $finalPoint + $result['type5'];
                            break;
                        case '*':
                            $Point = $finalPoint * $result['type5'];
                            break;
                    }
                    return ['operator' => $operator, 'point' => $result['type5'], 'msg' => 'เดือนเกิด '.$operator.$result['type5'].' Point ('.$finalPoint.$operator.$result['type5'].') ได้ '.$Point.' คะแนน' ,'point_birthmonth'=> 1];
                }else{
                   return ['operator' => $operator, 'point' => $times,'point_birthmonth'=> 0]; 
                }
            }else{
                return ['operator' => $operator, 'point' => $times,'point_birthmonth'=> 0];
            }
        }else{
            return ['operator' => $operator, 'point' => $times,'point_birthmonth'=> 0];
        }
    }

}
<?php
class omnidashboard_model extends CI_Model { 
	var $my_server;  
 	 
 	function get_leadday($startdate='',$enddate=''){

		$sql = "select 
                count(aicrm_crmentity.crmid) as record , 
                LEFT(aicrm_crmentity.createdtime,10) as day 
                from aicrm_leaddetails
                inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_leaddetails.leadid
                where aicrm_crmentity.deleted = 0 ";
         
        if(isset($startdate) && $startdate !=''){
            $sql .= " AND aicrm_crmentity.createdtime >= '".$startdate."'
                        AND aicrm_crmentity.createdtime <= '".$enddate."' ";
        }else{
            $sql .= " AND aicrm_crmentity.createdtime >= '".date("Y-m-d", strtotime( date( 'Y-m-d' )." -1 months"))."'
                        AND aicrm_crmentity.createdtime <= '".date("Y-m-d")."' ";
        }
        $sql .= " group by LEFT(aicrm_crmentity.createdtime,10) order by aicrm_crmentity.createdtime ASC";

		$query = $this->db->query($sql);
		
		$result = $query->result(0);
		
		return $result;
	}

    function get_leadweek($startdate='',$enddate=''){
        
        $sql = "select 
                count(aicrm_crmentity.crmid) as record , 
                WEEK(aicrm_crmentity.createdtime) as week ,
                YEAR(aicrm_crmentity.createdtime) as year ,
                DATE_SUB(
                  DATE_ADD(MAKEDATE(YEAR(aicrm_crmentity.createdtime), 1), INTERVAL WEEK(aicrm_crmentity.createdtime) WEEK),
                  INTERVAL WEEKDAY(
                  DATE_ADD(MAKEDATE(YEAR(aicrm_crmentity.createdtime), 1), INTERVAL WEEK(aicrm_crmentity.createdtime) WEEK)
                ) +1 DAY) as startweek,
                DATE_SUB(
                  DATE_ADD(MAKEDATE(YEAR(aicrm_crmentity.createdtime), 1), INTERVAL WEEK(aicrm_crmentity.createdtime) WEEK),
                  INTERVAL WEEKDAY(
                  DATE_ADD(MAKEDATE(YEAR(aicrm_crmentity.createdtime), 1), INTERVAL WEEK(aicrm_crmentity.createdtime) WEEK)
                ) -5 DAY) as endweek
                from aicrm_leaddetails
                inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_leaddetails.leadid
                where aicrm_crmentity.deleted = 0 ";

                if(isset($startdate) && $startdate !=''){
                    $sql .= " AND aicrm_crmentity.createdtime >= '".$startdate."'
                                AND aicrm_crmentity.createdtime <= '".$enddate."' ";
                }else{
                    $sql .= " AND aicrm_crmentity.createdtime >= '".date("Y-m-d", strtotime( date( 'Y-m-d' )." -1 months"))."'
                                AND aicrm_crmentity.createdtime <= '".date('Y-m-d')."' ";
                }
                            
                $sql .= "  group by WEEK(aicrm_crmentity.createdtime),YEAR(aicrm_crmentity.createdtime) order by YEAR(aicrm_crmentity.createdtime) ASC , WEEK(aicrm_crmentity.createdtime)ASC ";
        //echo $sql; exit;
        $query = $this->db->query($sql);
        
        $result = $query->result(0);
        
        return $result;
    }

    function get_leadmonth($startdate='',$enddate=''){
        
        $sql = "select 
                    count(aicrm_crmentity.crmid) as record , 
                    MONTH(aicrm_crmentity.createdtime) as month ,
                    YEAR(aicrm_crmentity.createdtime) as year 
                    from aicrm_leaddetails
                    inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_leaddetails.leadid
                    where aicrm_crmentity.deleted = 0 ";
                if(isset($startdate) && $startdate !=''){
                    /*$sql .= " AND aicrm_crmentity.createdtime >= '".$startdate."'
                                AND aicrm_crmentity.createdtime <= '".$enddate."' ";*/
                    $sql .= " AND YEAR(aicrm_crmentity.createdtime) = '".date("Y", strtotime($startdate))."' ";
                }else{
                    $sql .= " AND YEAR(aicrm_crmentity.createdtime) = '".date("Y")."' ";
                    /*$sql .= " AND aicrm_crmentity.createdtime >= '".date("Y-m-d", strtotime( date( 'Y-m-d' )." -3 years"))."'
                                AND aicrm_crmentity.createdtime <= '".date('Y-m-d')."' ";*/
                }
                $sql .= " group by MONTH(aicrm_crmentity.createdtime),YEAR(aicrm_crmentity.createdtime) order by YEAR(aicrm_crmentity.createdtime) ASC , MONTH(aicrm_crmentity.createdtime)ASC ";

        $query = $this->db->query($sql);
        
        $result = $query->result(0);
        
        return $result;
    }

    function get_leadstatusmonth($startdate='',$enddate=''){
        
        $sql = "SELECT sum(dup.record_create) as record_create , sum(dup.record_convert) as record_convert , dup.month , dup.year  from (
                    SELECT 
                    COUNT(aicrm_crmentity.crmid) AS record_create,
                    0 as record_convert,
                    MONTH(aicrm_crmentity.createdtime) AS month,
                    YEAR(aicrm_crmentity.createdtime) AS year
                    FROM aicrm_leaddetails
                    INNER JOIN aicrm_leadscf ON aicrm_leadscf.leadid = aicrm_leaddetails.leadid
                    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_leaddetails.leadid
                    WHERE aicrm_crmentity.deleted = 0 ";
                    if(isset($startdate) && $startdate !=''){
                        $sql .= " AND aicrm_crmentity.createdtime >= '".$startdate."'
                                AND aicrm_crmentity.createdtime <= '".$enddate."' ";
                    }else{
                        $sql .= " AND aicrm_crmentity.createdtime >= '".date("Y-m-d", strtotime( date( 'Y-m-d' )." -6 MONTH"))."'
                                AND aicrm_crmentity.createdtime <= '".date('Y-m-d')."' ";
                    }
   
                    $sql .= " GROUP BY MONTH(aicrm_crmentity.createdtime) , YEAR(aicrm_crmentity.createdtime)
                    union all
                    SELECT
                    0 as record_create,
                    COUNT(aicrm_crmentity.crmid) AS record_convert,
                    MONTH(aicrm_convert_lead2acc.createdate) as month ,
                    YEAR(aicrm_convert_lead2acc.createdate) as year
                    FROM aicrm_leaddetails
                    INNER JOIN aicrm_leadscf ON aicrm_leadscf.leadid = aicrm_leaddetails.leadid
                    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_leaddetails.leadid
                    INNER JOIN aicrm_convert_lead2acc ON aicrm_convert_lead2acc.leadid = aicrm_leaddetails.leadid
                    WHERE aicrm_crmentity.deleted = 0 ";
                    if(isset($startdate) && $startdate !=''){
                        $sql .= "  AND aicrm_convert_lead2acc.createdate >= '".$startdate."'
                                    AND aicrm_convert_lead2acc.createdate <= '".$enddate."' ";
                     }else{
                        $sql .= " AND aicrm_convert_lead2acc.createdate >= '".date("Y-m-d", strtotime( date( 'Y-m-d' )." -6 MONTH"))."'
                                AND aicrm_convert_lead2acc.createdate <= '".date('Y-m-d')."' ";
                    }
                    $sql .= " GROUP BY MONTH(aicrm_convert_lead2acc.createdate) , YEAR(aicrm_convert_lead2acc.createdate)
                ) as dup group by dup.month , dup.year
                ORDER BY dup.year ASC , dup.month ASC ";

        //echo $sql; exit;
        $query = $this->db->query($sql);
        
        $result = $query->result(0);
        
        return $result;
    }

    function get_leadsourcemonth($startdate='',$enddate=''){
       
        $sql = "select 
                count(aicrm_crmentity.crmid) as record , 
                ifnull(aicrm_leaddetails.leadsource,'') as leadsource , 
                MONTH(aicrm_crmentity.createdtime) as month ,
                YEAR(aicrm_crmentity.createdtime) as year 
                from aicrm_leaddetails
                inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_leaddetails.leadid
                where aicrm_crmentity.deleted = 0 ";

        $sql .= " AND aicrm_leaddetails.leadsource is not null ";

        //date("Y-m-d", strtotime( date( 'Y-m-d' )." -6 months"))
        if(isset($startdate) && $startdate !=''){
            $sql .= " AND aicrm_crmentity.createdtime >= '".$startdate."'
                        AND aicrm_crmentity.createdtime <= '".$enddate."' ";
        }else{
            $sql .= " AND aicrm_crmentity.createdtime >= '".date("Y-m-d", strtotime( date( 'Y-m-d' )." -6 months"))."'
                        AND aicrm_crmentity.createdtime <= '".date("Y-m-d")."' ";
        }
        $sql .= " group by aicrm_leaddetails.leadsource,MONTH(aicrm_crmentity.createdtime),YEAR(aicrm_crmentity.createdtime) order by YEAR(aicrm_crmentity.createdtime) ASC , MONTH(aicrm_crmentity.createdtime)ASC ,aicrm_leaddetails.leadsource ASC";

        $query = $this->db->query($sql);
        
        $result = $query->result(0);
        
        return $result;
    }

    function get_leadsourcequater($startdate='',$enddate=''){
       
        $sql = "SELECT 
            COUNT(aicrm_crmentity.crmid) AS record,
            ifnull(aicrm_leaddetails.leadsource,'') as leadsource , 
            YEAR(aicrm_crmentity.createdtime) AS year,
            QUARTER(aicrm_crmentity.createdtime) as quarter
        FROM aicrm_leaddetails
        INNER JOIN aicrm_leadscf ON aicrm_leadscf.leadid = aicrm_leaddetails.leadid
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_leaddetails.leadid
        WHERE aicrm_crmentity.deleted = 0 ";

            $sql .= " AND aicrm_leaddetails.leadsource is not null ";
            //date("Y-m-d", strtotime( date( 'Y-m-d' )." -6 months"))
            if(isset($startdate) && $startdate !=''){
                $sql .= " AND YEAR(aicrm_crmentity.createdtime) = '".date("Y", strtotime($startdate))."' ";
            }else{
                $sql .= " AND YEAR(aicrm_crmentity.createdtime) = '".date('Y')."' ";
            }
            $sql .= " GROUP BY aicrm_leaddetails.leadsource , QUARTER(aicrm_crmentity.createdtime) , YEAR(aicrm_crmentity.createdtime) ORDER BY YEAR(aicrm_crmentity.createdtime) ASC , QUARTER(aicrm_crmentity.createdtime) ASC , aicrm_leaddetails.leadsource ASC";

        $query = $this->db->query($sql);
        
        $result = $query->result(0);
        
        return $result;
    }

    function get_leadsourcepicklist(){
        $sql = "SELECT * FROM db_moaioc.aicrm_leadsource where presence = 1 order by leadsourceid asc ";
        $query = $this->db->query($sql);
        
        $result = $query->result(0);
        
        return $result;
    }

    function get_datacustomer(){

        $result = array();
        $sql = "SELECT message_customer.customerid ,message_customer.channel FROM message_customer";
        $query = $this->db->query($sql);
        $data = $query->result(0);
        
        $sql1 = "SELECT count(*) as record FROM message_chathistory";
        $query1 = $this->db->query($sql1);
        $data_chat = $query1->result(0);

        $sql2 = "SELECT count(*) as record FROM message_chathistory where channel = 'facebook' ";
        $query2 = $this->db->query($sql2);
        $chat_facebook = $query2->result(0);

        $sql3 = "SELECT count(*) as record FROM message_chathistory where channel = 'line' ";
        $query3 = $this->db->query($sql3);
        $chat_line = $query3->result(0);

        $result['customer'] = $data;
        $result['chat'] = $data_chat[0];
        $result['chat_facebook'] = $chat_facebook[0];
        $result['chat_line'] = $chat_line[0];
        //alert($result); exit;
        return $result;
    }

}

?>
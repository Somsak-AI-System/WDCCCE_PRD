<?php
class Report_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('relate_model');
        $this->primarymodule = '';
    }

    public function Get_Primary_Module($restricted_modules=['Events','Webmails']){
        $sql = $this->db->get('aicrm_tab');
        $rs_tabs = $sql->result_array();
        $tabs_id = [];
        foreach($rs_tabs as $tab){
            if($tab['presence'] == '1') continue;      // skip disabled modules
            if($tab['isentitytype'] != '1') continue;  // skip extension modules
            if(in_array($tab['name'], $restricted_modules)) { // skip restricted modules
                continue;
            }

            if($tab['name']!='Calendar'){
                $tabs_id[$tab['tabid']] = $tab['tablabel'];
            } else {
                $tabs_id[9] = $tab['tablabel'];
                $tabs_id[16] = $tab['tablabel'];

            }
        }
        $tabids = array_keys($tabs_id);

        $this->db->select('blockid, blocklabel, tabid')->from('aicrm_blocks');
        $this->db->where_in('tabid', $tabids);
        $sql = $this->db->get();
        $rs_modules = $sql->result_array();

        $modules = [];
        foreach($rs_modules as $module){
            $blockid = $module['blockid'];
            $blocklabel = $module['blocklabel'];
            $tab_module = $tabs_id[$module['tabid']];

            if(!empty($blocklabel)){
                if($tab_module == 'Calendar'){ //&& $blocklabel == 'LBL_CUSTOM_INFORMATION'
                    //$modules[$tab_module][$blockid] = $blocklabel;
                    $modules[16] = [ // 9,16
                        'tabid' => 16,
                        'name' => $tab_module
                    ];
                } else {
                    //$modules[$tab_module][$blockid] = $blocklabel;
                    $modules[$module['tabid']] = [
                        'tabid' => $module['tabid'],
                        'name' => $tab_module
                    ];
                }
            }
        }
        return $modules;
    }

    public function Get_Relate_Module($restricted_modules=['Events','Webmails']){
        $query = "SELECT
                aicrm_tab.name,
                aicrm_relatedlists.tabid,
                aicrm_relatedlists.related_tabid
            FROM
                aicrm_tab
            INNER JOIN aicrm_relatedlists ON aicrm_tab.tabid = aicrm_relatedlists.related_tabid
            WHERE
                aicrm_tab.isentitytype = 1
            AND aicrm_tab.name NOT IN ('". implode("','", $restricted_modules) ."')
            AND aicrm_tab.presence = 0
            AND aicrm_relatedlists.label != 'Activity History'
            UNION
            SELECT
                module,
                aicrm_tab.tabid,
                aicrm_fieldmodulerel.module
            FROM
                aicrm_fieldmodulerel
            INNER JOIN aicrm_tab ON aicrm_tab.name = aicrm_fieldmodulerel.relmodule
            INNER JOIN aicrm_tab AS aicrm_tabrel ON aicrm_tabrel.name = aicrm_fieldmodulerel.module
            AND aicrm_tabrel.presence = 0
            INNER JOIN aicrm_field ON aicrm_field.fieldid = aicrm_fieldmodulerel.fieldid
            WHERE
                aicrm_tab.isentitytype = 1
            AND aicrm_tab.name NOT IN ('". implode("','", $restricted_modules) ."')
            AND aicrm_tab.presence = 0
            AND aicrm_field.fieldname NOT LIKE 'cf_%'";
        $sql = $this->db->query($query);
        $rs_relate_tabs = $sql->result_array();

        $relate_modules = [];
        foreach($rs_relate_tabs as $relate_tab){
            if(isset($relate_modules[$relate_tab['tabid']])){
                $relate_modules[$relate_tab['tabid']][] = [
                    'tabid' => $relate_tab['related_tabid'],
                    'name' => $relate_tab['name']
                ];
            }else{
                $relate_modules[$relate_tab['tabid']] = [];
                $relate_modules[$relate_tab['tabid']][] = [
                    'tabid' => $relate_tab['related_tabid'],
                    'name' => $relate_tab['name']
                ];
            }
        }

        return $relate_modules;
    }

    public function Get_Blocks_Fields($tabid){
        $this->db->select('aicrm_field.*, aicrm_blocks.blocklabel, aicrm_tab.tablabel');
        $this->db->join('aicrm_blocks', 'aicrm_blocks.blockid=aicrm_field.block', 'inner');
        $this->db->join('aicrm_tab', 'aicrm_tab.tabid=aicrm_field.tabid', 'inner');
        $this->db->order_by('aicrm_blocks.sequence, aicrm_field.sequence');
        $this->db->where(['aicrm_field.tabid'=>$tabid]);
        $sql = $this->db->get('aicrm_field');
        $result = $sql->result_array();

        return $result;
    }

    public function Settings_Groups_Member_Model(){
        $return = [];
        $sql = $this->db->get_where('aicrm_users', ['status'=>'Active']);
        $rs_users = $sql->result_array();
        foreach($rs_users as $user){
            $return[] = [
                'type' => 'Users',
                'id' => 'Users:'.$user['id'],
                'name' => $user['first_name'].' '.$user['last_name']
            ];
        }

        $sql = $this->db->get('aicrm_groups');
        $rs_groups = $sql->result_array();
        foreach($rs_groups as $group){
            $return[] = [
                'type' => 'Groups',
                'id' => 'Groups:'.$group['groupid'],
                'name' => $group['groupname']
            ];
        }

        $this->db->order_by('parentrole');
        $sql = $this->db->get_where('aicrm_role', ['depth !='=>0]);
        $rs_roles = $sql->result_array();
        foreach($rs_roles as $role){
            $return[] = [
                'type' => 'Roles',
                'id' => 'Roles:'.$role['roleid'],
                'name' => $role['rolename']
            ];
        }

        return $return;
    }

    public function getTabInfo($tabid){
        $tabid = in_array($tabid, [9,16]) ? 9:$tabid;

        $this->db->join('aicrm_entityname', 'aicrm_entityname.tabid=aicrm_tab.tabid', 'inner');
        $sql = $this->db->get_where('aicrm_tab', ['aicrm_tab.tabid'=>$tabid]);
        $rs = $sql->row_array();
        return $rs;
    }

    public function getReportsQuery($data){
        $this->primarymodule = $data['report']['primarymodule'];
        $modules = [];
        $relate_module = $data['report']['secondarymodules_id']!='' ? explode(':', $data['report']['secondarymodules_id']):[];
        $modules[] = $data['report']['primarymodule_id'];
        $modules = array_merge($modules, $relate_module);

        $report_data = json_decode($data['report']['data'], true);

        $selectd_field = $this->getAdvSelectedField($report_data['datafields'], $report_data['groupbyfield']); //echo $selectd_field; exit();
        $from = $this->getReportsTableQuery($data['report']['primarymodule'], $data['report']['secondarymodules']);
        $relate = $this->getRelationTablesQuery($data['report']['primarymodule'], $data['report']['secondarymodules_id']);
        $where = $this->generateAdvFilterSql($data['conditions']);
        $group_by = $this->getAdvGroupby($report_data['groupbyfield']);

        $sql = '';
        $sql .= 'SELECT '.$selectd_field;
        $sql .= ' '.$from;
        $sql .= ' '.implode(' ', $relate);
        $sql .= ' WHERE aicrm_crmentity.deleted = 0'.$where;
        $sql .= $group_by;
        //        $sql .= ' LIMIT 10';
        //        echo $sql;
        return $sql;
    }

    public function getAdvSelectedField($advDataField=[], $groupby){
        $selected = [];
        $field = explode(':', $groupby);
        $selected_group = '';
        if($field[1] == 'branchid'){
            $selected_group = 'aicrm_branchs.branch_name as branchid';
        }else if(in_array($field[1], ['smownerid'])){
            if($field[0]=='aicrm_crmentity'){
                $selected_group = 'CONCAT(aicrm_users.first_name," ",aicrm_users.last_name) as smownerid';
            }
        }else{
            $selected_group = $field[0].'.'.$field[1];
        }
        $selected[] = $selected_group;

        foreach($advDataField as $row){
            $field = explode(':', $row);
            if($field[0] == 'RECORD_COUNT'){
                $selected[] = 'count(*) AS RECORD_COUNT';
            }else{
                $selected[] = 'IFNULL('.$field[5].'('.$field[0].'.'.$field[1].'), 0) AS '.$field[5].'_'.$field[1];
            }

        }
        $selected = implode(', ', $selected);
        return $selected;
    }

    public function generateAdvFilterSql($advfilterlist=[]){
        $dateSpecificConditions = $this->getStdFilterConditions();
        $sql = '';
        $and = [];
        $or = [];

        foreach ($advfilterlist as $columnindex => $columninfo) {
            $fieldcolname = $columninfo["columnname"];
            $comparator = $columninfo["comparator"];
            $value = $columninfo["value"];
            $columncondition = $columninfo["column_condition"];
            $groupid = $columninfo["groupid"];
            $advcolsql = [];

            $selectedFields = explode(':', $fieldcolname);
            $moduleTable = $selectedFields[0];
            $datatype = $selectedFields[4];

            $columnName = $moduleTable.'.'.$selectedFields[1];
            if($moduleTable=="aicrm_activity"){
                if(in_array($datatype, ['D'])){
                    $value = convertDateTimeToDB($value);

                    if($selectedFields[1]=="date_start"){
                        $columnName = "CONCAT(".$moduleTable.".date_start,'',".$moduleTable.".time_start)";
                    }
                }
            }

            list($com, $moduleName) = explode('_', $moduleTable, 2);
            $emailTableName = '';
            if ($moduleName == "Emails" && $moduleName != $this->primarymodule && $selectedFields[0] == "aicrm_activity") {
                $emailTableName = "aicrm_activityEmails";
            }

            if ($fieldcolname != "" && $comparator != "") {
                if (in_array($comparator, $dateSpecificConditions)) {
                }else{
                    if($groupid == 1){ // and
                        $and[] = $columnName.$this->getAdvComparator($comparator, $value, $datatype, $columnName);
                    }else{ // or
                        $or[] = $columnName.$this->getAdvComparator($comparator, $value, $datatype, $columnName);
                    }
                }
            }
        }

        if(!empty($and)){
            $and = ' ( '.implode(' AND ', $and).' ) ';
        }

        if(!empty($or)){
            $or = ' ( '.implode(' OR ', $or).' ) ';
        }

        if(!empty($and) && !empty($or)){
            $sql = ' AND ( '.$and.' AND '.$or.' ) ';
        }else if(!empty($and)){
            $sql = ' AND '. $and;
        }else if(!empty($or)){
            $sql = ' AND '. $or;
        }

        return $sql;
    }

    public function getAdvGroupby($advfiltergroup){
        $filter = explode(':', $advfiltergroup);
        $group_by = ' GROUP BY '.$filter[0].'.'.$filter[1];
        return $group_by;
    }

    public function getAdvComparator($comparator, $value, $datatype = "", $columnName = '') {

        $default_charset = 'UTF-8';
        $value = html_entity_decode(trim($value), ENT_QUOTES, $default_charset);
        $value_len = strlen($value);
        $is_field = false;
        if ($value_len > 1 && $value[0] == '$' && $value[$value_len - 1] == '$') {
            $temp = str_replace('$', '', $value);
            $is_field = true;
        }
        if ($datatype == 'C') {
            $value = str_replace("yes", "1", str_replace("no", "0", $value));
        }

        if ($comparator == "e" || $comparator == 'y') {
            if (trim($value) == "NULL") {
                $rtvalue = " is NULL";
            } elseif (trim($value) != "") {
                $rtvalue = " = '" . $value . "'";
            } elseif (trim($value) == "" && $datatype == "V") {
                $rtvalue = " = '" . $value . "'";
            } else {
                $rtvalue = " is NULL";
            }
        }
        if ($comparator == "n" || $comparator == 'ny') {
            if (trim($value) == "NULL") {
                $rtvalue = " is NOT NULL";
            } elseif (trim($value) != "") {
                if ($columnName)
                    $rtvalue = " <> '" . $value . "' OR " . $columnName . " IS NULL ";
                else
                    $rtvalue = " <> '" . $value . "'";
            }elseif (trim($value) == "" && $datatype == "V") {
                $rtvalue = " <> '" . $value . "'";
            } else {
                $rtvalue = " is NOT NULL";
            }
        }
        if ($comparator == "s") {
            $rtvalue = " like '" . $this->formatForSqlLike($value, 2, $is_field) . "'";
        }
        if ($comparator == "ew") {
            $rtvalue = " like '" . $this->formatForSqlLike($value, 1, $is_field) . "'";
        }
        if ($comparator == "c") {
            $rtvalue = " like '" . $this->formatForSqlLike($value, 0, $is_field) . "'";
        }
        if ($comparator == "k") {
            $rtvalue = " not like '" . $this->formatForSqlLike($value, 0, $is_field) . "'";
        }
        if ($comparator == "l") {
            $rtvalue = " < '" . $value. "'";
        }
        if ($comparator == "g") {
            $rtvalue = " > '" . $value . "'";
        }
        if ($comparator == "m") {
            $rtvalue = " <= '" . $value . "'";
        }
        if ($comparator == "h") {
            $rtvalue = " >= '" . $value . "'";
        }
        if ($comparator == "b") {
            $rtvalue = " < '" . $value . "'";
        }
        if ($comparator == "a") {
            $rtvalue = " > '" . $value . "'";
        }
        if ($is_field == true) {
            $rtvalue = str_replace("'", "", $rtvalue);
            $rtvalue = str_replace("\\", "", $rtvalue);
        }
        return $rtvalue;
    }

    function formatForSqlLike($str, $flag=0, $is_field=false) {
        if (isset($str)) {
            if($is_field==false){
                $str = str_replace('%', '\%', $str);
                $str = str_replace('_', '\_', $str);
                if ($flag == 0) {
                    if(empty($str))
                        $str = ''.$str.'';
                    else
                        $str = '%'. $str .'%';
                } elseif ($flag == 1) {
                    $str = '%'. $str;
                } elseif ($flag == 2) {
                    $str = $str .'%';
                }
            } else {
                if ($flag == 0) {
                    $str = 'concat("%",'. $str .',"%")';
                } elseif ($flag == 1) {
                    $str = 'concat("%",'. $str .')';
                } elseif ($flag == 2) {
                    $str = 'concat('. $str .',"%")';
                }
            }
        }
        return $str;
    }

    public function getAdvancedFilterOptions() {
        return array(
            'e' => 'LBL_EQUALS',
            'n' => 'LBL_NOT_EQUAL_TO',
            's' => 'LBL_STARTS_WITH',
            'ew' => 'LBL_ENDS_WITH',
            'c' => 'LBL_CONTAINS',
            'k' => 'LBL_DOES_NOT_CONTAIN',
            'l' => 'LBL_LESS_THAN',
            'g' => 'LBL_GREATER_THAN',
            'm' => 'LBL_LESS_THAN_OR_EQUAL',
            'h' => 'LBL_GREATER_OR_EQUAL',
            'b' => 'LBL_BEFORE',
            'a' => 'LBL_AFTER',
            'bw' => 'LBL_BETWEEN',
            'y' => 'LBL_IS_EMPTY',
            'ny'=> 'LBL_IS_NOT_EMPTY',
            'lessthanhoursbefore' => 'LBL_LESS_THAN_HOURS_BEFORE',
            'lessthanhourslater' => 'LBL_LESS_THAN_HOURS_LATER',
            'morethanhoursbefore' => 'LBL_MORE_THAN_HOURS_BEFORE',
            'morethanhourslater' => 'LBL_MORE_THAN_HOURS_LATER',
        );
    }

    public function getAdvancedFilterOpsByFieldType() {
        return array(
            'V' => array('e','n','s','ew','c','k','y','ny'),
            'N' => array('e','n','l','g','m','h', 'y','ny'),
            'T' => array('e','n','l','g','m','h','bw','b','a','y','ny'),
            'I' => array('e','n','l','g','m','h','y','ny'),
            'C' => array('e','n','y','ny'),
            'D' => array('e','n','g','l','b','a','y','ny'),
            'DT' => array('e','n','bw','b','a','y','ny','lessthanhoursbefore','lessthanhourslater','morethanhoursbefore','morethanhourslater'),
            'NN' => array('e','n','l','g','m','h','y','ny'),
            'E' => array('e','n','s','ew','c','k','y','ny')
        );
    }

    public function getAggregateFunctions(){
        $functions = array('SUM','AVG','MIN','MAX');
        return $functions;
    }

    public function getStdFilterConditions() {
        return Array("custom","prevfy" ,"thisfy" ,"nextfy","prevfq",
            "thisfq","nextfq","yesterday","today","tomorrow",
            "lastweek","thisweek","nextweek","lastmonth","thismonth",
            "nextmonth","last7days","last14days","last30days","last60days","last90days",
            "last120days","next30days","next60days","next90days","next120days",
        );
    }

    public function requireTable($table){
        return false;
    }

    public function getReportsTableQuery($module, $secondarymodules, $type = '') {
        $secondary_module = explode(':', $secondarymodules);
        $query = '';
        if ($module == "Leads") {
            $query = "from aicrm_leaddetails
				inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_leaddetails.leadid";

            if ($this->requireTable('aicrm_leadsubdetails')) {
                $query .= "	inner join aicrm_leadsubdetails on aicrm_leadsubdetails.leadsubscriptionid=aicrm_leaddetails.leadid";
            }
            if ($this->requireTable('aicrm_leadaddress')) {
                $query .= "	inner join aicrm_leadaddress on aicrm_leadaddress.leadaddressid=aicrm_leaddetails.leadid";
            }
            if ($this->requireTable('aicrm_leadscf')) {
                $query .= " inner join aicrm_leadscf on aicrm_leaddetails.leadid = aicrm_leadscf.leadid";
            }
            if ($this->requireTable('aicrm_groupsLeads')) {
                $query .= "	left join aicrm_groups as aicrm_groupsLeads on aicrm_groupsLeads.groupid = aicrm_crmentity.smownerid";
            }
            if ($this->requireTable('aicrm_usersLeads')) {
                $query .= " left join aicrm_users as aicrm_usersLeads on aicrm_usersLeads.id = aicrm_crmentity.smownerid";
            }

            $query .= " left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid
				left join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid";

            if ($this->requireTable('aicrm_lastModifiedByLeads')) {
                $query .= " left join aicrm_users as aicrm_lastModifiedByLeads on aicrm_lastModifiedByLeads.id = aicrm_crmentity.modifiedby";
            }
            if ($this->requireTable('aicrm_createdbyLeads')) {
                $query .= " left join aicrm_users as aicrm_createdbyLeads on aicrm_createdbyLeads.id = aicrm_crmentity.smcreatorid";
            }

        //            $focus = CRMEntity::getInstance($module);
        //            $relquery = $focus->getReportsUiType10Query($module, $this->queryPlanner);
        //            $query .= $relquery . ' ';
        //
        //            $query .= " " . $this->getRelatedModulesQuery($module,$this->secondarymodule).
        //                getNonAdminAccessControlQuery($this->primarymodule,$current_user).
        //                " where aicrm_crmentity.deleted=0 and aicrm_leaddetails.converted=0";
        } else if ($module == "Accounts") {
            $query = "from aicrm_account
				inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_account.accountid";

            if ($this->requireTable('aicrm_accountbillads')) {
                $query .= " inner join aicrm_accountbillads on aicrm_account.accountid=aicrm_accountbillads.accountaddressid";
            }
            if ($this->requireTable('aicrm_accountshipads')) {
                $query .= " inner join aicrm_accountshipads on aicrm_account.accountid=aicrm_accountshipads.accountaddressid";
            }
            if ($this->requireTable('aicrm_accountscf')) {
                $query .= " inner join aicrm_accountscf on aicrm_account.accountid = aicrm_accountscf.accountid";
            }
            if ($this->requireTable('aicrm_groupsAccounts')) {
                $query .= " left join aicrm_groups as aicrm_groupsAccounts on aicrm_groupsAccounts.groupid = aicrm_crmentity.smownerid";
            }
            if ($this->requireTable('aicrm_accountAccounts')) {
                $query .= "	left join aicrm_account as aicrm_accountAccounts on aicrm_accountAccounts.accountid = aicrm_account.parentid";
            }
            if ($this->requireTable('aicrm_usersAccounts')) {
                $query .= " left join aicrm_users as aicrm_usersAccounts on aicrm_usersAccounts.id = aicrm_crmentity.smownerid";
            }

            $query .= " left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid
				left join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid";

            if ($this->requireTable('aicrm_lastModifiedByAccounts')) {
                $query.= " left join aicrm_users as aicrm_lastModifiedByAccounts on aicrm_lastModifiedByAccounts.id = aicrm_crmentity.modifiedby";
            }
            if ($this->requireTable('aicrm_createdbyAccounts')) {
                $query .= " left join aicrm_users as aicrm_createdbyAccounts on aicrm_createdbyAccounts.id = aicrm_crmentity.smcreatorid";
            }

        //            $focus = CRMEntity::getInstance($module);
        //            $relquery = $focus->getReportsUiType10Query($module, $this->queryPlanner);
        //            $query .= $relquery . ' ';
        //
        //            $query .= " ".$this->getRelatedModulesQuery($module,$this->secondarymodule).
        //                getNonAdminAccessControlQuery($this->primarymodule,$current_user).
        //                " where aicrm_crmentity.deleted=0 ";
        } else if ($module == "Contacts") {
            $query = "from aicrm_contactdetails
				inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_contactdetails.contactid";

            if ($this->requireTable('aicrm_contactaddress')) {
                $query .= "	inner join aicrm_contactaddress on aicrm_contactdetails.contactid = aicrm_contactaddress.contactaddressid";
            }
            if ($this->requireTable('aicrm_customerdetails')) {
                $query .= "	inner join aicrm_customerdetails on aicrm_customerdetails.customerid = aicrm_contactdetails.contactid";
            }
            if ($this->requireTable('aicrm_contactsubdetails')) {
                $query .= "	inner join aicrm_contactsubdetails on aicrm_contactdetails.contactid = aicrm_contactsubdetails.contactsubscriptionid";
            }
            if ($this->requireTable('aicrm_contactscf')) {
                $query .= "	inner join aicrm_contactscf on aicrm_contactdetails.contactid = aicrm_contactscf.contactid";
            }
            if ($this->requireTable('aicrm_groupsContacts')) {
                $query .= " left join aicrm_groups aicrm_groupsContacts on aicrm_groupsContacts.groupid = aicrm_crmentity.smownerid";
            }
            if ($this->requireTable('aicrm_contactdetailsContacts')) {
                $query .= "	left join aicrm_contactdetails as aicrm_contactdetailsContacts on aicrm_contactdetailsContacts.contactid = aicrm_contactdetails.reportsto";
            }
            if ($this->requireTable('aicrm_accountContacts')) {
                $query .= "	left join aicrm_account as aicrm_accountContacts on aicrm_accountContacts.accountid = aicrm_contactdetails.accountid";
            }
            if ($this->requireTable('aicrm_usersContacts')) {
                $query .= " left join aicrm_users as aicrm_usersContacts on aicrm_usersContacts.id = aicrm_crmentity.smownerid";
            }

            $query .= " left join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid
				left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid";

            if ($this->requireTable('aicrm_lastModifiedByContacts')) {
                $query .= " left join aicrm_users as aicrm_lastModifiedByContacts on aicrm_lastModifiedByContacts.id = aicrm_crmentity.modifiedby";
            }
            if ($this->requireTable('aicrm_createdbyContacts')) {
                $query .= " left join aicrm_users as aicrm_createdbyContacts on aicrm_createdbyContacts.id = aicrm_crmentity.smcreatorid";
            }

        //            $focus = CRMEntity::getInstance($module);
        //            $relquery = $focus->getReportsUiType10Query($module, $this->queryPlanner);
        //            $query .= $relquery . ' ';
        //
        //            $query .= " ".$this->getRelatedModulesQuery($module,$this->secondarymodule).
        //                getNonAdminAccessControlQuery($this->primarymodule,$current_user).
        //                " where aicrm_crmentity.deleted=0";
        } else if ($module == "Products") {
            $query .= " from aicrm_products";
            $query .= " inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_products.productid";
            if ($this->requireTable("aicrm_productcf")) {
                $query .= " left join aicrm_productcf on aicrm_products.productid = aicrm_productcf.productid";
            }
            if ($this->requireTable("aicrm_lastModifiedByProducts")) {
                $query .= " left join aicrm_users as aicrm_lastModifiedByProducts on aicrm_lastModifiedByProducts.id = aicrm_crmentity.modifiedby";
            }
            if ($this->requireTable('aicrm_createdbyProducts')) {
                $query .= " left join aicrm_users as aicrm_createdbyProducts on aicrm_createdbyProducts.id = aicrm_crmentity.smcreatorid";
            }
            if ($this->requireTable("aicrm_usersProducts")) {
                $query .= " left join aicrm_users as aicrm_usersProducts on aicrm_usersProducts.id = aicrm_crmentity.smownerid";
            }
            if ($this->requireTable("aicrm_groupsProducts")) {
                $query .= " left join aicrm_groups as aicrm_groupsProducts on aicrm_groupsProducts.groupid = aicrm_crmentity.smownerid";
            }
            if ($this->requireTable("aicrm_vendorRelProducts")) {
                $query .= " left join aicrm_vendor as aicrm_vendorRelProducts on aicrm_vendorRelProducts.vendorid = aicrm_products.vendor_id";
            }
            if ($this->requireTable("innerProduct")) {
                $query .= " LEFT JOIN (
						SELECT aicrm_products.productid,
								(CASE WHEN (aicrm_products.currency_id = 1 ) THEN aicrm_products.unit_price
									ELSE (aicrm_products.unit_price / aicrm_currency_info.conversion_rate) END
								) AS actual_unit_price
						FROM aicrm_products
						LEFT JOIN aicrm_currency_info ON aicrm_products.currency_id = aicrm_currency_info.id
						LEFT JOIN aicrm_productcurrencyrel ON aicrm_products.productid = aicrm_productcurrencyrel.productid
						AND aicrm_productcurrencyrel.currencyid = " . $current_user->currency_id . "
				) AS innerProduct ON innerProduct.productid = aicrm_products.productid";
            }

        //            $focus = CRMEntity::getInstance($module);
        //            $relquery = $focus->getReportsUiType10Query($module, $this->queryPlanner);
        //            $query .= $relquery . ' ';
        //
        //            $query .= " ".$this->getRelatedModulesQuery($module,$this->secondarymodule).
        //                getNonAdminAccessControlQuery($this->primarymodule,$current_user)."
        //				where aicrm_crmentity.deleted=0";
        } else if ($module == "HelpDesk") {
            $query = "from aicrm_troubletickets inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_troubletickets.ticketid";

            if ($this->requireTable('aicrm_ticketcf')) {
                $query .= " inner join aicrm_ticketcf on aicrm_ticketcf.ticketid = aicrm_troubletickets.ticketid";
            }
        //            if ($this->requireTable('aicrm_crmentityRelHelpDesk', $matrix)) {
        //                $query .= " left join aicrm_crmentity as aicrm_crmentityRelHelpDesk on aicrm_crmentityRelHelpDesk.crmid = aicrm_troubletickets.parent_id";
        //            }
            if ($this->requireTable('aicrm_accountRelHelpDesk')) {
                $query .= " left join aicrm_account as aicrm_accountRelHelpDesk on aicrm_accountRelHelpDesk.accountid=aicrm_crmentityRelHelpDesk.crmid";
            }
            if ($this->requireTable('aicrm_contactdetailsRelHelpDesk')) {
                $query .= " left join aicrm_contactdetails as aicrm_contactdetailsRelHelpDesk on aicrm_contactdetailsRelHelpDesk.contactid= aicrm_troubletickets.contact_id";
            }
            if ($this->requireTable('aicrm_productsRel')) {
                $query .= " left join aicrm_products as aicrm_productsRel on aicrm_productsRel.productid = aicrm_troubletickets.product_id";
            }
            if ($this->requireTable('aicrm_groupsHelpDesk')) {
                $query .= " left join aicrm_groups as aicrm_groupsHelpDesk on aicrm_groupsHelpDesk.groupid = aicrm_crmentity.smownerid";
            }
            if ($this->requireTable('aicrm_usersHelpDesk')) {
                $query .= " left join aicrm_users as aicrm_usersHelpDesk on aicrm_crmentity.smownerid=aicrm_usersHelpDesk.id";
            }

            // TODO optimize inclusion of these tables
            $query .= " left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid";
            $query .= " left join aicrm_users on aicrm_crmentity.smownerid=aicrm_users.id";

            if ($this->requireTable('aicrm_lastModifiedByHelpDesk')) {
                $query .= "  left join aicrm_users as aicrm_lastModifiedByHelpDesk on aicrm_lastModifiedByHelpDesk.id = aicrm_crmentity.modifiedby";
            }
            if ($this->requireTable('aicrm_createdbyHelpDesk')) {
                $query .= " left join aicrm_users as aicrm_createdbyHelpDesk on aicrm_createdbyHelpDesk.id = aicrm_crmentity.smcreatorid";
            }

        //            $focus = CRMEntity::getInstance($module);
        //            $relquery = $focus->getReportsUiType10Query($module, $this->queryPlanner);
        //            $query .= $relquery . ' ';
        //
        //            $query .= " ".$this->getRelatedModulesQuery($module,$this->secondarymodule).
        //                getNonAdminAccessControlQuery($this->primarymodule,$current_user).
        //                " where aicrm_crmentity.deleted=0 ";
        } else if ($module == "Calendar") {
        //            $referenceModuleList = Vtiger_Util_Helper::getCalendarReferenceModulesList();
        //            $referenceTablesList = array();
        //            foreach ($referenceModuleList as $referenceModule) {
        //                $entityTableFieldNames = getEntityFieldNames($referenceModule);
        //                $entityTableName = $entityTableFieldNames['tablename'];
        //                $referenceTablesList[] = $entityTableName . 'RelCalendar';
        //            }
        //
        //            $matrix = $this->queryPlanner->newDependencyMatrix();
        //
        //            $matrix->setDependency('aicrm_cntactivityrel', array('aicrm_contactdetailsCalendar'));
        //            $matrix->setDependency('aicrm_seactivityrel', array('aicrm_crmentityRelCalendar'));
        //            $matrix->setDependency('aicrm_crmentityRelCalendar', $referenceTablesList);

            $query = "from aicrm_activity
				inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_activity.activityid
				inner join aicrm_activitycf on aicrm_activitycf.activityid=aicrm_activity.activityid
				left join aicrm_activity_reminder on aicrm_activity_reminder.activity_id=aicrm_activity.activityid";

        //            if (in_array('Contacts', $secondary_module)) {
        //                $query .= " left join aicrm_contactdetails as aicrm_contactdetailsCalendar on aicrm_contactdetailsCalendar.contactid= aicrm_cntactivityrel.contactid";
        //            }

            if ($this->requireTable('aicrm_activitycf')) {
                $query .= " left join aicrm_activitycf on aicrm_activitycf.activityid = aicrm_crmentity.crmid";
            }
        //            if ($this->requireTable('aicrm_cntactivityrel', $matrix)) {
        //                $query .= " left join aicrm_cntactivityrel on aicrm_cntactivityrel.activityid= aicrm_activity.activityid";
        //            }
            if ($this->requireTable('aicrm_contactdetailsCalendar')) {
                $query .= " left join aicrm_contactdetails as aicrm_contactdetailsCalendar on aicrm_contactdetailsCalendar.contactid= aicrm_cntactivityrel.contactid";
            }
            if ($this->requireTable('aicrm_groupsCalendar')) {
                $query .= " left join aicrm_groups as aicrm_groupsCalendar on aicrm_groupsCalendar.groupid = aicrm_crmentity.smownerid";
            }
            if ($this->requireTable('aicrm_usersCalendar')) {
                $query .= " left join aicrm_users as aicrm_usersCalendar on aicrm_usersCalendar.id = aicrm_crmentity.smownerid";
            }

            // TODO optimize inclusion of these tables
            $query .= " left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid";
            $query .= " left join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid";

        //            if ($this->requireTable('aicrm_seactivityrel', $matrix)) {
        //                $query .= " left join aicrm_seactivityrel on aicrm_seactivityrel.activityid = aicrm_activity.activityid";
        //            }
            if ($this->requireTable('aicrm_activity_reminder')) {
                $query .= " left join aicrm_activity_reminder on aicrm_activity_reminder.activity_id = aicrm_activity.activityid";
            }
            if ($this->requireTable('aicrm_recurringevents')) {
                $query .= " left join aicrm_recurringevents on aicrm_recurringevents.activityid = aicrm_activity.activityid";
            }
        //            if ($this->requireTable('aicrm_crmentityRelCalendar', $matrix)) {
        //                $query .= " left join aicrm_crmentity as aicrm_crmentityRelCalendar on aicrm_crmentityRelCalendar.crmid = aicrm_seactivityrel.crmid";
        //            }

        //            foreach ($referenceModuleList as $referenceModule) {
        //                $entityTableFieldNames = getEntityFieldNames($referenceModule);
        //                $entityTableName = $entityTableFieldNames['tablename'];
        //                $entityIdFieldName = $entityTableFieldNames['entityidfield'];
        //                $referenceTable = $entityTableName . 'RelCalendar';
        //                if ($this->requireTable($referenceTable)) {
        //                    $query .= " LEFT JOIN $entityTableName AS $referenceTable ON $referenceTable.$entityIdFieldName = aicrm_crmentityRelCalendar.crmid";
        //                }
        //            }

            if ($this->requireTable('aicrm_lastModifiedByCalendar')) {
                $query .= " left join aicrm_users as aicrm_lastModifiedByCalendar on aicrm_lastModifiedByCalendar.id = aicrm_crmentity.modifiedby";
            }
            if ($this->requireTable('aicrm_createdbyCalendar')) {
                $query .= " left join aicrm_users as aicrm_createdbyCalendar on aicrm_createdbyCalendar.id = aicrm_crmentity.smcreatorid";
            }

        //            $focus = CRMEntity::getInstance($module);
        //            $relquery = $focus->getReportsUiType10Query($module, $this->queryPlanner);
        //            $query .= $relquery . ' ';
        //
        //            $query .= " ".$this->getRelatedModulesQuery($module,$this->secondarymodule).
        //                getNonAdminAccessControlQuery($this->primarymodule,$current_user).
        //                " WHERE aicrm_crmentity.deleted=0 and (aicrm_activity.activitytype != 'Emails')";
        } else if ($module == "Quotes") {
        //            $matrix = $this->queryPlanner->newDependencyMatrix();
        //            $matrix->setDependency('aicrm_inventoryproductreltmpQuotes', array('aicrm_productsQuotes', 'aicrm_serviceQuotes'));

            $query = "from aicrm_quotes
			inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_quotes.quoteid";

            if ($this->requireTable('aicrm_quotesbillads')) {
                $query .= " inner join aicrm_quotesbillads on aicrm_quotes.quoteid=aicrm_quotesbillads.quotebilladdressid";
            }
            if ($this->requireTable('aicrm_quotesshipads')) {
                $query .= " inner join aicrm_quotesshipads on aicrm_quotes.quoteid=aicrm_quotesshipads.quoteshipaddressid";
            }
            if ($this->requireTable("aicrm_currency_info$module")) {
                $query .= " left join aicrm_currency_info as aicrm_currency_info$module on aicrm_currency_info$module.id = aicrm_quotes.currency_id";
            }
            if ($type !== 'COLUMNSTOTOTAL' || $this->lineItemFieldsInCalculation == true) {
        //                if ($this->requireTable("aicrm_inventoryproductreltmpQuotes", $matrix)) {
        //                    $query .= " left join aicrm_inventoryproductrel as aicrm_inventoryproductreltmpQuotes on aicrm_quotes.quoteid = aicrm_inventoryproductreltmpQuotes.id";
        //                }
                if ($this->requireTable("aicrm_productsQuotes")) {
                    $query .= " left join aicrm_products as aicrm_productsQuotes on aicrm_productsQuotes.productid = aicrm_inventoryproductreltmpQuotes.productid";
                }
                if ($this->requireTable("aicrm_serviceQuotes")) {
                    $query .= " left join aicrm_service as aicrm_serviceQuotes on aicrm_serviceQuotes.serviceid = aicrm_inventoryproductreltmpQuotes.productid";
                }
            }
            if ($this->requireTable("aicrm_quotescf")) {
                $query .= " left join aicrm_quotescf on aicrm_quotes.quoteid = aicrm_quotescf.quoteid";
            }
            if ($this->requireTable("aicrm_groupsQuotes")) {
                $query .= " left join aicrm_groups as aicrm_groupsQuotes on aicrm_groupsQuotes.groupid = aicrm_crmentity.smownerid";
            }
            if ($this->requireTable("aicrm_usersQuotes")) {
                $query .= " left join aicrm_users as aicrm_usersQuotes on aicrm_usersQuotes.id = aicrm_crmentity.smownerid";
            }

            // TODO optimize inclusion of these tables
            $query .= " left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid";
            $query .= " left join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid";

            if ($this->requireTable("aicrm_lastModifiedByQuotes")) {
                $query .= " left join aicrm_users as aicrm_lastModifiedByQuotes on aicrm_lastModifiedByQuotes.id = aicrm_crmentity.modifiedby";
            }
            if ($this->requireTable('aicrm_createdbyQuotes')) {
                $query .= " left join aicrm_users as aicrm_createdbyQuotes on aicrm_createdbyQuotes.id = aicrm_crmentity.smcreatorid";
            }
            if ($this->requireTable("aicrm_usersRel1")) {
                $query .= " left join aicrm_users as aicrm_usersRel1 on aicrm_usersRel1.id = aicrm_quotes.inventorymanager";
            }
            if ($this->requireTable("aicrm_potentialRelQuotes")) {
                $query .= " left join aicrm_potential as aicrm_potentialRelQuotes on aicrm_potentialRelQuotes.potentialid = aicrm_quotes.potentialid";
            }
            if ($this->requireTable("aicrm_contactdetailsQuotes")) {
                $query .= " left join aicrm_contactdetails as aicrm_contactdetailsQuotes on aicrm_contactdetailsQuotes.contactid = aicrm_quotes.contactid";
            }
            if ($this->requireTable("aicrm_leaddetailsQuotes")) {
                $query .= " left join aicrm_leaddetails as aicrm_leaddetailsQuotes on aicrm_leaddetailsQuotes.leadid = aicrm_quotes.contactid";
            }
            if ($this->requireTable("aicrm_accountQuotes")) {
                $query .= " left join aicrm_account as aicrm_accountQuotes on aicrm_accountQuotes.accountid = aicrm_quotes.accountid";
            }
            if ($this->requireTable('aicrm_currency_info')) {
                $query .= ' LEFT JOIN aicrm_currency_info ON aicrm_currency_info.id = aicrm_quotes.currency_id';
            }

        //            $focus = CRMEntity::getInstance($module);
        //            $relquery = $focus->getReportsUiType10Query($module, $this->queryPlanner);
        //            $query .= $relquery . ' ';
        //
        //            $focus = CRMEntity::getInstance($module);
        //            $query .= " " . $this->getRelatedModulesQuery($module, $this->secondarymodule) .
        //                getNonAdminAccessControlQuery($this->primarymodule, $current_user) .
        //                " where aicrm_crmentity.deleted=0";
        } else if ($module == "PurchaseOrder") {

        //            $matrix = $this->queryPlanner->newDependencyMatrix();
        //            $matrix->setDependency('aicrm_inventoryproductreltmpPurchaseOrder', array('aicrm_productsPurchaseOrder', 'aicrm_servicePurchaseOrder'));

            $query = "from aicrm_purchaseorder
			inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_purchaseorder.purchaseorderid";

            if ($this->requireTable("aicrm_pobillads")) {
                $query .= " inner join aicrm_pobillads on aicrm_purchaseorder.purchaseorderid=aicrm_pobillads.pobilladdressid";
            }
            if ($this->requireTable("aicrm_poshipads")) {
                $query .= " inner join aicrm_poshipads on aicrm_purchaseorder.purchaseorderid=aicrm_poshipads.poshipaddressid";
            }
            if ($this->requireTable("aicrm_currency_info$module")) {
                $query .= " left join aicrm_currency_info as aicrm_currency_info$module on aicrm_currency_info$module.id = aicrm_purchaseorder.currency_id";
            }
            if ($type !== 'COLUMNSTOTOTAL' || $this->lineItemFieldsInCalculation == true) {
        //                if ($this->requireTable("aicrm_inventoryproductreltmpPurchaseOrder", $matrix)) {
        //                    $query .= " left join aicrm_inventoryproductrel as aicrm_inventoryproductreltmpPurchaseOrder on aicrm_purchaseorder.purchaseorderid = aicrm_inventoryproductreltmpPurchaseOrder.id";
        //                }
                if ($this->requireTable("aicrm_productsPurchaseOrder")) {
                    $query .= " left join aicrm_products as aicrm_productsPurchaseOrder on aicrm_productsPurchaseOrder.productid = aicrm_inventoryproductreltmpPurchaseOrder.productid";
                }
                if ($this->requireTable("aicrm_servicePurchaseOrder")) {
                    $query .= " left join aicrm_service as aicrm_servicePurchaseOrder on aicrm_servicePurchaseOrder.serviceid = aicrm_inventoryproductreltmpPurchaseOrder.productid";
                }
            }
            if ($this->requireTable("aicrm_purchaseordercf")) {
                $query .= " left join aicrm_purchaseordercf on aicrm_purchaseorder.purchaseorderid = aicrm_purchaseordercf.purchaseorderid";
            }
            if ($this->requireTable("aicrm_groupsPurchaseOrder")) {
                $query .= " left join aicrm_groups as aicrm_groupsPurchaseOrder on aicrm_groupsPurchaseOrder.groupid = aicrm_crmentity.smownerid";
            }
            if ($this->requireTable("aicrm_usersPurchaseOrder")) {
                $query .= " left join aicrm_users as aicrm_usersPurchaseOrder on aicrm_usersPurchaseOrder.id = aicrm_crmentity.smownerid";
            }
            if ($this->requireTable("aicrm_accountsPurchaseOrder")) {
                $query .= " left join aicrm_account as aicrm_accountsPurchaseOrder on aicrm_accountsPurchaseOrder.accountid = aicrm_purchaseorder.accountid";
            }

            // TODO optimize inclusion of these tables
            $query .= " left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid";
            $query .= " left join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid";

            if ($this->requireTable("aicrm_lastModifiedByPurchaseOrder")) {
                $query .= " left join aicrm_users as aicrm_lastModifiedByPurchaseOrder on aicrm_lastModifiedByPurchaseOrder.id = aicrm_crmentity.modifiedby";
            }
            if ($this->requireTable('aicrm_createdbyPurchaseOrder')) {
                $query .= " left join aicrm_users as aicrm_createdbyPurchaseOrder on aicrm_createdbyPurchaseOrder.id = aicrm_crmentity.smcreatorid";
            }
            if ($this->requireTable("aicrm_vendorRelPurchaseOrder")) {
                $query .= " left join aicrm_vendor as aicrm_vendorRelPurchaseOrder on aicrm_vendorRelPurchaseOrder.vendorid = aicrm_purchaseorder.vendorid";
            }
            if ($this->requireTable("aicrm_contactdetailsPurchaseOrder")) {
                $query .= " left join aicrm_contactdetails as aicrm_contactdetailsPurchaseOrder on aicrm_contactdetailsPurchaseOrder.contactid = aicrm_purchaseorder.contactid";
            }
            if ($this->requireTable('aicrm_currency_info')) {
                $query .= ' LEFT JOIN aicrm_currency_info ON aicrm_currency_info.id = aicrm_purchaseorder.currency_id';
            }

        //            $focus = CRMEntity::getInstance($module);
        //            $relquery = $focus->getReportsUiType10Query($module, $this->queryPlanner);
        //            $query .= $relquery . ' ';
        //
        //            $query .= " " . $this->getRelatedModulesQuery($module, $this->secondarymodule) .
        //                getNonAdminAccessControlQuery($this->primarymodule, $current_user) .
        //                " where aicrm_crmentity.deleted=0";
        } else if ($module == "Invoice") {
        //            $matrix = $this->queryPlanner->newDependencyMatrix();
        //            $matrix->setDependency('aicrm_inventoryproductreltmpInvoice', array('aicrm_productsInvoice', 'aicrm_serviceInvoice'));

            $query = "from aicrm_invoice
			inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_invoice.invoiceid";

            if ($this->requireTable("aicrm_invoicebillads")) {
                $query .=" inner join aicrm_invoicebillads on aicrm_invoice.invoiceid=aicrm_invoicebillads.invoicebilladdressid";
            }
            if ($this->requireTable("aicrm_invoiceshipads")) {
                $query .=" inner join aicrm_invoiceshipads on aicrm_invoice.invoiceid=aicrm_invoiceshipads.invoiceshipaddressid";
            }
            if ($this->requireTable("aicrm_currency_info$module")) {
                $query .=" left join aicrm_currency_info as aicrm_currency_info$module on aicrm_currency_info$module.id = aicrm_invoice.currency_id";
            }
            // lineItemFieldsInCalculation - is used to when line item fields are used in calculations
            if ($type !== 'COLUMNSTOTOTAL' || $this->lineItemFieldsInCalculation == true) {
                // should be present on when line item fields are selected for calculation
        //                if ($this->requireTable("aicrm_inventoryproductreltmpInvoice", $matrix)) {
        //                    $query .=" left join aicrm_inventoryproductrel as aicrm_inventoryproductreltmpInvoice on aicrm_invoice.invoiceid = aicrm_inventoryproductreltmpInvoice.id";
        //                }
                if ($this->requireTable("aicrm_productsInvoice")) {
                    $query .=" left join aicrm_products as aicrm_productsInvoice on aicrm_productsInvoice.productid = aicrm_inventoryproductreltmpInvoice.productid";
                }
                if ($this->requireTable("aicrm_serviceInvoice")) {
                    $query .=" left join aicrm_service as aicrm_serviceInvoice on aicrm_serviceInvoice.serviceid = aicrm_inventoryproductreltmpInvoice.productid";
                }
            }
            if ($this->requireTable("aicrm_salesorderInvoice")) {
                $query .= " left join aicrm_salesorder as aicrm_salesorderInvoice on aicrm_salesorderInvoice.salesorderid=aicrm_invoice.salesorderid";
            }
            if ($this->requireTable("aicrm_invoicecf")) {
                $query .= " left join aicrm_invoicecf on aicrm_invoice.invoiceid = aicrm_invoicecf.invoiceid";
            }
            if ($this->requireTable("aicrm_groupsInvoice")) {
                $query .= " left join aicrm_groups as aicrm_groupsInvoice on aicrm_groupsInvoice.groupid = aicrm_crmentity.smownerid";
            }
            if ($this->requireTable("aicrm_usersInvoice")) {
                $query .= " left join aicrm_users as aicrm_usersInvoice on aicrm_usersInvoice.id = aicrm_crmentity.smownerid";
            }

            // TODO optimize inclusion of these tables
            $query .= " left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid";
            $query .= " left join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid";

            if ($this->requireTable("aicrm_lastModifiedByInvoice")) {
                $query .= " left join aicrm_users as aicrm_lastModifiedByInvoice on aicrm_lastModifiedByInvoice.id = aicrm_crmentity.modifiedby";
            }
            if ($this->requireTable('aicrm_createdbyInvoice')) {
                $query .= " left join aicrm_users as aicrm_createdbyInvoice on aicrm_createdbyInvoice.id = aicrm_crmentity.smcreatorid";
            }
            if ($this->requireTable("aicrm_accountInvoice")) {
                $query .= " left join aicrm_account as aicrm_accountInvoice on aicrm_accountInvoice.accountid = aicrm_invoice.accountid";
            }
            if ($this->requireTable("aicrm_contactdetailsInvoice")) {
                $query .= " left join aicrm_contactdetails as aicrm_contactdetailsInvoice on aicrm_contactdetailsInvoice.contactid = aicrm_invoice.contactid";
            }
            if ($this->requireTable('aicrm_currency_info')) {
                $query .= ' LEFT JOIN aicrm_currency_info ON aicrm_currency_info.id = aicrm_invoice.currency_id';
            }

        //            $focus = CRMEntity::getInstance($module);
        //            $relquery = $focus->getReportsUiType10Query($module, $this->queryPlanner);
        //            $query .= $relquery . ' ';
        //
        //            $query .= " " . $this->getRelatedModulesQuery($module, $this->secondarymodule) .
        //                getNonAdminAccessControlQuery($this->primarymodule, $current_user) .
        //                " where aicrm_crmentity.deleted=0";
        } else if ($module == "SalesOrder") {
        //            $matrix = $this->queryPlanner->newDependencyMatrix();
        //            $matrix->setDependency('aicrm_inventoryproductreltmpSalesOrder', array('aicrm_productsSalesOrder', 'aicrm_serviceSalesOrder'));

            $query = "from aicrm_salesorder
			inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_salesorder.salesorderid";

            if ($this->requireTable("aicrm_sobillads")) {
                $query .= " inner join aicrm_sobillads on aicrm_salesorder.salesorderid=aicrm_sobillads.sobilladdressid";
            }
            if ($this->requireTable("aicrm_soshipads")) {
                $query .= " inner join aicrm_soshipads on aicrm_salesorder.salesorderid=aicrm_soshipads.soshipaddressid";
            }
            if ($this->requireTable("aicrm_currency_info$module")) {
                $query .= " left join aicrm_currency_info as aicrm_currency_info$module on aicrm_currency_info$module.id = aicrm_salesorder.currency_id";
            }
            if ($type !== 'COLUMNSTOTOTAL' || $this->lineItemFieldsInCalculation == true) {
        //                if ($this->requireTable("aicrm_inventoryproductreltmpSalesOrder", $matrix)) {
        //                    $query .= " left join aicrm_inventoryproductrel as aicrm_inventoryproductreltmpSalesOrder on aicrm_salesorder.salesorderid = aicrm_inventoryproductreltmpSalesOrder.id";
        //                }
                if ($this->requireTable("aicrm_productsSalesOrder")) {
                    $query .= " left join aicrm_products as aicrm_productsSalesOrder on aicrm_productsSalesOrder.productid = aicrm_inventoryproductreltmpSalesOrder.productid";
                }
                if ($this->requireTable("aicrm_serviceSalesOrder")) {
                    $query .= " left join aicrm_service as aicrm_serviceSalesOrder on aicrm_serviceSalesOrder.serviceid = aicrm_inventoryproductreltmpSalesOrder.productid";
                }
            }
            if ($this->requireTable("aicrm_salesordercf")) {
                $query .=" left join aicrm_salesordercf on aicrm_salesorder.salesorderid = aicrm_salesordercf.salesorderid";
            }
            if ($this->requireTable("aicrm_contactdetailsSalesOrder")) {
                $query .= " left join aicrm_contactdetails as aicrm_contactdetailsSalesOrder on aicrm_contactdetailsSalesOrder.contactid = aicrm_salesorder.contactid";
            }
            if ($this->requireTable("aicrm_quotesSalesOrder")) {
                $query .= " left join aicrm_quotes as aicrm_quotesSalesOrder on aicrm_quotesSalesOrder.quoteid = aicrm_salesorder.quoteid";
            }
            if ($this->requireTable("aicrm_accountSalesOrder")) {
                $query .= " left join aicrm_account as aicrm_accountSalesOrder on aicrm_accountSalesOrder.accountid = aicrm_salesorder.accountid";
            }
            if ($this->requireTable("aicrm_potentialRelSalesOrder")) {
                $query .= " left join aicrm_potential as aicrm_potentialRelSalesOrder on aicrm_potentialRelSalesOrder.potentialid = aicrm_salesorder.potentialid";
            }
            if ($this->requireTable("aicrm_invoice_recurring_info")) {
                $query .= " left join aicrm_invoice_recurring_info on aicrm_invoice_recurring_info.salesorderid = aicrm_salesorder.salesorderid";
            }
            if ($this->requireTable("aicrm_groupsSalesOrder")) {
                $query .= " left join aicrm_groups as aicrm_groupsSalesOrder on aicrm_groupsSalesOrder.groupid = aicrm_crmentity.smownerid";
            }
            if ($this->requireTable("aicrm_usersSalesOrder")) {
                $query .= " left join aicrm_users as aicrm_usersSalesOrder on aicrm_usersSalesOrder.id = aicrm_crmentity.smownerid";
            }

            // TODO optimize inclusion of these tables
            $query .= " left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid";
            $query .= " left join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid";

            if ($this->requireTable("aicrm_lastModifiedBySalesOrder")) {
                $query .= " left join aicrm_users as aicrm_lastModifiedBySalesOrder on aicrm_lastModifiedBySalesOrder.id = aicrm_crmentity.modifiedby";
            }
            if ($this->requireTable('aicrm_createdbySalesOrder')) {
                $query .= " left join aicrm_users as aicrm_createdbySalesOrder on aicrm_createdbySalesOrder.id = aicrm_crmentity.smcreatorid";
            }
            if ($this->requireTable('aicrm_currency_info')) {
                $query .= ' LEFT JOIN aicrm_currency_info ON aicrm_currency_info.id = aicrm_salesorder.currency_id';
            }

        //            $focus = CRMEntity::getInstance($module);
        //            $relquery = $focus->getReportsUiType10Query($module, $this->queryPlanner);
        //            $query .= $relquery . ' ';
        //
        //            $query .= " " . $this->getRelatedModulesQuery($module, $this->secondarymodule) .
        //                getNonAdminAccessControlQuery($this->primarymodule, $current_user) .
        //                " where aicrm_crmentity.deleted=0";
        } else if ($module == "Campaigns") {
            $query = "from aicrm_campaign
			inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_campaign.campaignid";
            if ($this->requireTable("aicrm_campaignscf")) {
                $query .= " inner join aicrm_campaignscf as aicrm_campaignscf on aicrm_campaignscf.campaignid=aicrm_campaign.campaignid";
            }
            if ($this->requireTable("aicrm_productsCampaigns")) {
                $query .= " left join aicrm_products as aicrm_productsCampaigns on aicrm_productsCampaigns.productid = aicrm_campaign.product_id";
            }
            if ($this->requireTable("aicrm_groupsCampaigns")) {
                $query .= " left join aicrm_groups as aicrm_groupsCampaigns on aicrm_groupsCampaigns.groupid = aicrm_crmentity.smownerid";
            }
            if ($this->requireTable("aicrm_usersCampaigns")) {
                $query .= " left join aicrm_users as aicrm_usersCampaigns on aicrm_usersCampaigns.id = aicrm_crmentity.smownerid";
            }

            // TODO optimize inclusion of these tables
            $query .= " left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid";
            $query .= " left join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid";

            if ($this->requireTable("aicrm_lastModifiedBy$module")) {
                $query .= " left join aicrm_users as aicrm_lastModifiedBy" . $module . " on aicrm_lastModifiedBy" . $module . ".id = aicrm_crmentity.modifiedby";
            }
            if ($this->requireTable("aicrm_createdby$module")) {
                $query .= " left join aicrm_users as aicrm_createdby$module on aicrm_createdby$module.id = aicrm_crmentity.smcreatorid";
            }

        //            $focus = CRMEntity::getInstance($module);
        //            $relquery = $focus->getReportsUiType10Query($module, $this->queryPlanner);
        //            $query .= $relquery . ' ';
        //
        //            $query .= " ".$this->getRelatedModulesQuery($module,$this->secondarymodule).
        //                getNonAdminAccessControlQuery($this->primarymodule,$current_user).
        //                " where aicrm_crmentity.deleted=0";
        } else if ($module == "Emails") {
            $query = "from aicrm_activity
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid AND aicrm_activity.activitytype = 'Emails'";

            if ($this->requireTable("aicrm_email_track")) {
                $query .= " LEFT JOIN aicrm_email_track ON aicrm_email_track.mailid = aicrm_activity.activityid";
            }
            if ($this->requireTable("aicrm_groupsEmails")) {
                $query .= " LEFT JOIN aicrm_groups AS aicrm_groupsEmails ON aicrm_groupsEmails.groupid = aicrm_crmentity.smownerid";
            }
            if ($this->requireTable("aicrm_usersEmails")) {
                $query .= " LEFT JOIN aicrm_users AS aicrm_usersEmails ON aicrm_usersEmails.id = aicrm_crmentity.smownerid";
            }

            // TODO optimize inclusion of these tables
            $query .= " LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid";
            $query .= " LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid";

            if ($this->requireTable("aicrm_lastModifiedBy$module")) {
                $query .= " LEFT JOIN aicrm_users AS aicrm_lastModifiedBy" . $module . " ON aicrm_lastModifiedBy" . $module . ".id = aicrm_crmentity.modifiedby";
            }
            if ($this->requireTable("aicrm_createdby$module")) {
                $query .= " left join aicrm_users as aicrm_createdby$module on aicrm_createdby$module.id = aicrm_crmentity.smcreatorid";
            }

        //            $focus = CRMEntity::getInstance($module);
        //            $relquery = $focus->getReportsUiType10Query($module, $this->queryPlanner);
        //            $query .= $relquery . ' ';
        //
        //            $query .= " ".$this->getRelatedModulesQuery($module,$this->secondarymodule).
        //                getNonAdminAccessControlQuery($this->primarymodule,$current_user).
        //                " WHERE aicrm_crmentity.deleted = 0";
        } else if($module == "Inspection") {
            $query = "from aicrm_inspection
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_inspection.inspectionid";
        } else if($module == "Agreement") {
            $query = "from aicrm_agreement
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_agreement.agreementid";

            $query .= " LEFT JOIN aicrm_branchs ON aicrm_branchs.branchid = aicrm_agreement.branchid";
        } else if($module == "Transfer") {
            $query = "from aicrm_transfer
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_transfer.transferid";

            $query .= " LEFT JOIN aicrm_branchs ON aicrm_branchs.branchid = aicrm_transfer.branchid";
        } else {
        //            if ($module != '') {
        //                $focus = CRMEntity::getInstance($module);
        //                $query = $focus->generateReportsQuery($module, $this->queryPlanner) .
        //                    $this->getRelatedModulesQuery($module, $this->secondarymodule) .
        //                    getNonAdminAccessControlQuery($this->primarymodule, $current_user) .
        //                    " WHERE aicrm_crmentity.deleted=0"; aicrm_transfer
        //            }
        }



        $secondarymodule = explode(":", $secondarymodules);
        //        if(in_array('Calendar', $secondarymodule) || $module == 'Calendar') {
        //            $currentUserModel = Users_Record_Model::getCurrentUserModel();
        //            $tabId = getTabid('Calendar');
        //            $task_tableName = 'vt_tmp_u'.$currentUserModel->id.'_t'.$tabId.'_task';
        //            $event_tableName = 'vt_tmp_u'.$currentUserModel->id.'_t'.$tabId.'_events';
        //            if(!$currentUserModel->isAdminUser()
        //                && stripos($query, $event_tableName) && stripos($query, $task_tableName)) {
        //                $moduleFocus = CRMEntity::getInstance('Calendar');
        //                $scope = '';
        //                if(in_array('Calendar', $secondarymodule)) $scope = 'Calendar';
        //                $condition = $moduleFocus->buildWhereClauseConditionForCalendar($scope);
        //                if($condition) {
        //                    $query .= ' AND '.$condition;
        //                }
        //            }
        //        }

        return $query;
    }

    public function getRelationTablesQuery($primarymodule, $relatemodules=''){
        $relates = [];
        $tabinfo = [];
        $relate_tab = [];
        if($relatemodules!=''){
            $modules = explode(':', $relatemodules);
            switch ($primarymodule){
                case'Accounts':
                    foreach($modules as $secmodule){
                        $tabinfo = $this->getTabInfo($secmodule);
                        $relate_tab = $this->relate_model->setAccountsRelationTables($tabinfo['modulename']);
                    }
                    break;
                case'Contacts':
                    foreach($modules as $secmodule){
                        $tabinfo = $this->getTabInfo($secmodule);
                        $relate_tab = $this->relate_model->setContactsRelationTables($tabinfo['modulename']);
                    }
                    break;
                case'Calendar':
                    foreach($modules as $secmodule){
                        $tabinfo = $this->getTabInfo($secmodule);
                        $relate_tab = $this->relate_model->setCalendarRelationTables($tabinfo['modulename']);
                    }
                    break;
            }

            $relates[] = $this->getRelationQuery($primarymodule, $relate_tab, $tabinfo['modulename'], $tabinfo['tablename'], $tabinfo['entityidcolumn']);
        }

        return $relates;
    }

    function getRelationQuery($module, $tab, $secmodule, $table_name, $column_name) {

        foreach ($tab as $key => $value) {
            $tables[] = $key;
            $fields[] = $value;
        }
        $pritablename = $tables[0]; // aicrm_quotes
        $sectablename = $tables[1]; // aicrm_account
        $prifieldname = $fields[0][0]; // accountid
        $secfieldname = $fields[0][1]; // quoteid
        $tmpname = $pritablename . 'tmp' . $secmodule;
        $condition = "";
        if (!empty($tables[1]) && !empty($fields[1])) {
            $condvalue = $tables[1] . "." . $fields[1];
            $condition = "$table_name.$prifieldname=$condvalue";
        } else {
            $condvalue = $table_name . "." . $column_name;
            $condition = "$pritablename.$secfieldname=$condvalue";
        }
        //alert($pritablename.' = '.$sectablename.' - '.$prifieldname.' - '.$secfieldname);
        $query = " LEFT JOIN $pritablename ON ($pritablename.$prifieldname=$sectablename.$prifieldname)";

        return $query;
    }

    public function getStandarFiltersStartAndEndDate($type) {
        global $current_user;
        $userPeferredDayOfTheWeek = $current_user->column_fields['dayoftheweek'];

        $today = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
        $todayName = date('l', strtotime($today));

        $tomorrow = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));
        $yesterday = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")));

        $currentmonth0 = date("Y-m-d", mktime(0, 0, 0, date("m"), "01", date("Y")));
        $currentmonth1 = date("Y-m-t");
        $lastmonth0 = date("Y-m-d", mktime(0, 0, 0, date("m") - 1, "01", date("Y")));
        $lastmonth1 = date("Y-m-t", strtotime("-1 Month"));
        $nextmonth0 = date("Y-m-d", mktime(0, 0, 0, date("m") + 1, "01", date("Y")));
        $nextmonth1 = date("Y-m-t", strtotime("+1 Month"));

        // (Last Week) If Today is "Sunday" then "-2 week Sunday" will give before last week Sunday date
        if ($todayName == $userPeferredDayOfTheWeek)
            $lastweek0 = date("Y-m-d", strtotime("-1 week $userPeferredDayOfTheWeek"));
        else
            $lastweek0 = date("Y-m-d", strtotime("-2 week $userPeferredDayOfTheWeek"));
        $prvDay = date('l', strtotime(date('Y-m-d', strtotime('-1 day', strtotime($lastweek0)))));
        $lastweek1 = date("Y-m-d", strtotime("-1 week $prvDay"));

        // (This Week) If Today is "Sunday" then "-1 week Sunday" will give last week Sunday date
        if ($todayName == $userPeferredDayOfTheWeek)
            $thisweek0 = date("Y-m-d", strtotime("-0 week $userPeferredDayOfTheWeek"));
        else
            $thisweek0 = date("Y-m-d", strtotime("-1 week $userPeferredDayOfTheWeek"));
        $prvDay = date('l', strtotime(date('Y-m-d', strtotime('-1 day', strtotime($thisweek0)))));
        $thisweek1 = date("Y-m-d", strtotime("this $prvDay"));

        // (Next Week) If Today is "Sunday" then "this Sunday" will give Today's date
        if ($todayName == $userPeferredDayOfTheWeek)
            $nextweek0 = date("Y-m-d", strtotime("+1 week $userPeferredDayOfTheWeek"));
        else
            $nextweek0 = date("Y-m-d", strtotime("this $userPeferredDayOfTheWeek"));
        $prvDay = date('l', strtotime(date('Y-m-d', strtotime('-1 day', strtotime($nextweek0)))));
        $nextweek1 = date("Y-m-d", strtotime("+1 week $prvDay"));

        $next7days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 6, date("Y")));
        $next30days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 29, date("Y")));
        $next60days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 59, date("Y")));
        $next90days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 89, date("Y")));
        $next120days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 119, date("Y")));

        $last7days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")));
        $last14days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 13, date("Y")));
        $last30days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 29, date("Y")));
        $last60days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 59, date("Y")));
        $last90days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 89, date("Y")));
        $last120days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 119, date("Y")));

        $currentFY0 = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y")));
        $currentFY1 = date("Y-m-t", mktime(0, 0, 0, "12", date("d"), date("Y")));
        $lastFY0 = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y") - 1));
        $lastFY1 = date("Y-m-t", mktime(0, 0, 0, "12", date("d"), date("Y") - 1));
        $nextFY0 = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y") + 1));
        $nextFY1 = date("Y-m-t", mktime(0, 0, 0, "12", date("d"), date("Y") + 1));

        if (date("m") <= 3) {
            $cFq = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y")));
            $cFq1 = date("Y-m-d", mktime(0, 0, 0, "03", "31", date("Y")));
            $nFq = date("Y-m-d", mktime(0, 0, 0, "04", "01", date("Y")));
            $nFq1 = date("Y-m-d", mktime(0, 0, 0, "06", "30", date("Y")));
            $pFq = date("Y-m-d", mktime(0, 0, 0, "10", "01", date("Y") - 1));
            $pFq1 = date("Y-m-d", mktime(0, 0, 0, "12", "31", date("Y") - 1));
        } else if (date("m") > 3 and date("m") <= 6) {
            $pFq = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y")));
            $pFq1 = date("Y-m-d", mktime(0, 0, 0, "03", "31", date("Y")));
            $cFq = date("Y-m-d", mktime(0, 0, 0, "04", "01", date("Y")));
            $cFq1 = date("Y-m-d", mktime(0, 0, 0, "06", "30", date("Y")));
            $nFq = date("Y-m-d", mktime(0, 0, 0, "07", "01", date("Y")));
            $nFq1 = date("Y-m-d", mktime(0, 0, 0, "09", "30", date("Y")));
        } else if (date("m") > 6 and date("m") <= 9) {
            $nFq = date("Y-m-d", mktime(0, 0, 0, "10", "01", date("Y")));
            $nFq1 = date("Y-m-d", mktime(0, 0, 0, "12", "31", date("Y")));
            $pFq = date("Y-m-d", mktime(0, 0, 0, "04", "01", date("Y")));
            $pFq1 = date("Y-m-d", mktime(0, 0, 0, "06", "30", date("Y")));
            $cFq = date("Y-m-d", mktime(0, 0, 0, "07", "01", date("Y")));
            $cFq1 = date("Y-m-d", mktime(0, 0, 0, "09", "30", date("Y")));
        } else if (date("m") > 9 and date("m") <= 12) {
            $nFq = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y") + 1));
            $nFq1 = date("Y-m-d", mktime(0, 0, 0, "03", "31", date("Y") + 1));
            $pFq = date("Y-m-d", mktime(0, 0, 0, "07", "01", date("Y")));
            $pFq1 = date("Y-m-d", mktime(0, 0, 0, "09", "30", date("Y")));
            $cFq = date("Y-m-d", mktime(0, 0, 0, "10", "01", date("Y")));
            $cFq1 = date("Y-m-d", mktime(0, 0, 0, "12", "31", date("Y")));
        }

        if ($type == "today") {

            $datevalue[0] = $today;
            $datevalue[1] = $today;
        } elseif ($type == "yesterday") {

            $datevalue[0] = $yesterday;
            $datevalue[1] = $yesterday;
        } elseif ($type == "tomorrow") {

            $datevalue[0] = $tomorrow;
            $datevalue[1] = $tomorrow;
        } elseif ($type == "thisweek") {

            $datevalue[0] = $thisweek0;
            $datevalue[1] = $thisweek1;
        } elseif ($type == "lastweek") {

            $datevalue[0] = $lastweek0;
            $datevalue[1] = $lastweek1;
        } elseif ($type == "nextweek") {

            $datevalue[0] = $nextweek0;
            $datevalue[1] = $nextweek1;
        } elseif ($type == "thismonth") {

            $datevalue[0] = $currentmonth0;
            $datevalue[1] = $currentmonth1;
        } elseif ($type == "lastmonth") {

            $datevalue[0] = $lastmonth0;
            $datevalue[1] = $lastmonth1;
        } elseif ($type == "nextmonth") {

            $datevalue[0] = $nextmonth0;
            $datevalue[1] = $nextmonth1;
        } elseif ($type == "next7days") {

            $datevalue[0] = $today;
            $datevalue[1] = $next7days;
        } elseif ($type == "next30days") {

            $datevalue[0] = $today;
            $datevalue[1] = $next30days;
        } elseif ($type == "next60days") {

            $datevalue[0] = $today;
            $datevalue[1] = $next60days;
        } elseif ($type == "next90days") {

            $datevalue[0] = $today;
            $datevalue[1] = $next90days;
        } elseif ($type == "next120days") {

            $datevalue[0] = $today;
            $datevalue[1] = $next120days;
        } elseif ($type == "last7days") {

            $datevalue[0] = $last7days;
            $datevalue[1] = $today;
        } elseif ($type == "last14days") {
            $datevalue[0] = $last14days;
            $datevalue[1] = $today;
        } elseif ($type == "last30days") {

            $datevalue[0] = $last30days;
            $datevalue[1] = $today;
        } elseif ($type == "last60days") {

            $datevalue[0] = $last60days;
            $datevalue[1] = $today;
        } else if ($type == "last90days") {

            $datevalue[0] = $last90days;
            $datevalue[1] = $today;
        } elseif ($type == "last120days") {

            $datevalue[0] = $last120days;
            $datevalue[1] = $today;
        } elseif ($type == "thisfy") {

            $datevalue[0] = $currentFY0;
            $datevalue[1] = $currentFY1;
        } elseif ($type == "prevfy") {

            $datevalue[0] = $lastFY0;
            $datevalue[1] = $lastFY1;
        } elseif ($type == "nextfy") {

            $datevalue[0] = $nextFY0;
            $datevalue[1] = $nextFY1;
        } elseif ($type == "nextfq") {

            $datevalue[0] = $nFq;
            $datevalue[1] = $nFq1;
        } elseif ($type == "prevfq") {

            $datevalue[0] = $pFq;
            $datevalue[1] = $pFq1;
        } elseif ($type == "thisfq") {
            $datevalue[0] = $cFq;
            $datevalue[1] = $cFq1;
        } else {
            $datevalue[0] = "";
            $datevalue[1] = "";
        }
        return $datevalue;
    }

    public function get_leadsourcepicklist(){
        $sql = "SELECT * FROM db_moaioc.aicrm_leadsource where presence = 1 order by leadsourceid asc ";
        $query = $this->db->query($sql);
        $result = $query->result(0);
        return $result;
    }

    public function get_leadstatuspicklist(){
        $sql = "SELECT * FROM db_moaioc.aicrm_leadstatus where presence = 1 order by leadstatusid asc ";
        $query = $this->db->query($sql);
        
        $result = $query->result(0);
        
        return $result;
    }

}
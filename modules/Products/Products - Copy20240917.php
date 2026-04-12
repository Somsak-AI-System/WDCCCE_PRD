<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
require_once('data/SugarBean.php');
require_once('include/utils/utils.php');
require_once('include/RelatedListView.php');
require_once('user_privileges/default_module_view.php');

class Products extends CRMEntity
{
    var $db, $log; // Used in class functions of CRMEntity
    var $table_name = 'aicrm_products';
    var $table_index = 'productid';
    var $column_fields = Array();
    var $table_comment = "aicrm_productscomments";

    /**
     * Mandatory table for supporting custom fields.
     */
    var $customFieldTable = Array('aicrm_productcf', 'productid');
    var $tab_name = Array('aicrm_crmentity', 'aicrm_products', 'aicrm_productcf', 'aicrm_branchs', 'aicrm_seproductsrel');
    var $tab_name_index = Array('aicrm_crmentity' => 'crmid', 'aicrm_products' => 'productid', 'aicrm_branchs' => 'branchid', 'aicrm_productcf' => 'productid', 'aicrm_seproductsrel' => 'productid', 'aicrm_producttaxrel' => 'productid', 'aicrm_productscomments' => 'productid');

    // This is the list of aicrm_fields that are in the lists.
    var $list_fields = Array(
        'หมายเลขสินค้า' => Array('aicrm_products' => 'product_no'),
        'Material Code' => Array('aicrm_products' => 'material_code'),
    	'ชื่อสินค้า (TH)' => Array('aicrm_products' => 'productname'),
    	'CRM Product Code' => Array('aicrm_products' => 'product_code_crm'),
        'Prefix' => Array('aicrm_products' => 'product_prefix'),
        'Design No.' => Array('aicrm_products' => 'product_design_no'),
        'Design Name' => Array('aicrm_products' => 'product_design_name'),
        'Brand' => Array('aicrm_products' => 'product_brand'),
    );

    var $list_fields_name = Array(
        'หมายเลขสินค้า' => 'product_no',
        'Material Code' => 'material_code',
        'ชื่อสินค้า (TH)' => 'productname',
        'CRM Product Code' => 'product_code_crm',
        'Prefix' => 'product_prefix',
        'Design No.' => 'product_design_no',
        'Design Name' => 'product_design_name',
        'Brand' => 'product_brand',
    );
    var $list_link_field = 'productname';
    var $search_fields = Array(
        'หมายเลขสินค้า' => Array('aicrm_products' => 'product_no'),
        'Material Code' => Array('aicrm_products' => 'material_code'),
    	'ชื่อสินค้า (TH)' => Array('aicrm_products' => 'productname'),
        'ขนาดบรรจุ (แผ่น/กล่อง)' => Array('aicrm_products' => 'package_size_sheet_per_box'),
        'ขนาดบรรจุ (ตรม./กล่อง)' => Array('aicrm_products' => 'package_size_sqm_per_box'),
    	'สถานะของสินค้า' => Array('aicrm_products' => 'producttatus'),
    );
    var $search_fields_name = Array(
        'หมายเลขสินค้า' => 'product_no',
        'Material Code' => 'material_code',
        'ชื่อสินค้า (TH)' => 'productname',
        'ขนาดบรรจุ (แผ่น/กล่อง)' => 'package_size_sheet_per_box',
        'ขนาดบรรจุ (ตรม./กล่อง)' => 'package_size_sqm_per_box',
        'สถานะของสินค้า' => 'producttatus',
    );
    // Placeholder for sort fields - All the fields will be initialized for Sorting through initSortFields
    var $sortby_fields = Array('productid', 'productname', 'smownerid');
    //Added these variables which are used as default order by and sortorder in ListView
    var $default_order_by = 'crmid';
    var $default_sort_order = 'ASC';

    // Used when enabling/disabling the mandatory fields for the module.
    // Refers to aicrm_field.fieldname values.
    var $mandatory_fields = Array('createdtime', 'modifiedtime', 'productname', 'imagename');
    // Josh added for importing and exporting -added in patch2
    var $unit_price;

    /**    Constructor which will set the column_fields in this object
     */
    function Products()
    {
        $this->log = LoggerManager::getLogger('product');
        $this->log->debug("Entering Products() method ...");
        $this->db = PearDatabase::getInstance();
        $this->column_fields = getColumnFields('Products');
        $this->log->debug("Exiting Product method ...");
    }

    /**    Function used to get the sort order for Product listview
     * @return string    $sorder    - first check the $_REQUEST['sorder'] if request value is empty then check in the $_SESSION['PRODUCTS_SORT_ORDER'] if this session value is empty then default sort order will be returned.
     */
    function getSortOrder()
    {
        global $log;
        $log->debug("Entering getSortOrder() method ...");
        if (isset($_REQUEST['sorder']))
            $sorder = $this->db->sql_escape_string($_REQUEST['sorder']);
        else
            $sorder = (($_SESSION['PRODUCTS_SORT_ORDER'] != '') ? ($_SESSION['PRODUCTS_SORT_ORDER']) : ($this->default_sort_order));
        $log->debug("Exiting getSortOrder() method ...");
        return $sorder;
    }

    /**    Function used to get the order by value for Product listview
     * @return string    $order_by  - first check the $_REQUEST['order_by'] if request value is empty then check in the $_SESSION['PRODUCTS_ORDER_BY'] if this session value is empty then default order by will be returned.
     */
    function getOrderBy()
    {
        global $log;
        $log->debug("Entering getOrderBy() method ...");

        $use_default_order_by = '';
        if (PerformancePrefs::getBoolean('LISTVIEW_DEFAULT_SORTING', true)) {
            $use_default_order_by = $this->default_order_by;
        }

        if (isset($_REQUEST['order_by']))
            $order_by = $this->db->sql_escape_string($_REQUEST['order_by']);
        else
            $order_by = (($_SESSION['PRODUCTS_ORDER_BY'] != '') ? ($_SESSION['PRODUCTS_ORDER_BY']) : ($use_default_order_by));
        $log->debug("Exiting getOrderBy method ...");
        return $order_by;
    }

    function save_module($module)
    {
        //Inserting into product_taxrel table
        /*if ($_REQUEST['ajxaction'] != 'DETAILVIEW') {
            $this->insertTaxInformation('aicrm_producttaxrel', 'Products');
            $this->insertPriceInformation('aicrm_productcurrencyrel', 'Products');
        }*/

        if (isset($this->parentid) && $this->parentid != '') {
            $this->insertIntoseProductsRel($this->id, $this->parentid, $this->return_module);
        }

        $this->insertIntoAttachment($this->id, 'Products');
        $this->insertIntoCommentTable("aicrm_productscomments",'productid');
    }

    function insertIntoCommentTable($table_name, $module)
    {
        global $log;
        $log->info("in insertIntoCommentTable  ".$table_name."    module is  ".$module);
        global $adb;
        global $current_user;

        $current_time = $adb->formatDate(date('YmdHis'), true);
        if($this->column_fields['assigned_user_id'] != ''){
            $ownertype = 'user';
        }   
        else
        {
            $ownertype = 'customer';
        }

        if($this->column_fields['comments'] != ''){         
            $comment = $this->column_fields['comments'];
        }
        else
        {
            $comment = $_REQUEST['comments'];
        }   
        
        if($comment!=""){
            $sql = "insert into ".$table_name." values(?,?,?,?,?,?)";
            $params = array('', $this->id, from_html($comment), $current_user->id, $ownertype, $current_time);
            $adb->pquery($sql, $params);
        }
    }

    function getCommentInformation($crmid)
    {
        global $log;
        $log->debug("Entering getCommentInformation(".$crmid.") method ...");
        global $adb;
        global $mod_strings, $default_charset;
        $sql = "select * from aicrm_productscomments where productid=? order by createdtime desc";
        $result = $adb->pquery($sql, array($crmid));
        $noofrows = $adb->num_rows($result);

        //In ajax save we should not add this div
        if($_REQUEST['action'] != 'ServiceRequestAjax')
        {
            $list .= '<div id="comments_div" style="overflow: auto;height:200px;width:100%;display:block;">';
            $enddiv = '</div>';
        }
        for($i=0;$i<$noofrows;$i++)
        {
            if($adb->query_result($result,$i,'comments') != '')
            {
                //this div is to display the comment
                $comment = $adb->query_result($result,$i,'comments');
                // Asha: Fix for ticket #4478 . Need to escape html tags during ajax save.
                if($_REQUEST['action'] == 'ServiceRequestAjax') {
                    $comment = htmlentities($comment, ENT_QUOTES, $default_charset);
                }
                $list .= '<div valign="top" style="width:99%;padding-top:10px;" class="dataField">';
                $list .= make_clickable(nl2br($comment));

                $list .= '</div>';

                //this div is to display the author and time
                $list .= '<div valign="top" style="width:99%;border-bottom:1px dotted #CCCCCC;padding-bottom:5px;" class="dataLabel"><font color=darkred>';
                $list .= $mod_strings['LBL_AUTHOR'].' : ';

                if($adb->query_result($result,$i,'ownertype') == 'user')
                    $list .= getUserName($adb->query_result($result,$i,'ownerid'));
                elseif($adb->query_result($result,$i,'ownertype') == 'customer') {
                    $contactid = $adb->query_result($result,$i,'ownerid');
                    $list .= getContactName($contactid);
                }
                $list .= ' on '.date('d-m-Y H:i:s',strtotime($adb->query_result($result,$i,'createdtime'))).' &nbsp;';

                $list .= '</font></div>';
            }
        }

        $list .= $enddiv;

        $log->debug("Exiting getCommentInformation method ...");
        return $list;
    }
    
    /**    function to save the product tax information in aicrm_producttaxrel table
     * @param string $tablename - aicrm_tablename to save the product tax relationship (producttaxrel)
     * @param string $module - current module name
     *    $return void
     */
    function insertTaxInformation($tablename, $module)
    {
        global $adb, $log;
        $log->debug("Entering into insertTaxInformation($tablename, $module) method ...");
        $tax_details = getAllTaxes();

        $tax_per = '';
        //Save the Product - tax relationship if corresponding tax check box is enabled
        //Delete the existing tax if any
        if ($this->mode == 'edit') {
            for ($i = 0; $i < count($tax_details); $i++) {
                $taxid = getTaxId($tax_details[$i]['taxname']);
                $sql = "delete from aicrm_producttaxrel where productid=? and taxid=?";
                $adb->pquery($sql, array($this->id, $taxid));
            }
        }
        for ($i = 0; $i < count($tax_details); $i++) {
            $tax_name = $tax_details[$i]['taxname'];
            $tax_checkname = $tax_details[$i]['taxname'] . "_check";
            if ($_REQUEST[$tax_checkname] == 'on' || $_REQUEST[$tax_checkname] == 1) {
                $taxid = getTaxId($tax_name);
                $tax_per = $_REQUEST[$tax_name];
                if ($tax_per == '') {
                    $log->debug("Tax selected but value not given so default value will be saved.");
                    $tax_per = getTaxPercentage($tax_name);
                }

                $log->debug("Going to save the Product - $tax_name tax relationship");

                $query = "insert into aicrm_producttaxrel values(?,?,?)";
                $adb->pquery($query, array($this->id, $taxid, $tax_per));
            }
        }

        $log->debug("Exiting from insertTaxInformation($tablename, $module) method ...");
    }

    /**    function to save the product price information in aicrm_productcurrencyrel table
     * @param string $tablename - aicrm_tablename to save the product currency relationship (productcurrencyrel)
     * @param string $module - current module name
     *    $return void
     */
    function insertPriceInformation($tablename, $module)
    {
        global $adb, $log, $current_user;
        $log->debug("Entering into insertPriceInformation($tablename, $module) method ...");
        // Update the currency_id based on the logged in user's preference
        $currencyid = fetchCurrency($current_user->id);
        $adb->pquery("update aicrm_products set currency_id=? where productid=?", array($currencyid, $this->id));

        $currency_details = getAllCurrencies('all');

        //Delete the existing currency relationship if any
        if ($this->mode == 'edit') {
            for ($i = 0; $i < count($currency_details); $i++) {
                $curid = $currency_details[$i]['curid'];
                $sql = "delete from aicrm_productcurrencyrel where productid=? and currencyid=?";
                $adb->pquery($sql, array($this->id, $curid));
            }
        }

        $product_base_conv_rate = getBaseConversionRateForProduct($this->id, $this->mode);

        //Save the Product - Currency relationship if corresponding currency check box is enabled
        for ($i = 0; $i < count($currency_details); $i++) {
            $curid = $currency_details[$i]['curid'];
            $curname = $currency_details[$i]['currencylabel'];
            $cur_checkname = 'cur_' . $curid . '_check';
            $cur_valuename = 'curname' . $curid;
            $base_currency_check = 'base_currency' . $curid;
            if ($_REQUEST[$cur_checkname] == 'on' || $_REQUEST[$cur_checkname] == 1) {
                $conversion_rate = $currency_details[$i]['conversionrate'];
                $actual_conversion_rate = $product_base_conv_rate * $conversion_rate;
                $converted_price = $actual_conversion_rate * $_REQUEST['unit_price'];
                $actual_price = $_REQUEST[$cur_valuename];

                $log->debug("Going to save the Product - $curname currency relationship");

                $query = "insert into aicrm_productcurrencyrel values(?,?,?,?)";
                $adb->pquery($query, array($this->id, $curid, $converted_price, $actual_price));

                // Update the Product information with Base Currency choosen by the User.
                if ($_REQUEST['base_currency'] == $cur_valuename) {
                    $adb->pquery("update aicrm_products set currency_id=?, unit_price=? where productid=?", array($curid, $actual_price, $this->id));
                }
            }
        }

        $log->debug("Exiting from insertPriceInformation($tablename, $module) method ...");
    }

    function updateUnitPrice()
    {
        $prod_res = $this->db->pquery("select unit_price, currency_id from aicrm_products where productid=?", array($this->id));
        $prod_unit_price = $this->db->query_result($prod_res, 0, 'unit_price');
        $prod_base_currency = $this->db->query_result($prod_res, 0, 'currency_id');

        $query = "update aicrm_productcurrencyrel set actual_price=? where productid=? and currencyid=?";
        $params = array($prod_unit_price, $this->id, $prod_base_currency);
        $this->db->pquery($query, $params);
    }

    function insertIntoAttachment($id, $module)
    {
        global $log, $adb;
        $log->debug("Entering into insertIntoAttachment($id,$module) method.");

        $file_saved = false;

        foreach ($_FILES as $fileindex => $files) {
            if ($files['name'] != '' && $files['size'] > 0) {
                if ($_REQUEST[$fileindex . '_hidden'] != '')
                    $files['original_name'] = vtlib_purify($_REQUEST[$fileindex . '_hidden']);
                else
                    $files['original_name'] = stripslashes($files['name']);
                $files['original_name'] = str_replace('"', '', $files['original_name']);
                $file_saved = $this->uploadAndSaveFile($id, $module, $files);
            }
        }
        //Remove the deleted aicrm_attachments from db - Products
        if ($module == 'Products' && $_REQUEST['del_file_list'] != '') {
            $del_file_list = explode("###", trim($_REQUEST['del_file_list'], "###"));
            foreach ($del_file_list as $del_file_name) {
                $attach_res = $adb->pquery("select aicrm_attachments.attachmentsid from aicrm_attachments inner join aicrm_seattachmentsrel on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid where crmid=? and name=?", array($id, $del_file_name));
                $attachments_id = $adb->query_result($attach_res, 0, 'attachmentsid');

                $del_res1 = $adb->pquery("delete from aicrm_attachments where attachmentsid=?", array($attachments_id));
                $del_res2 = $adb->pquery("delete from aicrm_seattachmentsrel where attachmentsid=?", array($attachments_id));
            }
        }

        $log->debug("Exiting from insertIntoAttachment($id,$module) method.");
    }
    /**    function used to get the list of leads which are related to the product
     * @param int $id - product id
     * @return array - array which will be returned from the function GetRelatedList
     */
    function get_job_list($id, $cur_tab_id, $rel_tab_id, $actions = false)
    {
        global $log, $singlepane_view, $currentModule, $current_user;
        $log->debug("Entering get_job_list(" . $id . ") method ...");
        $this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
        require_once("modules/$related_module/$related_module.php");
        $other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
        $singular_modname = vtlib_toSingular($related_module);

        $parenttab = getParentTab();

        if ($singlepane_view == 'true')
            $returnset = '&return_module=' . $this_module . '&return_action=DetailView&return_id=' . $id;
        else
            $returnset = '&return_module=' . $this_module . '&return_action=CallRelatedList&return_id=' . $id;

        $button = '';

        if ($actions) {
            if (is_string($actions)) $actions = explode(',', strtoupper($actions));
            if (in_array('SELECT', $actions) && isPermitted($related_module, 4, '') == 'yes') {
                $button .= "<input title='" . getTranslatedString('LBL_SELECT') . " " . getTranslatedString($related_module) . "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='" . getTranslatedString('LBL_SELECT') . " " . getTranslatedString($related_module) . "'>&nbsp;";
            }
            if (in_array('ADD', $actions) && isPermitted($related_module, 1, '') == 'yes') {
                $button .= "<input title='" . getTranslatedString('LBL_ADD_NEW') . " " . getTranslatedString($singular_modname) . "' class='crmbutton small create'" .
                " onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
                " value='" . getTranslatedString('LBL_ADD_NEW') . " " . getTranslatedString($singular_modname) . "'>&nbsp;";
            }
        }

        $query = "SELECT aicrm_jobs.*, aicrm_jobscf.*, aicrm_crmentity.crmid,
        aicrm_crmentity.smownerid,
        aicrm_products.productname,
        CASE
        WHEN ( aicrm_users.user_name NOT LIKE '') THEN aicrm_users.user_name
        ELSE aicrm_groups.groupname END AS user_name FROM aicrm_jobs
        LEFT JOIN aicrm_jobscf ON aicrm_jobs.jobid = aicrm_jobscf.jobid
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_jobs.jobid
        LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_jobs.product_id
        LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
        LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
        WHERE aicrm_crmentity.deleted = 0 AND aicrm_jobs.product_id = " . $id;

        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

        if ($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_contacts method ...");
        return $return_value;
    }

    /**    function used to get the number of vendors which are related to the product
     * @param int $id - product id
     * @return int number of rows - return the number of products which do not have relationship with vendor
     */
    function product_novendor()
    {
        global $log;
        $log->debug("Entering product_novendor() method ...");
        $query = "SELECT aicrm_products.productname, aicrm_crmentity.deleted
        FROM aicrm_products
        INNER JOIN aicrm_crmentity
        ON aicrm_crmentity.crmid = aicrm_products.productid
        WHERE aicrm_crmentity.deleted = 0
        AND aicrm_products.vendor_id is NULL";
        $result = $this->db->pquery($query, array());
        $log->debug("Exiting product_novendor method ...");
        return $this->db->num_rows($result);
    }

    /**
     * Function to get Product's related Products
     * @param  integer $id - productid
     * returns related Products record in array format
     */
    function get_competitor($id, $cur_tab_id, $rel_tab_id, $actions=false) {
        global $log, $singlepane_view,$currentModule,$current_user;
        $log->debug("Entering get_agreement(".$id.") method ...");
        $this_module = $currentModule;
        $related_module = vtlib_getModuleNameById($rel_tab_id);
        require_once("modules/$related_module/$related_module.php");
        $other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
        $singular_modname = vtlib_toSingular($related_module);
        $parenttab = getParentTab();

        if($singlepane_view == 'true')
            $returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
        else
            $returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

        $button = '';

        if($actions) {
            if(is_string($actions)) $actions = explode(',', strtoupper($actions));

            if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
                $button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
                " onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
                " value='". getTranslatedString('LBL_ADD_NEW'). " Competitor'>&nbsp;";
            }
        }

        $query = "SELECT  aicrm_users.user_name,aicrm_products.productid,aicrm_products.productname, aicrm_account.accountname,
        aicrm_crmentity.*,
        aicrm_competitor.*
        FROM aicrm_competitor
        LEFT JOIN aicrm_competitorcf ON aicrm_competitorcf.competitorid = aicrm_competitor.competitorid
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_competitor.competitorid
        LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
        LEFT JOIN aicrm_groups ON aicrm_groups.groupid=aicrm_crmentity.smownerid
        LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_competitor.account_id
        LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_competitor.product_id
        WHERE aicrm_crmentity.deleted = 0
        AND aicrm_competitor.product_id = '".$id."'
        ";

        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

        if($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_quotes method ...");
        return $return_value;
    }

    function get_serial($id, $cur_tab_id, $rel_tab_id, $actions=false) {
        global $log, $singlepane_view,$currentModule,$current_user;
        $log->debug("Entering get_agreement(".$id.") method ...");
        $this_module = $currentModule;
        $related_module = vtlib_getModuleNameById($rel_tab_id);
        require_once("modules/$related_module/$related_module.php");
        $other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
        $singular_modname = vtlib_toSingular($related_module);
        $parenttab = getParentTab();

        if($singlepane_view == 'true')
            $returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
        else
            $returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

        $button = '';

        if($actions) {
            if(is_string($actions)) $actions = explode(',', strtoupper($actions));
            
            if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
                $button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
                    " onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
                    " value='". getTranslatedString('LBL_ADD_NEW'). " Serial'>&nbsp;";
            }
        }

        $query = "SELECT  aicrm_users.user_name,
			aicrm_crmentity.*,
			aicrm_serial.*,
			aicrm_serialcf.*,
			aicrm_products.*
			FROM aicrm_serial
			LEFT JOIN aicrm_serialcf ON aicrm_serialcf.serialid = aicrm_serial.serialid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_serial.serialid
			LEFT JOIN aicrm_users ON aicrm_users.id=aicrm_crmentity.smownerid
			LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_serial.product_id
			WHERE aicrm_crmentity.deleted = 0
			AND aicrm_serial.product_id = '".$id."'
			";
       
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

        if($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_quotes method ...");
        return $return_value;

    }

    function get_errors($id, $cur_tab_id, $rel_tab_id, $actions=false) {
        global $log, $singlepane_view,$currentModule,$current_user;
        $log->debug("Entering get_agreement(".$id.") method ...");
        $this_module = $currentModule;
        $related_module = vtlib_getModuleNameById($rel_tab_id);
        require_once("modules/$related_module/$related_module.php");
        $other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
        $singular_modname = vtlib_toSingular($related_module);
        $parenttab = getParentTab();

        if($singlepane_view == 'true')
            $returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
        else
            $returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

        $button = '';

        if($actions) {
            if(is_string($actions)) $actions = explode(',', strtoupper($actions));
            
            if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
                $button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
                    " onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
                    " value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
            }
        }

        $query = "SELECT  aicrm_users.user_name,
			aicrm_crmentity.*,
			aicrm_errors.*,
			aicrm_errorscf.*,
			aicrm_products.*
			FROM aicrm_errors
			LEFT JOIN aicrm_errorscf ON aicrm_errorscf.errorsid = aicrm_errors.errorsid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_errors.errorsid
			LEFT JOIN aicrm_users ON aicrm_users.id=aicrm_crmentity.smownerid
			LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_errors.product_id
			WHERE aicrm_crmentity.deleted = 0
			AND aicrm_errors.product_id = '".$id."'
			";
       
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

        if($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_quotes method ...");
        return $return_value;

    }

    function get_quotes($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_quotes(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
		$singular_modname = vtlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}
        $query = "SELECT
            aicrm_crmentity.*, aicrm_quotes.*, aicrm_quotescf.*,
        aicrm_inventoryproductrel.id, aicrm_inventoryproductrel.productid, aicrm_inventoryproductrel.product_name
        FROM
            aicrm_quotes
            INNER JOIN aicrm_quotescf ON aicrm_quotescf.quoteid = aicrm_quotes.quoteid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_quotes.quoteid
            LEFT JOIN aicrm_inventoryproductrel ON aicrm_quotes.quoteid = aicrm_inventoryproductrel.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id 
        WHERE
            aicrm_crmentity.deleted = 0 
            AND aicrm_inventoryproductrel.productid = ".$id;
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_quotes method ...");
		return $return_value;
	}

    function get_sparepart($id, $cur_tab_id, $rel_tab_id, $actions=false) {
        global $log, $singlepane_view,$currentModule,$current_user;
        $log->debug("Entering get_agreement(".$id.") method ...");
        $this_module = $currentModule;
        $related_module = vtlib_getModuleNameById($rel_tab_id);
        require_once("modules/$related_module/$related_module.php");
        $other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
        $singular_modname = vtlib_toSingular($related_module);
        $parenttab = getParentTab();

        if($singlepane_view == 'true')
            $returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
        else
            $returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

        $button = '';

        if($actions) {
            if(is_string($actions)) $actions = explode(',', strtoupper($actions));
            
            if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
                $button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
                    " onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
                    " value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
            }
        }

        $query = "SELECT  aicrm_users.user_name,
			aicrm_crmentity.*,
			aicrm_sparepart.*,
			aicrm_sparepartcf.*,
			aicrm_products.*
			FROM aicrm_sparepart
			LEFT JOIN aicrm_sparepartcf ON aicrm_sparepartcf.sparepartid = aicrm_sparepart.sparepartid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_sparepart.sparepartid
			LEFT JOIN aicrm_users ON aicrm_users.id=aicrm_crmentity.smownerid
			LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_sparepart.product_id
			WHERE aicrm_crmentity.deleted = 0
			AND aicrm_sparepart.product_id = '".$id."'
			";
       
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

        if($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_quotes method ...");
        return $return_value;

    }

    /**
    * Function to get Products related Tickets
    * @param  integer   $id      - product_id
    * returns related Ticket record in array format
    */
    function get_tickets($id, $cur_tab_id, $rel_tab_id, $actions=false) {

        global $log, $singlepane_view,$currentModule,$current_user;
        $log->debug("Entering get_tickets(".$id.") method ...");
        $this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
        require_once("modules/$related_module/$related_module.php");
        $other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
        $singular_modname = vtlib_toSingular($related_module);

        $parenttab = getParentTab();

        if($singlepane_view == 'true')
            $returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
        else
            $returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

        $button = '';

            if(is_string($actions)){
                $actions = explode(',', strtoupper($actions));
            }
            if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
                $button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
            }

            if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {//echo "555";
                $button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
                    " onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
                    " value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
            }

        $query = "SELECT case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name,
            aicrm_troubletickets.*,
            aicrm_ticketcf.*,aicrm_account.*,
            CONCAT(aicrm_contactdetails.firstname,' ',aicrm_contactdetails.lastname) as contactname
            FROM aicrm_troubletickets
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_troubletickets.ticketid
            LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_troubletickets.accountid
            LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid=aicrm_troubletickets.contactid
            LEFT JOIN aicrm_users ON aicrm_users.id=aicrm_crmentity.smownerid
            LEFT JOIN aicrm_groups  ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            INNER JOIN aicrm_ticketcf ON aicrm_ticketcf.ticketid  = aicrm_troubletickets.ticketid
            WHERE  aicrm_crmentity.deleted = 0 AND aicrm_troubletickets.product_id = ".$id ;
        
        //Appending the security parameter
        global $current_user;
        require('user_privileges/user_privileges_'.$current_user->id.'.php');
        require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
        $tab_id=getTabid('Contacts');
        if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
        {
            $sec_parameter=getListViewSecurityParameter('Contacts');
            $query .= ' '.$sec_parameter;

        }

        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

        if($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_tickets method ...");
        return $return_value;
    }

    function get_marketingtools($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_marketingtools(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
		$singular_modname = vtlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}
        $query = "SELECT
        aicrm_marketingtools.*,
        aicrm_marketingtoolscf.*,
        aicrm_crmentity.crmid,
        aicrm_crmentity.smownerid,
        aicrm_products.productname,
        aicrm_crmentity.description,
    CASE
            
            WHEN ( aicrm_users.user_name NOT LIKE '' ) THEN
            aicrm_users.user_name ELSE aicrm_groups.groupname 
        END AS user_name 
    FROM
        aicrm_marketingtools
        LEFT JOIN aicrm_marketingtoolscf ON aicrm_marketingtoolscf.marketingtoolsid = aicrm_marketingtools.marketingtoolsid
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_marketingtools.marketingtoolsid
        LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_marketingtools.product_id
        LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
        LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id 
    WHERE
        aicrm_crmentity.deleted = 0 
        AND aicrm_marketingtools.product_id = ".$id;
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_marketingtools method ...");
		return $return_value;
	}
    /**    function used to get the export query for product
     * @param reference $where - reference of the where variable which will be added with the query
     * @return string $query - return the query which will give the list of products to export
     */
    function create_export_query($where)
    {
        global $log;
        $log->debug("Entering create_export_query(" . $where . ") method ...");

        include("include/utils/ExportUtils.php");

        //To get the Permitted fields query and the permitted fields list
        $sql = getPermittedFieldsQuery("Products", "detail_view");
        $fields_list = getFieldsListFromQuery($sql);

        $query = "SELECT $fields_list FROM " . $this->table_name . "
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid
        LEFT JOIN aicrm_productcf ON aicrm_products.productid = aicrm_productcf.productid
        LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
        LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid and aicrm_users.status = 'Active'
        LEFT JOIN aicrm_users as aicrm_usersCreator on aicrm_crmentity.smcreatorid = aicrm_usersCreator.id
        LEFT JOIN aicrm_users as aicrm_usersModified on aicrm_crmentity.modifiedby = aicrm_usersModified.id
        ";
        $query .= setFromQuery("Products");
        $query .= "	WHERE aicrm_crmentity.deleted = 0 ";
        ///*and aicrm_users.status = 'Active'*/ 
        //LEFT JOIN aicrm_vendor ON aicrm_vendor.vendorid = aicrm_products.vendor_id
        //LEFT JOIN aicrm_users ON aicrm_users.id=aicrm_products.handler

        if ($where != "")
            $query .= " AND ($where) ";

        $log->debug("Exiting create_export_query method ...");
        return $query;

    }

    /** Function to check if the product is parent of any other product
     */
    function isparent_check()
    {
        global $adb;
        $isparent_query = $adb->pquery(getListQuery("Products") . " AND (aicrm_products.productid IN (SELECT productid from aicrm_seproductsrel WHERE aicrm_seproductsrel.productid = ? AND aicrm_seproductsrel.setype='Products'))", array($this->id));
        $isparent = $adb->num_rows($isparent_query);
        return $isparent;
    }

    /** Function to check if the product is member of other product
     */
    function ismember_check()
    {
        global $adb;
        $ismember_query = $adb->pquery(getListQuery("Products") . " AND (aicrm_products.productid IN (SELECT crmid from aicrm_seproductsrel WHERE aicrm_seproductsrel.crmid = ? AND aicrm_seproductsrel.setype='Products'))", array($this->id));
        $ismember = $adb->num_rows($ismember_query);
        return $ismember;
    }

    /**
     * Move the related records of the specified list of id's to the given record.
     * @param String This module name
     * @param Array List of Entity Id's from which related records need to be transfered
     * @param Integer Id of the the Record to which the related records are to be moved
     */
    function transferRelatedRecords($module, $transferEntityIds, $entityId)
    {
        global $adb, $log;
        $log->debug("Entering function transferRelatedRecords ($module, $transferEntityIds, $entityId)");

        $rel_table_arr = Array("HelpDesk" => "aicrm_troubletickets", "Products" => "aicrm_seproductsrel", "Attachments" => "aicrm_seattachmentsrel",
            "Quotes" => "aicrm_inventoryproductrel", "PurchaseOrder" => "aicrm_inventoryproductrel", "SalesOrder" => "aicrm_inventoryproductrel",
            "Invoice" => "aicrm_inventoryproductrel", "PriceBooks" => "aicrm_pricebookproductrel", "Leads" => "aicrm_seproductsrel",
            "Accounts" => "aicrm_seproductsrel", "Potentials" => "aicrm_seproductsrel", "Contacts" => "aicrm_seproductsrel",
            "Documents" => "aicrm_senotesrel","Job" => "aicrm_jobs","Serial" => "aicrm_serial","Competitor" => "aicrm_competitor");

        $tbl_field_arr = Array("aicrm_troubletickets" => "ticketid", "aicrm_seproductsrel" => "crmid", "aicrm_seattachmentsrel" => "attachmentsid",
            "aicrm_inventoryproductrel" => "id", "aicrm_pricebookproductrel" => "pricebookid", "aicrm_seproductsrel" => "crmid",
            "aicrm_senotesrel" => "notesid","aicrm_jobs" => "jobid","aicrm_serial" => "serialid","aicrm_competitor" => "competitorid");

        $entity_tbl_field_arr = Array("aicrm_troubletickets" => "product_id", "aicrm_seproductsrel" => "crmid", "aicrm_seattachmentsrel" => "crmid",
            "aicrm_inventoryproductrel" => "productid", "aicrm_pricebookproductrel" => "productid", "aicrm_seproductsrel" => "productid",
            "aicrm_senotesrel" => "crmid" ,"aicrm_jobs" => "product_id","aicrm_serial" => "product_id","aicrm_competitor" => "product_id");

        foreach ($transferEntityIds as $transferId) {
            foreach ($rel_table_arr as $rel_module => $rel_table) {
                $id_field = $tbl_field_arr[$rel_table];
                $entity_id_field = $entity_tbl_field_arr[$rel_table];
                // IN clause to avoid duplicate entries
                $sel_result = $adb->pquery("select $id_field from $rel_table where $entity_id_field=? " .
                    " and $id_field not in (select $id_field from $rel_table where $entity_id_field=?)",
                    array($transferId, $entityId));
                $res_cnt = $adb->num_rows($sel_result);
                if ($res_cnt > 0) {
                    for ($i = 0; $i < $res_cnt; $i++) {
                        $id_field_value = $adb->query_result($sel_result, $i, $id_field);
                        $adb->pquery("update $rel_table set $entity_id_field=? where $entity_id_field=? and $id_field=?",
                            array($entityId, $transferId, $id_field_value));
                    }
                }
            }
        }
        $log->debug("Exiting transferRelatedRecords...");
    }

    /*
     * Function to get the secondary query part of a report
     * @param - $module primary module name
     * @param - $secmodule secondary module name
     * returns the query string formed on fetching the related data for report for secondary module
     */
    function generateReportsSecQuery($module, $secmodule)
    {
        // echo $module." / ".$secmodule; exit;
        global $current_user;
        $query = $this->getRelationQuery($module, $secmodule, "aicrm_products", "productid");
        if ($module == "Contacts") {
            $query = " Left join aicrm_products on aicrm_products.productid = aicrm_contactdetails.product_id
            left join aicrm_productcf  on aicrm_productcf.productid = aicrm_products.productid
            and aicrm_contactdetails.product_id = aicrm_productcf.productid
            left join aicrm_crmentity as aicrm_crmentityProducts on aicrm_crmentityProducts.crmid=aicrm_products.productid and aicrm_crmentityProducts.deleted=0
            left join aicrm_users as aicrm_usersProducts on aicrm_usersProducts.id = aicrm_products.handler
            left join aicrm_vendor as aicrm_vendorRelProducts on aicrm_vendorRelProducts.vendorid = aicrm_products.vendor_id
            left join aicrm_users as aicrm_usersModifiedProducts on aicrm_crmentity.smcreatorid=aicrm_usersModifiedProducts.id
            left join aicrm_users as aicrm_usersCreatorProducts on aicrm_crmentity.smcreatorid=aicrm_usersModifiedProducts.id";
        }elseif($module=='Leads' && $secmodule =='Products'){
            // echo $module." / ".$secmodule; exit;
            $query .= " 
            LEFT JOIN aicrm_crmentity AS aicrm_crmentityProducts ON aicrm_crmentityProducts.crmid = aicrm_products.productid 
            AND aicrm_crmentityProducts.deleted = 0
            LEFT JOIN aicrm_users AS aicrm_usersModifiedProducts ON aicrm_crmentity.smcreatorid = aicrm_usersModifiedProducts.id
            LEFT JOIN aicrm_users AS aicrm_usersCreatorProducts ON aicrm_crmentity.smcreatorid = aicrm_usersModifiedProducts.id
            ";

        }elseif($module=='Accounts' && $secmodule =='Products'){
            $query .= " left join aicrm_crmentity as aicrm_crmentityProducts on aicrm_crmentityProducts.crmid=aicrm_products.productid and aicrm_crmentityProducts.deleted=0
            left join aicrm_productcf on aicrm_productcf.productid = aicrm_crmentityProducts.crmid
            left join aicrm_groups as aicrm_groupsProducts on aicrm_groupsProducts.groupid = aicrm_crmentityProducts.smownerid
            left join aicrm_users as aicrm_usersProducts on aicrm_usersProducts.id = aicrm_crmentityProducts.smownerid
            left join aicrm_users as aicrm_usersCreatorProducts on aicrm_usersCreatorProducts.id = aicrm_crmentityProducts.smcreatorid
            left join aicrm_users as aicrm_usersModifiedProducts on aicrm_usersModifiedProducts.id = aicrm_crmentityProducts.modifiedby";
        }elseif($module=='Deal' && $secmodule =='Products'){
            $query .= "  left join aicrm_crmentity as aicrm_crmentityProducts on aicrm_crmentityProducts.crmid=aicrm_products.productid and aicrm_crmentityProducts.deleted=0
            left join aicrm_productcf on aicrm_productcf.productid = aicrm_crmentityProducts.crmid
            left join aicrm_groups as aicrm_groupsProducts on aicrm_groupsProducts.groupid = aicrm_crmentityProducts.smownerid
            left join aicrm_users as aicrm_usersProducts on aicrm_usersProducts.id = aicrm_crmentityProducts.smownerid
            left join aicrm_users as aicrm_usersCreatorProducts on aicrm_usersCreatorProducts.id = aicrm_crmentityProducts.smcreatorid
            left join aicrm_users as aicrm_usersModifiedProducts on aicrm_usersModifiedProducts.id = aicrm_crmentityProducts.modifiedby";
        } else {
            $query = $this->getRelationQuery($module, $secmodule, "aicrm_products", "productid");
            $query .= " LEFT JOIN (
            SELECT aicrm_products.productid,
            (CASE WHEN (aicrm_products.currency_id = 1 ) THEN aicrm_products.unit_price
            ELSE (aicrm_products.unit_price / aicrm_currency_info.conversion_rate) END
            ) AS actual_unit_price
            FROM aicrm_products
            LEFT JOIN aicrm_currency_info ON aicrm_products.currency_id = aicrm_currency_info.id
            LEFT JOIN aicrm_productcurrencyrel ON aicrm_products.productid = aicrm_productcurrencyrel.productid
            AND aicrm_productcurrencyrel.currencyid = " . $current_user->currency_id . "
            ) AS innerProduct ON innerProduct.productid = aicrm_products.productid
            left join aicrm_crmentity as aicrm_crmentityProducts on aicrm_crmentityProducts.crmid=aicrm_products.productid and aicrm_crmentityProducts.deleted=0
            left join aicrm_productcf on aicrm_products.productid = aicrm_productcf.productid
            left join aicrm_users as aicrm_usersProducts on aicrm_usersProducts.id = aicrm_products.handler
            left join aicrm_vendor as aicrm_vendorRelProducts on aicrm_vendorRelProducts.vendorid = aicrm_products.vendor_id
            left join aicrm_users as aicrm_usersModifiedProducts on aicrm_crmentity.smcreatorid=aicrm_usersModifiedProducts.id
            left join aicrm_users as aicrm_usersCreatorProducts on aicrm_crmentity.smcreatorid=aicrm_usersModifiedProducts.id";
        }
        return $query;
    }


    /*
     * Function to get the relation tables for related modules
     * @param - $secmodule secondary module name
     * returns the array with table names and fieldnames storing relations between module and this module
     */
    function setRelationTables($secmodule)
    {
        //echo $secmodule; exit;
        $rel_tables = array(
            "Documents" => array("aicrm_senotesrel" => array("crmid", "notesid"), "aicrm_products" => "productid"),
            "Competitor" => array("aicrm_competitor" => array("product_id", "competitorid"), "aicrm_products" => "productid"),
            "Serial" => array("aicrm_serial"=>array("product_id","serialid"),"aicrm_products" => "productid"),
            "Job" => array("aicrm_jobs"=>array("product_id","jobid"),"aicrm_products" => "productid"),
        );
        return $rel_tables[$secmodule];
    }

    function deleteProduct2ProductRelation($record, $return_id, $is_parent)
    {
        global $adb;
        if ($is_parent == 0) {
            $sql = "delete from aicrm_seproductsrel WHERE crmid = ? AND productid = ?";
            $adb->pquery($sql, array($record, $return_id));
        } else {
            $sql = "delete from aicrm_seproductsrel WHERE crmid = ? AND productid = ?";
            $adb->pquery($sql, array($return_id, $record));
        }
    }

    function insertIntoseProductsRel($record_id, $parentid, $return_module)
    {
        global $adb;
        $query = $adb->pquery("SELECT * from aicrm_seproductsrel WHERE ((crmid=? and productid=?) OR (crmid=? and productid=?)) AND setype='Products'", array($record_id, $parentid, $parentid, $record_id));
        if ($adb->num_rows($query) == 0 && $return_module == 'Products') {
            $adb->pquery("insert into aicrm_seproductsrel values (?,?,?)", array($record_id, $parentid, $return_module));
        }
    }

    // Function to unlink all the dependent entities of the given Entity by Id
    function unlinkDependencies($module, $id)
    {
        global $log;
        //Backup Campaigns-Product Relation
        $cmp_q = 'SELECT campaignid FROM aicrm_campaign WHERE product_id = ?';
        $cmp_res = $this->db->pquery($cmp_q, array($id));
        if ($this->db->num_rows($cmp_res) > 0) {
            $cmp_ids_list = array();
            for ($k = 0; $k < $this->db->num_rows($cmp_res); $k++) {
                $cmp_ids_list[] = $this->db->query_result($cmp_res, $k, "campaignid");
            }
            $params = array($id, RB_RECORD_UPDATED, 'aicrm_campaign', 'product_id', 'campaignid', implode(",", $cmp_ids_list));
            $this->db->pquery('INSERT INTO aicrm_relatedlists_rb VALUES (?,?,?,?,?,?)', $params);
        }
        //we have to update the product_id as null for the campaigns which are related to this product
        $this->db->pquery('UPDATE aicrm_campaign SET product_id=0 WHERE product_id = ?', array($id));

        $this->db->pquery('DELETE from aicrm_seproductsrel WHERE productid=? or crmid=?', array($id, $id));

        parent::unlinkDependencies($module, $id);
    }

    // Function to unlink an entity with given Id from another entity
    function unlinkRelationship($id, $return_module, $return_id)
    {
        // echo $return_module; exit;
        global $log;
        if (empty($return_module) || empty($return_id)) return;
        if ($return_module == 'Calendar') {
            $sql = 'DELETE FROM aicrm_seactivityrel WHERE crmid = ? AND activityid = ?';
            $this->db->pquery($sql, array($id, $return_id));
        } elseif ($return_module == 'Leads' || $return_module == 'Accounts' || $return_module == 'Contacts' || $return_module == 'Potentials') {
            $sql = 'DELETE FROM aicrm_seproductsrel WHERE productid = ? AND crmid = ?';
            $this->db->pquery($sql, array($id, $return_id));
        } elseif ($return_module == 'Sparepart') {
            $relation_query = 'UPDATE aicrm_sparepart SET product_id=0 WHERE sparepartid=?';
            $this->db->pquery($relation_query, array($return_id));
        } else {
            $sql = 'DELETE FROM aicrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
            $params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
            $this->db->pquery($sql, $params);
        }
    }


}

?>
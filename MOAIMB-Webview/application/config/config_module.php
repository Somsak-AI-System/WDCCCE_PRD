<?php
//Accounts
$config['module']['Accounts']["search"] =  array('aicrm_account.accountname'=>'Account Name','aicrm_account.account_no'=>'Account No.','aicrm_account.accounttype'=>'Account Type','aicrm_account.account_group'=>'Account Group');
$config['module']['Accounts']["header"] = [
    ['field' => 'account_no', 'label' => 'Account No'],
    ['field' => 'accountname', 'label' => 'Account Name'],
    ['field' => 'accounttype', 'label' => 'Account Type'],
    ['field' => 'account_group', 'label' => 'Account Group']
];

//Contacts
$config['module']['Contacts']["search"] =  array('aicrm_contactdetails.contactname'=>'Contact Name','aicrm_contactdetails.contact_no'=>'Contact No.','aicrm_contactdetails.mobile'=>'Telephone','aicrm_contactdetails.email'=>'E-Mail');
$config['module']['Contacts']["header"] = [
    ['field' => 'contact_no', 'label' => 'Contact No'],
    ['field' => 'contactname', 'label' => 'Contact Name'],
    ['field' => 'mobile', 'label' => 'Telephone'],
    ['field' => 'email', 'label' => 'E-Mail']
];

//Deal
$config['module']['Deal']["search"] =  array(
    'aicrm_deal.deal_no'=>'หมายเลขโอกาสในการขาย',
    'aicrm_deal.deal_name'=>'ชื่อโอกาสการขาย',
    'parentid'=>'ชื่อลูกค้า',
    'aicrm_deal.stage'=>'สถานะของดีล'
);
$config['module']['Deal']["header"] = [
    ['field' => 'deal_no', 'label' => 'หมายเลขโอกาสในการขาย'],
    ['field' => 'deal_name', 'label' => 'ชื่อโอกาสการขาย'],
    ['field' => 'title', 'label' => 'ชื่อลูกค้า'],
    ['field' => 'status', 'label' => 'สถานะของดีล']
];

//Products
$config['module']['Products']["search"] =  array('aicrm_products.material_code'=>'Material Code','aicrm_products.product_brand'=>'Brand','aicrm_products.product_sub_group'=>'Product Sub Group','aicrm_products.product_catalog_code'=>'Catalog Code');
$config['module']['Products']["header"] = [
    ['field' => 'material_code', 'label' => 'Material Code'],
    ['field' => 'product_brand', 'label' => 'Brand'],
    ['field' => 'product_sub_group', 'label' => 'Product Sub Group'],
    ['field' => 'product_catalog_code', 'label' => 'Catalog Code']
];

//Competitor Product
$config['module']['Competitorproduct']["search"] =  array('aicrm_competitorproduct.competitorproduct_no'=>'Competitor Product Code','aicrm_competitorproduct.competitorproduct_name_th'=>'Competitor Product Name','aicrm_competitorproduct.competitor_product_brand'=>'Competitor Product Brand','aicrm_competitorproduct.competitor_product_group'=>'Competitor Product Group');  
$config['module']['Competitorproduct']["header"] = [
    ['field' => 'competitorproduct_no', 'label' => 'Competitor Product Code'],
    ['field' => 'competitorproduct_name_th', 'label' => 'Competitor Product Name'],
    ['field' => 'competitor_product_brand', 'label' => 'Competitor Product Brand'],
    ['field' => 'competitor_product_group', 'label' => 'Competitor Product Group']
];

?>
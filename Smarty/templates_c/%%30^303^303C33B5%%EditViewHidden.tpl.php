<?php /* Smarty version 2.6.18, created on 2026-04-08 16:50:50
         compiled from EditViewHidden.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'vtlib_purify', 'EditViewHidden.tpl', 100, false),)), $this); ?>

<?php if ($this->_tpl_vars['MODULE'] == 'Emails'): ?>
	<form name="EditView" method="POST" ENCTYPE="multipart/form-data" action="index.php" onsubmit="VtigerJS_DialogBox.block();">
        <input type="hidden" name="form">
        <input type="hidden" name="send_mail">
        <input type="hidden" name="contact_id" value="<?php echo $this->_tpl_vars['CONTACT_ID']; ?>
">
        <input type="hidden" name="user_id" value="<?php echo $this->_tpl_vars['USER_ID']; ?>
">
        <input type="hidden" name="filename" value="<?php echo $this->_tpl_vars['FILENAME']; ?>
">
        <input type="hidden" name="old_id" value="<?php echo $this->_tpl_vars['OLD_ID']; ?>
">

<?php elseif ($this->_tpl_vars['MODULE'] == 'Contacts'): ?>
	<?php echo $this->_tpl_vars['ERROR_MESSAGE']; ?>

    <form name="EditView" method="POST" ENCTYPE="multipart/form-data" action="index.php" onsubmit="VtigerJS_DialogBox.block();">
		<input type="hidden" name="activity_mode" value="<?php echo $this->_tpl_vars['ACTIVITY_MODE']; ?>
">
		<input type="hidden" name="opportunity_id" value="<?php echo $this->_tpl_vars['OPPORTUNITY_ID']; ?>
">
		<input type="hidden" name="contact_role">
		<input type="hidden" name="case_id" value="<?php echo $this->_tpl_vars['CASE_ID']; ?>
">
		<INPUT TYPE="HIDDEN" NAME="MAX_FILE_SIZE" VALUE="800000">
		<input type="hidden" name="campaignid" value="<?php echo $this->_tpl_vars['campaignid']; ?>
">

<?php elseif ($this->_tpl_vars['MODULE'] == 'Potentials'): ?>
	<form name="EditView" method="POST" action="index.php" onsubmit="VtigerJS_DialogBox.block();">
		<input type="hidden" name="contact_id" value="<?php echo $this->_tpl_vars['CONTACT_ID']; ?>
">

<?php elseif ($this->_tpl_vars['MODULE'] == 'Calendar'): ?>
	<form name="EditView" method="POST" action="index.php" onsubmit="VtigerJS_DialogBox.block();">
		<input type="hidden" name="activity_mode" value="<?php echo $this->_tpl_vars['ACTIVITY_MODE']; ?>
" >
		<!-- <input type="hidden" name="product_id" value="<?php echo $this->_tpl_vars['PRODUCTID']; ?>
"> -->

<?php elseif ($this->_tpl_vars['MODULE'] == 'Quotes'): ?>
	<!-- (id="frmEditView") content added to form tag and new hidden field added,  -->
	<form id="frmEditView" name="EditView" method="POST" action="index.php" onSubmit="settotalnoofrows();VtigerJS_DialogBox.block();">
		<input type="hidden" name="hidImagePath" id="hidImagePath" value="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
"/>
	<!-- End of code added -->

<?php elseif ($this->_tpl_vars['MODULE'] == 'HelpDesk'): ?>
	<form name="EditView" method="POST" action="index.php" ENCTYPE="multipart/form-data" onsubmit="VtigerJS_DialogBox.block();">
		<input type="hidden" name="old_smownerid" value="<?php echo $this->_tpl_vars['OLDSMOWNERID']; ?>
">
		<input type="hidden" NAME="MAX_FILE_SIZE" VALUE="800000">
		<input type="hidden" name="old_id" value="<?php echo $this->_tpl_vars['OLD_ID']; ?>
">

<?php elseif ($this->_tpl_vars['MODULE'] == 'Leads'): ?>
	<form name="EditView" method="POST" ENCTYPE="multipart/form-data" action="index.php" onsubmit="VtigerJS_DialogBox.block();">
		<input type="hidden" NAME="MAX_FILE_SIZE" VALUE="800000">
        <input type="hidden" name="campaignid" value="<?php echo $this->_tpl_vars['campaignid']; ?>
">

<?php elseif ($this->_tpl_vars['MODULE'] == 'Accounts' || $this->_tpl_vars['MODULE'] == 'PriceBooks'): ?>
	<form name="EditView" method="POST" ENCTYPE="multipart/form-data" action="index.php" onsubmit="VtigerJS_DialogBox.block();">
		<input type="hidden" NAME="MAX_FILE_SIZE" VALUE="800000">
        <input type="hidden" name="campaignid" value="<?php echo $this->_tpl_vars['campaignid']; ?>
">

<?php elseif ($this->_tpl_vars['MODULE'] == 'Documents'): ?>
	<form name="EditView" method="POST" ENCTYPE="multipart/form-data" action="index.php" onsubmit="VtigerJS_DialogBox.block();">
		<input type="hidden" name="max_file_size" value="<?php echo $this->_tpl_vars['MAX_FILE_SIZE']; ?>
">
		<input type="hidden" name="form">
		<input type="hidden" name="email_id" value="<?php echo $this->_tpl_vars['EMAILID']; ?>
">
		<input type="hidden" name="ticket_id" value="<?php echo $this->_tpl_vars['TICKETID']; ?>
">
		<input type="hidden" name="fileid" value="<?php echo $this->_tpl_vars['FILEID']; ?>
">
		<input type="hidden" name="old_id" value="<?php echo $this->_tpl_vars['OLD_ID']; ?>
">
		<input type="hidden" name="parentid" value="<?php echo $this->_tpl_vars['PARENTID']; ?>
">
		<input type="hidden" name="related_id" value="<?php echo $this->_tpl_vars['RELATEDID']; ?>
">

<?php elseif ($this->_tpl_vars['MODULE'] == 'Products' || $this->_tpl_vars['MODULE'] == 'KnowledgeBase' || $this->_tpl_vars['MODULE'] == 'Order' || $this->_tpl_vars['MODULE'] == 'Announcement' || $this->_tpl_vars['MODULE'] == 'Campaigns' || $this->_tpl_vars['MODULE'] == 'Deal' || $this->_tpl_vars['MODULE'] == 'Promotion' || $this->_tpl_vars['MODULE'] == 'Questionnaire' || $this->_tpl_vars['MODULE'] == 'Faq' || $this->_tpl_vars['MODULE'] == 'Competitorproduct' || $this->_tpl_vars['MODULE'] == 'Premuimproduct' || $this->_tpl_vars['MODULE'] == 'Promotionvoucher'): ?>
	<?php echo $this->_tpl_vars['ERROR_MESSAGE']; ?>

	<form name="EditView" method="POST" ENCTYPE="multipart/form-data" action="index.php" onsubmit="VtigerJS_DialogBox.block();">
		<input type="hidden" name="activity_mode" value="<?php echo $this->_tpl_vars['ACTIVITY_MODE']; ?>
">
		<input type="hidden" NAME="MAX_FILE_SIZE" VALUE=" 1048576">

<?php elseif ($this->_tpl_vars['MODULE'] == 'Job'): ?>
	<?php echo $this->_tpl_vars['ERROR_MESSAGE']; ?>

	<form name="EditView" method="POST" ENCTYPE="multipart/form-data" action="index.php" onsubmit="VtigerJS_DialogBox.block();">
		<input type="hidden" name="activity_mode" value="<?php echo $this->_tpl_vars['ACTIVITY_MODE']; ?>
">
		<INPUT TYPE="HIDDEN" NAME="MAX_FILE_SIZE" VALUE="800000">
		<input name="del_file_list" type="hidden" value="">

		<?php elseif ($this->_tpl_vars['MODULE'] == 'Smartquestionnaire' || $this->_tpl_vars['MODULE'] == 'Questionnairetemplate'): ?>
		<?php echo $this->_tpl_vars['ERROR_MESSAGE']; ?>

		<form name="EditView" method="POST" ENCTYPE="multipart/form-data" action="index.php" onsubmit="VtigerJS_DialogBox.block();">
			<input type="hidden" name="activity_mode" value="<?php echo $this->_tpl_vars['ACTIVITY_MODE']; ?>
">
			<INPUT TYPE="HIDDEN" NAME="MAX_FILE_SIZE" VALUE="800000">

<?php else: ?>
	<?php echo $this->_tpl_vars['ERROR_MESSAGE']; ?>

	<form name="EditView" method="POST" action="index.php" onsubmit="VtigerJS_DialogBox.block();">
<?php endif; ?>

<input type="hidden" name="pagenumber" value="<?php echo vtlib_purify($_REQUEST['start']); ?>
">
<input type="hidden" name="module" value="<?php echo $this->_tpl_vars['MODULE']; ?>
">
<input type="hidden" name="record" value="<?php echo $this->_tpl_vars['ID']; ?>
">
<input type="hidden" name="mode" id="mode" value="<?php echo $this->_tpl_vars['MODE']; ?>
">
<input type="hidden" name="action">
<input type="hidden" name="parenttab" value="<?php echo $this->_tpl_vars['CATEGORY']; ?>
">
<input type="hidden" name="return_module" value="<?php echo $this->_tpl_vars['RETURN_MODULE']; ?>
">
<input type="hidden" name="return_id" value="<?php echo $this->_tpl_vars['RETURN_ID']; ?>
">
<input type="hidden" name="return_action" value="<?php echo $this->_tpl_vars['RETURN_ACTION']; ?>
">
<input type="hidden" name="return_viewname" value="<?php echo $this->_tpl_vars['RETURN_VIEWNAME']; ?>
">
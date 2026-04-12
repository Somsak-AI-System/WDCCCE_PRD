$(document).ready(function() {
	
    $('#ncp_pass').validate({
		rules: {
			acc_pass: {required: true, maxlength: 12, minlength: 6},
			repass: {equalTo: '#acc_pass'}
		}
	});
	$('#ncp_pro').validate({
		rules: {
			acc_user: {required: true, maxlength: 12, minlength: 6},
			acc_name: {required: true, maxlength: 12, minlength: 6}
		}
	});
	
});
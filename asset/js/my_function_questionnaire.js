function addvalidate_number(id,sPrecision)
 {
	 sPrecision = typeof sPrecision !== 'undefined' ? sPrecision : "0";
	 jQuery(id).numberbox({
		  min:0,
		  precision:sPrecision
	 });
 }

 function addreq(id)
{
	 jQuery(id).validatebox({
	    required: true,
	});
}
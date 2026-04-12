
function MoneyFormat(val,row){
	
			return val.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
	}
	
function MoneyFormatString(val,row){
			
			//console.log(val);
			if (val ===  null ){
				return val;
			}else{	
				return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
			}
			
		
	}
function pad(n, width, z) {
  z = z || '0';
  n = n + '';
  return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
}
	
function formatDMYHHMMSS(val,row){
    if (!val || val=='')
	  return '';
 	  var d = new Date(val);
      var str = (val.split(' '));
	  var str_date =str[0];
	   var str_time =str[1];
	  var ss = (str_date.split('-'));
	  var tt =  (str_time.split(':'));
	  var sss =  (tt[2].split('.'));
         var y = parseInt(ss[0],10);
         var m = pad( parseInt(ss[1],10), 2);
         var d =pad(  parseInt(ss[2],10), 2);
		 var date_ddmmyy = d+"/"+m+"/"+y+' '+tt[0]+':'+tt[1]+':'+sss[0];
		 
     return date_ddmmyy;

	}
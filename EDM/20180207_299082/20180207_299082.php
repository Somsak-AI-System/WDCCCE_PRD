
		<!--DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"-->
		<html>
		<head>
		<title>Test 3333</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		</head>
		<script>
		var XMLHttpArray = [
				function() {return new XMLHttpRequest()},
				function() {return new ActiveXObject("Msxml2.XMLHTTP")},
				function() {return new ActiveXObject("Msxml2.XMLHTTP")},
				function() {return new ActiveXObject("Microsoft.XMLHTTP")}
		];
		function createXMLHTTPObject(){
				var xmlhttp = false;
				for(var i=0; i<XMLHttpArray.length; i++){
						try{
								xmlhttp = XMLHttpArray[i]();
						}catch(e){
								continue;
						}
						break;
				}
				return xmlhttp;
		}////
		
		function doQuery(click_link) {
			var req = createXMLHTTPObject();
			//alert(click_link);
			<?
				if (isset($_GET["id"])){
					$varid = $_GET["id"];
				}else{
					$varid = "0";
				}
				if (isset($_GET["campaignid"])){
					$varcampaignid = $_GET["campaignid"];
				}else{
					$varcampaignid = "0";
				}
				if (isset($_GET["marketid"])){
					$varmarketid = $_GET["marketid"];
				}else{
					$varmarketid = "0";
				}				
			?>
			var id="";
			id = <?=$varid;?>;
			var campaignid="";
			campaignid = <?=$varcampaignid;?>;
			var marketid="";
			marketid = <?=$varmarketid;?>;
			var strURL = "http://localhost:8090/mjdp//EDM/update_click_html.php?id="+ id+"&click_link="+click_link+"&campaignid="+campaignid+"&marketid="+marketid;
			//alert(strURL);
				if (req){
					req.onreadystatechange = function(){
						if (req.readyState == 4) { //data is retrieved from server
							if (req.status == 200) { // which reprents ok status                    
								//alert(5555);
							}else{ 
								//alert("There was a problem while using XMLHTTP:\n");
							}
						}            
					}        
					req.open("GET", strURL, true); //open url using get method
					req.send(null);//send the results
				}
			}
		</script>
		
		<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">		
			<table align="center" class="mceItemTable" id="Table_01" border="0" cellspacing="0" cellpadding="0" width="800" >
				<tbody>
					<tr>
						  <td  >
				<p>ทดสอบ รัตนา ภักดี</p>

<p><a href="http://localhost:8090/mjdp//EDM/update_click_edm.php?param=id212<?=$_REQUEST["id"]?>212campaignid212<?=$_REQUEST["campaignid"]?>212marketid212<?=$_REQUEST["marketid"]?>212module212<?=$_REQUEST["module"]?>212crmid212<?=$_REQUEST["crmid"]?>212link2122212click_type212html212url212http://www.google.co.th">www.google.co.th</a></p></td>
					</tr>
					<tr>
						<td height="50" align="center" style="font-size: 0px;" mce_style="font-size:0px;">
							<div style="color: rgb(102, 102, 102); line-height: 20px; font-family: tahoma; font-size: 12px;" mce_style="font-size: 12px; line-height: 20px; font-family: tahoma; color:#666666;">
							หากท่านไม่ต้องการรับข่าวสารจากทางเราอีก <a style="color:#fd8103;" target="_blank" href="http://localhost:8090/mjdp//EDM/unsub.php?id=<?=$_GET["id"]?>&campaignid=<?=$_GET["campaignid"]?>&marketid=<?=$_GET["marketid"]?>&module=<?=$_GET["module"]?>&crmid=<?=$_GET["crmid"]?>"  onClick="doQuery(16);">กรุณายกเลิกการรับข่าวสารที่นี่</a><br mce_bogus="1" /></div>
						</td>
					</tr>
				</tbody>
			</table>	
			</body>
		</html>			
		

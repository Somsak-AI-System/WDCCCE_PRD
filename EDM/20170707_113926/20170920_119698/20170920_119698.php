
		<!--DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"-->
		<html>
		<head>
		<title>Subject E-Mail</title>
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
				<!-- Save for Web Slices (2017-09-20_13-07-26.jpg) -->
<table border="0" cellpadding="0" cellspacing="0" id="Table_01" style="height:741px; width:630px">
	<tbody>
		<tr>
			<td colspan="3"><img alt="" src="http://localhost:8090/mjdp/EDM/images/index_01.png" style="height:91px; width:622px" /></td>
		</tr>
		<tr>
			<td colspan="3"><img alt="" src="http://localhost:8090/mjdp/EDM/images/index_02.png" style="height:84px; width:622px" /></td>
		</tr>
		<tr>
			<td colspan="3"><img alt="" src="http://localhost:8090/mjdp/EDM/images/index_03.png" style="height:89px; width:622px" /></td>
		</tr>
		<tr>
			<td><img alt="" src="http://localhost:8090/mjdp/EDM/images/index_04.png" style="height:121px; width:251px" /></td>
			<td><img alt="" src="http://localhost:8090/mjdp/qrcode/{accountid}.png" style="height:200px; width:200px" /></td>
			<td><img alt="" src="http://localhost:8090/mjdp/EDM/images/index_06.png" style="height:121px; width:251px" /></td>
		</tr>
		<tr>
			<td colspan="3"><img alt="" src="http://localhost:8090/mjdp/EDM/images/index_07.png" style="height:30px; width:622px" /></td>
		</tr>
		<tr>
			<td><img alt="" src="http://localhost:8090/mjdp/EDM/images/index_08.png" style="height:21px; width:251px" /></td>
			<td style="text-align:center">{accountid}</td>
			<td><img alt="" src="http://localhost:8090/mjdp/EDM/images/index_10.png" style="height:21px; width:251px" /></td>
		</tr>
		<tr>
			<td colspan="3"><img alt="" src="http://localhost:8090/mjdp/EDM/images/index_11.png" style="height:85px; width:622px" /></td>
		</tr>
		<tr>
			<td colspan="3"><img alt="" src="http://localhost:8090/mjdp/EDM/images/index_12.png" style="height:59px; width:622px" /></td>
		</tr>
		<tr>
			<td colspan="3"><img alt="" src="http://localhost:8090/mjdp/EDM/images/index_13.jpg" style="height:88px; width:622px" /></td>
		</tr>
	</tbody>
</table>
<!-- End Save for Web Slices --></td>
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
		

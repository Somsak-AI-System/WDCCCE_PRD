<link rel="stylesheet" type="text/css" href="lib/jquery/plugins/ui.tabs.css" />
<link rel="stylesheet" type="text/css" media="print" href="css/print.css" / >
<link rel="stylesheet" type="text/css" href="css/staff-global.css" />
<script type="text/javascript" src="lib/jquery/jquery.js"></script>
<script type="text/javascript" src="lib/jquery/plugins/ui.tabs.js"></script>
<script type="text/javascript" src="lib/jquery/plugins/jquery.hotkeys.min.js"></script>
<script type="text/javascript" src="lib/jquery/plugins/jquery.cookie.min.js"></script>
<script type="text/javascript" src="lib/jquery/plugins/jquery.highlight-3.js"></script> 

<script type="text/javascript" src="lib/yui/utilities/utilities.js"></script> 
<script type="text/javascript" src="lib/yui/datasource/datasource-min.js"></script>
<script type="text/javascript" src="lib/yui/autocomplete/autocomplete-min.js"></script>
<script type="text/javascript" src="lib/yui/button/button-min.js"></script> 
<script type="text/javascript" src="lib/yui/container/container_core-min.js"></script> 
<script type="text/javascript" src="lib/yui/menu/menu-min.js"></script> 
<script type="text/javascript" src="js/staff-global.js"></script>

    <div id="header" class="clearfix">
        <div id="toplevelnav">
            <ul id="toplevelmenu">

                <li><a href="/cgi-bin/koha/circ/circulation-home.pl">Circulation</a></li>
                <li><a href="/cgi-bin/koha/members/members-home.pl">Patrons</a></li>
                <li><a href="/cgi-bin/koha/catalogue/search.pl">Search</a></li>
                
                
                <li class="more">
                    <a id="showmore" href="/cgi-bin/koha/mainpage.pl">More</a>
                    <div id="moremenu">
                        <div class="bd">
                            <ul>
                            <li><a href="/cgi-bin/koha/virtualshelves/shelves.pl">Lists</a></li>
                            
                            <li><a href="/cgi-bin/koha/cataloguing/addbooks.pl">Cataloging</a></li>
                            
                            
                            <li><a href="/cgi-bin/koha/acqui/acqui-home.pl">Acquisitions</a></li>
                            
                            
                            <li><a href="/cgi-bin/koha/authorities/authorities-home.pl">Authorities</a></li>
                            
                            
                            <li><a href="/cgi-bin/koha/serials/serials-home.pl">Serials</a></li>

                            
                            
                            <li><a href="/cgi-bin/koha/reports/reports-home.pl">Reports</a></li>
                            
                            
                            <li><a href="/cgi-bin/koha/tools/tools-home.pl">Tools</a></li>
                            
                            
                            <li><a href="/cgi-bin/koha/admin/admin-home.pl">Administration</a></li>
                            
                            <li><a href="/cgi-bin/koha/about.pl">About Koha</a></li>
                            </ul>
                        </div>
                    </div>

                </li>
            </ul>
        </div>
    <div id="login">
        
            <strong>NO_LIBRARY_SET</strong>
            
                (<a href="/cgi-bin/koha/circ/selectbranchprinter.pl">Set library</a>)
            
        |
        
        
            <span class="loggedinusername">
                koha3
            </span>

            
            (<a href="/cgi-bin/koha/mainpage.pl?logout.x=1" onClick="clearHoldFor();">Log Out</a>) |
            
        
        <a href="/cgi-bin/koha/help.pl" id="helper" onClick="window.open('/cgi-bin/koha/help.pl?url='+escape(document.url),'Koha_Help','width=600,height=600,toolbar=false,scrollbars=yes'); return false;">[ ? ]</a>
    </div>
</div>	


<h1 id="logo"><a href="/cgi-bin/koha/mainpage.pl">CREATIVE RESOURCE CENTER FOR DESIGN</a></h1><!-- Begin Catalogue Resident Search Box -->
<script type="text/javascript">
//<![CDATA[
YAHOO.util.Event.onContentReady("header_search", function() {
    new function() {
        // Define a custom formatter function
        this.fnCustomFormatter = function(oResultItem, sQuery) {
            var name        = oResultItem[0];
            var cardnumber  = oResultItem[1];
            var address     = oResultItem[2];
            var city        = oResultItem[3];
            var zip         = oResultItem[4];
            var aMarkup = [
                "<div class=\"sample-result\">",
                name,
                " (",
                cardnumber,
                ")<small> -- ",
                address,
                ", ",
                city,
                "<\/small>",
                "<\/div>"];
            return (aMarkup.join(""));
        };

        // Instantiate one XHR DataSource and define schema as an array:
        //     ["Record Delimiter",
        //     "Field Delimiter"]
        this.oACDS = new YAHOO.widget.DS_XHR("/cgi-bin/koha/circ/ysearch.pl", ["\n", "\t"]);
        this.oACDS.responseType = YAHOO.widget.DS_XHR.TYPE_FLAT;
        this.oACDS.maxCacheEntries = 60;
        this.oACDS.queryMatchSubset = true;
    
        // Instantiate first AutoComplete
        var myInput = document.getElementById('findborrower');
        var myContainer = document.getElementById('yborrowercontainer');
        this.oAutoComp = new YAHOO.widget.AutoComplete(myInput,myContainer,this.oACDS);
        //this.oAutoComp.delimChar = ";";
        //this.oAutoComp.queryDelay = 1;
        this.oAutoComp.formatResult = this.fnCustomFormatter;
}
});    
//]]>
</script>
<div id="header_search">
	
	<div id="circ_search" class="residentsearch" style="display:none;">
	<p class="tip">Enter patron card number or partial name:</p>

    <form action="/cgi-bin/koha/circ/circulation.pl" method="post" autocomplete="off">
    
    <div class="autocomplete">
            <div id="borrowerautocomplete" class="autocomplete">
                <input autocomplete="off" id="findborrower" name="findborrower" type="text" />
                <div id="yborrowercontainer"></div>
                <input id="ysearchsubmit" type="submit" class="submit" value="Submit" />
                <input name="branch" value="" type="hidden" />
                <input name="printer" value="" type="hidden" />
            </div>

        </div>
	
    </form>
	</div>
	
	

<div id="checkin_search" class="residentsearch" style="display:none;">
    <p class="tip">Scan a barcode to check in:</p>
    <form method="post" action="/cgi-bin/koha/circ/returns.pl" autocomplete="off">
        <input name="barcode" id="ret_barcode" size="40" />
        <input value="Submit" class="submit" type="submit" />

    </form>
</div>
	
	
	<div id="catalog_search" class="residentsearch">
	<p class="tip">Enter search keywords:</p>
		<form action="/cgi-bin/koha/catalogue/search.pl"  method="get" id="cat-search-block">
			 <input type="text" name="q" id="search-form" size="40" value="" title="Enter the terms you wish to search for." class="form-text" />
				<input type="submit" class="submit" value="Submit" />
		</form>
	</div>

	
	
			<ul>
			<li><a href="/cgi-bin/koha/circ/circulation.pl#circ_search">Check Out</a></li>
    <li><a href="/cgi-bin/koha/circ/returns.pl#checkin_search">Check In</a></li>
			<li class="ui-tabs-selected"><a href="/cgi-bin/koha/catalogue/search.pl#catalog_search">Search the Catalog</a></li>
			</ul>	
</div>




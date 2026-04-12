// JavaScript Document
$(function() {
 var closeModal = function(hash)
    {
	
        var $modalWindow = $(hash.w);
 
        $modalWindow.fadeOut('2000', function()
        {
            hash.o.remove();
            //refresh parent
            if (hash.refreshAfterClose == true)
            {
                window.location.href = document.location.href;
            }
        });
    };
function LoadIframe(URL) {
	var http = false;

    if(navigator.appName == "Microsoft Internet Explorer") {
      http = new ActiveXObject("Microsoft.XMLHTTP");
    } else {
      http = new XMLHttpRequest();
    }
      http.abort();

      http.open("GET", URL, true);
      http.onreadystatechange=function() {
        if(http.readyState == 4) {
          document.getElementById( 'jqmContent' ).src = URL;
        }
      }
      http.send(null);
    }
	
	$(".popup-search").click(function() {
	  var objectId =  $(this).attr('rel');
	 
      var URL = $(this).data("target"); 
	 
	  var  fullURL= URL;
	  
		LoadIframe(fullURL);
	 $('#SearchWindow').jqm().jqmShow({
		  overlay: 70,
		  modal:false,
		  target: '#jqmContent'   
				
		   });
    });
	
	
	});
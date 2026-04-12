<!-- <!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<aside class="left-sidebar">
    <!-- <div class="d-flex no-block nav-text-box align-items-center">
        <span><img src="../assets/images/logo-icon.png" alt="elegant admin template"></span>
        <a class="nav-lock waves-effect waves-dark ml-auto hidden-md-down" href="javascript:void(0)"><i class="mdi mdi-toggle-switch"></i></a>
        <a class="nav-toggler waves-effect waves-dark ml-auto hidden-sm-up" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
    </div> -->

    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="iconleftbar active_bar"> 
                    <!-- <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" > -->
                    <a class="waves-effect waves-dark" href="javascript:void(0)" onclick="return loadIframe('https://moaioc.moai-crm.com/agent?userid=<?php echo USERID;?>&channel=all&socialid=0');" />
                        
                        <!-- <i class="fa fa-phone"></i> -->
                        <i>
                            <img src="<?php echo site_assets_url('images/icons/logo_allchat.png'); ?>" border="0" alt="" width="35" height="35">
                        </i>
                    </a>
                </li>
                <li class="iconleftbar">
                    <a class="waves-effect waves-dark" href="javascript:void(0)" onclick="return loadIframe('https://moaioc.moai-crm.com/agent?userid=<?php echo USERID;?>&channel=line&socialid=1');" />
                        <i>
                            <img src="<?php echo site_assets_url('images/icons/logo_linechat.png'); ?>" border="0" alt="" width="35" height="35" >
                        </i>
                    </a>
                </li>
                <li class="iconleftbar">
                    <a class="waves-effect waves-dark" href="javascript:void(0)" onclick="return loadIframe('https://moaioc.moai-crm.com/agent?userid=<?php echo USERID;?>&channel=facebook&socialid=2');" />
                        <i>
                            <img src="<?php echo site_assets_url('images/icons/logo_messengerchat.png'); ?>" border="0" alt="" width="35" height="35">
                        </i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    
</aside>
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<style type="text/css">
/*.iconleftbar {
  border: none;
  outline: none;
  padding: 10px 16px;
  background-color: #f1f1f1;
  cursor: pointer;
  font-size: 18px;
}*/

/* Style the active class, and buttons on mouse-over */
.sidebar-nav {
    padding: 15px 0 0 0px;
    float: right !important;
    width: 20% !important;
    margin-left: 1% !important;
    margin-right: 1.2% !important;
    margin-top: 1% !important;
    margin-bottom: 1% !important;
    height: 10px !important;
}
.sidebar-nav > ul > li {
    margin-bottom: 2px !important;
}
.sidebar-nav ul li a {
    padding: 5px 10px 10px 15px !important;
}
.active_bar{
  background-color: #ededed !important;
  border-radius: 8px;
  color: white;
  height: 51px;
}
.skin-default-dark .left-sidebar {
    background: #E0E0E0;
}
.iconleftbar:hover{
    background-color: #ededed !important;
}
</style>
<script type="text/javascript">

var header = document.getElementById("sidebarnav");

var btns = header.getElementsByClassName("iconleftbar");
for (var i = 0; i < btns.length; i++) {
  btns[i].addEventListener("click", function() {
  var current = document.getElementsByClassName("active_bar");
  current[0].className = current[0].className.replace(" active_bar", "");
  this.className += " active_bar";
  });
}    

function loadIframe(url) {
    //
    var $iframe = $('#react');
    if ( $iframe.length ) {
        $iframe.attr('src',url);   
        return false;
    }
    return true;
}

/*function loadIframe(iframeName, url) {
    var $iframe = $('#' + iframeName);
    if ( $iframe.length ) {
        $iframe.attr('src',url);   
        return false;
    }
    return true;
}*/


</script>

<style>

    .left-sidebar {
        top: 65px;
        z-index: 0;
        width: 245px;
        left: -190px;
    }

    /*.left-sidebar:hover {
        left: 0px;
    }*/

    .skin-default-dark .left-sidebar {
        background: #ffffff;
        margin-top: -9px;
        box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.2);
    }

    .skin-default-dark .left-sidebar .sidebar-nav ul li a i {
        color: #000000;
    }

    .iconleftbar {
        background: #fff;
        color: #000;
    }

    .iconleftbar:hover {
        background: #fff;
        border-radius: 5px;
    }

    .disabled {
        pointer-events: none;
        cursor: default;
        text-decoration: none;
        color: black;
        opacity: 0.5;
    }

    .sidebar-nav>ul>li {
        margin-top: 0px;
        margin-bottom: 0px;
    }


</style> -->
<style>
    @font-face {
        font-family: PromptMedium;
        src: url(assets/fonts/Prompt-Medium.ttf);
    }

    .header {
        padding: 10px 10px 10px 10px;
        font-size: 16px;
    }

    .header a {
        /*text-decoration: none;*/
        color: #e97126;
        font-weight: 500;
    }

    .box-border {
        border: 1px solid #ccc;
    }

    .ui-resizable-se,
    .ui-resizable-sw {
        opacity: 0 !important;
    }

    .ui-resizable-se {
        bottom: -5px !important;
        right: -5px !important;
    }

    .widget-buttons {
        position: absolute;
        top: 0px;
        right: 0px;
        /*background-color:#ccc;*/
        height: 30px;
        padding: 5px;
    }
    .grid-stack .grid-stack-item .grid-stack-item-content {
        overflow: hidden;
    }

    /*.grid-stack-item {
        transform: scale(1.4);
    }

    .grid-stack-item .grid-stack-item-in {
        transition: top .5s ease-in-out;
    }*/

    .nav-tabs {
        border-bottom: 0px;
    }

    .nav-tabs > li > a {
        color: #2b2b2b;
        background-color: #f7f7f7;
        padding: 7px 15px;
        font-family: PromptMedium;
        margin-right: 10px;
        font-size: 12px;
    }
    .nav-tabs > li > a > i {
        color: #ccc;
        cursor: pointer;
        font-size: 80%;
        margin-left: 4px;
    }

    .nav-tabs > li.active > a,
    .nav-tabs > li.active > a:hover,
    .nav-tabs > li.active > a:focus {
        color: #2b2b2b;
        background-color: #fef0e7;
        border: 1px solid #e97126;
        /*border-top-color: transparent;
        border-left-color: transparent;
        border-right-color: transparent;
        border-bottom: 0px solid #000;*/
        cursor: default;
        border-radius: 0px;
    }
    .nav-tabs > li > a:hover,
    .nav-tabs > li > a:focus {
        color: #2b2b2b;
        background-color: #fef0e7;
        border: 1px solid #e97126;
        /*border-top-color: transparent;
        border-top-color: transparent;
        border-left-color: transparent;
        border-right-color: transparent;
        border-bottom: 3px solid #555;*/
    }
    .nav-tabs li.active i,
    .nav-tabs > li > a:hover > i,
    .nav-tabs > li > a:focus > i {
        color: #555;
    }

    .panel-heading {
        cursor: move;
        /*background-color: #e0e0e0;*/
    }
    .panel-body {
        /*border: 1px solid #e0e0e0;*/
        /*height: 300px;*/
    }

    .adddb {
        background-color: #018ffb;
        font-size: 12px;
        font-family: PromptMedium;
        border: 0px;
        padding: 8px 15px;
    }

    .adddb:hover {
        background-color: #018ffb;
        font-size: 12px;
        font-family: PromptMedium;
        border: 0px;
    }

    ul.menu li {
        display: inline;
    }

    .btndb {
        background-color: #fff;
        box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.2);
        font-size: 12px;
        font-family: PromptMedium;
    }

    .btndbactive.active {
        /*background-color: black;*/
        /*border: 1px solid;*/
        box-shadow: 0px 0px 3px 0px #e97126;
    }

    .btn:focus,
    .btn:active:focus,
    .btn.active:focus,
    .btn.focus,
    .btn:active.focus,
    .btn.active.focus {
        outline: 0px auto -webkit-focus-ring-color;
    }

    .dropbtn {
        background-color: #fff;
        box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.2);
        font-size: 12px;
        font-family: PromptMedium;
    }

    .dropbtn:hover,
    .dropbtn:focus {
        background-color: #fff;
        /*border: 0px;*/
    }

    .dropdown {
        position: relative;
        display: inline-block;
        font-family: PromptMedium;
        font-weight: 100;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #fff;
        min-width: 160px;
        overflow: auto;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown a:hover {
        background-color: #ededed;
    }

    .show {
        display: block;
    }

    .modal.left .modal-dialog,
    .modal.right .modal-dialog {
        position: fixed;
        margin: auto;
        width: 500px;
        height: 100%;
        -webkit-transform: translate3d(0%, 0, 0);
        -ms-transform: translate3d(0%, 0, 0);
        -o-transform: translate3d(0%, 0, 0);
        transform: translate3d(0%, 0, 0);
    }

    .modal.left .modal-content,
    .modal.right .modal-content {
        height: 100%;
        overflow-y: auto;
    }

    .modal.left .modal-body,
    .modal.right .modal-body {
        padding: 15px 15px 80px;
    }

    /*Right*/
    .modal.right.fade .modal-dialog {
        right: -320px;
        -webkit-transition: opacity 0.3s linear, right 0.3s ease-out;
        -moz-transition: opacity 0.3s linear, right 0.3s ease-out;
        -o-transition: opacity 0.3s linear, right 0.3s ease-out;
        transition: opacity 0.3s linear, right 0.3s ease-out;
    }

    .modal.right.fade.in .modal-dialog {
        right: 0;
    }

    /* ----- MODAL STYLE ----- */
    .modal-content {
        border-radius: 0;
        border: none;
    }

    .modal-header {
        padding: 20px 16px;
        /*background-color: #5cb85c;*/
        color: #000000;
        border-bottom: 0px;
    }

    input[type="radio"] {
        margin-top: -1px;
        vertical-align: middle;
    }

    input[type="radio"]:after {
        width: 15px;
        height: 15px;
        border-radius: 8px;
        top: -2px;
        left: -1px;
        position: relative;
        background-color: #ededed;
        content: "";
        display: inline-block;
        visibility: visible;
        border: 0px solid black;
    }

    input[type="radio"]:checked:after {
        width: 15px;
        height: 15px;
        border-radius: 8px;
        top: -2px;
        left: -1px;
        position: relative;
        background-color: #ffffff;
        content: "";
        display: inline-block;
        visibility: visible;
        border: 5px solid #e97126;
    }

    input[type="text"] {
        font-family: PromptMedium;
        font-size: 11px;
        font-weight: 100;
    }

    textarea {
        font-family: PromptMedium;
        font-size: 11px;
        font-weight: 100;
    }

    .btnadddbmodal {
        background-color: #e97126;
        border-color: #e97126;
        font-size: 12px;
        font-family: PromptMedium;
        padding: 10px 10px 10px 10px;
        color: #ffffff;
    }

    .btnadddbmodal:hover {
        background-color: #e97126;
        border-color: #e97126;
        color: #ffffff;
    }

    .btnadddbmodal:focus {
        background-color: #e97126;
        border-color: #e97126;
        color: #ffffff;
    }

    .btncancelmodal {
        padding: 10px 10px 10px 10px;
        font-size: 12px;
        color: #e97126;
        font-family: PromptMedium;
        background-color: #ffffff;
        border-color: #ffffff;
        box-shadow: inset 0 0px 0px rgba(0, 0, 0, 0.125);
    }

    .btncancelmodal:hover {
        color: #e97126;
        background-color: #ffffff;
        border-color: #ffffff;
        box-shadow: inset 0 0px 0px rgba(0, 0, 0, 0.125);
    }

    /*.select_box{
      width: 100%;
      overflow: hidden;
      border: 1px solid #000;
      position: relative;
      padding: 10px 0;
  }
  .select_box:after{
      content: '>';
      font: 17px "Consolas", monospace;
      color: #333;
      -webkit-transform: rotate(90deg);
      -moz-transform: rotate(90deg);
      -ms-transform: rotate(90deg);
      transform: rotate(90deg);
      right: 5px;
      top: 30%;
      position: absolute;
      pointer-events: none;
  }
  .select_box select{
      width: 475px;
      border: 0;
      position: relative;
      z-index: 99;
      background: none;
  }*/

    .tooltip2 {
        position: relative;
        display: inline-block;
        border-bottom: 0px dotted black;
    }

    .tooltip2 .tooltiptext {
        visibility: hidden;
        width: 200px;
        background-color: black;
        color: #fff;
        /*text-align: center;*/
        font-size: 11px;
        font-family: PromptMedium;
        border-radius: 6px;
        padding: 5px 5px 5px 5px;
        position: absolute;
        z-index: 1;
        top: -5px;
        left: 110%;
    }

    .tooltip2 .tooltiptext::after {
        content: "";
        position: absolute;
        top: 50%;
        right: 100%;
        margin-top: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: transparent black transparent transparent;
    }
    .tooltip2:hover .tooltiptext {
        visibility: visible;
    }

    .checkbox {
        width: 100%;
        margin: 15px auto;
        position: relative;
        display: block;
    }

    .checkbox input[type="checkbox"] {
        width: auto;
        opacity: 0.00000001;
        position: absolute;
        left: 0;
        margin-left: -20px;
    }
    .checkbox label {
        position: relative;
    }
    .checkbox label:before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        margin: 4px;
        width: 15px;
        height: 15px;
        transition: transform 0.28s ease;
        border-radius: 3px;
        border: 1px solid #a9a9a9;
        background-color: #ffffff;
    }
    .checkbox label:after {
        content: "";
        display: block;
        width: 10px;
        height: 5px;
        border-bottom: 2px solid #ffffff;
        border-left: 2px solid #ffffff;
        -webkit-transform: rotate(-45deg) scale(0);
        transform: rotate(-45deg) scale(0);
        transition: transform ease 0.25s;
        will-change: transform;
        position: absolute;
        top: 8px;
        left: 7px;
    }
    .checkbox input[type="checkbox"]:checked ~ label::before {
        color: #ffffff;
        background-color: #e97126;
    }

    .checkbox input[type="checkbox"]:checked ~ label::after {
        -webkit-transform: rotate(-45deg) scale(1);
        transform: rotate(-45deg) scale(1);
    }

    .checkbox label {
        min-height: 34px;
        display: block;
        /*padding-left: 40px;*/
        margin-bottom: 0;
        font-weight: normal;
        cursor: pointer;
        vertical-align: middle;
    }
    .checkbox label span {
        position: absolute;
        top: 50%;
        -webkit-transform: translateY(-50%);
        transform: translateY(-50%);
        font-size: 11px;
        font-family: PromptMedium;
        color: #2b2b2b;
    }
    .checkbox input[type="checkbox"]:focus + label::before {
        outline: 0;
    }

    .search input[type="text"] {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 11px;
        font-family: PromptMedium;
        background-color: white;
        /*background-image: url('searchicon.png');*/
        background-image: url("../extend/assets/images/icons/icon_search_grey.png");
        background-position: 10px 10px;
        background-repeat: no-repeat;
        padding: 10px 15px 10px 40px;
        -webkit-transition: width 0.4s ease-in-out;
        transition: width 0.4s ease-in-out;
        background-size: 15px;
    }

    .panel-clr {
        background-color: #ededed;
        border-radius: 5px 5px 0px 0px;
    }

    .panel-clr.on {
        background-color: #fecfb1;
        border-radius: 5px 5px 0px 0px;
    }

    .panel-heading {
        padding: 0;
        border: 0;
    }
    .panel-title > a,
    .panel-title > a:active {
        display: block;
        padding: 15px;
        color: #2b2b2b;
        font-size: 12px;
        font-family: PromptMedium;
        font-weight: bold;
        text-transform: none;
        letter-spacing: 1px;
        word-spacing: 3px;
        text-decoration: none;
    }

    .panel-titleaw {
        border-bottom: 1px solid #ededed;
        font-size: 16px;
    }

    .panel-body {
        height: 100%;
        background-color: #ffffff;
        border: 1px solid #ededed;
        /*overflow: auto;*/
        border-radius: 0px 0px 5px 5px;
    }

    .panel-default {
        padding-bottom: 5px;
        border-color: #ffffff;
        box-shadow: 0 0px 0px rgba(0, 0, 0, 0.05);
    }

    .panel-headingaw a:before {
        font-family: "Glyphicons Halflings";
        content: "\e114";
        /*content: "▲";*/
        /*float: left;*/
        float: right;
        transition: all 0.5s;
    }
    .panel-headingaw.active a:before {
        -webkit-transform: rotate(180deg);
        -moz-transform: rotate(180deg);
        transform: rotate(180deg);
    }

    .panel-bodyaw {
        height: 100%;
        background-color: #ffffff;
        border: 0px solid #ededed;
        /*overflow: auto;*/
        /*border-radius: 0px 0px 5px 5px;*/
    }

    .panel-clrom {
        /*background-color: #ededed;*/
        /*border: 5px 5px 0px 0px;*/
    }

    .panel-clrom.on {
        /*background-color: #fecfb1;*/
        /*background-color: #fecfb1;*/
        /*background-color: #ededed;*/
        /*border: 5px 5px 0px 0px;*/
    }

    /* The container */
    .container2 {
        display: block;
        position: relative;
        padding-left: 25px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 11px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        font-family: PromptMedium;
        color: #2b2b2b;
        font-weight: 100;
    }

    /* Hide the browser's default checkbox */
    .container2 input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    /* Create a custom checkbox */
    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 15px;
        width: 15px;
        background-color: #eee;
    }

    /* On mouse-over, add a grey background color */
    /*.container2:hover input ~ .checkmark {
      background-color: #ccc;
  }*/

    /* When the checkbox is checked, add a blue background */
    .container2 input:checked ~ .checkmark {
        background-color: #e97126;
    }

    /* Create the checkmark/indicator (hidden when not checked) */
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    /* Show the checkmark when checked */
    .container2 input:checked ~ .checkmark:after {
        display: block;
    }

    /* Style the checkmark/indicator */
    .container2 .checkmark:after {
        left: 6px;
        top: 2px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 3px 3px 0;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
    }

    #rightsidebar {
        display: none;
        width: 350px;
        margin-right: 0px;
        position: absolute;
        top: 23px;
        right: 0;
        padding: 0;
        text-align: left;
        /*border: 1px solid red;*/
        margin-top: 68px;
        z-index: 50;
        /*background-color: #000;*/
        box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.2);
        background-color: #ffffff;
        -webkit-transition: all 0.5s ease;
        -moz-transition: all 0.5s ease;
        -o-transition: all 0.5s ease;
        transition: all 0.5s ease;
        padding-bottom: 20%;
    }

    #rightsidebar a {
        -webkit-transition: all 0.3s ease;
        -moz-transition: all 0.3s ease;
        -o-transition: all 0.3s ease;
        transition: all 0.3s ease;
    }

    #sidebarcontents {
        padding: 10px 10px 10px 10px;
    }

    #leftcontent {
        -webkit-transition: all 0.5s ease;
        -moz-transition: all 0.5s ease;
        -o-transition: all 0.5s ease;
        transition: all 0.5s ease;
    }

    .textheading {
        color: #2b2b2b;
        font-size: 12px;
        font-family: PromptMedium;
    }

    .rightsidebarheading {
        border-bottom: 1px solid #ededed;
        margin-left: -25px;
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .rightsidebarcheckbox {
        padding-left: 10px;
        padding-top: 10px;
    }

    #nodb {
        display: none;
    }

    #viewdb {
        display: block;
    }

    .center {
        height: 550px;
        position: relative;
        margin-top: 20px;
    }

    .center .centercontent {
        margin: 0;
        position: absolute;
        top: 50%;
        left: 50%;
        -ms-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
    }

    p {
        font-family: PromptMedium;
        font-size: 12px;
    }

    .card {
        border-radius: 10px;
    }

    .vertical-center {
        margin: 0;
        position: absolute;
        top: 50%;
        -ms-transform: translateY(-50%);
        transform: translateY(-50%);
    }

    div.dataTables_wrapper {
        margin-bottom: 3em;
    }

    .headingchart {
        padding: 1% 1% 1% 2%;
    }

    .chartcontent {
        border-radius: 5px;
        box-shadow: 0px 0px 2px 0px rgba(0, 0, 0, 0.2);
        border: 0px;
    }

    .panel-title {
        font-family: PromptMedium;
        font-size: 16px;
        color: #2b2b2b;
    }

    .btn-xs {
        background-color: #ffffff;
    }

    .dropdown,
    .dropleft,
    .dropright,
    .dropup {
        position: relative;
    }

    .topbar .top-navbar .mailbox {
        width: 300px;
    }

    .mailbox {
        z-index: 100;
        margin-left: -145px;
        min-width: 200px;
        box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.2);
        border: 0px;
    }

    .mailbox a {
        text-decoration: none;
        display: block;
        margin-left: -40px;
        font-size: 12px;
        font-family: PromptMedium;
        color: #2b2b2b;
        padding: 12px 16px;
    }

    /*.nav-link a {
        background-color: #ffffff;
        color: #ffffff;
    }*/

    .dropdownchart a:hover {
        background-color: transparent;
    }

    .mailbox a:hover {
        background-color: #ededed;
    }

    fullscreenin {
        top: 5%;
        position: relative;
        /*transition: all .5s cubic-bezier(0, 0, 0.2, 1);*/
        /*background: #428bca;*/
        height: 100%;
        /*transition-property: all;
        transition-duration: .5s;
        transition-timing-function: cubic-bezier(0, 1, 0.5, 1);*/
        /*-moz-transition: all .5s cubic-bezier(0, 0, 0.2, 1);;
        -o-transition: all .5s cubic-bezier(0, 0, 0.2, 1);;
        -webkit-transition: all .5s cubic-bezier(0, 0, 0.2, 1);;
        transition: all .5s cubic-bezier(0, 0, 0.2, 1);;*/
        /*transition: top .5s cubic-bezier(0.17, 0.04, 0.03, 0.94);*/
        /*transition: top .5s cubic-bezier(0.17, 0.04, 0.03, 0.94);*/
        transition: top 0.5s ease-in-out;
        /*background-color: #ffffff;*/
    }
    .fullscreenout {
        /*transition: margin .5s cubic-bezier(0.17, 0.04, 0.03, 0.94);*/
        transition: margin 0.5s ease-in-out;
        margin-top: 5px;
    }

    .grid-stack.grid-stack-animate,
    .grid-stack.grid-stack-animate .grid-stack-item {
        transition: margin 0.5s ease-in-out;
        margin-right: 10px;
    }

    .form-inline {
        display: block;
    }
</style>
<?php
$grid_width = 3;
$grid_height = 4;
?>
<div class="container-fluid" id="header" style="background-color: #fff;">
    <div class="header" id="opendefaultscreen">
        <div class="col-12">
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <p style="font-family: PromptMedium; color: #2b2b2b; font-weight: 500; font-size: 16px;">
                            Analytice >
                            <a href="">
                                Dashboards
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-6">
                            <ul class="nav nav-tabs">
                                <!-- <input type="text" id="tabidhide" value=""> -->
                                <?php
                                foreach($templates as $key =>
                                $tab){ if($key == 'home'){ $title = @$tab['title']=='' ? 'Home':@$tab['title']; echo '
                                <li class="'.@$tab['status'].'"><a data-toggle="tab" data-tabkey="'.$key.'" href="#'.$key.'" data-desciption="'.@$tab['desciption'].'" data-owner="'.@$tab['owner'].'">'.$title.'</a></li>
                                '; }else{ echo '
                                <li class="'.@$tab['status'].'">
                                    <a data-toggle="tab" data-tabkey="'.$key.'" href="#tab'.$key.'" data-desciption="'.@$tab['desciption'].'" data-owner="'.@$tab['owner'].'">
                                        '.@$tab['title'].'<i class="fa fa-times" data-tabkey="'.$key.'" onclick="$.removeTab(this)"></i>
                                    </a>
                                </li>
                                '; } if(@$tab['status'] == 'active'){ echo '
                                <input type="text" id="tabidhide" value="'.$key.'" hidden />
                                '; } } ?>
                                <!-- <li><a href="#tab1" role="tab" data-toggle="tab">Hint</a></li> -->

                                <div class="pull-right adddashboards">
                                    <button type="button" class="btn btn-primary adddb" data-toggle="modal" data-target="#addtabmodal">Add Dashboard</button>
                                </div>
                            </ul>
                        </div>
                        <div class="col-sm-6">
                            <div class="pull-right" style="display: -webkit-inline-box; float: right;">
                                <div>
                                    <label style="display: inline; font-size: 12px; font-family: PromptMedium; font-weight: 400;">เลือกช่วงเวลา</label>&nbsp;
                                    <input
                                        class="form-control input-daterange-datepicker"
                                        type="text"
                                        name="daterange"
                                        style="display: inline-block; width: 200px; color: #018ffb; box-shadow: 0px 0px 2px 0px rgba(0, 0, 0, 0.2); border: 0px;"
                                    />
                                </div>
                                &nbsp;
                                <div class="dropdown">
                                    <button onclick="Action_Function()" class="btn dropbtn">Actions <img src="<?php echo site_assets_url('images/icons/icon_chevrondown_black.png'); ?>" width="20" height="20" /></button>
                                    <div id="myDropdown" class="dropdown-content" style="font-size: 12px;">
                                        <a href="javascript:void(0)" onclick="$.clickmodaldbdetails()">Dashboard details</a>
                                        <a href="javascript:void(0)" onclick="openFullscreen();">Full screen</a>
                                        <a href="javascript:void(0)" onclick="$.setasdefault()">Set as default</a>
                                        <a href="javascript:void(0)" onclick="$.delete()">Delete</a>
                                    </div>
                                </div>
                                &nbsp; <button type="button" class="btn btndb" onclick="$.share()"><img src="<?php echo site_assets_url('images/icons/Icon_Share_Black.png'); ?>" width="20" height="20" />&nbsp; Share</button>&nbsp;
                                <button type="button" class="btn btndb" onclick="$.clickmodaladdwidget()">
                                    Add Widget
                                    <img src="<?php echo site_assets_url('images/icons/icon_chevrondown_black.png'); ?>" width="20" height="20" />
                                </button>
                                &nbsp;
                                <button type="button" class="btn btndb btndbactive" onclick="openRightMenu()">
                                    <img id="myImage" src="<?php echo site_assets_url('images/icons/icon_chevronsleft.png'); ?>" width="20" height="20" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid" id="fullscreen" style="background-color: #fff;">
        <div class="header" id="openfullscreen" style="display: none;">
            <div class="col-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <ul class="nav nav-tabs navfullscreen" id="menu">
                                    <?php
                                    foreach($templates as $key =>
                                    $tab){ if($key == 'home'){ $title = @$tab['title']=='' ? 'Home':@$tab['title']; echo '
                                    <li style="display: none;" class="'.@$tab['status'].'"><a data-toggle="tab" data-tabkey="'.$key.'" href="#'.$key.'">'.$title.'</a></li>
                                    '; }else{ echo '
                                    <li style="display: none;" class="'.@$tab['status'].'">
                                        <a data-toggle="tab" data-tabkey="'.$key.'" href="#tab'.$key.'">'.@$tab['title'].'<i class="fa fa-times" data-tabkey="'.$key.'" onclick="$.removeTab(this)"></i></a>
                                    </li>
                                    '; } } ?>
                                </ul>
                            </div>
                            <div class="col-sm-6">
                                <div class="pull-right" style="display: -webkit-inline-box; float: right;">
                                    <img src="<?php echo site_assets_url('images/icons/close.png'); ?>" onclick="closeFullscreen();" style="width: 20px; cursor: pointer;" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="border-bottom: 1px solid #ddd;"></div>

        <br />

        <!-- <div class="container-fluid" id="nodb">
            <div class="card" style="border-radius: 5px;">
                <div class="card-body">
                    <div class="row" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125);">
                        <div class="col-sm-12" style="margin-top: -10px; margin-left: -20px;">
                            <div class="col-sm-6">
                                <img src="<?php echo site_assets_url('images/pic_chart3-01h.png'); ?>" width="100" height="39" />
                            </div>
                            <div class="col-sm-6" style="float: right;">
                                <img src="<?php echo site_assets_url('images/pic_chart3-02.png'); ?>" width="100" height="38" style="float: right; margin-right: -60px;" />
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="center">
                            <div class="centercontent">
                                <div class="card">
                                    <div class="card-body" style="background-color: #f7f7f7; padding-left: 0px; padding-right: 0px; padding-bottom: 0px;">
                                        <div style="background-color: #fff; margin-top: 40px;">
                                            <img src="<?php echo site_assets_url('images/pic_chart2-01.png'); ?>" width="400" height="250" style="display: block; margin-left: auto; margin-right: auto;" />
                                        </div>
                                    </div>
                                </div>
                                <div style="text-align: center; margin-top: 20px;">
                                    <p><span style="color: #e97126;">Add</span> any widgets you Tell a story with your data and share it with your team</p>
                                </div>
                                <div style="text-align: center; margin-top: 20px;">
                                    <button type="button" class="btn btnadddbmodal" onclick="$.clickmodaladdwidget()" style="padding-left: 50px; padding-right: 50px;">Add Widgets to this dashboard</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="container-fluid" id="viewdb">
            <div id="rightsidebar">
                <div id="sidebarcontents">
                    <div class="col-12">
                        <div class="row rightsidebarheading">
                            <div class="col-sm-6">
                                <span class="textheading">Year</span>
                            </div>
                            <div class="col-sm-6" style="text-align: right;">
                                <a href="javascript:void(0)" onclick="pinRightMenu()">
                                    <i>
                                        <img id="pinimg" src="<?php echo site_assets_url('images/icons/pin_01_grey.png'); ?>" border="0" alt="" width="20" height="20" />
                                    </i>
                                </a>
                            </div>
                        </div>
                        <div class="row rightsidebarcheckbox">
                            <div class="col-sm-12">
                                <div class="row">
                                    <label class="container2">
                                        2020
                                        <input type="checkbox" />
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="row">
                                    <label class="container2">
                                        2019
                                        <input type="checkbox" />
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="row">
                                    <label class="container2">
                                        2018
                                        <input type="checkbox" />
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="row">
                                    <label class="container2">
                                        2017
                                        <input type="checkbox" />
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="row">
                                    <label class="container2">
                                        2016
                                        <input type="checkbox" />
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row rightsidebarheading">
                            <div class="col-sm-6">
                                <span class="textheading">Quarter</span>
                            </div>
                            <div class="col-sm-6"></div>
                        </div>
                        <div class="row rightsidebarcheckbox">
                            <div class="col-sm-12">
                                <div class="row">
                                    <label class="container2">
                                        Q1
                                        <input type="checkbox" />
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="row">
                                    <label class="container2">
                                        Q2
                                        <input type="checkbox" />
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="row">
                                    <label class="container2">
                                        Q3
                                        <input type="checkbox" />
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="row">
                                    <label class="container2">
                                        Q4
                                        <input type="checkbox" />
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row rightsidebarheading">
                            <div class="col-sm-6">
                                <span class="textheading">Month</span>
                            </div>
                            <div class="col-sm-6"></div>
                        </div>
                        <div class="row rightsidebarcheckbox">
                            <div class="col-sm-12">
                                <div class="row">
                                    <label class="container2">
                                        January
                                        <input type="checkbox" />
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="row">
                                    <label class="container2">
                                        Febuary
                                        <input type="checkbox" />
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="row">
                                    <label class="container2">
                                        March
                                        <input type="checkbox" />
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="row">
                                    <label class="container2">
                                        April
                                        <input type="checkbox" />
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="row">
                                    <label class="container2">
                                        May
                                        <input type="checkbox" />
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="row">
                                    <label class="container2">
                                        June
                                        <input type="checkbox" />
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="row">
                                    <label class="container2">
                                        July
                                        <input type="checkbox" />
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="row">
                                    <label class="container2">
                                        August
                                        <input type="checkbox" />
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="row">
                                    <label class="container2">
                                        September
                                        <input type="checkbox" />
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="row">
                                    <label class="container2">
                                        October
                                        <input type="checkbox" />
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="row">
                                    <label class="container2">
                                        November
                                        <input type="checkbox" />
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="row">
                                    <label class="container2">
                                        December
                                        <input type="checkbox" />
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="leftcontent">
                <div class="tab-content">
                    <?php foreach($templates as $key =>
                    $tab_data){ if($key == 'home'){ $tab_id = $key; }else{ $tab_id = 'tab'.$key; } ?>
                    <div id="<?php echo $tab_id; ?>" class="tab-pane fade in <?php echo @$tab_data['status']; ?>">
                        <?php if ($key != 'home') { ?>
                        <?php if (isset($tab_data['widgets']) && !empty($tab_data['widgets'])) { ?>

                        <?php } else { ?>
                        <div class="container-fluid" id="nodb" style="display: block;">
                            <div class="card" style="border-radius: 5px;">
                                <div class="card-body">
                                    <div class="row" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125);">
                                        <div class="col-sm-12" style="margin-top: -10px; margin-left: -20px;">
                                            <div class="col-sm-6">
                                                <img src="<?php echo site_assets_url('images/pic_chart3-01h.png'); ?>" width="100" height="39" />
                                            </div>
                                            <div class="col-sm-6" style="float: right;">
                                                <img src="<?php echo site_assets_url('images/pic_chart3-02.png'); ?>" width="100" height="38" style="float: right; margin-right: -60px;" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="center">
                                            <div class="centercontent">
                                                <div class="card">
                                                    <div class="card-body" style="background-color: #f7f7f7; padding-left: 0px; padding-right: 0px; padding-bottom: 0px;">
                                                        <div style="background-color: #fff; margin-top: 40px;">
                                                            <img src="<?php echo site_assets_url('images/pic_chart2-01.png'); ?>" width="400" height="250" style="display: block; margin-left: auto; margin-right: auto;" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="text-align: center; margin-top: 20px;">
                                                    <p><span style="color: #e97126;">Add</span> any widgets you Tell a story with your data and share it with your team</p>
                                                </div>
                                                <div style="text-align: center; margin-top: 20px;">
                                                    <button type="button" class="btn btnadddbmodal" onclick="$.clickmodaladdwidget()" style="padding-left: 50px; padding-right: 50px;">Add Widgets to this dashboard</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <?php } else { ?>

                        <?php } ?>
                        <!--  <div class="row" style="margin-top:5px; margin-bottom:5px;">
                            <div class="col-md-12">
                                <div class="btn-group pull-right">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Add Widget <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <?php foreach($widgets as $widget){ ?>
                                            <li><a href="javascript:;" onclick="$.genGraph('<?php echo $tab_id; ?>', '<?php echo $key; ?>', <?php echo $widget['reportid']; ?>, '<?php echo $widget['reportcharttype']; ?>')"><?php echo $widget['reportname']; ?></a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div> -->

                        <div class="row grid-stack">
                            <?php if(isset($tab_data['widgets']) && !empty($tab_data['widgets'])){
                            foreach($tab_data['widgets'] as $grid){ ?>
                            <div
                                class="grid-stack-item"
                                data-id="<?php echo $grid['reportid']; ?>"
                                data-tabkey="<?php echo $key; ?>"
                                data-type="<?php echo $grid['reportcharttype']; ?>"
                                data-tab="<?php echo $tab_id; ?>"
                                data-chartid="<?php echo $grid['chartid']; ?>"
                                data-gs-id="<?php echo 'item_'.$grid['reportid']; ?>"
                                data-gs-x="<?php echo $grid['x']; ?>"
                                data-gs-y="<?php echo $grid['y']; ?>"
                                data-gs-width="<?php echo $grid['width']; ?>"
                                data-gs-min-width="<?php echo $grid_width; ?>"
                                data-gs-height="<?php echo $grid['height']; ?>"
                                data-gs-min-height="<?php echo $grid_height; ?>"
                            >
                                <div class="grid-stack-item-content box-border chartcontent">
                                    <div class="panel-heading clearfix headingchart">
                                        <span class="panel-title pull-left"></span>

                                        <div class="pull-right widget-buttons">
                                            <button class="btn btn-xs" onclick="$.refreshBox(this)">
                                                <i class="fa fa-redo"></i>
                                            </button>
                                            <!-- <button class="btn btn-xs" onclick="$.removeBox(this)">
                                                <i class="fa fa-times"></i>
                                            </button> -->
                                            <!-- <button class="btn btn-xs">
                                                <i><img src="<?php echo site_assets_url('images/icons/61140.png'); ?>" width="10" height="10" /></i>
                                            </button> -->
                                            <li class="nav-item dropdown dropdownchart">
                                                <!-- <a class="nav-link dropdown-toggle" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: transparent;"> <i><img src="<?php echo site_assets_url('images/icons/61140.png'); ?>" width="10" height="10" /></i>
                                                </a> -->
                                                <button class="btn btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i><img src="<?php echo site_assets_url('images/icons/61140.png'); ?>" width="10" height="10" /></i>
                                                </button>
                                                <div class="dropdown-menu mailbox">
                                                    <ul style="list-style-type: none;">
                                                        <li style="list-style-type: none;">
                                                            <a href="javascript:void(0)" style="margin-top: -5px;" onclick="$.duplicate(this);">Duplicate</a>
                                                        </li>
                                                        <li style="list-style-type: none;">
                                                            <a href="javascript:void(0)" onclick="$.export(this)">Export</a>
                                                        </li>
                                                        <li style="list-style-type: none;">
                                                            <a href="javascript:void(0)" onclick="$.rename(this)">Rename</a>
                                                        </li>
                                                        <hr style="margin-top: 0px; margin-bottom: 0px; margin-left: -40px;" />
                                                        <li style="list-style-type: none;" onclick="">
                                                            <a href="javascript:void(0)" style="color: red;" onclick="$.removeBox(this)">Remove from dashborard</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                        </div>
                                    </div>
                                    <div class="panel-body" style="overflow: auto;">
                                        <!--  <?php if ($grid['reportcharttype'] == 'brush-chart') { ?>
                                            <div id="chartline_<?php echo $tab_id.'_'.$grid['reportid']; ?>" class="item_graph"></div>
                                            <div id="chartarea_<?php echo $tab_id.'_'.$grid['reportid']; ?>" class="item_graph"></div>
                                        <?php } else if ($grid['reportcharttype'] == 'grid') { ?>
                                            <div id="<?php echo $grid['reportid']; ?>" class="item_graph">
                                                <table id="table_<?php echo $tab_id.'_'.$grid['reportid']; ?>" class="table table-striped table-bordered display nowrap" cellspacing="0" style="width:100%!important; font-size: 12px; font-family: PromptMedium; font-weight: 100;"></table>
                                            </div>
                                        <? } else { ?>
                                            <div id="graph_<?php echo $tab_id.'_'.$grid['reportid']; ?>" class="item_graph"></div>
                                        <?php } ?> -->
                                        <div id="graph_<?php echo $tab_id.'_'.$grid['reportid']; ?>" class="item_graph"></div>

                                        <?php if ($grid['reportcharttype'] == 'brush-chart') { ?>
                                        <div id="chartline_<?php echo $tab_id.'_'.$grid['reportid']; ?>"></div>
                                        <div id="chartarea_<?php echo $tab_id.'_'.$grid['reportid']; ?>"></div>
                                        <?php } ?>
                                        <!-- <div id="chart-area"></div> -->
                                        <?php if ($grid['reportcharttype'] == 'grid') { ?>
                                        <table
                                            id="table_<?php echo $tab_id.'_'.$grid['reportid']; ?>"
                                            class="table table-striped table-bordered display nowrap"
                                            cellspacing="0"
                                            style="width: 100% !important; font-size: 12px; font-family: PromptMedium; font-weight: 100;"
                                        ></table>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <?php }
                    } ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div id="chart-container"></div>

        <!-- Modal -->
        <!-- <div class="modal fade" id="addtabmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add dashboard</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            Dashboard Name
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="dashboard_title" id="dashboard_title">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="$.addTab()">Save</button>
                </div>
            </div>
        </div>
    </div> -->
    </div>
    <!-- Modal -->
    <div class="modal right fade" id="addtabmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h4 class="modal-title" id="myModalLabel2">Add Dashboard</h4> -->
                    <span style="font-family: PromptMedium; color: #2b2b2b; margin-top: 10px;">
                        <b style="font-size: 20px;">Add Dashboard</b>
                    </span>
                    <span class="close" data-dismiss="modal" aria-label="Close"><img src="<?php echo site_assets_url('images/icons/close.png'); ?>" width="15" height="15" /></span>
                </div>

                <div class="modal-body">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12" style="border: 1px solid #a9a9a9; padding: 1% 1% 1% 1%; border-radius: 5px;">
                                <label style="font-size: 11px; font-family: PromptMedium; font-weight: 100; color: #2b2b2b;">Dashboard Name <span style="color: #e97126;">*</span></label>
                                <input type="text" class="form-control" name="dashboard_name" id="dashboard_name" style="box-shadow: inset 0 1px 1px rgb(255 255 255 / 8%); border: none; height: 20px;" />
                            </div>
                        </div>
                        <div class="row" style="margin-top: 20px;">
                            <div class="col-sm-12" style="border: 1px solid #a9a9a9; padding: 1% 1% 1% 1%; border-radius: 5px;">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <label style="font-size: 11px; font-family: PromptMedium; font-weight: 100;">Who can access this dashboard?</label>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <input type="radio" name="radio" />&nbsp;&nbsp; <span style="font-family: PromptMedium; color: #2b2b2b; font-size: 12px; font-weight: 100;">Privte to owner (me)</span><br />
                                    <input type="radio" name="radio" />&nbsp;&nbsp;
                                    <span style="font-family: PromptMedium; color: #2b2b2b; font-size: 12px; font-weight: 100;">Everyone</span>
                                    <br />
                                    <div style="margin-left: 25px; margin-top: 10px;">
                                        <input type="radio" name="radio2" />&nbsp;&nbsp; <span style="font-family: PromptMedium; color: #2b2b2b; font-size: 12px; font-weight: 100;">View and edit</span><br />
                                        <input type="radio" name="radio2" />&nbsp;&nbsp;
                                        <span style="font-family: PromptMedium; color: #2b2b2b; font-size: 12px; font-weight: 100;">View only</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="text" id="userid" hidden="hide" value="<?php echo $userid; ?>" />
                        <!-- <input type="text" id="owner" hidden="owner" value="<?php echo $owner; ?>"> -->
                    </div>
                    <div class="col-sm-12" style="margin-top: 30px;">
                        <!-- <button type="button" class="btn btn-primary btnadddbmodal" onclick="$.addTab()" style="float: right;">Add Dashboard</button> -->
                        <button type="button" class="btn btn-primary btnadddbmodal" onclick="$.adddb()" style="float: right;">Add Dashboard</button>
                        <button type="button" class="btn btn-secondary btncancelmodal" data-dismiss="modal" style="float: right; margin-right: 10px;">Cancel</button>
                    </div>
                </div>
            </div>
            <!-- modal-content -->
        </div>
        <!-- modal-dialog -->
    </div>
    <!-- modal -->

    <div class="modal right fade" id="dbdetailsmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span style="font-family: PromptMedium; color: #2b2b2b; margin-top: 8px;">
                        <b style="font-size: 20px;">Dashboard details</b>
                    </span>
                    <span class="close" data-dismiss="modal" aria-label="Close"><img src="<?php echo site_assets_url('images/icons/close.png'); ?>" width="15" height="15" /></span>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12" style="border: 1px solid #a9a9a9; padding: 1% 1% 1% 1%; border-radius: 5px;">
                                <label style="font-size: 11px; font-family: PromptMedium; font-weight: 100; color: #2b2b2b;">Dashboard Name <span style="color: #e97126;">*</span></label>
                                <?php
                                foreach($templates as $key =>
                                $tab){ if($key == 'home'){ $title = @$tab['title']=='' ? 'Home':@$tab['title']; }else{ } if(@$tab['status'] == 'active'){ echo '
                                <input type="text" class="form-control" name="dashboard_title" id="dashboard_title" style="box-shadow: inset 0 1px 1px rgb(255 255 255 / 8%); border: none; height: 20px;" value="'.@$tab['title'].'" />'; } }
                                ?>
                                <!-- <input type="text" class="form-control" name="dashboard_title" id="dashboard_title" style="box-shadow: inset 0 1px 1px rgb(255 255 255 / 8%); border: none; height: 20px;" value="'.@$tab['title'].'" /> -->
                            </div>
                        </div>
                        <div class="row" style="margin-top: 20px;">
                            <div class="col-sm-12" style="border: 1px solid #a9a9a9; padding: 1% 1% 1% 1%; border-radius: 5px;">
                                <label style="font-size: 11px; font-family: PromptMedium; font-weight: 100; color: #2b2b2b;">Dashboard Owner <span style="color: #e97126;">*</span></label>
                                <?php
                                foreach($templates as $key =>
                                $tab){ if($key == 'home'){ $title = @$tab['title']=='' ? 'Home':@$tab['title']; }else{ } if(@$tab['status'] == 'active'){ echo '
                                <input type="text" class="form-control" name="dbowner" id="dbowner" style="box-shadow: inset 0 1px 1px rgb(255 255 255 / 8%); border: none; height: 20px;" value="'.@$tab['owner'].'" />'; } } ?>
                                <!-- <select id="dbowner" name="dbowner" class="form-control" style="border: 0px; box-shadow: inset 0 0px 0px rgba(0, 0, 0, 0.075); font-family: PromptMedium; font-size: 11px; color: #2b2b2b;">
                                    <option>'.@$tab['owner'].'</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select> -->
                                <!-- <div class="select_box">
                                   <select class="form-control" style="box-shadow: inset 0 0px 0px rgba(0,0,0,0.075);">
                                     <option>Test This Select</option>
                                     <option>Test This Select</option>
                                    </select>
                                </div> -->
                            </div>
                        </div>
                        <div class="row" style="margin-top: 20px;">
                            <div class="col-sm-12" style="border: 1px solid #a9a9a9; padding: 1% 1% 1% 1%; border-radius: 5px;">
                                <label style="font-size: 11px; font-family: PromptMedium; font-weight: 100; color: #2b2b2b;">Dashboard Desciption</label>&nbsp;
                                <div class="tooltip2">
                                    <i class="fa fa-info-circle"></i>
                                    <span class="tooltiptext">Add a decription for you team, or @mention to get their full attentic</span>
                                </div>
                                <?php
                                foreach($templates as $key =>
                                $tab){ if($key == 'home'){ $title = @$tab['title']=='' ? 'Home':@$tab['title']; }else{ } if(@$tab['status'] == 'active'){ echo '
                                <textarea class="form-control" id="desciption" name="desciption" rows="3" style="border: 0px; box-shadow: inset 0 0px 0px rgba(0, 0, 0, 0.075);">'.@$tab['desciption'].'</textarea>'; } } ?>
                                <!-- <textarea class="form-control" id="desciption" name="desciption" rows="3" style="border: 0px; box-shadow: inset 0 0px 0px rgba(0, 0, 0, 0.075);">'.@$tab['desciption'].'</textarea> -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="checkbox">
                                <input type="checkbox" id="checkbox" name="" value="" />
                                <label for="checkbox">&nbsp;&nbsp;<span style="margin-top: -5px;">Show description on the dashboard</span></label>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                            <div class="col-sm-12" style="margin-top: 30px;">
                                <button type="button" class="btn btn-primary btnadddbmodal" style="float: right;" onclick="$.adddashboarddetails()">Save</button>
                                <button type="button" class="btn btn-secondary btncancelmodal" data-dismiss="modal" style="float: right; margin-right: 10px;">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal right fade" id="addwidgetmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="overflow-x: hidden;">
                <div class="modal-header">
                    <span style="font-family: PromptMedium; color: #2b2b2b; margin-top: 8px;">
                        <b style="font-size: 20px;">Add Widget</b>
                    </span>
                    <span class="close" data-dismiss="modal" aria-label="Close"><img src="<?php echo site_assets_url('images/icons/close.png'); ?>" width="15" height="15" /></span>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12">
                        <div class="row search">
                            <input type="text" class="form-control" name="search" placeholder="Search.." />
                        </div>
                        <div class="row" style="margin-top: 20px;">
                            <form id="form_wiget" action="#" method="POST">
                                <div class="tab-content">
                                    <div class="tab-pane tab-paneaddwidget active" id="tabpaneaddwidget">
                                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                            <?php 
                                            $i=1;
                                            foreach($widget_menu as $key =>
                                            $value){?>
                                            <fieldset>
                                                <div class="panel panel-default">
                                                    <div class="panel-headingaw panel-clrom active" role="tab" id="<?php echo $key; ?>">
                                                        <div class="panel-title panel-titleaw">
                                                            <a
                                                                class="collapsed"
                                                                role="button"
                                                                data-toggle="collapse"
                                                                data-parent="#accordion"
                                                                href="#collapse<?php echo $key; ?>"
                                                                aria-expanded="false"
                                                                aria-controls="collapse<?php echo $key; ?>"
                                                            >
                                                                <span style="margin-right: 355px;"><?php echo $key; ?></span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div id="collapse<?php echo $key; ?>" class="panel-collapse panel-collapseom collapse in" role="tabpanel" aria-labelledby="<?php echo $key; ?>">
                                                        <?php foreach($value as $k =>
                                                        $v){?>
                                                        <div class="panel-body panel-bodyaw">
                                                            <label class="container2">
                                                                <?php echo $k; ?>
                                                                <input type="checkbox" name="checkwidget" id="<?php echo $k; ?>" onclick="$.checked('<?php echo $k; ?>');" />
                                                                <span class="checkmark"></span>
                                                            </label>
                                                            <div style="margin-left: 20px;">
                                                                <?php foreach($v as $km =>
                                                                $vm){?>
                                                                <label class="container2">
                                                                    <?php echo $vm['widgetname']; ?>
                                                                    <input
                                                                        type="checkbox"
                                                                        name="widget"
                                                                        id="<?php echo $vm['chartid']; ?>"
                                                                        value="<?php echo $vm['chartid'] ?>,<?php echo $vm['widgettypename'] ?>,<?php echo $vm['reportid'] ?>"
                                                                        class="<?php echo $k; ?>"
                                                                    />
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                        <?php  } ?>
                                                    </div>
                                                </div>
                                            </fieldset>

                                            <?php  $i++;} ?>
                                            <!-- <div class="panel panel-default">
                                                <div class="panel-headingaw panel-clrom active" role="tab" id="marketing">
                                                    <div class="panel-title panel-titleaw">
                                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseMarketing" aria-expanded="false" aria-controls="collapseMarketing">
                                                            <span style="margin-right: 355px;">Marketing</span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div id="collapseMarketing" class="panel-collapse panel-collapseom collapse in" role="tabpanel" aria-labelledby="marketing">
                                                    <div class="panel-body panel-bodyaw">
                                                        <label class="container2">
                                                            Lead
                                                            <input type="checkbox" />
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <div style="margin-left: 20px;">
                                                            <label class="container2">
                                                                Leads by Day
                                                                <input type="checkbox" />
                                                                <span class="checkmark"></span>
                                                            </label>
                                                            <label class="container2">
                                                                Leads by Week
                                                                <input type="checkbox" />
                                                                <span class="checkmark"></span>
                                                            </label>
                                                            <label class="container2">
                                                                Leads by Month
                                                                <input type="checkbox" />
                                                                <span class="checkmark"></span>
                                                            </label>
                                                            <label class="container2">
                                                                Leads by Month by Lead Source
                                                                <input type="checkbox" />
                                                                <span class="checkmark"></span>
                                                            </label>
                                                            <label class="container2">
                                                                Leads by Status
                                                                <input type="checkbox" />
                                                                <span class="checkmark"></span>
                                                            </label>
                                                            <label class="container2">
                                                                Leads 10 Leads
                                                                <input type="checkbox" />
                                                                <span class="checkmark"></span>
                                                            </label>
                                                            <label class="container2">
                                                                Leads 10 Converted Leade
                                                                <input type="checkbox" />
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel panel-default">
                                                    <div class="panel-headingaw panel-clrom active" role="tab" id="sales">
                                                        <div class="panel-title panel-titleaw">
                                                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSales" aria-expanded="false" aria-controls="collapseSales">
                                                                <span style="margin-right: 355px;">Sales</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div id="collapseSales" class="panel-collapse panel-collapseom collapse in" role="tabpanel" aria-labelledby="sales">
                                                        <div class="panel-body panel-bodyaw">
                                                            <label class="container2">
                                                                Sales
                                                                <input type="checkbox" />
                                                                <span class="checkmark"></span>
                                                            </label>
                                                            <div style="margin-left: 20px;">
                                                                <label class="container2">
                                                                    Total Sales Vs Target
                                                                    <input type="checkbox" />
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <label class="container2">
                                                                    Total Sales Vs Last Year
                                                                    <input type="checkbox" />
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <label class="container2">
                                                                    Top Sellers
                                                                    <input type="checkbox" />
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <label class="container2">
                                                                    Sales by Week
                                                                    <input type="checkbox" />
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <label class="container2">
                                                                    Sales by Month
                                                                    <input type="checkbox" />
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <label class="container2">
                                                                    Sales by Month by Product Category
                                                                    <input type="checkbox" />
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <label class="container2">
                                                                    Sales by Month by Employee
                                                                    <input type="checkbox" />
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <label class="container2">
                                                                    Sales by Status
                                                                    <input type="checkbox" />
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <label class="container2">
                                                                    Last 10 Accounts
                                                                    <input type="checkbox" />
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <label class="container2">
                                                                    Sales by Month
                                                                    <input type="checkbox" />
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                            </div>
                                                            <label class="container2">
                                                                Account
                                                                <input type="checkbox" />
                                                                <span class="checkmark"></span>
                                                            </label>
                                                            <div style="margin-left: 20px;">
                                                                <label class="container2">
                                                                    Account by Type
                                                                    <input type="checkbox" />
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <label class="container2">
                                                                    Account by Status
                                                                    <input type="checkbox" />
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <label class="container2">
                                                                    Accumulated Account
                                                                    <input type="checkbox" />
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <label class="container2">
                                                                    Top 10 Accounts by Biling
                                                                    <input type="checkbox" />
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <label class="container2">
                                                                    Top 10 Accounts by Revenue
                                                                    <input type="checkbox" />
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <label class="container2">
                                                                    Last 10 Accounts
                                                                    <input type="checkbox" />
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="panel panel-default">
                                                        <div class="panel-headingaw panel-clrom active" role="tab" id="services">
                                                            <div class="panel-title panel-titleaw">
                                                                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseServices" aria-expanded="false" aria-controls="collapseServices">
                                                                    <span style="margin-right: 355px;">Services</span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div id="collapseServices" class="panel-collapse panel-collapseom collapse in" role="tabpanel" aria-labelledby="services">
                                                            <div class="panel-body panel-bodyaw">
                                                                <label class="container2">
                                                                    Case
                                                                    <input type="checkbox" />
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <div style="margin-left: 20px;">
                                                                    <label class="container2">
                                                                        Case Status
                                                                        <input type="checkbox" />
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                    <label class="container2">
                                                                        Created Cases Vs Solved Cases
                                                                        <input type="checkbox" />
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                    <label class="container2">
                                                                        Case by Channel
                                                                        <input type="checkbox" />
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                    <label class="container2">
                                                                        Case by Type
                                                                        <input type="checkbox" />
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                    <label class="container2">
                                                                        Satisfction Score
                                                                        <input type="checkbox" />
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                    <label class="container2">
                                                                        Time to First Response & Full
                                                                        <input type="checkbox" />
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                </div>
                                                                <label class="container2">
                                                                    Resolve
                                                                    <input type="checkbox" />
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <div style="margin-left: 20px;">
                                                                    <label class="container2">
                                                                        Last 10 cases of Account
                                                                        <input type="checkbox" />
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                </div>
                                                                <label class="container2">
                                                                    Job
                                                                    <input type="checkbox" />
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <div style="margin-left: 20px;">
                                                                    <label class="container2">
                                                                        Job Status
                                                                        <input type="checkbox" />
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                    <label class="container2">
                                                                        Job by Type
                                                                        <input type="checkbox" />
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                    <label class="container2">
                                                                        Job by Team
                                                                        <input type="checkbox" />
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                    <label class="container2">
                                                                        Job by Employee
                                                                        <input type="checkbox" />
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-sm-12" style="margin-top: 30px;">
                                            <input type="text" id="chartid" value="" hidden="hide" />
                                            <button type="button" class="btn btn-primary btnadddbmodal" style="float: right;" onclick="$.addwidget(this)">Add to Dashboard</button>
                                            <button type="button" class="btn btn-secondary btncancelmodal" data-dismiss="modal" style="float: right; margin-right: 5px;">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="funnel-container"></div>

        <!-- <link href="<?php echo site_assets_url('css/node_modules/daterangepicker/daterangepicker.css');?>" rel="stylesheet"> -->

        <!-- <script src="<?php echo site_assets_url('css/node_modules/daterangepicker/daterangepicker.js');?>"></script> -->
        <!-- <script src="<?php echo site_assets_url('js/moment.min.js'); ?>" ></script> -->

        <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> -->
        <link rel="stylesheet" type="text/css" href="<?php echo site_assets_url('apexcharts/dist/apexcharts.css');?>" />

        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

        <script src="<?php echo site_assets_url('apexcharts/dist/apexcharts.js');?>"></script>
        <script src="<?php echo site_assets_url('apexcharts/dist/apexcharts.min.js');?>"></script>

        <script src="<?php echo site_assets_url('DataTables/js/jquery.dataTables.js');?>"></script>

        <script src="<?php echo site_assets_url('DataTables/js/dataTables.bootstrap.js');?>"></script>

        <script type="text/javascript">
            $('input[name="daterange"]').daterangepicker(
                {
                    opens: "left",
                    locale: {
                        format: "DD/MM/YYYY",
                    },
                },
                function (start, end, label) {
                    // console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                    var startdate = start.format("YYYY-MM-DD");
                    var enddate = end.format("YYYY-MM-DD");
                    //console.log(startdate);
                    //console.log(enddate);

                    getChartDatadate(startdate, enddate);
                }
            );

            //   $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {

            //   });

            //   $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
            //     $(this).val('');
            //     console.log('Cancel');
            // });

            function Action_Function() {
                document.getElementById("myDropdown").classList.toggle("show");
            }

            // Close the dropdown if the user clicks outside of it
            window.onclick = function (event) {
                if (!event.target.matches(".dropbtn")) {
                    var dropdowns = document.getElementsByClassName("dropdown-content");
                    var i;
                    for (i = 0; i < dropdowns.length; i++) {
                        var openDropdown = dropdowns[i];
                        if (openDropdown.classList.contains("show")) {
                            openDropdown.classList.remove("show");
                        }
                    }
                }
            };

            function openRightMenu() {
                // document.getElementById("rightsidebar").style.display = "block";

                var rightsidebar = document.getElementById("rightsidebar");
                var leftcontent = document.getElementById("leftcontent");

                if (rightsidebar.style.display === "block") {
                    rightsidebar.style.display = "none";
                    // console.log('t');
                    document.getElementById("myImage").src = "<?php echo site_assets_url('images/icons/icon_chevronsleft.png'); ?>";
                    $(".btndbactive").removeClass("active");
                    leftcontent.style.marginRight = "0%";
                    rightsidebar.style.width = "350px";
                    document.getElementById("pinimg").src = "<?php echo site_assets_url('images/icons/pin_01_grey.png'); ?>";
                } else {
                    rightsidebar.style.display = "block";
                    // console.log('e');
                    document.getElementById("myImage").src = "<?php echo site_assets_url('images/icons/chevronsright_orange.png'); ?>";
                    $(".btndbactive").addClass("active");
                }
            }

            function pinRightMenu() {
                var rightsidebar = document.getElementById("rightsidebar");
                var leftcontent = document.getElementById("leftcontent");

                if (leftcontent.style.marginRight == "15%") {
                    leftcontent.style.marginRight = "0%";
                    rightsidebar.style.width = "350px";
                    // console.log('t');
                    document.getElementById("pinimg").src = "<?php echo site_assets_url('images/icons/pin_01_grey.png'); ?>";
                } else {
                    leftcontent.style.marginRight = "15%";
                    rightsidebar.style.width = "15%";
                    document.getElementById("pinimg").src = "<?php echo site_assets_url('images/icons/pin_02_orange.png'); ?>";
                }
            }
        </script>

        <script>

                const grid_width = 3;
                const grid_height = 4;

                $('.nav-tabs li a').click(function(e){
                    var tab_id = $(this).data('tabkey');
                    var desciption = $(this).data('desciption');
                    var owner = $(this).data('owner');
                    // console.log(owner);

                    $('#tabidhide').val(tab_id);
                    // console.log(tab_id);
                    // console.log($(this).html());
                    // console.log($(this).text());
                    var name = $(this).text();

                    if (tab_id == 'home') {
                        $('.tab-content .tab-pane').removeClass('active');
                        $('.tab-content .tab-pane').removeClass('in');
                            // console.log('home')
                    } else {
                        $('.tab-content .tab-pane').addClass('active');
                        $('.tab-content .tab-pane').addClass('in');
                        // console.log('ff');
                    }

                    $.post('<?php echo site_url('dashboard/setTabActive'); ?>', {tab_id:tab_id}, function(rs){
                        console.log(rs);

                        $('.nav-tabs.navfullscreen li').removeClass('active');
                        $('.nav-tabs.navfullscreen li').css("display","none");
                        $('.nav-tabs.navfullscreen li.active').css("display","block");

                        if (tab_id == 'home') {
                            var a = $('<a />').attr({ 'data-toggle':'tab', 'data-tabkey':tab_id, 'href':'#home'}).html(name);
                            $(a).append(i);
                            $('#dashboard_title').val(name);
                            $('#desciption').val(desciption);
                            $('#dbowner').val(owner);
                            console.log(owner);
                            // $('#'+tab_id).css("display","block");
                        } else {
                            var a = $('<a />').attr({ 'data-toggle':'tab', 'data-tabkey':tab_id, 'href':'#tab'+tab_id }).html(name);
                            $(a).append(i);
                            $('#dashboard_title').val(name);
                            $('#desciption').val(desciption);
                            $('#dbowner').val(owner);
                            console.log(owner);
                            // $('#home').css("display","none");
                        }

                        var i = $('<i />',{ class:'fa fa-times' }).click(function(){
                            $.removeTab(this);
                        });

                        var li = $('<li style="display: none;" class="active" />').append(a);
                        // $("#menu").append('<li><a href="#">New list item</a></li>');

                        $("#menu").append(li);

                    });
                });

                // $('.nav-tabs li ').click(function(e){
                //     var tab_id = $(this).data('tabkey'); console.log(tab_id);
                //     // $.post('<?php echo site_url('dashboard/setTabActive'); ?>', {tab_id:tab_id}, function(rs){
                //     //     console.log(rs);
                //     // });
                //     $(this).parent().remove();
                //     $('#tab'+tab_id).hide();

                //     $('.nav-tabs a[href="#home"]').tab('show');
                //     $('#home').show();

                //     $.post('<?php echo site_url('dashboard/removeTab'); ?>', {tab_id:tab_id}, function(){});
                // })

                $.addTab = function(){
                    var dashboard_title = $('#dashboard_title').val();
                    if(dashboard_title != ''){
                        $('#addtabmodal').modal('toggle');
                        $.post('<?php echo site_url('dashboard/addTab'); ?>', {dashboard_title:dashboard_title}, function(rs){
                            $('#dashboard_title').val('');
                            var tab_id = rs.tab_id;

                            $('.nav-tabs li').removeClass('active');

                            var i = $('<i />',{ class:'fa fa-times' }).click(function(){
                                $.removeTab(this)
                            });
                            var a = $('<a />').attr({ 'data-toggle':'tab', 'data-tabkey':tab_id, 'href':'#tab'+tab_id }).html(dashboard_title);
                            $(a).append(i);
                            var li = $('<li />').append(a);
                            $('.nav-tabs li:last').after(li);
                            //$('.nav-tabs').append(li);

                            $.post('<?php echo site_url('dashboard/addTabContent'); ?>', {tab_id:tab_id}, function(html){
                                $('.tab-content').append(html);

                                $('.nav-tabs a[href="#tab'+tab_id+'"]').tab('show');
                                $('#'+tab_id).show();
                                $('.grid-stack').gridstack({
                                    resizable: {
                                        handles: 'e, se, s, sw, w'
                                    }
                                });
                                $('.grid-stack').on('change', function(event, items) {
                                    let data = []
                                    $(items).each(function(i, item){
                                        let x = item.x;
                                        let y = item.y;
                                        let width = item.width;
                                        let height = item.height;
                                        let tab_id = item.el.data('tab');
                                        let id = item.el.data('id');
                                        let type = item.el.data('type');

                                        data.push({
                                            tab_id:tab_id, type:type, id:id, x:x, y:y, width:width, height:height
                                        })
                                    })
            //                        $.post('<?php //echo site_url('home/updateTemplate'); ?>//', {data:data}, function(){})
                                });
                                $('.grid-stack').on('gsresizestop', function(event, item) {
                                    let tab_id = $(item).data('tab'); //console.log(tab_id)
                                    let id = $(item).data('id');
                                    let type = $(item).data('type');
                                    let file = $(item).data('file');

                                    $.post('<?php echo site_url('report/getMSData'); ?>/'+id, function(rs){
                                        // $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs);
                                        if (type == 'brush-chart') {
                                            console.log(rs);
                                            $('#chartline_'+tab_id+'_'+id).chartGenerate(type, rs);
                                            $('#chartarea_'+tab_id+'_'+id).chartGenerate(type, rs);
                                        } else {
                                            $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs);
                                        }
                                    },'json')

            //                        let mat = type.match(/ms/g);
            //                        if(mat){
            //                            $.post('<?php //echo site_url('report/getMSData'); ?>///'+id, function(rs){
            //                                $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs);
            //                            },'json')
            //                        }else{
            //                            let stacked = type.match(/stacked/g);
            //                            if(stacked){
            //                                $.post('<?php //echo site_url('report/getMSData'); ?>///'+id, function(rs){
            //                                    $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs);
            //                                },'json')
            //                            }else{
            //                                $.post('<?php //echo site_url('Home/getData'); ?>//', {id:id, file:file}, function(rs){
            //                                    $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs);
            //                                },'json')
            //                            }
            //
            //                        }
                                });
                            })
                        },'json')
                    }
                }

                $.removeTab = function(obj){
                    console.log('removeTab');
                    let tab_id = $(obj).data('tabkey');
                    console.log(obj);
                    $(obj).parent().remove();
                    // $(obj).parent('.nav-tabs li').remove();
                    // this.closest(obj).remove();
                    $('#tab'+tab_id).hide();

                    $('.nav-tabs a[href="#home"]').tab('show');
                    $('#home').show();

                    $.post('<?php echo site_url('dashboard/removeTab'); ?>', {tab_id:tab_id}, function(){
                        if (tab_id == 'home') {
                            $('.tab-content .tab-pane').removeClass('active');
                            $('.tab-content .tab-pane').removeClass('in');
                            // console.log('home')
                        } else {
                            $('.tab-content .tab-pane').addClass('active');
                            $('.tab-content .tab-pane').addClass('in');
                            // console.log('ff');
                        }
                    });
                }

                $.disableLink = function(obj){
                    alert('remove')
                    $(obj).parent('a').click(function(e){
                        e.preventDefault()
                    })
                }

                $.openTab = function(obj){
                    alert('tab');
                }

                $('.grid-stack').gridstack({
                    resizable: {
                        handles: 'e, se, s, sw, w'
                    },
                    cell_height: 80,
                    vertical_margin: 10,
                    animate: true,
                    draggable: {
                        handle: '.panel-heading',
                    }
                });

            //    $('.grid-stack').on('dragstop', function (event, item) {
            //         console.log(item)
            //    });

                $('.grid-stack').on('gsresizestop', function(event, item) {
                    let tab_id = $(item).data('tab'); //console.log(tab_id)
                    let id = $(item).data('id');
                    let type = $(item).data('type');

                    $.post('<?php echo site_url('report/getMSData'); ?>/'+id, function(rs){
                        // $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs);
                        console.log(rs);
                        if (type == 'brush-chart') {
                            // console.log(type);
                            $('#chartline_'+tab_id+'_'+id).chartGenerate(type, rs, e);
                            $('#chartarea_'+tab_id+'_'+id).chartGenerate(type, rs, e);
                        } else if (type == 'grid') {
                            // console.log(type);
                            $('#' + id).chartGenerate(type, rs, e);
                        } else {
                            $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs,e);
                        }
                    },'json')

            //        let mat = type.match(/ms/g);
            //        if(mat){
            //            $.post('<?php //echo site_url('report/getMSData'); ?>///'+id, function(rs){
            //                $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs);
            //            },'json')
            //        }else{
            //            let stacked = type.match(/stacked/g);
            //            if(stacked){
            //                $.post('<?php //echo site_url('report/getMSData'); ?>///'+id, function(rs){
            //                    $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs);
            //                },'json')
            //            }else{
            //                $.post('<?php //echo site_url('Home/getData'); ?>//', {id:id, file:file}, function(rs){
            //                    $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs);
            //                },'json')
            //            }
            //        }
                });

                $('.grid-stack').on('change', function(event, items) {
                    console.log("updateTemplate");
                    let data = []
                    $(items).each(function(i, item){
                        let x = item.x;
                        let y = item.y;
                        let width = item.width;
                        let height = item.height;
                        let tab_id = item.el.data('tab');
                        let id = item.el.data('id');
                        let tabid = item.el.data('tabkey');
                        let type = item.el.data('type');
                        let chartid = item.el.data('chartid');
                        // console.log(item.el.data('chartid'));

                        // console.log(item);

                        data.push({
                            tabid:tabid, type:type, id:id, chartid:chartid, x:x, y:y, width:width, height:height
                        })

                    });

                    // console.log(data);

                    $.post('<?php echo site_url('dashboard/updateTemplate'); ?>', {data:data}, function(){
                        // console.log(data);
                        $('#nodb').css('display','none');
                    })
                });

                $('.item_graph').each(function(i,e){
                    let tab_id = $(e).parents('.grid-stack-item').data('tab');
                    let id = $(e).parents('.grid-stack-item').data('id');
                    let file = $(e).parents('.grid-stack-item').data('file');
                    let type = $(e).parents('.grid-stack-item').data('type');

                    $.post('<?php echo site_url('report/getMSData'); ?>/'+id, function(rs){
                        $(e).parents('.grid-stack-item-content').find('.panel-title').html(rs.title);
                        // $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs,e);

                        // console.log('item_graph'+type);
                        if (type == 'brush-chart') {
                            // console.log(type);
                            $('#chartline_'+tab_id+'_'+id).chartGenerate(type, rs, tab_id, id);
                            $('#chartarea_'+tab_id+'_'+id).chartGenerate(type, rs, tab_id,id);
                        } else if (type == 'grid') {
                            // console.log(type);
                            $('#' + id).chartGenerate(type, rs, tab_id, id);
                        } else {
                            $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs, tab_id, id);
                        }

                    },'json')

            //        let mat = type.match(/ms/g);
            //        if(mat){
            //            $.post('<?php //echo site_url('report/getMSData'); ?>///'+id, function(rs){
            //                $(e).parents('.grid-stack-item-content').find('.panel-title').html(rs.title);
            //                $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs);
            //            },'json')
            //        }else{
            //            let stacked = type.match(/stacked/g);
            //            idashboardf(stacked){
            //                $.post('<?php //echo site_url('report/getMSData'); ?>///'+id, function(rs){
            //                    $(e).parents('.grid-stack-item-content').find('.panel-title').html(rs.title);
            //                    $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs);
            //                },'json')
            //            }else{
            //                $.post('<?php //echo site_url('Home/getData'); ?>//', {id:id, file:file}, function(rs){
            //                    $(e).parents('.grid-stack-item-content').find('.panel-title').html(rs.title);
            //                    $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs);
            //                },'json')
            //            }
            //        }
                })

                function getChartDatadate(startdate,enddate) {
                    // console.log('gettest');
                    console.log(startdate);
                    console.log(enddate);

                    $('.item_graph').each(function(i,e){
                        let tab_id = $(e).parents('.grid-stack-item').data('tab');
                        let id = $(e).parents('.grid-stack-item').data('id');
                        let file = $(e).parents('.grid-stack-item').data('file');
                        let type = $(e).parents('.grid-stack-item').data('type');

                        console.log(tab_id+id);

                        $.post('<?php echo site_url('report/getMSData'); ?>/'+id+'/'+startdate+'/'+enddate, function(rs){
                            // $(e).parents('.grid-stack-item-content').find('.panel-title').html(rs.title);
                            $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs,e);
                        },'json')
                    })
                }


                var chart_ms_config = {
                    "palettecolors":"2196f3,0d47a1,1976d2,64b5f6,fff176,ffd54f,ffb74d",
                    "plotToolText": "<b>$label</b><br>$seriesName: $dataValue",
                    "exportEnabled": true,
                    "subcaption": "",
                    "xaxisname": "",
                    "yaxisname": "",
                    "numbersuffix": "",
                    "formatNumberScale": "2",
                    "placeValuesInside": "0",
                    "labelDisplay": "rotate",
                    "slantLabel": "1",
                    "rotateValues": "1",
                    "showValues": "1",
                    "valueFontColor": "#000000",
                    "is2d": "0",
                    "ishollow": "0",
                    "usesameslantangle": "0",
                    "theme": "fint"
                }

                var chart_config = {
                    "plotToolText": "<b>$label</b> : $dataValue",
                    "exportEnabled": true,
                    "subcaption": "",
                    "xaxisname": "",
                    "yaxisname": "",
                    "numberSuffix": "",
                    "formatNumberScale": "1",
                    "placeValuesInside": "1",
                    "labelDisplay": "rotate",
                    "slantLabel": "1",
                    "rotateValues": "0",
                    "showValues": "1",
                    "is2d": "0",
                    "ishollow": "0",
                    "usesameslantangle": "0",
                    "theme": "fint"
                }

                $.refreshBox = function(obj){
                    let el = $(obj).closest('.grid-stack-item');
                    let tab_key = $(el).data('tabkey');
                    let tab_id = $(el).data('tab');
                    let id = $(el).data('id');
                    let type = $(el).data('type');

                    $.post('<?php echo site_url('report/getMSData'); ?>/'+id, function(rs){
                        // if (type == 'grid') {
                        //     $('#table_'+tab_id+'_'+id).chartGenerate(type, rs);
                        // } else {
                        //     $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs);
                        // }
                        // console.log(type);
                        if (type == 'brush-chart') {
                            console.log(type);
                            console.log(rs);
                            $('#chartline_'+tab_id+'_'+id).chartGenerate(type, rs);
                            $('#chartarea_'+tab_id+'_'+id).chartGenerate(type, rs);
                        } else {
                            // console.log(type);
                            // console.log(rs);
                            $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs);
                        }
                    },'json')

            //        let mat = type.match(/ms/g);
            //        if(mat){
            //            $.post('<?php //echo site_url('report/getMSData'); ?>///'+id, function(rs){
            //                $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs);
            //            },'json')
            //        }else{
            //            let stacked = type.match(/stacked/g);
            //            if(stacked){
            //                $.post('<?php //echo site_url('report/getMSData'); ?>///'+id, function(rs){
            //                    $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs);
            //                },'json')
            //            }else{
            //                $.post('<?php //echo site_url('Home/getData'); ?>//', {id:id, file:file}, function(rs){
            //                    $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs);
            //                },'json')
            //            }
            //        }
                }

                // $.gettest = function() {
                //     console.log('gettest');
                // }

                $.removeBox = function(obj){
                    let el = $(obj).closest('.grid-stack-item');
                    let tab_key = $(el).data('tabkey');
                    let tab_id = $(el).data('tab');
                    let id = $(el).data('id');

                    let grid = $('#'+tab_id+' .grid-stack').data('gridstack');

                    // $('#'+tab_id+' .grid-stack .grid-stack-item').each(function(i){
                    //     console.log(tab_id);
                    //     console.log(id);
                    // });

                    $.post('<?php echo site_url('dashboard/removeWidget'); ?>', {tab_key:tab_key, id:id}, function(){

                    })

                    grid.remove_widget(el);
                }

                $.genBox = function(tab_id, tab_key, id, type, chartid){
                    console.log("genBox");
                    let grid_item = $('<div />',{ class:'grid-stack-item' }).attr({ 'data-id':id, 'data-tabkey':tab_key, 'data-type':type, 'data-chartid':chartid ,'data-tab':tab_id, 'data-gs-id':'item_'+id });
                    let grid_item_content = $('<div />',{ class:'grid-stack-item-content box-border chartcontent' });

                    let panel_heading = $('<div />',{ class:'panel-heading clearfix headingchart' });
                    let panel_title = $('<span />',{ class:'panel-title pull-left' });
                    let panel_body = $('<div />',{ class:'panel-body' }).css({ 'overflow':'auto' })

                    let graph = $('<div />',{ id:'graph_'+tab_id+'_'+id, class:'item_graph' });

                    let chartline = $('<div />', { id:'chartline_'+tab_id+'_'+id, class: 'item_graph' });
                    let chartarea = $('<div />', { id:'chartarea_'+tab_id+'_'+id, class: 'item_graph' });

                    let table = $('<div />', { id:'tab_id', class:'item_graph' });

                    let tabledetail = $('<table />',{ id:'table_'+tab_id+'_'+id, class:'table table-striped table-bordered display nowrap',style:'font-size: 12px; font-family: PromptMedium; font-weight: 100; width: 100%;' });

                    let btn_group = $('<div />',{ class:'pull-right widget-buttons' });
                    let btn_redo = $('<button />',{ class:'btn btn-xs' }).html('<i class="fa fa-redo"></i>').click(function(){
                        $.refreshBox(this)
                    });;

                    let li_dropdown = $('<li />',{ class: 'nav-item dropdown dropdownchart' });
                    let btn_li = $('<button />',{ class: 'btn btn-xs'}).attr({ 'data-toggle': 'dropdown' }).html('<i><img src="<?php echo site_assets_url('images/icons/61140.png'); ?>" width="10" height="10" /></i>');
                    let dropdown_menu = $('<div />',{ class: 'dropdown-menu mailbox' });
                    let ul_mailbox = $('<ul />', { class: 'list-style-type: none;' });
                    let li_duplicate = $('<li />', { style: 'list-style-type: none;'}).html('<a href="javascript:void(0)">Duplicate</a>').click(function(){
                        $.duplicate(this);
                    });
                    let li_export = $('<li />', { style: 'list-style-type: none;'}).html('<a href="javascript:void(0)">Export</a>').click(function(){
                        $.export(this);
                    });
                    let li_rename = $('<li />', { style: 'list-style-type: none;'}).html('<a href="javascript:void(0)">Rename</a>').click(function(){
                        $.rename(this);
                    });
                    let hr = $('<hr />', { style: 'margin-top: 0px; margin-bottom: 0px; margin-left: -40px;' });
                    let li_remove = $('<li />', { style: 'list-style-type: none;'}).html('<a href="javascript:void(0)" style="color: red;">Remove from dashborard</a>').click(function(){
                        $.removeBox(this);
                    });
                    // let btn_remove = $('<button />',{ class:'btn btn-xs' }).html('<i class="fa fa-times"></i>').click(function(){
                    //     $.removeBox(this)
                    //     });
                    $(dropdown_menu).append(ul_mailbox);
                    $(ul_mailbox).append(li_duplicate);
                    $(ul_mailbox).append(li_export);
                    $(ul_mailbox).append(li_rename);
                    $(ul_mailbox).append(hr);
                    $(ul_mailbox).append(li_remove);

                    $(li_dropdown).append(btn_li);
                    $(li_dropdown).append(dropdown_menu);
                    $(btn_group).append(btn_redo);
                    $(btn_group).append(li_dropdown);

                    $(panel_heading).append(panel_title);
                    $(panel_heading).append(btn_group);

                    if (type == "brush-chart") {
                        $(panel_body).append(graph);
                        $(panel_body).append(chartline);
                        $(panel_body).append(chartarea);
                        // console.log(type);
                    } else if (type == 'grid') {
                        // $(table).append(tabledetail);
                        $(panel_body).append(graph);
                        $(panel_body).append(tabledetail);
                    } else {
                        $(panel_body).append(graph);
                    }
                    // $(table).append(tabledetail);
                    // $(panel_body).append(table);

                    $(grid_item_content).append(panel_heading);
                    $(grid_item_content).append(panel_body);
                    $(grid_item).append(grid_item_content);

                    let grids = $('#'+tab_id+' .grid-stack').data('gridstack');
                    grids.add_widget(grid_item, 1, 1, grid_width, grid_height, true, grid_width, grid_height);
                }

                $.genGraph = function(tab_id, tab_key, id, type, chartid){
                    console.log("genGraph");
                    // console.log(type);
                    // $(".container-fluid").LoadingOverlay("show");
                    // if($('#graph_'+tab_id+'_'+id).length > 0){
                    //     // $(".container-fluid").LoadingOverlay("hide", true);
                    //     // console.log('rrr');
                    //     return false;
                    //     // console.log('fff');
                    // } else {
                    //     console.log('ssss');
                    // }

                    $.post('<?php echo site_url('report/getMSData'); ?>/'+id, function(rs){
                        $.genBox(tab_id, tab_key, id, type, chartid);
                        $('#graph_'+tab_id+'_'+id).parents('.grid-stack-item-content').find('.panel-title').html(rs.title);
                        // // console.log(rs);
                        // // $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs);
                        if (type == 'brush-chart') {
                            console.log(type);
                            console.log(rs);
                            // $('.grid-stack-item-content').find('.panel-title').html(rs.title);
                            $('#chartline_'+tab_id+'_'+id).chartGenerate(type, rs,tab_id,id);
                            $('#chartarea_'+tab_id+'_'+id).chartGenerate(type, rs,tab_id,id);
                        } else if (type == 'grid') {
                            // console.log(type);
                            $('#' + id).chartGenerate(type, rs,tab_id,id);
                        } else {
                            // console.log(type);
                            // console.log(rs);
                            // $('.grid-stack-item-content').find('.panel-title').html(rs.title);
                            $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs,tab_id,id);
                        }
                        // $(".container-fluid").LoadingOverlay("hide", true);
                    },'json')

            //        let mat = type.match(/ms/g);
            //        if(mat){
            //            $.post('<?php //echo site_url('report/getMSData'); ?>///'+id, function(rs){
            //                $.genBox(tab_id, tab_key, id, type);
            //                $('#graph_'+tab_id+'_'+id).parents('.grid-stack-item-content').find('.panel-title').html(rs.title);
            //                $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs);
            //                $(".container-fluid").LoadingOverlay("hide", true);
            //            },'json')
            //        }else{
            //            let stacked = type.match(/stacked/g);
            //            if(stacked){
            //                $.post('<?php //echo site_url('report/getMSData'); ?>///'+id, function(rs){
            //                    $.genBox(tab_id, tab_key, id, type, file);
            //                    $('#graph_'+tab_id+'_'+id).parents('.grid-stack-item-content').find('.panel-title').html(rs.title);
            //                    $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs);
            //                    $(".container-fluid").LoadingOverlay("hide", true);
            //                },'json')
            //            }else{
            //                $.post('<?php //echo site_url('Home/getData'); ?>//', {id:id, file:file}, function(rs){
            //                    $.genBox(tab_id, tab_key, id, type, file);
            //                    $('#graph_'+tab_id+'_'+id).parents('.grid-stack-item-content').find('.panel-title').html(rs.title);
            //                    $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs);
            //                    $(".container-fluid").LoadingOverlay("hide", true);
            //                },'json')
            //            }
            //        }
                }

                $.genData = function(data){
                    let config_data = chart_config;
                    config_data.showValues = data.showValues;
                    if(data.numberSuffix != null) config_data.numberSuffix = data.numberSuffix;
                    let sample_data = {
                        "chart": config_data,
                        "data": data.data,
                        "categories" : data.categories,
                        "columns" : data.columns,
                        "orderby" : data.orderby,
                    }
                    return sample_data;
                }

                $.genMSData = function(data){
                    let config_data = chart_ms_config;
                    if(data.tooltip_pattern != null) config_data.plotToolText = data.tooltip_pattern;
                    let sample_data = {
                        "chart": config_data,
                        "categories": data.categories,
                        "dataset": data.data,
                        "columns" : data.columns,
                        "orderby" : data.orderby,
                    }
                    //console.log(data);
                    return sample_data;
                }



                $.fn.chartGenerate = function(type, data_source, tab_id, id){
                    let mat = type.match(/ms/g);
                    // let tab_id = $(e).parents('.grid-stack-item').data('tab');
                    // let id = $(e).parents('.grid-stack-item').data('id');
                    // let file = $(e).parents('.grid-stack-item').data('file');
                    //console.log(type);
                    /*if(mat){
                        data_source = $.genMSData(data_source);
                    }else{
                        let stacked = type.match(/stacked/g);
                        if(stacked){
                            data_source = $.genMSData(data_source);
                        }else{
                            data_source = $.genData(data_source);
                        }
                    }*/
                    data_source = $.genData(data_source);
                    /*console.log(type);
                    console.log(data_source);*/
                    // console.log(data_source.categories);

                    console.log(type);

                    let div = $(this).attr('id');
                    let table = $(this).attr('id');
                    // console.log(div);
                    let item_height = $('#'+div).parents('.grid-stack-item').height();
                    let height = item_height - 80;
                    // new FusionCharts({
                    //     type: type,
                    //     renderAt: div,
                    //     width: "100%",
                    //     height: height,
                    //     dataFormat: "json",
                    //     dataSource: data_source
                    // }).render();

                    // console.log(type);
                    if (type == 'brush-chart') {
                        // console.log(type);
                        // console.log(data_source);
                        // console.log(data_source.data);
                        // console.log(data_source.categories);

                        var optionslinebrush = {
                            series: [{
                             data: data_source.data
                            }],
                            // series: data_source.data,
                            chart: {
                                id: 'chart2',
                                type: 'line',
                                height: 150,
                                toolbar: {
                                    autoSelected: 'pan',
                                    show: false
                                }
                            },
                            colors: ['#546E7A'],
                            stroke: {
                                width: 3
                            },
                            dataLabels: {
                                enabled: false
                            },
                            fill: {
                                opacity: 1,
                            },
                            markers: {
                                size: 0
                            },
                            xaxis: {
                                // type: 'datetime',
                                // categories: ['01/01/2011 GMT', '01/02/2011 GMT', '01/03/2011 GMT', '01/04/2011 GMT',
                       //          '01/05/2011 GMT', '01/06/2011 GMT'
                       //        ],
                                categories: data_source.categories
                            }
                        };

                        var chartlinebrush = new ApexCharts(document.querySelector('#chartline_'+tab_id+'_'+id), optionslinebrush);
                        chartlinebrush.render();
                        chartlinebrush.appendSeries([{
                           data: data_source.data
                        }]);

                        var optionslinebrush = {
                            series: [{
                                data: data_source.data
                            }],
                            // series: data_source.data,
                            chart: {
                                id: 'chart1',
                                height: 130,
                                type: 'area',
                                brush:{
                                    target: 'chart2',
                                    enabled: true
                                },
                                selection: {
                                    enabled: true,
                                    xaxis: {
                                        // min: undefined,
                                        // max: undefined
                                        // type: 'datetime',
                                        // min: new Date("11 Jan 2011 10:00:00").getTime(),
                                        // max: new Date("11 Feb 2011 10:00:00").getTime(),
                                    }
                                },
                            },
                            colors: ['#008FFB'],
                            fill: {
                                type: 'gradient',
                                gradient: {
                                    opacityFrom: 0.91,
                                    opacityTo: 0.1,
                                }
                            },
                            xaxis: {
                                // type: 'datetime',
                                // categories: ['01/01/2011 GMT', '01/02/2011 GMT', '01/03/2011 GMT', '01/04/2011 GMT',
                       //          '01/05/2011 GMT', '01/06/2011 GMT'
                       //           ],
                                categories: data_source.categories,
                                tooltip: {
                                    enabled: false
                                }
                            },
                            yaxis: {
                                tickAmount: 2
                            }
                        };

                        var chartLine = new ApexCharts(document.querySelector('#chartarea_'+tab_id+'_'+id), optionslinebrush);
                        chartLine.render();
                        chartLine.appendSeries([{
                           data: data_source.data
                        }]);

                    } else if (type == 'line-basic') {
                        // console.log(data_source);
                        // console.log(data_source.data);
                        // console.log(data_source.categories);
                        var options = {
                            // series: [{
                            //     // name: "Desktops",
                            //     data: [10, 41, 35, 51, 49, 62, 69, 91, 148]
                            // }],
                            series: data_source.data,
                            chart: {
                                height: height,
                                width: "100%",
                                type: 'line',
                                zoom: {
                                    enabled: false
                                },
                                toolbar: {
                                    show: false,
                                    tools: {
                                      download: false
                                    }
                                }
                            },
                            dataLabels: {
                                enabled: false
                            },
                            stroke: {
                                curve: 'straight'
                            },
                            grid: {
                                row: {
                                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                                    opacity: 0.5
                                },
                            },
                            xaxis: {
                                // categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                                categories: data_source.categories,
                            }
                        };
                    } else if (type == 'stacked-columns') {
                        //console.log('stack-bar');
                        //console.log(data_source.data);
                        var options = {
                            series: data_source.data,
                            /*series: [{
                                name: 'PRODUCT A',
                                data: [44, 55, 41, 67, 22, 43]
                            }, {
                                name: 'PRODUCT B',
                                data: [13, 23, 20, 8, 13, 27]
                            }, {
                                name: 'PRODUCT C',
                                data: [11, 17, 15, 15, 21, 14]
                            }, {
                                name: 'PRODUCT D',
                                data: [21, 7, 25, 13, 22, 8]
                            }],*/
                            chart: {
                                type: 'bar',
                                height: height,
                                width: "100%",
                                stacked: true,
                                toolbar: {
                                    show: true
                                },
                                zoom: {
                                    enabled: true
                                },
                                toolbar: {
                                    show: false,
                                    tools: {
                                      download: false
                                    }
                                }
                            },
                            responsive: [{
                                breakpoint: 480,
                                options: {
                                    legend: {
                                        position: 'bottom',
                                        offsetX: -10,
                                        offsetY: 0
                                    }
                                }
                            }],
                            plotOptions: {
                                bar: {
                                    horizontal: false,
                                },
                            },
                            xaxis: {
                                //type: 'datetime',
                                categories: data_source.categories,
                                //categories: ['01/01/2011 GMT', '01/02/2011 GMT', '01/03/2011 GMT', '01/04/2011 GMT','01/05/2011 GMT', '01/06/2011 GMT'],
                            },
                            legend: {
                                position: 'right',
                                offsetY: 40
                            },
                            fill: {
                                opacity: 1
                            }
                        };
                    } else if (type == 'grid') {
                        console.log(data_source);
                        //console.log(data_source.data);
                        //console.log(data_source.columns);

                        // for (var i = 0; i < data_source.length; i++) {
                        //     // console.log(data_source[i]);
                        // }

                        // var data = [
                        //     [ "Row 1 - Field 1", "Row 1 - Field 2", "Row 1 - Field 3" ],
                        //     [ "Row 2 - Field 1", "Row 2 - Field 2", "Row 2 - Field 3" ],
                        // ];

                        // var columns = [
                        //     { "title":"One" },
                        //     { "title":"Two" },
                        //     { "title":"Three" }
                        // ];

                        // var columns = data_source.columns;

                        // $('table.display').DataTable( {
                        //     dom: "Bfrtip",
                        //     data: data_source.data[i],
                        //     columns: data_source.columns[i],
                        //     retrieve: true,
                        //     paging: false
                        // });

                        // console.log(id);

                        // $('table.display').DataTable({
                        //     dom: "Bfrtip",
                        //     data: data_source.data,
                        //     columns: data_source.columns,
                        //     retrieve: true,
                        //     paging: false
                        // })

                        $.each($('#table_'+tab_id+'_'+id), function () {
                            var dt_id = $(this).attr('id');
                            console.log(dt_id);
                            // var datatable =  $('#' + dt_id).DataTable({
                            //     dom: "Bfrtip",
                            //     data: data_source.data,
                            //     columns: data_source.columns,
                            //     retrieve: true,
                            //     paging: false,
                            //     responsive: true
                            // });
                            var table;
                            if ($.fn.dataTable.isDataTable('#' + dt_id)) {
                                table = $('#' + dt_id).DataTable();
                                table.clear().draw();
                                table.rows.add(data_source.data);
                                table.columns.adjust().draw();
                                // table.clear();
                                // table.rows.add(data_source.data).draw();
                                console.log('ff');
                            } else {
                                table = $('#' + dt_id).DataTable({
                                    "dom" : "Bfrtip",
                                    "data": data_source.data,
                                    "columns": data_source.columns,
                                    "retrieve": true,
                                    "paging": false,
                                    "responsive": true,
                                    "searching": false,
                                    "order": [[ data_source.orderby, "desc" ]]
                                });
                                console.log('ee');
                            }
                        });

                    }

                    var chart = new ApexCharts(document.querySelector('#'+div), options);
                    chart.render();
                    chart.appendSeries({
                        series: data_source.data,
                    })


                    // var option = {
                    //     series: [{
                    //         data: data_source
                    //     }],
                    //     chart: {
                    //           height: height,
                    //           type: type,
                    //           width: "100%",
                    //           zoom: {
                    //             enabled: false
                    //         }
                    //     },
                    //     dataLabels: {
                    //         enabled: false
                    //     },
                    //     stroke: {
                    //         curve: 'straight'
                    //     },
                    //     title: {
                    //         text: '',
                    //         align: 'left'
                    //     },
                    //     grid: {
                    //       row: {
                    //         colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                    //         opacity: 0.5
                    //       },
                    //     },
                    //     //  xaxis: {
                    //     //     categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                    //     // }
                    // };
                    // var chart = new ApexCharts(document.querySelector('#'+div), option);
                    // chart.render();
                }

                // $.dbdetails = function(){
                //     // console.log('T');
                //     var dbdetailsmodal = document.getElementById('dbdetailsmodal');

                //     dbdetailsmodal.style.display = "block";
                // }


                $.adddb = function() {
                    // console.log('t');
                    // var nodb = document.getElementById('nodb');
                    // nodb.style.display = "block";
                    // var viewdb = document.getElementById('viewdb');
                    // viewdb.style.display = "none";
                    $('#addtabmodal').modal('toggle');

                    var url = "<?php echo site_url('report/addDashboards'); ?>";
                    var data = [];

                    $.ajax(url, {
                        type: "POST",
                        data: data,
                        success: function(data) {
                        console.log('s');
                            var dashboard_name = $('#dashboard_name').val();
                            var userid = $('#userid').val();
                            console.log(userid);
                            // var userid = ;
                            if(dashboard_name != ''){

                                // $('.nav-tabs li').removeClass('active');

                                // $('.nav-tabs').append(
                                //  '<li><a href="#tab7" role="tab" data-toggle="tab" onclick="loadTab(7,\"NewTab\", 0)">New Tab</a></li>'
                                //  );
                                    $.post('<?php echo site_url('dashboard/addTab'); ?>', {dashboard_title:dashboard_name,userid:userid}, function(rs){
                                        console.log(rs);
                                        $('#dashboard_title').val('');
                                        var tab_id = rs.tab_id;

                                        $('#tabidhide').val(tab_id);
                                        $('#dashboard_title').val(rs.tab_name);
                                        $('#dbowner').val(rs.owner);
                                        $('#desciption').val("");

                                        $('.nav-tabs li').removeClass('active');

                                        // var i = $('<i />',{ class:'fa fa-times', data-tabkey:tab_id }).click(function(){
                                        //     $.removeTab(this);
                                        // });

                                        var i = $('<i />').attr({'class':'fa fa-times', 'data-tabkey':tab_id }).click(function(){
                                            $.removeTab(this);
                                            console.log('tttt');
                                        });

                                        var a = $('<a />').attr({ 'data-toggle':'tab', 'data-tabkey':tab_id, 'href':'#tab'+tab_id }).html(dashboard_name);
                                        $(a).append(i);
                                        var li = $('<li class="active" />').append(a);
                                        $('.nav-tabs li:last').after(li);
                                        $(li).insertBefore( '.nav-tabs .adddashboards');

                                        $('.nav-tabs.navfullscreen li').removeClass('active');
                                        $('.nav-tabs.navfullscreen li').css("display","none");

                                        var i2 = $('<i />',{ class:'fa fa-times' }).click(function(){
                                            $.removeTab(this);
                                        });

                                        var a2 = $('<a />').attr({ 'data-toggle':'tab', 'data-tabkey':tab_id, 'href':'#tab'+tab_id }).html(dashboard_name);
                                            $(a2).append(i2);

                                        var li2 = $('<li style="display: none;" class="active" />').append(a2);
                                        // $("#menu").append('<li><a href="#">New list item</a></li>');

                                        $("#menu").append(li2);

                                        $.post('<?php echo site_url('dashboard/addTabContent'); ?>', {tab_id:tab_id}, function(html){
                                            // console.log(html);

                                            if (tab_id == 'home') {
                                                $('.tab-content .tab-pane').addClass('active');
                                                $('.tab-content .tab-pane').addClass('in');
                                                // console.log('gggg');
                                            } else {
                                                $('.tab-content .tab-pane').removeClass('active');
                                                $('.tab-content .tab-pane').removeClass('in');
                                                // console.log('ffg');
                                            }

                                            // $('.tab-content .tab-pane').removeClass('active');
                                            // $('.tab-content .tab-pane').removeClass('in');
                                            $('#tabpaneaddwidget').addClass('active');
                                            $('.tab-content').append(html);
                                            $('.nav-tabs a[href="#tab'+tab_id+'"]').tab('show');
                                            $('#tab'+tab_id).show();
                                            $('.grid-stack').gridstack({
                                                resizable: {
                                                    handles: 'e, se, s, sw, w'
                                                }
                                            });

                                            $('.grid-stack').on('change', function(event, items) {
                                                console.log("updateTemplate");
                                                let data = []
                                                $(items).each(function(i, item){
                                                    let x = item.x;
                                                    let y = item.y;
                                                    let width = item.width;
                                                    let height = item.height;
                                                    let tab_id = item.el.data('tab');
                                                    let id = item.el.data('id');
                                                    let tabid = item.el.data('tabkey');
                                                    let type = item.el.data('type');
                                                    let chartid = item.el.data('chartid');
                                                    // console.log(item.el.data('chartid'));

                                                    // console.log(item);

                                                    data.push({
                                                        tabid:tabid, type:type, id:id, chartid:chartid, x:x, y:y, width:width, height:height
                                                    })

                                                });

                                                // console.log(data);

                                                $.post('<?php echo site_url('dashboard/updateTemplate'); ?>', {data:data}, function(){
                                                    // console.log(data);
                                                    $('#nodb').css('display','none');
                                                })
                                            });

                                            // location.reload();

                                            // if ($('#addwidgetmodal').hasClass('in')) {
                                            //     console.log('show');
                                            // } else {
                                            //     console.log('e');
                                            // }

                                            // $('#tab'+tab_id).removeClass('active');
                                            // $('#tab'+tab_id).removeClass('in');


                                            $.clickmodaladdwidget = function() {
                                                console.log('yes2');
                                                $('#addwidgetmodal').modal('show');
                                                $('.modal-body .tab-content .tab-pane').removeClass('in');
                                                $('.modal-body .tab-content .tab-pane').removeClass('active');
                                                $('#tabpaneaddwidget').addClass('active');
                                            }

                                            // if ($('#tabpaneaddwidget').length) {
                                            //     console.log('s');
                                            //     $('#tab'+tab_id).removeClass('active');
                                            //     $('#tab'+tab_id).removeClass('in');
                                            // } else {
                                            //     console.log('e');
                                            // }



                                        });
                                        // $('.nav-tabs .adddashboards').before(li);
                                        // $('.nav-tabs').append(li);
                                        //$('.nav-tabs').append(li);
                                },'json');
                            }
                        },
                        error: function(data) {
                            console.log('e');
                        },
                    });

                }



                $.addwidget = function(obj) {
                    // console.log('add widget');
                    // var nodb = document.getElementById('nodb');
                    // nodb.style.display = "none";
                    // var viewdb = document.getElementById('viewdb');
                    // viewdb.style.display = "block";
                    // $('#addwidgetmodal').modal('toggle');
                    // $('input[name="widget"]:checked').each(function(i) {
                    //     console.log(this.value);
                    //     // console.log(widgettype);
                    // });

                    // var url = "<?php echo site_url('report/addWidget')?>";
                    // var data = $("#form_wiget").serialize();

                    // console.log(data);


                    $("#form_wiget").submit();
                    // $.ajax(url, {
                    //     type: "POST",
                    //     data: data,
                    //     success: function(data) {
                    //         console.log('s');
                    //         // $.genBox(tab_id, tab_key, id, type);
                    //         // $('#graph_'+tab_id+'_'+id).parents('.grid-stack-item-content').find('.panel-title').html(rs.title);
                    //         // $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs);
                    //         // $(".container-fluid").LoadingOverlay("hide", true);
                    //     },
                    //     error: function(data) {
                    //         console.log('e');
                    //     },
                    // });

                }

                $("#form_wiget").submit(function (e) {
                    //var allVals = [];
                    $('#addwidgetmodal').modal('hide');
                    var tabkey = $('#tabidhide').val();
                    // console.log(tabkey);
                    var tab_id;

                    if (tabkey == "home") {
                        // console.log('home');
                        tab_id = tabkey;
                        // console.log(tab_id);
                    } else {
                        // console.log('nohome');
                        tab_id = "tab" + tabkey;
                        // console.log(tab_id);
                    }

                    $('input[name="widget"]:checked').each(function () {
                          //     removed the space ^
                        var data = $(this).val();
                        var a_data = data.split(",");
                        //console.log(a_data);
                        //allVals.push({chartid:a_data[0],type:a_data[1]});
                        // console.log(tab_id);
                        // console.log(tabkey);
                        // console.log(a_data[2]);
                        // console.log(a_data[0]);
                        // console.log(a_data[1]);

                        $.genGraph(tab_id,tabkey,a_data[2],a_data[1],a_data[0]);
                        // genGraph(tabid,);
                        // $('#addwidgetmodal').modal('toggle');

                    });

                    // console.log(allVals);


                    /*var str = allVals;
                    var res = str.split(",");
                    console.log(res);*/

                    e.preventDefault();



                });

                $.chooeWidget = function(chartid,widgettype) {
                    $('input[name="widget"]:checked').each(function() {
                        console.log(chartid);
                        console.log(widgettype);
                    });
                }

                // $.checkwidget = function(id,chid) {
                //     console.log(id);
                //     console.log(chid);
                //     if (id == 'Marketing') {
                //         // console.log(id);
                //         $('input[name="checkwidget"]:checked').each(function() {
                //             // checkboxValues.push($(elem).val());
                //             // console.log(id);
                //             // $('#'+id).prop('checked', true);
                //             $('input[name="widget"]').prop('checked', true);
                //         });
                //     }
                //     // $('input[name="checkwidget"]:checked').each(function() {
                //     //     // checkboxValues.push($(elem).val());
                //     //     // console.log(id);
                //     //     $('input[name="widget"]').prop('checked', true);
                //     // });
                // }

                $.adddashboarddetails = function() {
                    $('#dbdetailsmodal').modal('toggle');

                    var tab_id = $('#tabidhide').val();
                    console.log(tab_id);
                    var dashboardtitle = $('#dashboard_title').val();
                    console.log(dashboardtitle);
                    var dbowner = $('#dbowner').val();
                    console.log(dbowner);
                    var desciption = $('#desciption').val();
                    console.log(desciption);

                    // var data = [];

                    // data.push({
                    //     dashboardtitle:dashboardtitle, desciption:desciption
                    // })

                    $.post('<?php echo site_url('dashboard/addDashboardsdetails'); ?>', {tab_id:tab_id,dashboardtitle:dashboardtitle,desciption:desciption}, function(rs){
                        console.log(rs);
                        $('#desciption').val("");
                    });

                    // var data [
                    //     // desciption: desciption,
                    // ]



                    // $.post('<?php echo site_url('dashboard/addDashboardsdetails'); ?>', {tab_id:tab_id,data:data}, function(){
                    //     console.log(data);
                    //     // $('#nodb').css('display','none');
                    // })

                    // var url = "<?php echo site_url('report/addDashboardsdetails') ?>";
                    // var data = [];

                    // $.ajax(url, {
                    //     type: "POST",
                    //     data: data,
                    //     success: function(data) {
                    //         console.log('s');
                    //     },
                    //     error: function(data) {
                    //         console.log('e');
                    //     },
                    // });
                }

                $.clickmodaldbdetails = function() {
                    $('#dbdetailsmodal').modal('show');
                }

                $.clickmodaladdwidget = function() {
                    console.log('yes1');
                    $('#addwidgetmodal').modal('show');
                    $('.modal-body .tab-content .tab-pane').removeClass('in');
                    $('.modal-body .tab-content .tab-pane').removeClass('active');
                    $('#tabpaneaddwidget').addClass('active');
                }

                $.setasdefault = function() {
                    console.log('setasdefault');
                    var url = "<?php echo site_url('report/setasdefault') ?>";
                    var data = [];
                    $.ajax(url, {
                        type: "POST",
                        data: data,
                        success: function(data) {
                            console.log('s');
                        },
                        error: function(data) {
                            console.log('e');
                        },
                    });
                }

                $.share = function(obj) {
                    console.log('share');
                    var url = "<?php echo site_url('report/share') ?>";
                    var data = [];
                    $.ajax(url, {
                        type: "POST",
                        data: data,
                        success: function(data) {
                            console.log('s');
                        },
                        error: function(data) {
                            console.log('e');
                        },
                    });
                }

                $.delete = function() {
                    console.log('delete');
                    var url = "<?php echo site_url('report/delete') ?>";
                    var data = [];
                    $.ajax(url, {
                        type: "POST",
                        data: data,
                        success: function(data) {
                            console.log('s');
                        },
                        error: function(data) {
                            console.log('e');
                        },
                    });
                }

                $.duplicate = function(obj) {

                    console.log('duplicate');
                    var el = $(obj).closest('.grid-stack-item');
                    var tab_key = $(el).data('tabkey');
                    var tab_id = $(el).data('tab');
                    var id = $(el).data('id');
                    console.log(tab_id+'_'+id);

                    var url = "<?php echo site_url('report/duplicate') ?>";
                    var data = [];
                    $.ajax(url, {
                        type: "POST",
                        data: data,
                        success: function(data) {
                            console.log('s');
                        },
                        error: function(data) {
                            console.log('e');
                        },
                    });
                }

                $.rename = function(obj) {
                    console.log('rename');
                    var el = $(obj).closest('.grid-stack-item');
                    var tab_key = $(el).data('tabkey');
                    var tab_id = $(el).data('tab');
                    var id = $(el).data('id');
                    console.log(tab_id+'_'+id);

                    var url = "<?php echo site_url('report/rename') ?>";
                    var data = [];
                    $.ajax(url, {
                        type: "POST",
                        data: data,
                        success: function(data) {
                            console.log('s');
                        },
                        error: function(data) {
                            console.log('e');
                        },
                    });
                }

                $.export = function(obj) {
                    console.log('export');
                    var el = $(obj).closest('.grid-stack-item');
                    var tab_key = $(el).data('tabkey');
                    var tab_id = $(el).data('tab');
                    var id = $(el).data('id');
                    console.log(tab_id + '_' + id);

                    var url = "<?php echo site_url('report/export') ?>";
                    var data = [];
                    $.ajax(url, {
                        type: "POST",
                        data: data,
                        success: function(data) {
                            console.log('s');
                        },
                        error: function(data) {
                            console.log('e');
                        },
                    });
                }

                $.checked = function(ck_class) {
                    // $('.'+ck_class).prop('checked', true);
                    // if($(this).prop("checked") == true){
                    //     console.log("Checkbox is checked.");
                    // }
                    // else if($(this).prop("checked") == false){
                    //     console.log("Checkbox is unchecked.");
                    // }
                    if ($("#"+ck_class).closest('fieldset').find('input').prop('checked') == true) {
                        $('#'+ck_class).closest('fieldset').find('input').prop('checked',true);
                    } else {
                        $('#'+ck_class).closest('fieldset').find('input').prop('checked',false);
                    }

                    // $('.'+ck_class).change(function() {
                    //     // console.log('fff');
                    //       var checkboxes = $(this).parent().find('.'+ck_class);
                    //       var checkedboxes = checkboxes.filter(':checked');

                    //       if (checkedboxes.length === checkedboxes.length) {
                    //         // console.log('vvv');
                    //         // $(this).closest('fieldset').find('input').prop('checked', true);
                    //         console.log(this);
                    //         $("#"+ck_class).closest('fieldset').find('input').prop('checked', true);
                    //       } else {
                    //         $("#"+ck_class).closest('fieldset').find('input').prop('checked',false);
                    //         // $(this).closest('fieldset').find('input').prop('checked', false);
                    //         console.log(this);
                    //       }

                    // });

                    // if ($("#"+ck_class).prop('checked') == true) {
                    //     console.log('true');
                    //     $('.'+ck_class).prop('checked', true);

                    // } else {
                    //     console.log("Checkbox is unchecked.");
                    //     $('.'+ck_class).prop('checked', false);

                    // }
                }
        </script>

        <script>
            $(function () {
                $(".panel-clrom").click(function () {
                    $(this).toggleClass("on");
                });

                $(".panel-collapseom").on("show.bs.collapse", function () {
                    $(this).siblings(".panel-headingaw").addClass("active");
                });

                $(".panel-collapseom").on("hide.bs.collapse", function () {
                    $(this).siblings(".panel-headingaw").removeClass("active");
                });

                /*$(".select-all").change(function () {
                    $(this).siblings().prop('checked', $(this).prop("checked"));
                });*/

                // function checked(ck_class){

                //     // $('.Lead').attr('checked', true);
                //     $('input[name="widget"]').prop('checked', true);

                // }

                /*$('#grepperRocks').prop('checked', true);
                $('.myCheckbox').prop('checked', true);
                $('.myCheckbox').prop('checked', false);*/

                // Below code is not working as expected

                // (All) is auto checked/unchecked
                /*$(".checkboxlistitem").change(function() {
                    var checkboxes = $(this).parent().find('.checkboxlistitem');
                    var checkedboxes = checkboxes.filter(':checked');

                    if(checkboxes.length === checkedboxes.length) {
                        $(this).parent().find('.select-all').prop('checked', true);
                    } else {
                        $(this).parent().find('.select-all').prop('checked', false);
                    }
                });*/

                // $('.nav-tabs li a').each(function() {
                //     // array.push($(this).attr('data-id'));
                //     console.log($(this).attr('data-tabkey'));
                //     // console.log('fff');
                //     if ($('.nav-tabs li').hasClass('active')) {
                //         console.log(this);
                //     }
                // })
            });

            document.addEventListener("fullscreenchange", exitHandler);
            document.addEventListener("webkitfullscreenchange", exitHandler);
            document.addEventListener("mozfullscreenchange", exitHandler);
            document.addEventListener("MSFullscreenChange", exitHandler);

            function exitHandler() {
                if (!document.fullscreenElement && !document.webkitIsFullScreen && !document.mozFullScreen && !document.msFullscreenElement) {
                    $("#openfullscreen").css("display", "none");
                }
            }
            var elem = document.getElementById("viewdb");
            //var elem = document.getElementById("header");
            var elem = document.getElementById("fullscreen");
            function openFullscreen() {
                if (elem.requestFullscreen) {
                    elem.requestFullscreen();
                } else if (elem.webkitRequestFullscreen) {
                    /* Safari */
                    elem.webkitRequestFullscreen();
                } else if (elem.msRequestFullscreen) {
                    /* IE11 */
                    elem.msRequestFullscreen();
                }
                $("#fullscreen").css("overflow", "auto");

                $("#fullscreen").addClass("fullscreenin");

                $("#openfullscreen").css("display", "block");

                $(".nav-tabs.navfullscreen li").css("display", "none");
                $(".nav-tabs.navfullscreen li.active").css("display", "block");

                //$('#viewdb').toggleClass('fullscreen');
                var rightsidebar = document.getElementById("rightsidebar");
                var leftcontent = document.getElementById("leftcontent");

                rightsidebar.style.display = "none";

                if (rightsidebar.style.display == "none") {
                    leftcontent.style.marginRight = "0%";
                    document.getElementById("myImage").src = "<?php echo site_assets_url('images/icons/icon_chevronsleft.png'); ?>";
                    $(".btndbactive").removeClass("active");
                    rightsidebar.style.width = "350px";
                    document.getElementById("pinimg").src = "<?php echo site_assets_url('images/icons/pin_01_grey.png'); ?>";
                }
            }

            function closeFullscreen() {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    /* Safari */
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) {
                    /* IE11 */
                    document.msExitFullscreen();
                }
                $("#fullscreen").removeClass("fullscreenin");
                $("#fullscreen").addClass("fullscreenout");
                $("#openfullscreen").css("display", "none");
                $("#fullscreen").css("overflow", "hidden");
            }
        </script>
    </div>
</div>

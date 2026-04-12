<style type="text/css">
    @font-face {
        font-family: PromptMedium;
        src: url(assets/fonts/Prompt-Medium.ttf);
    }

    .header {
        padding: 10px 10px 10px 10px;
        font-size: 16px;
    }

    .header a {
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
        height: 30px;
        padding: 5px;
    }
    .grid-stack .grid-stack-item .grid-stack-item-content {
        overflow: hidden;
    }

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
        cursor: default;
        border-radius: 0px;
    }
    .nav-tabs > li > a:hover,
    .nav-tabs > li > a:focus {
        color: #2b2b2b;
        background-color: #fef0e7;
        border: 1px solid #e97126;
    }
    .nav-tabs li.active i,
    .nav-tabs > li > a:hover > i,
    .nav-tabs > li > a:focus > i {
        color: #555;
    }

    .panel-heading {
        cursor: move;
    }
    .panel-body {

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
    .modal-content {
        border-radius: 0;
        border: none;
    }

    .modal-header {
        padding: 20px 16px;
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
    }

    .panel-clrom {
       
    }

    .panel-clrom.on {
        
    }

    
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

    .container2 input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 15px;
        width: 15px;
        background-color: #eee;
    }

    .container2 input:checked ~ .checkmark {
        background-color: #e97126;
    }

    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    
    .container2 input:checked ~ .checkmark:after {
        display: block;
    }

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
        margin-top: 68px;
        z-index: 50;
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

    .dropdownchart a:hover {
        background-color: transparent;
    }

    .mailbox a:hover {
        background-color: #ededed;
    }

    fullscreenin {
        top: 5%;
        position: relative;
        height: 100%;
        transition: top 0.5s ease-in-out;
    }
    .fullscreenout {
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
<style type="text/css">
    .footer, .page-wrapper{
      margin-left : auto;  
    }
    .btn-search{
        background-color: #E97126;
        border-color : #E97126;
        color:#fff;
    }
    .btn-search:hover {
        background-color: #ee8341;
        border-color : #ee8341;
        color: #fff;
    }
    .btn-export{
        background-color: #E97126;
        border-color : #E97126;
        color:#fff;
    }
    .btn-export:hover {
        background-color: #ee8341;
        border-color : #ee8341;
        color: #fff;
    }

    .btn-print{
        background-color: #818181;
        border-color : #818181;
        color:#fff;
    }
    .btn-print:hover {
        background-color: #a0a0a0;
        border-color : #a0a0a0;
        color: #fff;
    }

    .btn-primary{
        background-color: #357ebd;
        border-color: #357ebd;
    }
    .btn-primary:hover{
        background-color: #357ebd;
        border-color: #357ebd;
        color: #fff;
    }
    .btn-primary:not(:disabled):not(.disabled).active, .btn-primary:not(:disabled):not(.disabled):active, .show > .btn-primary.dropdown-toggle {
        color: #fff;
        background-color: #357ebd;
        border-color: #357ebd;
    }
    .vl {
        margin-left: 10px;
        margin-right: 10px;
        border-left: 2px solid #e2e2e2;
        height: 30px;
        margin-top: 3px;

    }
    .filetype{
        border: 1px solid #e97126 !important;
        background-color: #fff9f5 !important;
        color: #e97126 !important;
        font-weight: 500 !important;
    }
</style>

<div class="page-wrapper">

    <div class="container-fluid" id="header" style="background-color: #fff;">
        <div class="header" id="opendefaultscreen">
            <div class="col-12">
                
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <p style="font-family: PromptMedium; color: #2b2b2b; font-weight: 500; font-size: 16px !important;">
                                Dashboard
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            
                            <div class="col-sm-6" style="margin: auto;">
                                <ul class="nav nav-tabs">                                
                                    <li class="active"><a data-toggle="tab" data-tabkey="lead" href="#lead" data-desciption="" data-owner="">Leads Dashboard</a></li>
                                    <li class=""><a data-toggle="tab" data-tabkey="statisyical" href="#statisyical" data-desciption="" data-owner="">Chat Statistical</a></li>
                                </ul>
                            </div>

                            <div class="col-sm-6">
                                <div class="pull-right" style="display: -webkit-inline-box; float: right;">
                                    <div>
                                        <label style="display: inline; font-size: 12px; font-family: PromptMedium; font-weight: 400;">เลือกช่วงเวลา</label>&nbsp;
                                        <input class="form-control input-daterange-datepicker" type="text" id="daterange" name="daterange" style="display: inline-block; width: 200px; color: #018ffb; box-shadow: 0px 0px 2px 0px rgba(0, 0, 0, 0.2); border: 0px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div style="border-bottom: 1px solid #ddd;"></div>
    </div>

    <style type="text/css">
	    .grid-stack .grid-stack-item .grid-stack-item-content {
		    overflow: hidden !important;
		}
		.btn-xs, .btn-group-xs>.btn {
		    padding: 1px 5px;
		    font-size: 12px;
		    line-height: 1.5;
		    border-radius: 3px;
		}
    </style>

    <div class="container-fluid" id="viewdb">
        <div id="leftcontent">
            <div class="tab-content">

                <div id="lead" class="tab-pane fade in lead show active">
                    <div class="row grid-stack">

                        <div class="grid-stack-item ui-draggable ui-resizable ui-resizable-autohide" data-id="33" data-tabkey="lead" data-type="grid" data-tab="lead" data-chartid="1" data-gs-id="item_33" data-gs-x="0" data-gs-y="0" data-gs-width="3" data-gs-min-width="3" data-gs-height="3" data-gs-min-height="4" style="font-size: 14px;" >
						    <div class="grid-stack-item-content box-border chartcontent" style="font-size: 14px;">
						        <div class="panel-heading clearfix headingchart ui-draggable-handle" style="font-size: 14px;">
						            <span class="panel-title pull-left">จำนวนผู้มุ่งหวังต่อวัน</span>

						            <div class="pull-right widget-buttons" style="font-size: 14px;">
						                <button class="btn btn-xs" onclick="reload_leadday(this)" style="font-size: 14px;">
						                    <i class="fa fa-redo"></i>
						                </button>
						            </div>
						        </div>
						        <div class="panel-body" style="overflow: auto; font-size: 14px;">
						            <div id="graph_lead_33" class="item_graph" style="font-size: 14px;"></div>
						            <div id="chartline_leadday" style="font-size: 14px;"></div>
						            <div id="chartarea_leadday" style="font-size: 14px;"></div>
						        </div>
						    </div>
						</div>

                        <div class="grid-stack-item ui-draggable ui-resizable" data-id="33" data-tabkey="lead" data-type="grid" data-tab="lead" data-chartid="2" data-gs-id="item_33" data-gs-x="3" data-gs-y="0" data-gs-width="3" data-gs-min-width="3" data-gs-height="3" data-gs-min-height="4" style="font-size: 14px;">
						    <div class="grid-stack-item-content box-border chartcontent" style="font-size: 14px;">
						        <div class="panel-heading clearfix headingchart ui-draggable-handle" style="font-size: 14px;">
						            <span class="panel-title pull-left">แสดงจำนวนผู้มุ่งหวังทั้งหมดรายสัปดาห์</span>

						            <div class="pull-right widget-buttons" style="font-size: 14px;">
						                <button class="btn btn-xs" onclick="reload_leadweek(this)" style="font-size: 14px;">
						                    <i class="fa fa-redo"></i>
						                </button>
						            </div>
						        </div>
						        <div class="panel-body" style="overflow: auto; font-size: 14px;">
						            <div id="graph_leadweek" class="item_graph" style="font-size: 14px;"></div>
						            <!-- <div id="chartline_lead_33" style="font-size: 14px;"></div>
						            <div id="chartarea_lead_33" style="font-size: 14px;"></div> -->
						        </div>
						    </div>
						</div>

						<div class="grid-stack-item ui-draggable ui-resizable ui-resizable-autohide" data-id="33" data-tabkey="lead" data-type="grid" data-tab="lead" data-chartid="3" data-gs-id="item_33" data-gs-x="6" data-gs-y="0" data-gs-width="6" data-gs-min-width="3" data-gs-height="5" data-gs-min-height="3" style="font-size: 14px;">
						    <div class="grid-stack-item-content box-border chartcontent" style="font-size: 14px;">
						        <div class="panel-heading clearfix headingchart ui-draggable-handle" style="font-size: 14px;">
						            <span class="panel-title pull-left">แสดงจำนวนผู้มุ่งหวังทั้งหมดรายเดือน</span>

						            <div class="pull-right widget-buttons" style="font-size: 14px;">
						                <button class="btn btn-xs" onclick="reload_leadmonth(this)" style="font-size: 14px;">
						                    <i class="fa fa-redo"></i>
						                </button>
						            </div>
						        </div>
						        <div class="panel-body" style="overflow: auto; font-size: 14px;">
						            <div id="graph_leadmonth" class="item_graph" style="font-size: 14px;"></div>
						        </div>
						    </div>
						</div>

						<div class="grid-stack-item ui-draggable ui-resizable ui-resizable-autohide" data-id="33" data-tabkey="lead" data-type="grid" data-tab="lead" data-chartid="4" data-gs-id="item_33" data-gs-x="0" data-gs-y="4" data-gs-width="6" data-gs-min-width="3" data-gs-height="5" data-gs-min-height="3" style="font-size: 14px;">
						    <div class="grid-stack-item-content box-border chartcontent" style="font-size: 14px;">
						        <div class="panel-heading clearfix headingchart ui-draggable-handle" style="font-size: 14px;">
						            <span class="panel-title pull-left">แสดงจำนวนผู้มุ่งหวังทั้งหมดตามสถานะรายเดือน</span>

						            <div class="pull-right widget-buttons" style="font-size: 14px;">
						                <button class="btn btn-xs" onclick="reload_leadstatusmonth(this)" style="font-size: 14px;">
						                    <i class="fa fa-redo"></i>
						                </button>
						            </div>
						        </div>
						        <div class="panel-body" style="overflow: auto; font-size: 14px;">
						            <div id="graph_leadstatusmonth" class="item_graph" style="font-size: 14px;"></div>
						            
						        </div>
						    </div>
						</div>

						<div class="grid-stack-item ui-draggable ui-resizable ui-resizable-autohide" data-id="33" data-tabkey="lead" data-type="grid" data-tab="lead" data-chartid="5" data-gs-id="item_33" data-gs-x="6" data-gs-y="5" data-gs-width="6" data-gs-min-width="3" data-gs-height="5" data-gs-min-height="3"style="font-size: 14px;" >
						    <div class="grid-stack-item-content box-border chartcontent" style="font-size: 14px;">
						        <div class="panel-heading clearfix headingchart ui-draggable-handle" style="font-size: 14px;">
						            <span class="panel-title pull-left">แสดงจำนวนผู้มุ่งหวังทั้งหมดตามแหล่งที่มาของผู้มุ่งหวังรายเดือน</span>

						            <div class="pull-right widget-buttons" style="font-size: 14px;">
						                <button class="btn btn-xs" onclick="reload_leadsourcemonth(this)" style="font-size: 14px;">
						                    <i class="fa fa-redo"></i>
						                </button>
						            </div>
						        </div>
						        <div class="panel-body" style="overflow: auto; font-size: 14px;">
						            <div id="graph_leadsourcemonth" class="item_graph" style="font-size: 14px;"></div>
						            
						        </div>
						    </div>
						</div>

						<div class="grid-stack-item ui-draggable ui-resizable ui-resizable-autohide" data-id="33" data-tabkey="lead" data-type="grid" data-tab="lead" data-chartid="6" data-gs-id="item_33" data-gs-x="0" data-gs-y="9" data-gs-width="6" data-gs-min-width="3" data-gs-height="5" data-gs-min-height="3"style="font-size: 14px;" >
						    <div class="grid-stack-item-content box-border chartcontent" style="font-size: 14px;">
						        <div class="panel-heading clearfix headingchart ui-draggable-handle" style="font-size: 14px;">
						            <span class="panel-title pull-left">แสดงจำนวนผู้มุ่งหวังทั้งหมดตามแหล่งที่มาของผู้มุ่งหวังรายไตรมาส</span>

						            <div class="pull-right widget-buttons" style="font-size: 14px;">
						                <button class="btn btn-xs" onclick="reload_leadsourcequater(this)" style="font-size: 14px;">
						                    <i class="fa fa-redo"></i>
						                </button>
						            </div>
						        </div>
						        <div class="panel-body" style="overflow: auto; font-size: 14px;">
						            <div id="graph_leadquater" class="item_graph" style="font-size: 14px;"></div>
						        </div>
						    </div>
						</div>

                    </div>
                </div>

                <div id="statisyical" class="tab-pane fade in statisyical ">
                	<div class="row grid-stack">

                        <div class="grid-stack-item ui-draggable ui-resizable ui-resizable-autohide" data-id="33" data-tabkey="lead" data-type="grid" data-tab="lead" data-chartid="1" data-gs-id="item_33" data-gs-x="0" data-gs-y="0" data-gs-width="3" data-gs-min-width="4" data-gs-height="3" data-gs-min-height="3" style="font-size: 14px;" >
						    <div class="grid-stack-item-content box-border chartcontent" style="font-size: 14px;">
						        <div class="panel-heading clearfix headingchart ui-draggable-handle" style="font-size: 14px;">
						            <span class="panel-title pull-left">จำนวนผู้ติดต่อทั้งหมด</span>

						            <div class="pull-right widget-buttons" style="font-size: 14px;">
						                <button class="btn btn-xs" onclick="get_customer(this)" style="font-size: 14px;">
						                    <i class="fa fa-redo"></i>
						                </button>
						            </div>
						        </div>
						        <div class="panel-body" style="overflow: auto; font-size: 14px;">
						            <div id="graph_lead_33" class="item_graph" style="font-size: 14px;">
						            	<div class="row">
							            	<div class="col-12" style="padding: 3%; display: flex;">
							            		<div class="col-3" style="margin-top: auto;">
							            			<img src="<?php echo site_assets_url('images/icons/Icon_Users_Orange.png'); ?>" width="85%" height="90%" >
							            		</div>
							            		<div class="col-4" style="margin-top: auto;">
							            			<span class="contactall" style="font-size: 3em;color: #000" >0</span>
							            			<label style="margin-left: 10% ;font-size: 2em ;color: #000"> คน</label>
							            		</div>
							            	</div>
						            	</div>
						            </div>
						        </div>
						    </div>
						</div>
						<style type="text/css">
							label{
								font-family: PromptMedium;
							}
							spam{
								font-family: PromptMedium;
							}
						</style>
						<div class="grid-stack-item ui-draggable ui-resizable ui-resizable-autohide" data-id="33" data-tabkey="lead" data-type="grid" data-tab="lead" data-chartid="1" data-gs-id="item_33" data-gs-x="4" data-gs-y="6" data-gs-width="3" data-gs-min-width="4" data-gs-height="3" data-gs-min-height="3" style="font-size: 14px;" >
						    <div class="grid-stack-item-content box-border chartcontent" style="font-size: 14px;">
						        <div class="panel-heading clearfix headingchart ui-draggable-handle" style="font-size: 14px;">
						            <span class="panel-title pull-left">จำนวนผู้ติดต่อทางไลน์</span>

						            <div class="pull-right widget-buttons" style="font-size: 14px;">
						                <button class="btn btn-xs" onclick="get_customer(this)" style="font-size: 14px;">
						                    <i class="fa fa-redo"></i>
						                </button>
						            </div>
						        </div>
						        <div class="panel-body" style="overflow: auto; font-size: 14px;">
						            <div id="graph_lead_33" class="item_graph" style="font-size: 14px;">
						            	<div class="row">
							            	<div class="col-12" style="padding: 3%; display: flex;">
							            		<div class="col-3" style="margin-top: auto;">
							            			<img src="<?php echo site_assets_url('images/icons/Icon_User_Orange.png'); ?>" width="85%" height="90%" >
							            		</div>
							            		<div class="col-4" style="margin-top: auto;">
							            			<span class="contactline" style="font-size: 3em;color: #000" >0</span>
							            			<label style="margin-left: 10% ;font-size: 2em ;color: #000"> คน</label>
							            		</div>
							            	</div>
						            	</div>
						            </div>
						        </div>
						    </div>
						</div>

						<div class="grid-stack-item ui-draggable ui-resizable ui-resizable-autohide" data-id="33" data-tabkey="lead" data-type="grid" data-tab="lead" data-chartid="1" data-gs-id="item_33" data-gs-x="8" data-gs-y="0" data-gs-width="3" data-gs-min-width="4" data-gs-height="3" data-gs-min-height="3" style="font-size: 14px;" >
						    <div class="grid-stack-item-content box-border chartcontent" style="font-size: 14px;">
						        <div class="panel-heading clearfix headingchart ui-draggable-handle" style="font-size: 14px;">
						            <span class="panel-title pull-left">จำนวนผู้ติดต่อทางเฟสบุ๊ค</span>

						            <div class="pull-right widget-buttons" style="font-size: 14px;">
						                <button class="btn btn-xs" onclick="get_customer(this)" style="font-size: 14px;">
						                    <i class="fa fa-redo"></i>
						                </button>
						            </div>
						        </div>
						        <div class="panel-body" style="overflow: auto; font-size: 14px;">
						            <div id="graph_lead_33" class="item_graph" style="font-size: 14px;">
						            	<div class="row">
							            	<div class="col-12" style="padding: 3%; display: flex;">
							            		<div class="col-3" style="margin-top: auto;">
							            			<img src="<?php echo site_assets_url('images/icons/Icon_User_Orange.png'); ?>" width="85%" height="90%" >
							            		</div>
							            		<div class="col-4" style="margin-top: auto;">
							            			<span class="contactfacebook" style="font-size: 3em;color: #000" >0</span>
							            			<label style="margin-left: 10% ;font-size: 2em ;color: #000"> คน</label>
							            		</div>
							            	</div>
						            	</div>
						            </div>
						        </div>
						    </div>
						</div>

						<div class="grid-stack-item ui-draggable ui-resizable" data-id="33" data-tabkey="lead" data-type="grid" data-tab="lead" data-chartid="1" data-gs-id="item_33" data-gs-x="0" data-gs-y="3" data-gs-width="6" data-gs-min-width="4" data-gs-height="5" data-gs-min-height="3" style="font-size: 14px;" >
						    <div class="grid-stack-item-content box-border chartcontent" style="font-size: 14px;">
						        <div class="panel-heading clearfix headingchart ui-draggable-handle" style="font-size: 14px;">
						            <span class="panel-title pull-left">แสดงจำนวนผู้ติดต่อทั้งหมด รายวัน เทียบกับ 7 วันที่แล้ว</span>

						            <div class="pull-right widget-buttons" style="font-size: 14px;">
						                <button class="btn btn-xs" onclick="$.refreshBox(this)" style="font-size: 14px;">
						                    <i class="fa fa-redo"></i>
						                </button>
						            </div>
						        </div>
						        <div class="panel-body" style="overflow: auto; font-size: 14px;">
						            <div id="graph_lead7day" class="item_graph" style="font-size: 14px;"></div>
						        </div>
						    </div>
						</div>

						<div class="grid-stack-item ui-draggable ui-resizable ui-resizable-autohide" data-id="33" data-tabkey="lead" data-type="grid" data-tab="lead" data-chartid="1" data-gs-id="item_33" data-gs-x="6" data-gs-y="3" data-gs-width="6" data-gs-min-width="4" data-gs-height="5" data-gs-min-height="3" style="font-size: 14px;">
							<div class="grid-stack-item-content box-border chartcontent" style="font-size: 14px;">
							    <div class="panel-heading clearfix headingchart ui-draggable-handle" style="font-size: 14px;">
							        <span class="panel-title pull-left">แสดงจำนวนผู้ติดต่อทั้งหมดตามแต่ละวัน รายชั่วโมง</span>

							        <div class="pull-right widget-buttons" style="font-size: 14px;">
							            <button class="btn btn-xs" onclick="$.refreshBox(this)" style="font-size: 14px;">
							                <i class="fa fa-redo"></i>
							            </button>
							        </div>
							    </div>
							    <div class="panel-body" style="overflow: auto; font-size: 14px;">
							        <div id="graph_chatheatmap" class="item_graph" style="font-size: 14px;"></div>
							    </div>
							</div>    
						</div>

						<div class="grid-stack-item ui-draggable ui-resizable" data-id="33" data-tabkey="lead" data-type="grid" data-tab="lead" data-chartid="1" data-gs-id="item_33" data-gs-x="0" data-gs-y="8" data-gs-width="6" data-gs-min-width="4" data-gs-height="5" data-gs-min-height="3" style="font-size: 14px;">
						    <div class="grid-stack-item-content box-border chartcontent" style="font-size: 14px;">
							    <div class="panel-heading clearfix headingchart ui-draggable-handle" style="font-size: 14px;">
							        <span class="panel-title pull-left">แสดงจำนวนข้อความทั้งหมด, ไลน์, เฟชบุ๊ค</span>

							        <div class="pull-right widget-buttons" style="font-size: 14px;">
							            <button class="btn btn-xs" onclick="get_customer(this)" style="font-size: 14px;">
							                <i class="fa fa-redo"></i>
							            </button>
							        </div>
							    </div>
							    <style type="text/css">
							    	ul {
									    font-size: 35px;
									    list-style-type: square;
									    list-style-position: inside;
									}
									ul.a li::marker {
									  color: #2dd22f;
									  /*font-size: 30px;*/
									}
									ul.b li::marker {
									  color: #018ffb;
									}
									li > * {
									    vertical-align: text-top;
									}
							    </style>
							    <div class="panel-body" style="overflow: auto; font-size: 14px;">
						            <div id="block_all_chat" class="item_graph" style="font-size: 14px;">
						            	<div class="row">
						            		<div class="col-12" style="padding: 2% 0% 0% 4%; display: flex;">
						            			<span class="panel-title pull-left">จำนวนข้อความทั้งหมด</span>
						            		</div>
							            	<div class="col-12" style="padding: 1% 1% 1% 3%;; display: flex;">
							            		<div class="col-3" style="margin-top: auto;">
							            			<img src="<?php echo site_assets_url('images/icons/Icon_Tray_Orange.png'); ?>" width="70%" height="80%" >
							            		</div>
							            		<div class="col-4" style="margin-top: auto;">
							            			<span class="chatall" style="font-size: 4em;color: #000" >0</span>
							            			<label style="margin-left: 10% ;font-size: 2em ;color: #000"> ข้อความ</label>
							            		</div>
							            	</div>
							            	
							            	<div class="col-12" style="padding: 1% 1% 1% 3%;; display: flex;">
							            		<ul class="a">
												  <li><label style="vertical-align: middle;font-size: 20px">ไลน์</label></li>
												</ul>
												<ul class="b">
												  <li><label style="vertical-align: middle;font-size: 20px">เฟสบุ๊ค</label></li>
												</ul>
							            	</div>

							            	<div class="col-12" style="padding: 1% 3% 1% 3%;; display: flex;">
							            		<div id="chatfacebook" style="height: 35px; text-align: center; background-color: #018ffb;padding: inherit;">
							            			<span id="persenfacebook" style="color: #fff">0%</span></div>
							            		<div id="chatline" style="height: 35px; text-align: center; background-color: #2dd22f;padding: inherit;">
							            			<span id="persenline" style="color: #fff">0%</span></div>
							            	</div>

						            	</div>
						            </div>
						        </div>

							</div>
						</div>


					</div>
                </div>

            </div>
        </div>
    </div>

    <div id="chart-container"></div>

</div>


<style type="text/css">
    .paging_simple_numbers .pagination .paginate_button.active a, .paging_simple_numbers .pagination .paginate_button:hover a {
        background: #e97126;
        color: #fff;
    }
    table.dataTable tbody td {
        word-break: break-word;
        vertical-align: top;
    }
</style>

<link rel="stylesheet" href="<?php echo site_assets_url('DataTables/css/dataTables.bootstrap4.css');?>">
<link href="<?php echo site_url('assets/jquery-ui-1.12.1/jquery-ui.min.css'); ?>" rel="stylesheet">

<!-- Kendo UI Demo -->
<link href="<?php echo site_url('assets/kendoui/styles/kendo.common.min.css'); ?>" rel="stylesheet">
<link href="<?php echo site_url('assets/kendoui/styles/kendo.rtl.min.css'); ?>" rel="stylesheet">
<link href="<?php echo site_url('assets/kendoui/styles/kendo.bootstrap.min.css'); ?>" rel="stylesheet">
<link href="<?php echo site_url('assets/kendoui/styles/kendo.bootstrap.mobile.min.css'); ?>" rel="stylesheet">

<!-- Custom fonts for this template -->
<link href="<?php echo site_url('assets/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo site_url('assets/fontawesome-free/css/v4-shims.min.css'); ?>" rel="stylesheet" type="text/css">

<script src="<?php echo site_url('assets/jquery/jquery.min.js'); ?>"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> -->
<script src="<?php echo site_url('assets/jquery-ui-1.12.1/jquery-ui.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/bootstrap3/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/lodash.js'); ?>"></script>

<script src="<?php echo site_url('assets/kendoui/js/jszip.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/kendoui/js/kendo.all.min.js'); ?>"></script>

<script type="text/javascript" src="<?php echo site_url('assets/fusioncharts/fusioncharts.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('assets/fusioncharts/fusioncharts.charts.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('assets/fusioncharts/themes/fusioncharts.theme.fint.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('assets/fusioncharts/themes/fusioncharts.theme.ocean.js'); ?>"></script>

<link href="<?php echo site_url('assets/smartwizard/css/smart_wizard.css'); ?>" rel="stylesheet">
<link href="<?php echo site_url('assets/smartwizard/css/smart_wizard_theme_arrows.css'); ?>" rel="stylesheet">
<script type="text/javascript" src="<?php echo site_url('assets/smartwizard/js/jquery.smartWizard.js'); ?>"></script>

<link href="<?php echo site_url('assets/gridstack/gridstack.min.css'); ?>" rel="stylesheet" type="text/css">
<script src="<?php echo site_url('assets/gridstack/gridstack.js'); ?>"></script>
<script src="<?php echo site_url('assets/gridstack/gridstack.jQueryUI.min.js'); ?>"></script>

<script src="<?php echo site_url('assets/loadingoverlay.js'); ?>"></script>

<link rel="stylesheet" type="text/css" href="<?php echo site_assets_url('apexcharts/dist/apexcharts.css');?>" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<!-- <script src="<?php //echo site_assets_url('apexcharts/dist/apexcharts.js');?>"></script> -->
<script src="<?php echo site_assets_url('apexcharts/dist/apexcharts.min.js');?>"></script>

<script type="text/javascript">

$(document).ready(function(){
    var startdate;
    var enddate;

    $('input[name="daterange"]').daterangepicker(
        {
            opens: "left",
            locale: {
                format: "DD/MM/YYYY",
            },
        },
        function (start, end, label) {
            var startdate = start.format("YYYY-MM-DD");
            var enddate = end.format("YYYY-MM-DD");
            
            getChartDatadate(startdate, enddate);

        }
    );
  
    function numberWithCommas(number) {
        var parts = number.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(".");
    }
    get_leadday();
    get_leadweek();
    get_leadmonth();
    get_leadstatusmonth();
    get_leadsourcemonth();
    get_leadsourcequater();
    get_lead7day();
    get_chatheatmap();
    get_customer();
  
});
</script>



<script type="text/javascript">

	function get_leadday(){
		
		var url = "<?php echo site_url('omnidashboard/getleadday'); ?>";
	        var data = [];
	      
	        $.ajax(url, {
	            type: "POST",
	            data: '',
	            success: function (data) {

	                var rs = jQuery.parseJSON(data);
	         
	                var type = 'brush-chart'
                    
                    $('#chartline_leadday').chartGenerate(type, rs);
                    $('#chartarea_leadday').chartGenerate(type, rs);

	            },
	            error: function (data) {
	                console.log("f");
	            },
	        });
	}
    function get_leadweek(){
        
        var url = "<?php echo site_url('omnidashboard/getleadweek'); ?>";
            var data = [];
          
            $.ajax(url, {
                type: "POST",
                data: '',
                success: function (data) {

                    var rs = jQuery.parseJSON(data);
             
                    var type = 'line-basic'
                    
                    $('#graph_leadweek').chartGenerate(type, rs);

                },
                error: function (data) {
                    console.log("f");
                },
            });   
    }
    function get_leadmonth(){
        
        var url = "<?php echo site_url('omnidashboard/getleadmonth'); ?>";
            var data = [];
          
            $.ajax(url, {
                type: "POST",
                data: '',
                success: function (data) {

                    var rs = jQuery.parseJSON(data);
             
                    //var type = 'line-basic'
                    var type = 'column-with-data-labels'
                    $('#graph_leadmonth').chartGenerate(type, rs);

                },
                error: function (data) {
                    console.log("f");
                },
            });   
    }
    function get_leadstatusmonth(){
        
        var url = "<?php echo site_url('omnidashboard/getleadstatusmonth'); ?>";
            var data = [];
          
            $.ajax(url, {
                type: "POST",
                data: '',
                success: function (data) {

                    var rs = jQuery.parseJSON(data);
             
                    var type = 'column-basic'
                    
                    $('#graph_leadstatusmonth').chartGenerate(type, rs);

                },
                error: function (data) {
                    console.log("f");
                },
            });   
    }
    function get_leadsourcemonth(){
        
        var url = "<?php echo site_url('omnidashboard/getleadsourcemonth'); ?>";
            var data = [];
          
            $.ajax(url, {
                type: "POST",
                data: '',
                success: function (data) {

                    var rs = jQuery.parseJSON(data);
                    //console.log(rs);
                    var type = 'stacked-columns'
                    
                    $('#graph_leadsourcemonth').chartGenerate(type, rs);

                },
                error: function (data) {
                    console.log("f");
                },
            });   
    }
    function get_leadsourcequater(){
        
        var url = "<?php echo site_url('omnidashboard/getleadsourcequater'); ?>";
            var data = [];
          
            $.ajax(url, {
                type: "POST",
                data: '',
                success: function (data) {

                    var rs = jQuery.parseJSON(data);
             
                    var type = 'stacked-columns'
                    //console.log(rs);
                    $('#graph_leadquater').chartGenerate(type, rs);

                },
                error: function (data) {
                    console.log("f");
                },
            });   
    }
    function get_chatheatmap(){
    	var url = "<?php echo site_url('omnidashboard/getleadmonth'); ?>";
            var data = [];
          
            $.ajax(url, {
                type: "POST",
                data: '',
                success: function (data) {

                    var rs = jQuery.parseJSON(data);
             
                    var type = 'heatmap'
    				$('#graph_chatheatmap').chartGenerate(type, rs);

                },
                error: function (data) {
                    console.log("f");
                },
            });   
    }
    function get_lead7day(){
    	var url = "<?php echo site_url('omnidashboard/getleadmonth'); ?>";
        var data = [];
      
        $.ajax(url, {
            type: "POST",
            data: '',
            success: function (data) {

                var rs = jQuery.parseJSON(data);
         
                type ='line-charts-dashed';
				$('#graph_lead7day').chartGenerate(type, rs);

            },
            error: function (data) {
                console.log("f");
            },
        }); 
    }
    function get_customer(){
    	var url = "<?php echo site_url('omnidashboard/get_datacustomer'); ?>";
        var data = [];
      
        $.ajax(url, {
            type: "POST",
            data: '',
            success: function (data) {

                var rs = jQuery.parseJSON(data);
         		//$('.contactall').html(rs.data);
         		$('.contactline').html(rs.line);
         		$('.contactfacebook').html(rs.facebook);
         		$('.chatall').html(rs.chat);

         		$("#persenfacebook").html(rs.persenfacebook+'%');
         		$("#chatfacebook").css('width',rs.persenfacebook+"%");
         		$("#persenline").html(rs.persenline+'%');
         		$("#chatline").css('width',rs.persenline+"%");
         		
			    $('.contactall').html(rs.data);

			    $('.contactall').each(function () {
				  var $this = $(this);
				  jQuery({ Counter: 0 }).animate({ Counter: $this.text() }, {
				    duration: 1000,
				    easing: 'swing',
				    step: function () {
				      //$this.text(Math.ceil(this.Counter));
				      $this.text(commaSeparateNumber(Math.round(this.Counter)));
				    }
				  });
				});
				$('.contactline').each(function () {
				  var $this = $(this);
				  jQuery({ Counter: 0 }).animate({ Counter: $this.text() }, {
				    duration: 1000,
				    easing: 'swing',
				    step: function () {
				      //$this.text(Math.ceil(this.Counter));
				      $this.text(commaSeparateNumber(Math.round(this.Counter)));
				    }
				  });
				});
				$('.contactfacebook').each(function () {
				  var $this = $(this);
				  jQuery({ Counter: 0 }).animate({ Counter: $this.text() }, {
				    duration: 1000,
				    easing: 'swing',
				    step: function () {
				      //$this.text(Math.ceil(this.Counter));
				      $this.text(commaSeparateNumber(Math.round(this.Counter)));
				    }
				  });
				});
				$('.chatall').each(function () {
				  var $this = $(this);
				  jQuery({ Counter: 0 }).animate({ Counter: $this.text() }, {
				    duration: 2000,
				    easing: 'swing',
				    step: function () {
				      $this.text(commaSeparateNumber(Math.round(this.Counter)));
				    }
				  });
				});

                
            },
            error: function (data) {
                console.log("f");
            },
        }); 
    }
    function commaSeparateNumber(val){
      while (/(\d+)(\d{3})/.test(val.toString())){
        val = val.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
      }
      return val;
    }
    
    /*function Action_Function() {
        document.getElementById("myDropdown").classList.toggle("show");
    }*/

    function reload_leadday(){
    	var start = $('#daterange').data('daterangepicker').startDate._d;
        var end = $('#daterange').data('daterangepicker').endDate._d;
        var s_dd = String(start.getDate()).padStart(2, '0');
        var s_mm = String(start.getMonth() + 1).padStart(2, '0');
        var s_yyyy = start.getFullYear();
        var e_dd = String(end.getDate()).padStart(2, '0');
        var e_mm = String(end.getMonth() + 1).padStart(2, '0');
        var e_yyyy = end.getFullYear();
        var t_start = s_yyyy+"-"+s_mm+"-"+s_dd;
        var t_end = e_yyyy+"-"+e_mm+"-"+e_dd;

		var data = {
      		startdate : t_start,
      		enddate : t_end,
    	}

		var url = "<?php echo site_url('omnidashboard/getleadday'); ?>";
	 
	      
	        $.ajax(url, {
	            type: "POST",
	            data: data,
	            success: function (data) {

	                var rs = jQuery.parseJSON(data);
	         
	                var type = 'brush-chart'
                    
                    $('#chartline_leadday').chartGenerate(type, rs);
                    $('#chartarea_leadday').chartGenerate(type, rs);

	            },
	            error: function (data) {
	                console.log("f");
	            },
	        });
	}
	function reload_leadweek(){
		var start = $('#daterange').data('daterangepicker').startDate._d;
        var end = $('#daterange').data('daterangepicker').endDate._d;
        var s_dd = String(start.getDate()).padStart(2, '0');
        var s_mm = String(start.getMonth() + 1).padStart(2, '0');
        var s_yyyy = start.getFullYear();
        var e_dd = String(end.getDate()).padStart(2, '0');
        var e_mm = String(end.getMonth() + 1).padStart(2, '0');
        var e_yyyy = end.getFullYear();
        var t_start = s_yyyy+"-"+s_mm+"-"+s_dd;
        var t_end = e_yyyy+"-"+e_mm+"-"+e_dd;

		var data = {
      		startdate : t_start,
      		enddate : t_end,
    	}
    	var url = "<?php echo site_url('omnidashboard/getleadweek'); ?>";

      
        $.ajax(url, {
            type: "POST",
            data: data,
            success: function (data) {

                var rs = jQuery.parseJSON(data);
         
                var type = 'line-basic'
                
                $('#graph_leadweek').chartGenerate(type, rs);

            },
            error: function (data) {
                console.log("f");
            },
        });
	}
	function reload_leadmonth(){
		var start = $('#daterange').data('daterangepicker').startDate._d;
        var end = $('#daterange').data('daterangepicker').endDate._d;
        var s_dd = String(start.getDate()).padStart(2, '0');
        var s_mm = String(start.getMonth() + 1).padStart(2, '0');
        var s_yyyy = start.getFullYear();
        var e_dd = String(end.getDate()).padStart(2, '0');
        var e_mm = String(end.getMonth() + 1).padStart(2, '0');
        var e_yyyy = end.getFullYear();
        var t_start = s_yyyy+"-"+s_mm+"-"+s_dd;
        var t_end = e_yyyy+"-"+e_mm+"-"+e_dd;

		var data = {
      		startdate : t_start,
      		enddate : t_end,
    	}
    	var url = "<?php echo site_url('omnidashboard/getleadmonth'); ?>";
      
        $.ajax(url, {
            type: "POST",
            data: data,
            success: function (data) {

                var rs = jQuery.parseJSON(data);

                var type = 'column-with-data-labels'
                $('#graph_leadmonth').chartGenerate(type, rs);

            },
            error: function (data) {
                console.log("f");
            },
        });
	}
	function reload_leadstatusmonth(){
        var start = $('#daterange').data('daterangepicker').startDate._d;
        var end = $('#daterange').data('daterangepicker').endDate._d;
        var s_dd = String(start.getDate()).padStart(2, '0');
        var s_mm = String(start.getMonth() + 1).padStart(2, '0');
        var s_yyyy = start.getFullYear();
        var e_dd = String(end.getDate()).padStart(2, '0');
        var e_mm = String(end.getMonth() + 1).padStart(2, '0');
        var e_yyyy = end.getFullYear();
        var t_start = s_yyyy+"-"+s_mm+"-"+s_dd;
        var t_end = e_yyyy+"-"+e_mm+"-"+e_dd;

		var data = {
      		startdate : t_start,
      		enddate : t_end,
    	}
        var url = "<?php echo site_url('omnidashboard/getleadstatusmonth'); ?>";
        
        $.ajax(url, {
            type: "POST",
            data: data,
            success: function (data) {

                var rs = jQuery.parseJSON(data);
         
                var type = 'column-basic'
                
                $('#graph_leadstatusmonth').chartGenerate(type, rs);

            },
            error: function (data) {
                console.log("f");
            },
        });   
    }
    function reload_leadsourcemonth(){
        var start = $('#daterange').data('daterangepicker').startDate._d;
        var end = $('#daterange').data('daterangepicker').endDate._d;
        var s_dd = String(start.getDate()).padStart(2, '0');
        var s_mm = String(start.getMonth() + 1).padStart(2, '0');
        var s_yyyy = start.getFullYear();
        var e_dd = String(end.getDate()).padStart(2, '0');
        var e_mm = String(end.getMonth() + 1).padStart(2, '0');
        var e_yyyy = end.getFullYear();
        var t_start = s_yyyy+"-"+s_mm+"-"+s_dd;
        var t_end = e_yyyy+"-"+e_mm+"-"+e_dd;

		var data = {
      		startdate : t_start,
      		enddate : t_end,
    	}
        var url = "<?php echo site_url('omnidashboard/getleadsourcemonth'); ?>";          
        $.ajax(url, {
            type: "POST",
            data: data,
            success: function (data) {

                var rs = jQuery.parseJSON(data);
                //console.log(rs);
                var type = 'stacked-columns'
                
                $('#graph_leadsourcemonth').chartGenerate(type, rs);

            },
            error: function (data) {
                console.log("f");
            },
        });   
    }
    function reload_leadsourcequater(){
        var start = $('#daterange').data('daterangepicker').startDate._d;
        var end = $('#daterange').data('daterangepicker').endDate._d;
        var s_dd = String(start.getDate()).padStart(2, '0');
        var s_mm = String(start.getMonth() + 1).padStart(2, '0');
        var s_yyyy = start.getFullYear();
        var e_dd = String(end.getDate()).padStart(2, '0');
        var e_mm = String(end.getMonth() + 1).padStart(2, '0');
        var e_yyyy = end.getFullYear();
        var t_start = s_yyyy+"-"+s_mm+"-"+s_dd;
        var t_end = e_yyyy+"-"+e_mm+"-"+e_dd;

		var data = {
      		startdate : t_start,
      		enddate : t_end,
    	}
        var url = "<?php echo site_url('omnidashboard/getleadsourcequater'); ?>";
 
        $.ajax(url, {
            type: "POST",
            data: data,
            success: function (data) {

                var rs = jQuery.parseJSON(data);
         
                var type = 'stacked-columns'
                //console.log(rs);
                $('#graph_leadquater').chartGenerate(type, rs);

            },
            error: function (data) {
                console.log("f");
            },
        });   
    }
    function getChartDatadate(startdate, enddate){
    	reload_leadday();
    	reload_leadweek();
    	reload_leadmonth();
    	reload_leadstatusmonth();
    	reload_leadsourcemonth();
    	reload_leadsourcequater();
    	get_customer();
    }
    // Close the dropdown if the user clicks outside of it
    /*window.onclick = function (event) {
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
    };*/

    /*function openRightMenu() {
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
            document.getElementById("myImage").src = "<?php echo site_assets_url('images/icons/chevronsright_orange.png'); ?>";
            $(".btndbactive").addClass("active");
        }
    }*/

    /*function pinRightMenu() {
        var rightsidebar = document.getElementById("rightsidebar");
        var leftcontent = document.getElementById("leftcontent");

        if (leftcontent.style.marginRight == "15%") {
            leftcontent.style.marginRight = "0%";
            rightsidebar.style.width = "350px";
            document.getElementById("pinimg").src = "<?php echo site_assets_url('images/icons/pin_01_grey.png'); ?>";
        } else {
            leftcontent.style.marginRight = "15%";
            rightsidebar.style.width = "15%";
            document.getElementById("pinimg").src = "<?php echo site_assets_url('images/icons/pin_02_orange.png'); ?>";
        }
    }*/
</script>

<script>

    const grid_width = 3;
    const grid_height = 4;

    $('.nav-tabs li a').click(function(e){
        var tab_id = $(this).data('tabkey');
        var desciption = $(this).data('desciption');
        var owner = $(this).data('owner');

        $('#tabidhide').val(tab_id);
        var name = $(this).text();

        if (tab_id == 'lead') {
            $('.tab-content .tab-pane').removeClass('active');
            $('.tab-content .tab-pane').removeClass('in');
        } else {
            $('.tab-content .tab-pane').addClass('active');
            $('.tab-content .tab-pane').addClass('in');
        }

        //$.post('<?php echo site_url('dashboard/setTabActive'); ?>', {tab_id:tab_id}, function(rs){
           
            $('.nav-tabs.navfullscreen li').removeClass('active');
            $('.nav-tabs.navfullscreen li').css("display","none");
            $('.nav-tabs.navfullscreen li.active').css("display","block");

            if (tab_id == 'lead') {
                var a = $('<a />').attr({ 'data-toggle':'tab', 'data-tabkey':tab_id, 'href':'#lead'}).html(name);
                $(a).append(i);
                $('#dashboard_title').val(name);
                $('#desciption').val(desciption);
                $('#dbowner').val(owner);
                console.log(owner);
            } else {
                var a = $('<a />').attr({ 'data-toggle':'tab', 'data-tabkey':'statisyical', 'href':'#statisyical' }).html(name);
                $(a).append(i);
                $('#dashboard_title').val(name);
                $('#desciption').val(desciption);
                $('#dbowner').val(owner);
                console.log(owner);
            }

            var i = $('<i />',{ class:'fa fa-times' }).click(function(){
                $.removeTab(this);
            });

            var li = $('<li style="display: none;" class="active" />').append(a);

            $("#menu").append(li);

        //});
    });


    /*$.addTab = function(){
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
                var a = $('<a />').attr({ 'data-toggle':'tab', 'data-tabkey':'statisyical', 'href':'#statisyical' }).html(dashboard_title);
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
                    });
                    $('.grid-stack').on('gsresizestop', function(event, item) {
                        let tab_id = $(item).data('tab'); //console.log(tab_id)
                        let id = $(item).data('id');
                        let type = $(item).data('type');
                        let file = $(item).data('file');

                        $.post('<?php echo site_url('report/getMSData'); ?>/'+id, function(rs){
                            if (type == 'brush-chart') {
                                console.log(rs);
                                $('#chartline_'+tab_id+'_'+id).chartGenerate(type, rs);
                                $('#chartarea_'+tab_id+'_'+id).chartGenerate(type, rs);
                            } else {
                                $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs);
                            }
                        },'json')

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
        $('#tab'+tab_id).hide();

        $('.nav-tabs a[href="#lead"]').tab('show');
        $('#lead').show();

        $.post('<?php echo site_url('dashboard/removeTab'); ?>', {tab_id:tab_id}, function(){
            if (tab_id == 'lead') {
                $('.tab-content .tab-pane').removeClass('active');
                $('.tab-content .tab-pane').removeClass('in');
            } else {
                $('.tab-content .tab-pane').addClass('active');
                $('.tab-content .tab-pane').addClass('in');
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
    }*/

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


    /*$('.grid-stack').on('gsresizestop', function(event, item) {
        let tab_id = $(item).data('tab'); //console.log(tab_id)
        let id = $(item).data('id');
        let type = $(item).data('type');

        $.post('<?php echo site_url('report/getMSData'); ?>/'+id, function(rs){
            console.log(rs);
            if (type == 'brush-chart') {
                $('#chartline_'+tab_id+'_'+id).chartGenerate(type, rs);
                $('#chartarea_'+tab_id+'_'+id).chartGenerate(type, rs);
            } else if (type == 'grid') {

                $('#' + id).chartGenerate(type, rs);
            } else {
                $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs);
            }
        },'json')
    });*/

    /*$('.grid-stack').on('change', function(event, items) {
        //console.log("updateTemplate");
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

            data.push({
                tabid:tabid, type:type, id:id, chartid:chartid, x:x, y:y, width:width, height:height
            })

        });

        $.post('<?php echo site_url('dashboard/updateTemplate'); ?>', {data:data}, function(){

            $('#nodb').css('display','none');
        })
    });*/

    /*function getChartDatadate(startdate,enddate) {
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
    }*/

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

    /*$.refreshBox = function(obj){
        let el = $(obj).closest('.grid-stack-item');
        let tab_key = $(el).data('tabkey');
        let tab_id = $(el).data('tab');
        let id = $(el).data('id');
        let type = $(el).data('type');

        $.post('<?php echo site_url('report/getMSData'); ?>/'+id, function(rs){

            if (type == 'brush-chart') {
                console.log(type);
                console.log(rs);
                $('#chartline_'+tab_id+'_'+id).chartGenerate(type, rs);
                $('#chartarea_'+tab_id+'_'+id).chartGenerate(type, rs);
            } else {

                $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs);
            }
        },'json')
    }

    $.removeBox = function(obj){
        let el = $(obj).closest('.grid-stack-item');
        let tab_key = $(el).data('tabkey');
        let tab_id = $(el).data('tab');
        let id = $(el).data('id');

        let grid = $('#'+tab_id+' .grid-stack').data('gridstack');

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

        } else if (type == 'grid') {

            $(panel_body).append(graph);
            $(panel_body).append(tabledetail);
        } else {
            $(panel_body).append(graph);
        }


        $(grid_item_content).append(panel_heading);
        $(grid_item_content).append(panel_body);
        $(grid_item).append(grid_item_content);

        let grids = $('#'+tab_id+' .grid-stack').data('gridstack');
        grids.add_widget(grid_item, 1, 1, grid_width, grid_height, true, grid_width, grid_height);
    }*/

    $.genGraph = function(tab_id, tab_key, id, type, chartid){
        console.log("genGraph");


        $.post('<?php echo site_url('report/getMSData'); ?>/'+id, function(rs){
            $.genBox(tab_id, tab_key, id, type, chartid);
            $('#graph_'+tab_id+'_'+id).parents('.grid-stack-item-content').find('.panel-title').html(rs.title);

            if (type == 'brush-chart') {
                console.log(type);
                console.log(rs);

                $('#chartline_'+tab_id+'_'+id).chartGenerate(type, rs,tab_id,id);
                $('#chartarea_'+tab_id+'_'+id).chartGenerate(type, rs,tab_id,id);
            } else if (type == 'grid') {
                // console.log(type);
                $('#' + id).chartGenerate(type, rs,tab_id,id);
            } else {

                $('#graph_'+tab_id+'_'+id).chartGenerate(type, rs,tab_id,id);
            }

        },'json')
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
    function generateData(count, yrange) {
	    var i = 0;
	    var series = [];
	    while (i < count) {
	        var x = 'w' + (i + 1).toString();
	        var y = Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;

	        series.push({
	            x: x,
	            y: y
	        });
	        i++;
	    }
	    return series;
	}
    $.fn.chartGenerate = function(type, data_source, tab_id, id){
        let mat = type.match(/ms/g);
        data_source = $.genData(data_source);
        //console.log(type);

        let div = $(this).attr('id');
        let table = $(this).attr('id');
        // console.log(div);
        let item_height = $('#'+div).parents('.grid-stack-item').height();
        let height = item_height - 80;

        if (type == 'brush-chart') {
            var optionslinebrush = {
                series: [{
                 name: '',
                 data: data_source.data
                 //name: data_source.data
                }],

                chart: {
                    id: 'chart2',
                    type: 'line',
                    height: 150,
                    toolbar: {
                        autoSelected: 'pan',
                        show: false
                    }
                },
                colors: ['#0064Fa'],
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

                    categories: data_source.categories
                }
            };
            var chartlinebrush = new ApexCharts(document.querySelector('#chartline_leadday'), optionslinebrush);
            chartlinebrush.render();
            chartlinebrush.appendSeries([{
               data: data_source.data
            }]);

            var optionslinebrush = {
                series: [{
                	name: '',
                    data: data_source.data
                }],

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

                    categories: data_source.categories,
                    tooltip: {
                        enabled: false
                    }
                },
                yaxis: {
                    tickAmount: 2
                }
            };

            var chartLine = new ApexCharts(document.querySelector('#chartarea_leadday'), optionslinebrush);
            chartLine.render();
            chartLine.appendSeries([{
               data: data_source.data
            }]);
        } else if (type == 'line-basic'){
            var options = {       	
                series: data_source.data,
                colors: ['#0064Fa','#FF4560','#2DD22F','#0066B9','#2B2B2B','#A9A9A9','#FEB018','#7E60E1','#E647BE','#FECFB1'],
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
                    categories: data_source.categories,
                }
            };
        } else if (type == 'stacked-columns') {
            var options = {
                series: data_source.data,
				colors: ['#0064Fa','#FF4560','#2DD22F','#0066B9','#2B2B2B','#A9A9A9','#FEB018','#7E60E1','#E647BE','#FECFB1'],
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
                },
                legend: {
                    position: 'right',
                    offsetY: 40
                },
                fill: {
                    opacity: 1
                },
                
            };
        } else if (type == 'column-basic') {
            var options = {
            		//name: '',
				    series: data_source.data,

				    chart: {
				        type: 'bar',
				        height: 350,
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
				    colors: ['#0064Fa', '#FF4560', '#2DD22F', '#0066B9', '#2B2B2B', '#A9A9A9', '#FEB018', '#7E60E1', '#E647BE', '#FECFB1'],
				    plotOptions: {
				        bar: {
				            horizontal: false,
				            columnWidth: '55%',
				            endingShape: 'rounded'
				        },
				    },
				    dataLabels: {
				        enabled: false
				    },
				    stroke: {
				        show: true,
				        width: 2,
				        colors: ['transparent']
				    },
				    xaxis: {
				        categories: data_source.categories,
				    },
				    yaxis: {
				        title: {
				            text: ''
				        }
				    },
				    fill: {
				        opacity: 1
				    },
				    tooltip: {
				        y: {
				            formatter: function(val) {
				                return val
				            }
				        }
				    }
				};
        } else if (type == 'column-with-data-labels'){
            var options = {
			    series: [{
			        name: '',
			        data: data_source.data,
			        //colors: ['#0064Fa','#FF4560','#2DD22F','#0066B9','#2B2B2B','#A9A9A9','#FEB018','#7E60E1','#E647BE','#FECFB1'],
			    }],
			    chart: {
			        height: 350,
			        type: 'bar',
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
			    plotOptions: {
			        bar: {
			            borderRadius: 10,
			            dataLabels: {
			                position: 'top', // top, center, bottom
			            },
			        }
			    },
			    dataLabels: {
			        enabled: true,
			        formatter: function(val) {
			            return val;
			        },
			        offsetY: -20,
			        style: {
			            fontSize: '12px',
			            colors: ["#0064Fa"]
			        }
			    },

			    xaxis: {
			        categories: data_source.categories,
			        position: 'top',
			        axisBorder: {
			            show: false
			        },
			        axisTicks: {
			            show: false
			        },
			        crosshairs: {
			            fill: {
			                type: 'gradient',
			                gradient: {
			                    colorFrom: '#0064Fa',
			                    colorTo: '#0064Fa',
			                    stops: [0, 100],
			                    opacityFrom: 0.4,
			                    opacityTo: 0.5,
			                }
			            }
			        },
			        tooltip: {
			            enabled: true,
			        }
			    },
			    yaxis: {
			        axisBorder: {
			            show: false
			        },
			        axisTicks: {
			            show: false,
			        },
			        labels: {
			            show: false,
			            formatter: function(val) {
			                return val;
			            }
			        }

			    },
			    title: {
			        text: '',
			        floating: true,
			        offsetY: 330,
			        align: 'center',
			        style: {
			            color: '#0064Fa'
			        }
			    }
			};
        } else if (type == 'heatmap'){
        	var options = {
		          series: [{
		          name: 'Metric1',
		          data: generateData(18, {
		            min: 0,
		            max: 90
		          })
		        },
		        {
		          name: 'Metric2',
		          data: generateData(18, {
		            min: 0,
		            max: 90
		          })
		        },
		        {
		          name: 'Metric3',
		          data: generateData(18, {
		            min: 0,
		            max: 90
		          })
		        },
		        {
		          name: 'Metric4',
		          data: generateData(18, {
		            min: 0,
		            max: 90
		          })
		        },
		        {
		          name: 'Metric5',
		          data: generateData(18, {
		            min: 0,
		            max: 90
		          })
		        },
		        {
		          name: 'Metric6',
		          data: generateData(18, {
		            min: 0,
		            max: 90
		          })
		        },
		        {
		          name: 'Metric7',
		          data: generateData(18, {
		            min: 0,
		            max: 90
		          })
		        },
		        {
		          name: 'Metric8',
		          data: generateData(18, {
		            min: 0,
		            max: 90
		          })
		        },
		        {
		          name: 'Metric9',
		          data: generateData(18, {
		            min: 0,
		            max: 90
		          })
		        }
		        ],
		          chart: {
		          height: 350,
		          type: 'heatmap',
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
		        dataLabels: {
		          enabled: false
		        },
		        colors: ["#008FFB"],
		        title: {
		          text: ''
		        },
	        };
        } else if (type == 'line-charts-dashed'){
        	var options = {
		          series: [{
		            name: "Session Duration",
		            data: [45, 52, 38, 24, 33, 26, 21, 20, 6, 8, 15, 10]
		          },
		          {
		            name: "Page Views",
		            data: [35, 41, 62, 42, 13, 18, 29, 37, 36, 51, 32, 35]
		          }/*,
		          {
		            name: '',
		            data: []
		          }*/
		        ],
		          colors: ['#0064Fa','#FF4560','#2DD22F','#0066B9','#2B2B2B','#A9A9A9','#FEB018','#7E60E1','#E647BE','#FECFB1'],
		          chart: {
			          height: 350,
			          type: 'line',
			          zoom: {
			            enabled: false
			          },
			          /*toolbar: {
				         show: true
				      },*/
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
		          width: [5, 7, 5],
		          curve: 'straight',
		          dashArray: [0, 8, 5]
		        },
		        title: {
		          text: '',
		          align: 'left'
		        },
		        legend: {
		          tooltipHoverFormatter: function(val, opts) {
		          	return val
		            //return val + ' - ' + opts.w.globals.series[opts.seriesIndex][opts.dataPointIndex] + ''
		          }
		        },
		        markers: {
		          size: 0,
		          hover: {
		            sizeOffset: 6
		          }
		        },
		        xaxis: {
		          categories: ['01 Jan', '02 Jan', '03 Jan', '04 Jan', '05 Jan', '06 Jan', '07 Jan', '08 Jan', '09 Jan',
		            '10 Jan', '11 Jan', '12 Jan'
		          ],
		        },
		        tooltip: {
		          y: [
		            {
		              title: {
		                formatter: function (val) {
		                  return val 
		                }
		              }
		            },
		            {
		              title: {
		                formatter: function (val) {
		                  return val
		                }
		              }
		            }
		          ]
		        },
		        grid: {
		          borderColor: '#f1f1f1',
		        }
	        };
        } else if (type == 'grid') {
            //console.log(data_source);
            $.each($('#table_'+tab_id+'_'+id), function () {
                var dt_id = $(this).attr('id');
                console.log(dt_id);

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
    }

    /*$.adddb = function() {

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

                        $.post('<?php echo site_url('dashboard/addTab'); ?>', {dashboard_title:dashboard_name,userid:userid}, function(rs){
                            console.log(rs);
                            $('#dashboard_title').val('');
                            var tab_id = rs.tab_id;

                            $('#tabidhide').val(tab_id);
                            $('#dashboard_title').val(rs.tab_name);
                            $('#dbowner').val(rs.owner);
                            $('#desciption').val("");

                            $('.nav-tabs li').removeClass('active');


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

                            $("#menu").append(li2);

                            $.post('<?php echo site_url('dashboard/addTabContent'); ?>', {tab_id:tab_id}, function(html){

                                if (tab_id == 'lead') {
                                    $('.tab-content .tab-pane').addClass('active');
                                    $('.tab-content .tab-pane').addClass('in');
                                    // console.log('gggg');
                                } else {
                                    $('.tab-content .tab-pane').removeClass('active');
                                    $('.tab-content .tab-pane').removeClass('in');
                                    // console.log('ffg');
                                }
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

                                        data.push({
                                            tabid:tabid, type:type, id:id, chartid:chartid, x:x, y:y, width:width, height:height
                                        })

                                    });

                                    // console.log(data);

                                    $.post('<?php echo site_url('dashboard/updateTemplate'); ?>', {data:data}, function(){
                                        $('#nodb').css('display','none');
                                    })
                                });


                                $.clickmodaladdwidget = function() {
                                    console.log('yes2');
                                    $('#addwidgetmodal').modal('show');
                                    $('.modal-body .tab-content .tab-pane').removeClass('in');
                                    $('.modal-body .tab-content .tab-pane').removeClass('active');
                                    $('#tabpaneaddwidget').addClass('active');
                                }

                            });

                    },'json');
                }
            },
            error: function(data) {
                console.log('e');
            },
        });
    }

    $.addwidget = function(obj) {
        $("#form_wiget").submit();
    }

    $("#form_wiget").submit(function (e) {
        //var allVals = [];
        $('#addwidgetmodal').modal('hide');
        var tabkey = $('#tabidhide').val();

        var tab_id;

        if (tabkey == "lead") {

            tab_id = tabkey;

        } else {

            tab_id = "tab" + tabkey;

        }

        $('input[name="widget"]:checked').each(function () {
            var data = $(this).val();
            var a_data = data.split(",");

            $.genGraph(tab_id,tabkey,a_data[2],a_data[1],a_data[0]);

        });



        e.preventDefault();
    });*/

    /*$.chooeWidget = function(chartid,widgettype) {
        $('input[name="widget"]:checked').each(function() {
            console.log(chartid);
            console.log(widgettype);
        });
    }
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


        $.post('<?php echo site_url('dashboard/addDashboardsdetails'); ?>', {tab_id:tab_id,dashboardtitle:dashboardtitle,desciption:desciption}, function(rs){
            console.log(rs);
            $('#desciption').val("");
        });
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
    }*/

    /*$.export = function(obj) {
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
        if ($("#"+ck_class).closest('fieldset').find('input').prop('checked') == true) {
            $('#'+ck_class).closest('fieldset').find('input').prop('checked',true);
        } else {
            $('#'+ck_class).closest('fieldset').find('input').prop('checked',false);
        }
    }*/

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
</script>
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
                            <p style="font-family: PromptMedium; color: #2b2b2b; font-weight: 500; font-size: 16px;">
                                Report -
                                <a href="#">
                                    Response History
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
                                    
                                    <div>
                                        <label style="display: inline; font-size: 12px; font-family: PromptMedium; font-weight: 400;">เลือกช่วงเวลา</label>&nbsp;
                                        <input
                                            class="form-control input-daterange-datepicker"
                                            type="text"
                                            name="daterange"
                                            id="daterange"
                                            style="display: inline-block; width: 200px; color: rgb(117 117 117); box-shadow: 0px 0px 2px 0px rgba(0, 0, 0, 0.2); border: 0px;"
                                        />
                                    </div>
                                    &nbsp; 
                                    <div class="pull-right adddashboards" style="margin-left: 10px">
                                        <button type="button" class="btn btn-search" data-toggle="modal" data-target="#addtabmodal"><img src="<?php echo site_assets_url('images/icons/search-duotone-white.png'); ?>" style="width: 20px; height: 20px;" > แสดงรายการ</button>
                                    </div>
                                </ul>
                            </div>

                            <div class="col-sm-6">
                                <div class="pull-right" style="display: -webkit-inline-box; float: right;">
                                    <div>
                                        <label style="display: inline; font-size: 12px; font-family: PromptMedium; font-weight: 400;">Export to</label>&nbsp;
                                        <select id="filetype" name="filetype" class="form-control filetype" style="display: inline-block; width: 120px; color: rgb(117 117 117); box-shadow: 0px 0px 2px 0px rgba(0, 0, 0, 0.2); border: 0px; margin-right: 10px">
                                            <!-- <option value="csv">CSV</option> -->
                                            <option value="excel">Excel</option>
                                            <option value="pdf">PDF</option>
                                        </select>
                                    </div>
                                    &nbsp; 

                                    <div class="pull-right">
                                        <button type="button" class="btn btn-export" data-toggle="modal" data-target="#addtabmodal"><img src="<?php echo site_assets_url('images/icons/download-simple-white.png'); ?>" style="width: 20px; height: 20px;"></button>
                                    </div>

                                    <div class="vl"></div>

                                    <div class="pull-right" style="margin-right: 10px;">
                                        <button type="button" class="btn btn-print" data-toggle="modal" data-target="#addtabmodal"><img src="<?php echo site_assets_url('images/icons/printer-duotone-white.png'); ?>" style="width: 20px; height: 20px;" ></button><!-- onclick="jQuery.print('.print-label')" -->
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

            <div style="border-bottom: 1px solid #ddd;"></div>
            <br/>

            <div class="pull-right" style="margin-right: 30px; font-size: 16px !important; font-weight: 600">Total&nbsp;&nbsp;<span class="countRecord" style="color: #E97126">0</span></div>

            <div id="chat-container">    
                <table id="datatable" class="table table-striped table-bordered display" style="width:100%">
                  <thead>
                    <tr>
                        <th>ข้อความ</th>
                        <th>หัวข้อ</th>
                        <th>ผู้ใช้</th>
                        <th>วันที่-เวลา</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>ข้อความ</th>
                        <th>หัวข้อ</th>
                        <th>ผู้ใช้</th>
                        <th>วันที่-เวลา</th>
                    </tr>
                </tfoot>
                </table> 
            </div>

        </div>

    </div>

</div>


<!-- <div class="print-label" >
    <div class="pull-center" style="text-align: center;">
        <label style="font-size: 16px; font-weight: 500">Report Response History</label>
        <label style="font-size: 14px; float: right;"><? echo date('d-m-Y H:i:s');?></label>
    </div>
    <table id="datatable1" class="table table-striped table-bordered display dataTable" style="width: 100%; font-size: 14px;margin-top: 100px" role="grid" aria-describedby="datatable_info">
        <thead>
            <tr role="row">
                <th rowspan="1" colspan="1" aria-label="ข้อความ">ข้อความ</th>
                <th tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 292.006px;">หัวข้อ</th>
                <th tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 292.006px;">ผู้ใช้</th>
                <th tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 293px;">วันที่-เวลา</th>
            </tr>
        </thead>
        <tbody class="text-row">
            <tr role="row" class="even">
                <td class="sorting_1"></td>
                <td></td>
                <td>Punchy</td>
                <td>05-05-2021 12:00:47</td>
            </tr>
            <tr role="row" class="even">
                <td class="sorting_1"></td>
                <td></td>
                <td>Worapot Pengsuk</td>
                <td>05-05-2021 12:36:07</td>
            </tr>
            <tr role="row" class="even">
                <td class="sorting_1">งง</td>
                <td>text</td>
                <td>SAND</td>
                <td>17-05-2021 13:22:31</td>
            </tr>
            <tr role="row" class="even">
                <td class="sorting_1">+646</td>
                <td>text</td>
                <td>SAND</td>
                <td>31-05-2021 11:11:21</td>
            </tr>
            <tr role="row" class="even">
                <td class="sorting_1">.</td>
                <td>text</td>
                <td>SAND</td>
                <td>17-05-2021 10:41:37</td>
            </tr>
            <tr role="row" class="even">
                <td class="sorting_1">.</td>
                <td>text</td>
                <td>SAND</td>
                <td>31-05-2021 11:10:57</td>
            </tr>
            <tr role="row" class="even">
                <td class="sorting_1">.</td>
                <td>text</td>
                <td>SAND</td>
                <td>31-05-2021 11:11:06</td>
            </tr>
            <tr role="row" class="even">
                <td class="sorting_1">..</td>
                <td>text</td>
                <td>SAND</td>
                <td>17-05-2021 10:41:47</td>
            </tr>
            <tr role="row" class="even">
                <td class="sorting_1">..</td>
                <td>text</td>
                <td>SAND</td>
                <td>17-05-2021 15:51:44</td>
            </tr>
            <tr role="row" class="even">
                <td class="sorting_1">..</td>
                <td>text</td>
                <td>Sand Siripon</td>
                <td>31-05-2021 14:40:28</td>
            </tr>
        </tbody>
    </table>
</div> -->

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

<script type="text/javascript" src="<?php echo site_assets_url('js/jQuery.print.js');?>"></script>

<link rel="stylesheet" type="text/css" href="<?php echo site_assets_url('apexcharts/dist/apexcharts.css');?>" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="<?php echo site_assets_url('apexcharts/dist/apexcharts.js');?>"></script>
<script src="<?php echo site_assets_url('apexcharts/dist/apexcharts.min.js');?>"></script>
<script src="<?php echo site_assets_url('DataTables/js/jquery.dataTables.js');?>"></script>
<script src="<?php echo site_assets_url('DataTables/js/dataTables.bootstrap.js');?>"></script>

<script type="text/javascript">
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
        }
    );


    $('.btn-search').on('click', function(event, items) {

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

        var url = "<?php echo site_url('Omnireport/getmessage'); ?>";
        var data = {
            s_date:t_start,
            e_date:t_end
        };
        $.ajax(url, {
            type: "POST",
            data: data,
            success: function (data) {
                var result = jQuery.parseJSON(data);
                
                var commaNum = numberWithCommas(result['total']);
                $('.countRecord').html(commaNum);
                reload_datatable(result['data']);

            },
            error: function (data) {
                console.log("error");
            },
        });

    });

    $('.btn-export').on('click', function(event, items) {

        var start = $('#daterange').data('daterangepicker').startDate._d;
        var end = $('#daterange').data('daterangepicker').endDate._d;
        var filetype = $('#filetype').val();

        var s_dd = String(start.getDate()).padStart(2, '0');
        var s_mm = String(start.getMonth() + 1).padStart(2, '0');
        var s_yyyy = start.getFullYear();

        var e_dd = String(end.getDate()).padStart(2, '0');
        var e_mm = String(end.getMonth() + 1).padStart(2, '0');
        var e_yyyy = end.getFullYear();

        var t_start = s_yyyy+"-"+s_mm+"-"+s_dd;
        var t_end = e_yyyy+"-"+e_mm+"-"+e_dd;

        //alert(filetype);
        //window.open("omnireport/export");
        var url = "<?php echo site_url('Omnireport/export'); ?>";
        var data = {
            s_date:t_start,
            e_date:t_end,
            filetype:filetype
        };
        
        $.ajax(url, {
            type: "POST",
            data: data,
            success: function (data) {
                var result = jQuery.parseJSON(data);
                //console.log(result);
                var path = result['data']['path'];
                var filename = result['data']['filename'];
                window.open('../'+path+filename);
            },
            error: function (data) {
                console.log("error");
            },
        });


    });

    $('.btn-print').on('click', function(event, items) {

        var start = $('#daterange').data('daterangepicker').startDate._d;
        var end = $('#daterange').data('daterangepicker').endDate._d;
        //var filetype = $('#filetype').val();

        var s_dd = String(start.getDate()).padStart(2, '0');
        var s_mm = String(start.getMonth() + 1).padStart(2, '0');
        var s_yyyy = start.getFullYear();

        var e_dd = String(end.getDate()).padStart(2, '0');
        var e_mm = String(end.getMonth() + 1).padStart(2, '0');
        var e_yyyy = end.getFullYear();

        var t_start = s_yyyy+"-"+s_mm+"-"+s_dd;
        var t_end = e_yyyy+"-"+e_mm+"-"+e_dd;

        var url = "<?php echo site_url('Omnireport/getmessage'); ?>";
        var data = {
            s_date:t_start,
            e_date:t_end
        };
        var html = '';

        $.ajax(url, {
            type: "POST",
            data: data,
            success: function (data) {
                var result = jQuery.parseJSON(data);
                
               $.each(result['data'], function( index, value ) {
                  html += '<tr role="row" class="even">';
                  html += '<td class="sorting_1" style="word-wrap: break-word;">'+value['message']+'</td>';
                  html += '<td>'+value['message_type']+'</td>';
                  html += '<td>'+value['socialname']+'</td>';
                  html += '<td>'+value['messagetime']+'</td>';
                  html += '</tr>';

                });
                var newWin=window.open('','Print-Window');
                newWin.document.open();   
                newWin.document.write('<html><title>Response History</title><link rel="stylesheet" href="<?php echo site_assets_url('DataTables/css/dataTables.bootstrap4.css');?>"><link rel="shortcut icon" type="image/x-icon" href="<?php echo site_assets_url('favicon.ico'); ?>" /><link rel="stylesheet" href="<?php  echo site_assets_url('css/dist/css/style.min.css');?>" ><style>table.dataTable tbody td {word-break: break-word;vertical-align: top;}</style><body onload="window.print()" style="background-color:#fff"><div class="print-label" ><div class="pull-center" style="text-align: center;"><label style="font-size: 16px; font-weight: 500">Report Response History</label><label style="font-size: 14px; float: right;"><? echo date('d-m-Y H:i:s');?></label></div><table id="datatable1" class="table table-striped table-bordered display dataTable" style="width: 100%; font-size: 14px;margin-top: 100px" role="grid" aria-describedby="datatable_info"><thead><tr role="row"><th rowspan="1" colspan="1" aria-label="ข้อความ" style="width:40%">ข้อความ</th><th tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 292.006px;">หัวข้อ</th><th tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 292.006px;">ผู้ใช้</th><th tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 293px;">วันที่-เวลา</th></tr></thead><tbody class="text-row">'+html+'</tbody></table></div></body></html>');

                newWin.document.close();

            },
            error: function (data) {
                swal("", "ไม่พบข้อมูล", "error");
            },
        });

        //$("#datatable1").printThis();
        //console.log(result);

        /*var divToPrint=document.getElementById('datatable1');
        var newWin=window.open('','Print-Window');
        newWin.document.open();      
        newWin.document.write('<html><link rel="stylesheet" href="<?php echo site_assets_url('DataTables/css/dataTables.bootstrap4.css');?>"><link rel="stylesheet" href="<?php  echo site_assets_url('css/dist/css/style.min.css');?>" ><body onload="window.print()" style="background-color:#fff"><div class="print-label" ><div class="pull-center" style="text-align: center;"><label style="font-size: 16px; font-weight: 500">Report Response History</label><label style="font-size: 14px; float: right;"><? echo date('d-m-Y H:i:s');?></label></div><table id="datatable1" class="table table-striped table-bordered display dataTable" style="width: 100%; font-size: 14px;margin-top: 100px" role="grid" aria-describedby="datatable_info"><thead><tr role="row"><th rowspan="1" colspan="1" aria-label="ข้อความ">ข้อความ</th><th tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 292.006px;">หัวข้อ</th><th tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 292.006px;">ผู้ใช้</th><th tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 293px;">วันที่-เวลา</th></tr></thead><tbody class="text-row">'+html+'</tbody></table></div></body></html>');

        newWin.document.close();
        setTimeout(function(){newWin.close();},10);*/


    });

    function numberWithCommas(number) {
        var parts = number.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(".");
    }

    function reload_datatable(data){
      var data_table = $('#datatable').DataTable({
          "data" : data,
          "processing": true,
          //"retrieve": true,
          "destroy" :true,
          "columns": [
              { "data": "message","width": "40%" },
              { "data": "message_type" ,"width": "20%"},
              { "data": "socialname" ,"width": "20%"},
              { "data": "messagetime","width": "20%" }
          ],"columnDefs": [ {
                "searchable": false,
                "orderable": false,
                "targets": 0
            },

            ],
            
        });
    }

</script>
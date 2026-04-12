<style>
    /* ---------------------------------------------------
        SIDEBAR STYLE
    ----------------------------------------------------- */
    .dropdown-menu {
        padding-left: 5px;
    }
    .dropdown-menu > .dropdown-item {
        text-decoration: none!important;
    }

    #sidebar {
        width: 250px;
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        z-index: 999;
        /*background: #7386D5;*/
        /*color: #fff;*/
        transition: all 0.3s;
        border-right: 1px solid #DDDDDD;
        padding-left: 10px;
        padding-right: 10px;
    }

    #sidebar.active {
        margin-left: -250px;
    }

    #sidebar .sidebar-header {
        margin-top: 20px;
        border-bottom: 1px solid #DDDDDD;
        /*padding: 20px;*/
        /*background: #6d7fcc;*/
    }

    #sidebar ul.components {
        /*padding: 20px 0;*/
        /*border-bottom: 1px solid #47748b;*/
    }

    #sidebar ul p {
        /*color: #fff;*/
        padding: 10px;
    }

    #sidebar ul li a {
        padding: 10px;
        font-size: 1.1em;
        display: block;
        color: #000;
        text-decoration: none;
    }
    #sidebar ul li a:hover {
        color: #000;
        background: #DDDDDD;
    }

    #sidebar ul li.active > a, a[aria-expanded="true"] {
        color: #000;
        /*background: #6d7fcc;*/
    }

    /* ---------------------------------------------------
        CONTENT STYLE
    ----------------------------------------------------- */
    #content {
        width: calc(100% - 250px);
        padding: 0px 15px;
        min-height: 100vh;
        transition: all 0.3s;
        position: absolute;
        top: 0;
        right: 0;
    }
    #content.active {
        width: 100%;
    }
    #sidebarCollapse {
        padding: 6px 1px;
        border-radius: 0px;
        margin-left: -15px;
    }


    /* ---------------------------------------------------
        MEDIAQUERIES
    ----------------------------------------------------- */
    @media (max-width: 768px) {
        #sidebar {
            margin-left: -250px;
        }
        #sidebar.active {
            margin-left: 0;
        }
        #content {
            width: 100%;
        }
        #content.active {
            width: calc(100% - 250px);
        }
        #sidebarCollapse span {
            display: none;
        }
    }

    .no-close .ui-dialog-titlebar-close {display: none }
    .noTitleStuff .ui-dialog-titlebar {display:none}
    .ui-widget-overlay {opacity:0.3}
    .rotate45 {transform: rotate(45deg);}
</style>
<link rel="stylesheet" href="<?php echo site_url('assets/customscroll/jquery.mCustomScrollbar.css'); ?>">
<script src="<?php echo site_url('assets/customscroll/jquery.mCustomScrollbar.js'); ?>"></script>
<script src="<?php echo site_url('assets/graph.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#sidebar").mCustomScrollbar();

        $('#sidebarCollapse').on('click', function () {
            $(this).find('i').toggleClass("fa-chevron-left fa-chevron-right")
            $('#sidebar, #content').toggleClass('active');
            $('.collapse.in').toggleClass('in');
            $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        });
    });
</script>

<div class="wrapper">
    <!-- Sidebar Holder -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <h5 style="display:inline-block">FOLDERS</h5>
            <div class="pull-right">
                <button class="btn btn-xs" style="margin-top:5px" data-toggle="modal" data-target="#addfoldermodal"><i class="fa fa-plus"></i> </button>
            </div>
        </div>

        <div style="margin:10px 0px;">
            <input type="text" class="form-control" placeholder="Search for folders">
        </div>

        <ul class="list-unstyled components">
            <?php foreach($folders as $folder){ ?>
                <li>
                    <a href="#"><i class="fa fa-folder"></i> <?php echo $folder['foldername']; ?></a>
                </li>
            <?php } ?>
        </ul>
    </nav>

    <!-- Page Content Holder -->
    <div id="content">
        <div>
            <button type="button" id="sidebarCollapse" class="btn navbar-btn">
                <i class="fa fa-chevron-left"></i>
            </button>

            <div class="pull-right">
                <div class="btn-group pull-right">
                    <button type="button" class="btn btn-default dropdown-toggle" style="margin-top:10px" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-plus"></i> Add Report <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo site_url('report/chart'); ?>"><i class="fa fa-chart-pie"></i> Charts</a></li>
                        <li><a href="<?php echo site_url('report/detailreport'); ?>"><i class="fa fa-chart-pie"></i> Detail Report</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <table id="datagrid" class=""></table>
    </div>
</div>

<!-- Modal -->
    <div class="modal fade" id="addfoldermodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Folder</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            Folder Name
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="foldername" id="foldername">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="$.addFolder()">Save</button>
                </div>
            </div>
        </div>
    </div>

<script>
    $(function(){
        $('#datagrid').kendoGrid({
            columns: [
                { selectable: true, width: "50px" },
                { field:'action', title:'Action', filterable:false, template:function(dataItem){
                    var rotate = dataItem.pin==0 ? 'rotate45':'';
                    var actions = '';
                    actions += '<a class="btn-default" href="<?php echo site_url('report/viewcart'); ?>/'+ dataItem.reportid +'"><i class="fa fa-edit"></i></a> ';
                    actions += '<a class="btn-default" onclick="$.deleteReport('+ dataItem.reportid +')" href="javascript:;"><i class="fa fa-trash"></i></a> ';
                    actions += '<a class="btn-default" onclick="$.viewChart('+ dataItem.reportid +', \''+ dataItem.graphtype +'\')" href="javascript:;"><i class="fa fa-eye"></i></a> ';
                    actions += '<a class="btn-default" onclick="$.pinDashboard(this, '+ dataItem.reportid +', \''+ dataItem.graphtype +'\')" href="javascript:;"><i class="fa fa-thumbtack '+ rotate +'"></i></a> ';
                    return actions;
                }},
                { field:'reporttype', title:'Report Type', template:function(dataItem){
                    return '<div style="text-align:center; font-size:22px;"><i class="fa fa-'+ dataItem.reporttype +'"></i></div>';
                }},
                { field:'reportname', title:'Report Name' },
                { field:'primarymodule', title:'Primary Module' },
                { field:'foldername', title:'Folder Name' },
                { field:'owner', title:'Owner' }
            ],
            filterable: true
        })

        $("#datagrid tbody").on("dblclick", "tr", function(e) {
            var rowElement = this;
            var row = $(rowElement);
            var grid = $("#datagrid").getKendoGrid();

            var selected = grid.dataItem(row);
            if(selected.reportid != undefined){
                window.location.href = '<?php echo site_url('report/viewcart'); ?>/'+ selected.reportid;
            }
        });

        $.post('<?php echo site_url('report/getReportList'); ?>', function(rs){
            var datagrid = $('#datagrid').data("kendoGrid");
            var dataSource = new kendo.data.DataSource({
                data: rs
            });
            datagrid.setDataSource(dataSource);
        },'json');

        $.addFolder = function(){
            var foldername = $('#foldername').val();
            if(foldername != '' ){
                $('#addfoldermodal').modal('toggle');
                $.post('<?php echo site_url('report/addFolder'); ?>', { foldername:foldername }, function(){
                    var li = $('<li />');
                    var ah = $('<a />').attr({ href:'#' }).html('<i class="fa fa-folder"></i> '+ foldername);
                    $(li).append(ah);
                    $('.list-unstyled.components').append(li);

                    $('#foldername').val('');
                });
            }
        }

        $.viewChart = function(reportid, graphtype){
            $.post('<?php echo site_url('report/getMSData'); ?>/'+reportid, function(rs){
                var my_modal = $('<div />',{ id:'my_modal' });
                var graph_container = $('<div />',{ id:'graph_container' });

                $( my_modal ).dialog({
                    resizable: false,
                    height: "auto",
                    width: 500,
                    height: 600,
                    modal: true,
                    dialogClass: 'noTitleStuff',
                    buttons: {
                        Close: function() {
                            $( this ).dialog( "close" );
                            $( this ).html('');
                        }
                    }
                }).append(graph_container);
                $('#graph_container').chartGenerate(graphtype, rs);

            },'json');
        }

        $.deleteReport = function(reportid){
            if(confirm('Confirm Delete !') == true){
                $.post('<?php echo site_url('report/deleteReport'); ?>', {reportid:reportid}, function(rs){
                    window.location.reload();
                });
            }
        }


        $.pinDashboard = function(obj, reportid, graphtype){
            if($(obj).find('i').hasClass('rotate45') == true){
                // Pin Graph to Dashboard
                $.post('<?php echo site_url('report/pinToDashboard'); ?>', {action:'pin', reportid:reportid, graphtype:graphtype}, function(rs){
                    console.log(rs);
                    alert('Graph pinned to Dashboard');
                },'json');
            }else{
                // Unpin Graph from Dashboard
                $.post('<?php echo site_url('report/pinToDashboard'); ?>', {action:'unpin', reportid:reportid, graphtype:graphtype}, function(rs){
                    console.log(rs)
                    alert('Graph removed from Dashboard');
                },'json');
            }
            $(obj).find('i').toggleClass('rotate45')
        }
    });
</script>
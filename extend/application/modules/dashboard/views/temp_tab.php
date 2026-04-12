<!-- <?php $tab_id = 'tab'.$key; ?>
<div id="<?php echo $tab_id; ?>" class="tab-pane fade in active">
    <div class="row" style="margin-top:5px; margin-bottom:5px;">
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
    </div>

    <div class="row grid-stack">

    </div>
</div> -->

<?php $tab_id = 'tab'.$key; ?>
<div id="<?php echo $tab_id; ?>" class="tab-pane fade in active">
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
    <div class="row grid-stack">

    </div>
</div>
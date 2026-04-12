<?php $tab_id = 'tab'.$key; ?>
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
</div>
<div class="row" style="margin-top: 15px;">
    <div class="col-sm-12 text-center" style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium;">
        ค้นพบออเดอร์ทั้งหมด <?php echo count($data); ?> รายการ
    </div>
</div>

<div class="row">
    <div class="container demo col-sm-12">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <?php foreach ($data as $key => $value) { ?>
                    <div class="panel panel-default">
                        <div class="panel-heading panel-clr" role="tab" id="heading<?php echo $key ?>">
                            <div class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $key ?>" aria-expanded="true" aria-controls="collapseOne">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <?php 
                                                if($value['plan_date'] == '0000-00-00' || $value['plan_date'] == ''){
                                                    $plan_date = '00/00/0000';
                                                }else{
                                                    $plan_date = date("d/m/Y", strtotime($value['plan_date']));
                                                }
                                            ?>
                                            <div class="col-sm-1">#<?php echo ($key+1) ?></div>
                                            <div class="col-sm-10" style="text-align: center;">
                                                <span><?php echo $value['contact_no'] ?> - <?php echo $value['conract_name'] ?></span> / 
                                                <span><?php echo $value['order_no'] ?></span> -
                                                <span><?php echo date("d/m/Y", strtotime($value['order_date'])) ?></span> -
                                                <span><?php echo $plan_date; ?></span>
                                                <span><?php echo $value['plan_time'] ?></span> /
                                                <span><?php echo $value['order_status_order'] ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div id="collapse<?php echo $key ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $key ?>">
                            <div class="panel-body">
                                <div class="col-sm-12" style="background-color: #ededed; font-size: 11px; color: #2b2b2b;">
                                    <div class="row" style="padding-left: 10px; padding-top: 5px;">
                                        <div class="col-sm-3">
                                            <form class="form">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="control-label col-form-label">
                                                     Contact Name
                                                    </label>
                                                     <div class="col-sm">
                                                        <input class="form-control" type="text" value="<?php echo $value['conract_name'] ?>" />
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-sm-2">
                                            <form class="form">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="control-label col-form-label">
                                                        Telephone
                                                    </label>
                                                    <div class="col-sm">
                                                        <input class="form-control" type="text" value="<?php echo $value['telephone'] ?>" />
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-sm-2">
                                            <form class="form">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="control-label col-form-label">
                                                        Line
                                                    </label>
                                                    <div class="col-sm">
                                                        <input class="form-control" type="text" value="<?php echo $value['line_id'] ?>" />
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-sm-2">
                                            <form class="form">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="control-label col-form-label">
                                                        Facebook
                                                    </label>
                                                    <div class="col-sm">
                                                        <input class="form-control" type="text" value="<?php echo $value['facebook'] ?>" />
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-sm-3">
                                            <form class="form">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="control-label col-form-label">
                                                        Project/Address
                                                    </label>
                                                    <div class="col-sm">
                                                        <input class="form-control" type="text" value="<?php echo $value['project_address'] ?>" />
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="row" style="padding-left: 10px; margin-top: -20px;">
                                        <div class="col-sm-12">
                                            <form class="form">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="control-label col-form-label">
                                                        Delivery Location
                                                    </label>
                                                    <div class="col-sm">
                                                        <input class="form-control" type="text" value="<?php echo $value['delivery_location'] ?>" />
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: -20px; padding-left: 10px;">
                                        <div class="col-sm-3">
                                            <form class="form">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="control-label col-form-label">
                                                        Mat Type
                                                    </label>
                                                    <div class="col-sm">
                                                        <input class="form-control" type="text" value="<?php echo $value['mat_type_order'] ?>" />
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-sm-3">
                                            <form class="form">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="control-label col-form-label">
                                                        Plant
                                                    </label>
                                                    <div class="col-sm">
                                                        <input class="form-control" type="text" value="<?php echo $value['plant_name'] ?>" />
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-sm-3">
                                            <form class="form">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="control-label col-form-label">
                                                        Plan Date
                                                    </label>
                                                    <div class="col-sm">
                                                        <input class="form-control" type="text" value="<?php echo @$plan_date.' '.@$value['plan_time'] ?>" />
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-sm-3">
                                            <form class="form">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="control-label col-form-label">
                                                        Truck Size
                                                    </label>
                                                    <div class="col-sm">
                                                        <input class="form-control" type="text" value="<?php echo $value['truck_size_order'] ?>" />
                                                        <!-- <?php echo $value['detail'][0]['carsize']?> -->
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="row" style="padding-left: 10px; margin-top: -20px;">
                                        <div class="col-sm-12">
                                            <form class="form">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="control-label col-form-label">
                                                        Remark
                                                    </label>
                                                    <div class="col-sm">
                                                        <input class="form-control" type="text" value="<?php echo $value['description'] ?>" />
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="row" style="padding-left: 10px; padding-right: 10px;">
                                        <div class="table-responsive m-t-10">
                                            <table class="table table-striped" style="border: 1px solid #ededed;">
                                                <thead class="text-center" style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium;">
                                                    <tr>
                                                            <!-- <th style="width: 5%;">ได้คิว</th> -->
                                                            <th style="width: 30%;">รายการ</th>
                                                            <!-- <th>ติดต่อ</th> -->
                                                            <!-- <th style="width: 11%;">วันที่ใช้</th>
                                                            <th style="width: 7%;">เวลา</th> -->
                                                            <th style="width: 15%;">ST</th>
                                                            <th style="width: 10%;">ราคา/คิว</th>
                                                            <th style="width: 15%;">ค่าคอนกรีต</th>
                                                            <th style="width: 10%;">กำไร</th>
                                                            <th style="width: 10%;"><?php echo genLabel('LBL_STATUS'); ?></th>
                                                            <th style="width: 10%;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-center table-bordered" style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium;">
                                                                <tr style="background-color: #ffffff;">
                                                                <!-- <td class="centered custom-checkbox">
                                                                    <input type="checkbox" style="text-align: center;" />
                                                                </td> -->
                                                                <td style="text-align: left;" ><?php echo $value['detail'][0]['productname'] ?></td>
                                                                <!-- <td>
                                                                    <img src="<?php //echo site_assets_url('images/icons/phonetable.png'); ?>" width="15" height="15" />
                                                                </td>
                                                                <td>
                                                                    <input class="form-control" type="text" value="<?php //echo date("d/m/Y", strtotime($value['plan_date'])) ?>" />
                                                                </td>
                                                                <td>
                                                                    <input class="form-control" type="text" value="<?php echo $value['plan_time'] ?>" />
                                                                </td> -->
                                                                <td style="text-align: left;"><?php echo $value['strength_order'] ?></td>
                                                                <td style="text-align: right;"><b><?php echo number_format($value['detail'][0]['priceperunit'],2); ?></b></td>
                                                                <!-- <input class="form-control" type="text" style="text-align: right;" value="<?php //echo $value['detail'][0]['priceperunit'] ?>" /> -->
                                                                
                                                                <td style="text-align: right;"><b><?php echo number_format($value['detail'][0]['total1'],2); ?></b></td>
                                                                <td style="text-align: right;"><?php echo number_format($value['detail'][0]['profit'],2); ?></td>
                                                                <td>
                                                                    <b><?php echo $value['order_status_order']; ?></b>
                                                                    <!-- <input class="form-control" type="text" placeholder="รอแจ้ง" /> -->
                                                                </td>
                                                                <td>
                                                                    <!-- <label>
                                                                        <a href='<?php echo "http://".$_SERVER['HTTP_HOST']."/MXES/index.php?module=Order&action=EditView&record=".$value['orderid']."&return_module=Order&return_action=DetailView" ?>' target="_blank" style="color: #000;">แก้ไข</a>
                                                                    </label>
                                                                    l -->
                                                                    <label>
                                                                        <a href='<?php echo "http://".$_SERVER['HTTP_HOST']."/MXES/index.php?action=DetailView&module=Order&record=".$value['orderid']."&parenttab=Sales" ?>' target="_blank" style="color: #E97126;">รายละเอียด</a>
                                                                    </label>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <!-- <?php foreach ($value['detail'] as $k => $v) { ?>
                                                <?php } ?> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

        <!-- <div class="panel panel-default">
                                        <div class="panel-heading panel-clr" role="tab" id="headingTwo">
                                            <div class="panel-title">
                                                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <div class="col-sm-1">
                                                                #1
                                                            </div>
                                                            <div class="col-sm-10" style="text-align: center;">
                                                                COO1000 - วันศุกร์ที่ 31/07/2563 เวลา 09.41 น - ใช้วันเสาร์ที่ 01/08/2563 เวลา 09.00 น.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                            <div class="panel-body">
                                                <div class="col-sm-12" style="background-color: #ededed; font-size: 11px; color: #2b2b2b;">
                                                    <div class="row" style="padding-left: 10px; padding-top: 5px;">
                                                        <div class="col-sm-3">
                                                            <form class="form">
                                                                <div class="form-group row">
                                                                    <label for="example-text-input" class="control-label col-form-label">
                                                                        ชื่อลูกค้า
                                                                    </label>
                                                                    <div class="col-sm">
                                                                        <input class="form-control" type="text" />
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <form class="form">
                                                                <div class="form-group row">
                                                                    <label for="example-text-input" class="control-label col-form-label">
                                                                        เบอร์โทร
                                                                    </label>
                                                                    <div class="col-sm">
                                                                        <input class="form-control" type="text" />
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <form class="form">
                                                                <div class="form-group row">
                                                                    <label for="example-text-input" class="control-label col-form-label">
                                                                        ไลน์
                                                                    </label>
                                                                    <div class="col-sm">
                                                                        <input class="form-control" type="text" />
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <form class="form">
                                                                <div class="form-group row">
                                                                    <label for="example-text-input" class="control-label col-form-label">
                                                                        เฟสบุ๊ค
                                                                    </label>
                                                                    <div class="col-sm">
                                                                        <input class="form-control" type="text" />
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <form class="form">
                                                                <div class="form-group row">
                                                                    <label for="example-text-input" class="control-label col-form-label">
                                                                        โครงการ
                                                                    </label>
                                                                    <div class="col-sm">
                                                                        <input class="form-control" type="text" />
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="row" style="padding-left: 10px; margin-top: -20px;">
                                                        <div class="col-sm-12">
                                                            <form class="form">
                                                                <div class="form-group row">
                                                                    <label for="example-text-input" class="control-label col-form-label">
                                                                        ที่ตั้ง
                                                                    </label>
                                                                    <div class="col-sm">
                                                                        <input class="form-control" type="text" />
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="row" style="margin-top: -20px; padding-left: 10px;">
                                                        <div class="col-sm-3">
                                                            <form class="form">
                                                                <div class="form-group row">
                                                                    <label for="example-text-input" class="control-label col-form-label">
                                                                        ประเภทคอนกรีต
                                                                    </label>
                                                                    <div class="col-sm">
                                                                        <input class="form-control" type="text" />
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <form class="form">
                                                                <div class="form-group row">
                                                                    <label for="example-text-input" class="control-label col-form-label">
                                                                        กำลังอัด
                                                                    </label>
                                                                    <div class="col-sm">
                                                                        <input class="form-control" type="text" />
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <form class="form">
                                                                <div class="form-group row">
                                                                    <label for="example-text-input" class="control-label col-form-label">
                                                                        ต้องการใช้
                                                                    </label>
                                                                    <div class="col-sm">
                                                                        <input class="form-control" type="text" />
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <form class="form">
                                                                <div class="form-group row">
                                                                    <label for="example-text-input" class="control-label col-form-label">
                                                                        ขนาดรถ
                                                                    </label>
                                                                    <div class="col-sm">
                                                                        <input class="form-control" type="text" />
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="row" style="padding-left: 10px; margin-top: -20px;">
                                                        <div class="col-sm-12">
                                                            <form class="form">
                                                                <div class="form-group row">
                                                                    <label for="example-text-input" class="control-label col-form-label">
                                                                        รายละเอียดเพิ่มเติม
                                                                    </label>
                                                                    <div class="col-sm">
                                                                        <input class="form-control" type="text" />
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="row" style="padding-left: 10px; padding-right: 10px;">
                                                        <div class="table-responsive m-t-10">
                                                            <table class="table table-striped" style="border: 1px solid #ededed;">
                                                                <thead class="text-center" style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium;">
                                                                    <tr>
                                                                        <th style="width: 5%;">ได้คิว</th>
                                                                        <th style="width: 15%;">แพล้นท์</th>
                                                                        <th>ติดต่อ</th>
                                                                        <th style="width: 11%;">วันที่ใช้</th>
                                                                        <th style="width: 7%;">เวลา</th>
                                                                        <th>ST</th>
                                                                        <th>ราคา/คิว</th>
                                                                        <th>ค่าขนส่ง</th>
                                                                        <th style="width: 10%;">ค่าคอนกรีต</th>
                                                                        <th>กำไร</th>
                                                                        <th>ลูกค้า</th>
                                                                        <th style="width: 10%;">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="text-center table-bordered" style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium;">
                                                                    <tr style="background-color: #ffffff;">
                                                                        <td class="centered custom-checkbox">
                                                                            <input type="checkbox" style="text-align: center;" />
                                                                        </td>
                                                                        <td>โมอาย คอลเซนเตอร์</td>
                                                                        <td>
                                                                            <img src="<?php echo site_assets_url('images/icons/phonetable.png'); ?>" width="15" height="15" />
                                                                        </td>
                                                                        <td>
                                                                            <input class="form-control" type="text" placeholder="01/08/2563" />
                                                                        </td>
                                                                        <td>
                                                                            <input class="form-control" type="text" placeholder="10:35" />
                                                                        </td>
                                                                        <td>210</td>
                                                                        <td>
                                                                            <input class="form-control" type="text" placeholder="2690.0" />
                                                                        </td>
                                                                        <td>
                                                                            <input class="form-control" type="text" placeholder="400 / คิว" />
                                                                        </td>
                                                                        <td><b>5380.00</b></td>
                                                                        <td>209.60</td>
                                                                        <td>
                                                                            <input class="form-control" type="text" placeholder="รอแจ้ง" />
                                                                        </td>
                                                                        <td>
                                                                            <label>
                                                                                <a href="#" style="color: #000;">แก้ไข</a>
                                                                            </label>
                                                                            l
                                                                            <label>
                                                                                <a href="#" style="color: #000;">ลบ</a>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
            </div> -->
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $(".panel-clr").click(function () {
            $(this).toggleClass("on");
        });

        $(".panel-collapse").on("show.bs.collapse", function () {
            $(this).siblings(".panel-heading").addClass("active");
        });

        $(".panel-collapse").on("hide.bs.collapse", function () {
            $(this).siblings(".panel-heading").removeClass("active");
        });

        // Date Picker
        jQuery(".mydatepicker, #datepicker").datepicker();
        jQuery("#datepicker-autoclose").datepicker({
            autoclose: true,
            todayHighlight: true,
        });

        jQuery("#date-range").datepicker({
            toggleActive: true,
        });

        // Daterange picker
        $(".input-daterange-datepicker").daterangepicker({
            buttonClasses: ["btn", "btn-sm"],
            applyClass: "btn-danger",
            cancelClass: "btn-inverse",
        });
        $(".input-daterange-timepicker").daterangepicker({
            timePicker: true,
            format: "MM/DD/YYYY h:mm A",
            timePickerIncrement: 30,
            timePicker12Hour: true,
            timePickerSeconds: false,
            buttonClasses: ["btn", "btn-sm"],
            applyClass: "btn-danger",
            cancelClass: "btn-inverse",
        });
        $(".input-limit-datepicker").daterangepicker({
            format: "MM/DD/YYYY",
            minDate: "06/01/2015",
            maxDate: "06/30/2015",
            buttonClasses: ["btn", "btn-sm"],
            applyClass: "btn-danger",
            cancelClass: "btn-inverse",
            dateLimit: {
                days: 6,
            },
        });

    });
</script>

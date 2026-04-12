<div class="page-wrapper" style="background: #fff;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div id="googleMap" style="width:100%;height:350px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="col-12 m-t-5">
                                                        <input class="form-control" type="text" placeholder="Contact ID">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="col-12 m-t-5">
                                                        <input class="form-control" type="text" value="" id="example-datetime-local-input" placeholder="*วัน-เวลาที่ติดต่อ" onfocus="(this.type='datetime-local')" onblur="(this.type='text')">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="col-12 m-t-5">
                                                        <input class="form-control" type="text" placeholder="*ชื่อ-นามสกุล">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="col-12 m-t-5">
                                                        <input class="form-control" type="text" value="" id="example-datetime-local-input" placeholder="*วัน-เวลาที่ใช้คอนกรีต" onfocus="(this.type='datetime-local')" onblur="(this.type='text')">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="col-12 m-t-5">
                                                        <input class="form-control" type="text" name="" placeholder="*หมายเลขโทรศัพท์">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="col-12 m-t-5">
                                                        <input class="form-control" type="text" name="" placeholder="Line ID">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 m-t-5">
                                                <input class="form-control" type="text" name="" placeholder="*โครงการ/ที่ตั้ง - เทพื้น เทคาม เทเสา ฯลฯ">
                                            </div>
                                            <div class="col-12 m-t-5">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" placeholder="ค้นหาตำแหน่งที่ตั้งด้วยชื่อสถานที่,ละติจูด-ลองติจูด,ลิงค์กูเกิ้ลแมพ">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <i class="fa fa-retweet"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 m-t-5">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <select class="form-control">
                                                            <option selected="">รถใหญ่</option>
                                                            <option>1</option>
                                                            <option>2</option>
                                                            <option>3</option>
                                                            <option>4</option>
                                                            <option>5</option>
                                                        </select>
                                                    </div>
                                                    <input class="form-control" type="type" name="" placeholder="*ปริมาณ [กว้าง(ม) x ยาว(ม) x หนา(ชม)]" style="margin-left: 5px;">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <i class="fa fa-retweet"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 m-t-5">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <select class="form-control">
                                                            <option selected="">ทั่วไป</option>
                                                            <option>1</option>
                                                            <option>2</option>
                                                            <option>3</option>
                                                        </select>
                                                    </div>
                                                    <select class="form-control" style="margin-left: 5px;">
                                                        <option selected="">180 กก./ตร.ซม.</option>
                                                        <option>1</option>
                                                        <option>2</option>
                                                        <option>3</option>
                                                        <option>4</option>
                                                        <option>5</option>
                                                    </select>
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
    </div>

</div>

<script>
function myMap() {
    var mapProp= {
      center:new google.maps.LatLng(51.508742,-0.120850),
      zoom:5,
    };
    var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
}
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAx1XaB5SWwkowhESNVjWoDhjVFPKyGt7Q&callback=myMap"></script>
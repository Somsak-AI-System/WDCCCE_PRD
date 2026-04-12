<html>
    <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width" />
      <meta name="next-head-count" content="3" />
      
      <link rel="preload" href="<?php echo site_assets_url('css/chat/5ac3f88d480817cd26e8.css');?>" as="style"/>
      <link rel="stylesheet" href="<?php echo site_assets_url('css/chat/5ac3f88d480817cd26e8.css');?>" data-n-g=""/>
      <link rel="stylesheet" href="<?php echo site_assets_url('css/chat/bootstrap337.min.css');?>" data-n-g=""/>
      <link rel="stylesheet" href="<?php echo site_assets_url('css/chat/bootstrap.min.css');?>" data-n-g=""/>
      <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
      <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
      <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap.min.js"></script>
      <script src="<?php echo site_assets_url('css/chat/popper.min.js');?>"></script>
      <script src="<?php echo site_assets_url('css/chat/bootstrap.min.js');?>"></script>
      <link rel="stylesheet" href="<?php echo site_assets_url('css/chat/style.css');?>" data-n-g=""/>
      <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap.min.css" data-n-g=""/>

      <link rel="stylesheet" href="<?php echo site_assets_url('css/custom.css');?>" data-n-g=""/>
      <noscript data-n-css=""></noscript>
    </head>
    
    <body>  
        <div id="__next">
            <div class="jsx-3075241901 h-screen overflow-hidden">
                <div class="jsx-3075241901 flex">
                    <div class="sidebar flex-none h-screen" style="width: 100% !important">
                        <div class="px-2 h-customer-information-bar border-r border-gray-default">
                            <div class="px-3 pt-4">
                                <h2 class="font-medium text-lg leading-3">การตั้งค่า</h2>
                                <span class="total-list text-gray-light">มอดูลอาร์กิวเมนต์ลีนุกซ์เดสก์ท็อป ทรานแซ็คชันออราเคิล</span>
                            </div>

                            <div class="flex text-gray-light text-base lg:text-xs" style="height: 10%"></div>

                            <div class="relative">
                                <input name="keyword" class="block w-full my-2 text-xs py-2 pl-2 pr-10 text-black border border-gray-200 focus:border-gray-300" type="text" placeholder="ค้นหาโดยชื่อแชท แท็ก และข้อความได้" value="" />
                                <img class="absolute object-contain h-10 top-0 right-0 p-2" src="https://moaioc.moai-crm.com/agent/icon/Icon_Search_Grey.png" alt="search" />
                            </div>
                            
                        </div>
                        <div class="scroll-bar-wrap" style="height: 100% !important">

                              <div id="customer-list" class="chat-list chat-list-1 overflow-y-scroll h-full border-r border-gray-default">
                                <div class="infinite-scroll-component__outerdiv">
                                    <div class="infinite-scroll-component" style="height: auto; overflow: hidden auto;">
                                        <ul style="height: 800px">
                                            <li class="cursor-pointer border-chat-screen p-2">

                                              <div class="flex flex-wrap text-center content-center relative rounded-md h-full py-2 px-3 cursor-pointer text-black bg-white block w-full my-2 text-xs py-2 pl-2 pr-10 text-black border border-gray-200 focus:border-gray-300"> 
                                                  <span class="w-28 overflow-hidden text-left border-r border-fuchsia-900 pr-0 text-xs font-medium leading-5" style="border: 0px;">บรอดแคสต์</span>
                                                  <img class="absolute object-contain h-10 top-0 right-0 p-2" src="<?php echo site_assets_url('images/icons/caret-right-black.png'); ?>" alt="search">
                                              </div>

                                              <div class="flex flex-wrap text-center content-center relative rounded-md h-full py-2 px-3 cursor-pointer text-black bg-white block w-full my-2 text-xs py-2 pl-2 pr-10 text-black border border-gray-200 focus:border-gray-300"> 
                                                  <span class="w-28 overflow-hidden text-left border-r border-fuchsia-900 pr-0 text-xs font-medium leading-5" style="border: 0px;">รายชื่อผู้ติดต่อ</span>
                                                  <img class="absolute object-contain h-10 top-0 right-0 p-2" src="<?php echo site_assets_url('images/icons/caret-right-black.png'); ?>" alt="search">
                                              </div>

                                              <div class="flex flex-wrap text-center content-center relative rounded-md h-full py-2 px-3 cursor-pointer text-black bg-white block w-full my-2 text-xs py-2 pl-2 pr-10 text-black border border-gray-200 focus:border-gray-300"> 
                                                  <span class="w-28 overflow-hidden text-left border-r border-fuchsia-900 pr-0 text-xs font-medium leading-5" style="border: 0px;">ช่องทางการเชื่อมต่อ</span>
                                                  <img class="absolute object-contain h-10 top-0 right-0 p-2" src="<?php echo site_assets_url('images/icons/caret-right-black.png'); ?>" alt="search">
                                              </div>

                                              <div class="flex flex-wrap text-center content-center relative rounded-md h-full py-2 px-3 cursor-pointer text-black bg-white block w-full my-2 text-xs py-2 pl-2 pr-10 text-black border border-gray-200 focus:border-gray-300"> 
                                                  <span class="w-28 overflow-hidden text-left border-r border-fuchsia-900 pr-0 text-xs font-medium leading-5" style="border: 0px;">ช่วยเหลือ</span>
                                                  <img class="absolute object-contain h-10 top-0 right-0 p-2" src="<?php echo site_assets_url('images/icons/caret-right-black.png'); ?>" alt="search">
                                              </div>
                                                                                                
                                            </li>
                                        </ul>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="cover-bar"></div>
                        </div>
                    </div>

                    <div class="chat-wrapper flex-1 bg-chat-screen h-screen bg-white">
                        <div class="flex h-full">
                            <div class="flex-1">
                                <div class="customer-information-bar h-customer-information-bar border-b border-gray-default bg-white">
                                    <div class="border-b border-gray-default pt-3 px-3 pb-2">
                                       
                                        <div class="flex">
                                            <div class="pl-3">
                                                <span class="font-semibold text-black">บรอดแคสต์
                                                <div class="text-sm space-x-2">
                                                    <h5 class="text-xs font-medium text-black">มอดูลอาร์กิวเมนต์ลีนุกซ์เดสก์ท็อป ทรานแซ็คชันออราเคิลแซนเนล ฟอร์แมต</h5>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-right -mt-3">
                                            <div class="MuiFormControl-root">
                                                <div class="MuiInputBase-root jss1 rounded-sm MuiInputBase-formControl">
                                                  
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="p-1-5 flex gap-3 block-create">
                                        <div class="h-10">
                                          <a href="<?php echo site_url('settings/broadcastmanage'); ?>" >
                                            <div class="flex flex-wrap text-center content-center relative rounded-md h-full p-1 px-3 cursor-pointer text-white bg-purple cbroadcast">
                                                <img class="absolute object-contain h-10 top-0 left-0 p-2" src="<?php echo site_assets_url('images/icons/plus-circle-duotone-white.png'); ?>" alt="search">

                                                <span class="w-34 overflow-hidden border-r border-fuchsia-900 pr-0 text-right text-xs font-medium leading-5 " style="border: 0px;">สร้างบรอดแคสต์ SAP</span>
                                                 
                                            </div>
                                          </a>
                                        </div>
                                        
                                    </div>

                                    
                                </div>
                               
                                <div class="jsx-4069042043 list-screen" style="background-color: #fff;"><!-- container mt-3 p-5  -->
                                  <div class="row m-0" style="height: calc(100% - 0px);  display: flex;"> 
                                    
                                    <div class="col-md-8 p-0 d-flex flex-wrap">

                                      <div class="row m-0 p-0 min-w-full">
                                       
                                        <div class="col-md-12 p-0 d-flex border-r border-gray-default">
                                          <div class="my-card-content bg-white p-1 m-2 d-flex flex-column MuiFormControl-fullWidth">

                                            <div class="flex my-1">
                                                <div class="flex-none relative">
                                                    <div class="pt-02 text-xs text-gray-light">
                                                      <a href="#">
                                                        <span class="text-gray-light" style="font-size: 1rem">
                                                          <a href="<?php echo site_url('settings/index'); ?>"><i class="fa fa-angle-left" aria-hidden="true"></i> ย้อนกลับ
                                                        </span></a>
                                                      </a>
                                                    </div>
                                                </div>
                                                <div class="flex-none ml-auto text-right">
                                                    <style type="text/css">
                                                      .action-list{
                                                        transform: translate3d(-40px, 30px, 0px) !important;
                                                      }
                                                    </style>
                                                    <div class="pb-1 text-xs text-gray-light dropdown" style="cursor: pointer;">
                                                        <img class="w-7" src="<?php echo site_assets_url('images/icons/dots-three-white.png'); ?>" data-toggle="dropdown">
                                                        <div class="action-list dropdown-menu">
                                                          <a class="dropdown-item" href="#">แก้ไข</a>
                                                          <a class="dropdown-item" href="#">ทำซ้ำ</a>
                                                          <a class="dropdown-item red" href="#">ลบ</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                              <div class="col-md-12">
                                                <img class="rounded mx-auto d-block" width="20%" height="20%" src="<?php echo site_assets_url('images/icons/Broadcast-Img.jpg'); ?>">
                                              </div>
                                              <div class="col-md-12" align="center">
                                                <!-- <label>ส่วนลด 5% สำหรับการเปลี่ยนถ่ายน้ำมันเครื่องที่สาขา</label> -->
                                                <h2 class="font-medium text-base leading-8">ส่วนลด 5% สำหรับการเปลี่ยนถ่ายน้ำมันเครื่องที่สาขา</h2>
                                              </div>
                                              <div class="col-md-12" style="margin-bottom: 10px">
                                                
                                                <div class="col-md-4"></div>
                                                <div class="col-md-2">
                                                  <img class="rounded float-right d-block" src="<?php echo site_assets_url('images/icons/SetTime.png'); ?>">
                                                </div>
                                                <div class="col-md-1">
                                                  <img class="rounded mx-auto d-block" width="50%" height="50%" src="<?php echo site_assets_url('images/icons/logo_linechat.png'); ?>">
                                                </div>
                                                <div class="col-md-2"><span style="padding:0.3rem;font-size: 14px;border: 1px solid #cdcdcd;background-color: #f2f2f2;border-radius: 10px;">12/03/2021 12:33</span></div>
                                                <div class="col-md-3"></div>
                                                  
                                              </div>

                                              <div class="col-md-12" style="border: 1px solid #d1d5db; border-radius: 10px; margin-bottom: 20px">
                                                <div class="box-body" style="margin-top: 20px">
                                                <div class="form-group">
                                                  <h6>กลุ่มเป้าหมาย</h6>
                                                </div>
                                                <div class="form-group">
                                                  <label>อัพโหลดไฟล์แบบกำหนดเอง</label>
                                                </div>
                                                <div class="form-group">
                                                  <h7>ไฟล์แนบ</h7>
                                                </div>
                                                <div class="form-group">
                                                  <!-- <a href="#">AccountList_Vocher_code_5%.xlsx</a> -->
                                                  <a href="#" src="" style="text-decoration: revert; color: blue">Advance_Broadcast_template.xlsx<img src="<?php echo site_assets_url('images/icons/download-simple-orange.png'); ?>" style="width: 20px; height: 20px;margin: auto; display: -webkit-inline-box; margin: 0px 0px 10px 10px;"></a>
                                                </div>
                                                </div>
                                              </div>

                                              <div class="col-md-12" style="border: 1px solid #d1d5db; border-radius: 10px;">
                                                <div class="box-body" style="margin-top: 20px">
                                                  <div class="form-group">
                                                    <h6>ผลลัพธ์จากการบรอดแคสต์</h6>
                                                  </div>
                                                  <style type="text/css">
                                                    .ublock{
                                                      border: 1px solid #d1d5db;
                                                      border-radius: 10px;
                                                    }
                                                  </style>
                                                  <div class="col-md-12 margin-bottom-20">
                                                    <div class="col-md-3">
                                                      <div class="col-md-10 ublock" style="height: 120px">
                                                        <div class="col-md-12 mt-4 my-4"><h6 style="color: #9c9c9c">รายการทั้งหมด</h6></div>
                                                        <div class="col-md-12"><h5 style="color: #e97126;float: left;font-size: 1.5rem">130</h5><h6 style="color: #e97126;float: right; margin-top: 8px;font-weight: 400"> รายการ</h6></div>
                                                      </div>
                                                    </div>
                                                    
                                                    <div class="col-md-3">
                                                      <div class="col-md-10 ublock" style="height: 120px">
                                                        <div class="col-md-12 mt-4 my-4" style="padding-left: 10px;padding-right: 10px;"><h6 style="color: #9c9c9c">รายการพร้อมส่ง</h6></div>
                                                        <div class="col-md-12"><h5 style="color: #e97126;float: left;font-size: 1.5rem">33</h5>
                                                          <h6 style="color: #e97126;float: right; margin-top: 8px;font-weight: 400"> รายการ</h6>
                                                        </div>
                                                      </div>
                                                    </div>
                                                    
                                                    <div class="col-md-3">
                                                      <div class="col-md-10 ublock" style="height: 120px">
                                                        <div class="col-md-12 mt-4 my-4" ><h6 style="color: #9c9c9c">รายการส่งแล้ว</h6></div>
                                                        <div class="col-md-12"><h5 style="color: #e97126;float: left;font-size: 1.5rem">60</h5>
                                                          <h6 style="color: #e97126;float: right; margin-top: 8px;font-weight: 400"> รายการ</h6>
                                                        </div>
                                                      </div>
                                                    </div>
                                                    
                                                    <div class="col-md-3">
                                                      <div class="col-md-10 ublock" style="height: 120px">
                                                        <div class="col-md-12 mt-4 my-4" style="padding-left: 10px;padding-right: 10px;"><h6 style="color: #9c9c9c">รายการผิดพลาด</h6></div>
                                                        <div class="col-md-12"><h5 style="color: #e97126;float: left;font-size: 1.5rem">0</h5>
                                                          <h6 style="color: #e97126;float: right; margin-top: 8px;font-weight: 400"> รายการ</h6>

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
                                    
                                    <div class="col-md-4 p-0">
                                      <div class="bg-white m-2 p-2 position-absolute all-0 d-flex flex-column">

                                        <!-- <h2 class="font-medium text-base leading-8">ประวัติบรอดแคสต์ล่าสุด</h2> -->
                                        <div class="iphone">
                                        
                                          <div class="screen">
                                            <div class="receive message">
                                              nm, I'm growing back my mustache and admiring myself in the mirror.
                                              nm, I'm growing back my mustache and admiring myself in the mirror.
                                            </div>
                                            <div class="receive message">
                                              Good idea, other people need to admire my mustache, ttyl.
                                            </div>
                                            <div class="receive message">
                                              Good idea, other people need to admire my mustache, ttyl.
                                              Good idea, other people need to admire my mustache, ttyl.
                                              Good idea, other people need to admire my mustache, ttyl.
                                            </div>
                                            <div class="receive message">
                                              Good idea, other people need to admire my mustache, ttyl.
                                            </div>
                                            <div class="receive message">
                                              Good idea.
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

    </body>

</html>

<script type="text/javascript">
  
  $(document).ready(function() {
    
  });
</script>

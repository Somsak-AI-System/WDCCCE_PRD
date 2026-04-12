<div id="quick-import-wizard-step-2" class="quick-import-wizard-step hidden flex flex-col gap-4" style="width:600px">
    <div class="w-full flex flex-col gap-2">
        <div class="font-black text-xl">รีวิว</div>
        <div class="text-slate-500">ผลลัพธ์จากการตรวจสอบไฟล์ หากพบข้อผิดพลาดระบบจะไม่สามารถนำเข้าได้</div>
    </div>

    <div class="flex flex-col p-2 gap-2 border border-slate-200 rounded-lg items-center" id="loading">
        <span class="loader-1"></span>
    </div>

    <div class="w-full hidden" id="content">        
        <div class="flex flex-col p-2 gap-2 border border-slate-200 rounded-lg">
            <div class="w-full flex items-center justify-between p-2 bg-slate-100 rounded-lg">
                <div class="flex items-center gap-3">
                    <img src="asset/images/svg/Excel.svg" class="w-10 h-10" />
                    <div class="flex flex-col">
                    <div class="font-black" id="file-name">Filename.xlsx</div>
                    <div class="text-slate-300" id="file-size">230.00 KB</div>
                    </div>
                </div>
                
            </div>

            <div class="grid grid-cols-3 gap-2">
                <div class="flex flex-col gap-1 p-2 bg-slate-100 rounded-lg">
                    <div class="font-black">รายการสินค้า</div>
                    <div class="flex items-center gap-2">
                        <div>จำนวน : </div>
                        <div class="flex items-center gap-2 py-1 px-2 bg-green-500 rounded-full">
                            <img src="asset/images/svg/CheckW.svg" class="w-4 h-w" />
                            <div class="text-xs text-white"><span id="new-items">0</span> รายการ</div>
                        </div>
                    </div>
                    <div class="text-blue-500 cursor-pointer" onclick="jQuery.exportData('new')">ดาวน์โหลด</div>
                </div>

                <!-- <div class="flex flex-col gap-1 p-2 bg-slate-100 rounded-lg">
                    <div class="font-black">รายการอัพเดท</div>
                    <div class="flex items-center gap-2">
                        <div>จำนวน : </div>
                        <div class="flex items-center gap-2 py-1 px-2 bg-orange-500 rounded-full">
                            <img src="asset/images/svg/CheckW.svg" class="w-4 h-w" />
                            <div class="text-xs text-white"><span id="update-items">0</span> รายการ</div>
                        </div>
                    </div>
                    <div class="text-blue-500 cursor-pointer" onclick="jQuery.exportData('update')">ดาวน์โหลด</div>
                </div> -->

                <div class="flex flex-col gap-1 p-2 bg-slate-100 rounded-lg">
                    <div class="font-black">รายการผิดพลาด</div>
                    <div class="flex items-center gap-2">
                        <div>จำนวน : </div>
                        <div class="flex items-center gap-2 py-1 px-2 bg-red-500 rounded-full">
                            <img src="asset/images/svg/CheckW.svg" class="w-4 h-w" />
                            <div class="text-xs text-white"><span id="error-items">0</span> รายการ</div>
                        </div>
                    </div>
                    <div class="text-blue-500 cursor-pointer" onclick="jQuery.exportData('error')">ดาวน์โหลด</div>
                </div>
            </div>
        </div>
    </div>
</div>
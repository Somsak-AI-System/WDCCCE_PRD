<div id="quick-import-wizard-step-4" class="quick-import-wizard-step hidden flex flex-col gap-4" style="width:600px">
    <div class="w-full flex flex-col gap-2">
        <div class="font-black text-xl">เสร็จสิ้น</div>
        <div class="text-slate-500">ผลลัพธ์จากการตรวจสอบไฟล์ หากพบข้อผิดพลาดระบบจะไม่สามารถนำเข้าได้</div>
    </div>

    <div class="w-full">
        <div class="flex flex-col px-2 py-10 gap-4 border border-slate-200 rounded-lg items-center">
            <div class="">
                <img src="asset/images/svg/CheckG.svg" style="height:100px; width:100px;" />
            </div>
            <div>นำเข้าข้อมูลเสร็จสมบูรณ์</div>
            <div class="grid grid-cols-2 gap-2 w-full hidden">
                <div class="flex flex-col gap-1 p-2 bg-green-100 rounded-lg">
                    <div class="font-black">รายการใหม่</div>
                    <div class="flex items-center gap-2">
                        <div>จำนวน : </div>
                        <div class="flex items-center gap-2 py-1 px-2 bg-green-500 rounded-full">
                            <img src="asset/images/svg/CheckW.svg" class="w-4 h-w" />
                            <div class="text-xs text-white"><span id="new-items">0</span> รายการ</div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-1 p-2 bg-green-100 rounded-lg">
                    <div class="font-black">รายการอัพเดท</div>
                    <div class="flex items-center gap-2">
                        <div>จำนวน : </div>
                        <div class="flex items-center gap-2 py-1 px-2 bg-green-500 rounded-full">
                            <img src="asset/images/svg/CheckW.svg" class="w-4 h-w" />
                            <div class="text-xs text-white"><span id="update-items">0</span> รายการ</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
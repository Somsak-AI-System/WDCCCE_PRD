<div id="quick-import-wizard-step-1" class="quick-import-wizard-step flex flex-col gap-4" style="width:640px">
    <div class="w-full flex flex-col gap-2">
        <div class="font-black text-xl">อัปโหลด</div>
        <div class="text-slate-500">ดาวน์โหลดเทมเพลท และจัดการข้อมูลให้ตรงตามไฟล์เทมเพลทก่อนอัปโปลดไฟล์ Excel ของคุณ</div>
    </div>

    <div class="w-full">
        <div class="flex flex-col p-2 gap-4 border border-slate-200 rounded-lg">
            <div class="grid grid-cols-3 gap-2">
                <div class="flex items-center justify-center border-2 border-dotted border-slate-300 rounded-lg" style="height:150px">
                    <div class="flex flex-col gap-1">
                        <div class="text-center">
                            <img src="asset/images/svg/Excel.svg" class="w-8 h-8" />
                        </div>
                        <div class="text-center text-xs">ไม่รู้จะเริ่มต้นอย่างไร</div>
                        <div class="text-center text-xs"><a href="documents/templates/TemplatePricelistImport.xlsx" target="_blank" class="text-blue-500">กดที่นี้เพื่อดาวน์โหลด</a> เทมเพลทนี้</div>
                    </div>
                </div>

                <div class="col-span-2 flex items-center justify-center border-2 border-dotted border-slate-300 bg-slate-100 rounded-lg" id="dropArea" style="height:150px">
                    <div class="flex flex-col gap-1">
                        <div class="text-center">
                            <img src="asset/images/svg/Insert.svg" class="w-8 h-8" />
                        </div>
                        <div class="text-center text-xs">ลาก และ วางไฟล์ที่นี้</div>
                        <div class="text-center text-xs">หรือ <a href="javascript:void(0)" class="text-blue-500" onclick="jQuery.selectUploadFile()">คลิกเพื่ออัปโหลด</a></div>
                        <input type="file" id="fileInput" class="hidden" accept="<?php echo implode(',', $acceptType); ?>">
                    </div>
                </div>
            </div>

            <div id="file-selected" class="flex flex-col p-2 gap-4 hidden">
                <div class="w-full flex flex-col gap-2">
                    <div class="font-black text-xl">ไฟล์ที่อัปโหลดแล้ว</div>
                </div>

                <div class="flex items-center justify-between p-2 bg-slate-100 rounded-lg">
                    <div class="flex items-center gap-3">
                        <img src="asset/images/svg/Excel.svg" class="w-10 h-10" />
                        <div class="flex flex-col">
                            <div class="font-black" id="file-name">Filename.xlsx</div>
                            <div class="text-slate-300" id="file-size">230.00 KB</div>
                        </div>
                    </div>
                    <img src="asset/images/svg/XR.svg" class="w-6 h-6 cursor-pointer" onclick="jQuery.clearSelectdFile()" />
                </div>
            </div>
            
        </div>     
    </div>
</div>
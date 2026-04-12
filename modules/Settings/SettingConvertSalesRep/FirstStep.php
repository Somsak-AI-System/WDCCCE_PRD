<div id="rep-step-first" class="first-step mt-2 border">
    <div class="p-2 border-b bg-slate-200">
        <div class="font-black"><?php echo $tabData['name']; ?></div>
        <div class=""><?php echo $tabData['description']; ?></div>
    </div>
    <div class="p-2 border-b">
        <div class="font-black">Choose the old sales</div>
        <div class="flex items-center gap-2">
            <div class="w-40"><span class="text-red-500">*</span> Old sale Rep. : </div>
            <div>
                <input id="old_sale_rep" style="width:200px;" />
            </div>
        </div>
    </div>
    <div class="p-2 border-b" style="height:250px;">
        <div class="font-black">Choose the module below that you want to work on</div>
        <div class="flex items-center gap-2">
            <div class="w-40"><span class="text-red-500">*</span> Modules : </div>
            <div>
                <input id="modules" style="width:200px;" />
            </div>
            <div class="flex items-center gap-2 ml-2">
                <input type="checkbox" id="convert_quotation_with_so" name="convert_quotation_with_so" />
                <label for="convert_quotation_with_so" class="text-red-600">Convert quotation with SO</label>
            </div>
        </div>
    </div>
    <div class="p-2 border-b text-right bg-slate-200">
        <button type="button" onclick="$.checkStep1()">
            Next
        </button>
    </div>
</div>
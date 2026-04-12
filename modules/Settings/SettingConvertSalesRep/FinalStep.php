<div id="rep-step-final" class="final-step mt-2 border hidden">
    <div class="p-2 border-b bg-slate-200">
        <div class="font-black"><?php echo $tabData['name']; ?></div>
        <div class=""><?php echo $tabData['description']; ?></div>
    </div>
    <div class="p-2 border-b">
        <div class="font-black">Old Sales Rep. that you seleted</div>
        <div class="flex items-center gap-2">
            <div class="w-40">Old sale Rep. : </div>
            <div>
                <input id="old_sale_rep_view" class="bg-slate-200" readonly style="width:200px;" />
            </div>
        </div>
    </div>
    <div class="p-2 border-b">
        <div class="font-black">Choose the New Sales Rep. who wants to receive the data from Old Sales Rep.</div>
        <div class="flex items-center gap-2">
            <div class="w-40"><span class="text-red-500">*</span> New Sale Rep. : </div>
            <div>
                <input id="new_sale_rep" style="width:210px;" />
            </div>
        </div>
    </div>
    <div class="p-2 border-b">
        <div class="font-black">Remark</div>
        <div>
            <textarea id="remark" class="w-full" rows="5"></textarea>
        </div>
    </div>
    <div class="p-2 border-b text-right bg-slate-200">
        <div class="text-right">
            <button type="button" class="btn-back-step" onclick="$.backStep(this, 'final')">
                Back
            </button>
            <button type="button" id="btn-final-step" onclick="$.checkStepFinal()">
                Confirm to Convert
            </button>
        </div>
        <div class="text-red-500">
            *After convert data, you cannot cancel or undo the process, Please double-check before pressing the 'Confirm to Convert' button.
        </div>
    </div>
</div>
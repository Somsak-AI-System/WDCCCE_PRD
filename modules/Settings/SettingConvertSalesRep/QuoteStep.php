<div id="rep-step-quotes" class="module-step mt-2 border hidden">
    <div class="p-2 border-b bg-slate-200">
        <div class="font-black">Change sale Rep.</div>
        <div class="">Search and select your Quotation data</div>
    </div>
    <div class="p-2 border-b flex flex-col gap-1">
        <div class="font-black">Searc the data that you want to convert : </div>
        <div class="flex items-center gap-2" style="display:none;">
            <div class="w-40">Quotation No : </div>
            <div>
                <input type="text" id="quote_no" style="width:200px;" />
            </div>
        </div>
        <div class="flex items-center gap-2" style="display:none;">
            <div class="w-40">Quotation Name : </div>
            <div>
                <input type="text" id="quote_name" style="width:200px;" />
            </div>
        </div>

        <div class="flex items-center gap-2">
            <div class="w-40">Quotation Status : </div>
            <div>
                <input id="quotation_status" style="width:200px;" />
            </div>
        </div>

        <div class="flex items-center gap-2">
            <div class="w-40">วันที่ในใบเสนอราคา : </div>
            <div>
                <input id="quotation_date_from" class="easyui-datebox" style="width:200px;" data-options="formatter:function(date){var d=date;var day=(d.getDate()<10?'0':'')+d.getDate();var month=(d.getMonth()+1<10?'0':'')+(d.getMonth()+1);return day+'-'+month+'-'+d.getFullYear();}" />
            </div>
        </div>

        <div class="flex items-center gap-2">
            <div class="w-40">ถึง </div>
            <div>
                <input id="quotation_date_to" class="easyui-datebox" style="width:200px;" data-options="formatter:function(date){var d=date;var day=(d.getDate()<10?'0':'')+d.getDate();var month=(d.getMonth()+1<10?'0':'')+(d.getMonth()+1);return day+'-'+month+'-'+d.getFullYear();}" />
            </div>
        </div>
        
        <div class="text-center">
            <button type="button" id="btn-search-quotes" onclick="$.searchQuotes()">
                Search
            </button>
        </div>
    </div>
    <div class="p-2 border-b">
        <div class="font-black">Select the Quotes : </div>
        <div class="flex items-start justify-between">
            <div>
                <input id="quotes_left" />
            </div>
            <div class="flex flex-col gap-2">
                <button type="button" onclick="$.setSelectedQuotes()">
                    <img src="asset/images/svg/chevron-right.svg" class="w-4 h-4" />
                </button>
                <button type="button" onclick="$.setUnSelectedQuotes()">
                    <img src="asset/images/svg/chevron-left.svg" class="w-4 h-4" />
                </button>
                <button type="button" onclick="$.setSelectedQuotes('all')">
                    <img src="asset/images/svg/chevron-double-right.svg" class="w-4 h-4" />
                </button>
                <button type="button" onclick="$.setUnSelectedQuotes('all')">
                    <img src="asset/images/svg/chevron-double-left.svg" class="w-4 h-4" />
                </button>

            </div>
            <div>
                <input id="quotes_right" />
            </div>
        </div>
    </div>
    <div class="p-2 border-b text-right bg-slate-200">
        <button type="button" onclick="$.backStep(this, '20')">
            Back
        </button>
        <button type="button" onclick="$.checkStepQuotes()">
            Next
        </button>
    </div>
</div>
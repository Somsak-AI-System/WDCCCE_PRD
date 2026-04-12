<div id="rep-step-account" class="module-step mt-2 border hidden">
    <div class="p-2 border-b bg-slate-200">
        <div class="font-black">Change sale Rep.</div>
        <div class="">Search and select your Account data</div>
    </div>
    <div class="p-2 border-b flex flex-col gap-1">
        <div class="font-black">Searc the data that you want to convert : </div>
        <div class="flex items-center gap-2">
            <div class="w-40">Account No : </div>
            <div>
                <input type="text" id="account_no" style="width:200px;" />
            </div>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-40">Account Name TH : </div>
            <div>
                <input type="text" id="account_name_th" style="width:200px;" />
            </div>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-40">Account Name EN : </div>
            <div>
                <input type="text" id="account_name_en" style="width:200px;" />
            </div>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-40">Account Status : </div>
            <div>
                <input id="account_status" style="width:210px;" />
            </div>
        </div>
        <div class="text-center">
            <button type="button" id="btn-search-account" onclick="$.searchAccount()">
                Search
            </button>
        </div>
    </div>
    <div class="p-2 border-b">
        <div class="font-black">Select the Account : </div>
        <div class="flex items-start justify-between">
            <div>
                <input id="account_left" />
            </div>
            <div class="flex flex-col gap-2">
                <button type="button" onclick="$.setSelectedAccount()">
                    <img src="asset/images/svg/chevron-right.svg" class="w-4 h-4" />
                </button>
                <button type="button" onclick="$.setUnSelectedAccount()">
                    <img src="asset/images/svg/chevron-left.svg" class="w-4 h-4" />
                </button>
                <button type="button" onclick="$.setSelectedAccount('all')">
                    <img src="asset/images/svg/chevron-double-right.svg" class="w-4 h-4" />
                </button>
                <button type="button" onclick="$.setUnSelectedAccount('all')">
                    <img src="asset/images/svg/chevron-double-left.svg" class="w-4 h-4" />
                </button>

            </div>
            <div>
                <input id="account_right" />
            </div>
        </div>
    </div>
    <div class="p-2 border-b text-right bg-slate-200">
        <button type="button" onclick="$.backStep(this, '6')">
            Back
        </button>
        <button type="button" onclick="$.checkStepAccount()">
            Next
        </button>
    </div>
</div>
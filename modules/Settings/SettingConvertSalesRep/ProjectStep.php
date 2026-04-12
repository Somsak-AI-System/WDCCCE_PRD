<div id="rep-step-project" class="module-step mt-2 border hidden">
    <div class="p-2 border-b bg-slate-200">
        <div class="font-black">Change sale Rep.</div>
        <div class="">Search and select your Project Order data</div>
    </div>
    <div class="p-2 border-b flex flex-col gap-1">
        <div class="font-black">Searc the data that you want to convert : </div>
        <div class="flex items-center gap-2">
            <div class="w-40">Project No : </div>
            <div>
                <input type="text" id="project_no" style="width:200px;" />
            </div>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-40">Project Name : </div>
            <div>
                <input type="text" id="project_name" style="width:200px;" />
            </div>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-40">Project Status : </div>
            <div>
                <input id="project_status" style="width:210px;" />
            </div>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-40">Project Opportunity : </div>
            <div>
                <input id="project_opportunity" style="width:210px;" />
            </div>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-40">Project Size : </div>
            <div>
                <input id="project_size" style="width:210px;" />
            </div>
        </div>
        <!-- <div class="flex items-center gap-2">
                                                <div class="w-40">Main Acount : </div>
                                                <div>
                                                    <input type="text" id="project_account" />
                                                </div>
                                            </div> -->
        <div class="text-center">
            <button type="button" id="btn-search-project" onclick="$.searchProject()">
                Search
            </button>
        </div>
    </div>
    <div class="p-2 border-b">
        <div class="font-black">Select the Project Order : </div>
        <div class="flex items-start justify-between">
            <div>
                <input id="project_left" />
            </div>
            <div class="flex flex-col gap-2">
                <button type="button" onclick="$.setSelectedProject()">
                    <img src="asset/images/svg/chevron-right.svg" class="w-4 h-4" />
                </button>
                <button type="button" onclick="$.setUnSelectedProject()">
                    <img src="asset/images/svg/chevron-left.svg" class="w-4 h-4" />
                </button>
                <button type="button" onclick="$.setSelectedProject('all')">
                    <img src="asset/images/svg/chevron-double-right.svg" class="w-4 h-4" />
                </button>
                <button type="button" onclick="$.setUnSelectedProject('all')">
                    <img src="asset/images/svg/chevron-double-left.svg" class="w-4 h-4" />
                </button>

            </div>
            <div>
                <input id="project_right" />
            </div>
        </div>
    </div>
    <div class="p-2 border-b text-right bg-slate-200">
        <button type="button" onclick="$.backStep(this, '50')">
            Back
        </button>
        <button type="button" onclick="$.checkStepProject()">
            Next
        </button>
    </div>
</div>
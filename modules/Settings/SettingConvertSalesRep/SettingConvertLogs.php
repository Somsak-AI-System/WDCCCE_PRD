<div class="flex flex-col gap-1 p-2">
    <div class="flex items-center gap-1">
        <div class="w-30 bg-slate-200 border-slate-200">Log Data Date</div>
        <input id="log_date_start" />
        <div class="w-30 bg-slate-200 border-slate-200">ถึง</div>
        <input id="log_date_end" />
    </div>

    <div class="flex items-center gap-1">
        <div class="w-30 bg-slate-200 border-slate-200">Old Sales Rep.</div>
        <input id="log_old_sale" />
        <div class="w-30 bg-slate-200">New Sales Rep.</div>
        <input id="log_new_sale" />
    </div>

    <div class="flex items-center gap-1">
        <div class="w-30 bg-slate-200 border-slate-200">Module</div>
        <input id="log_module" />
    </div>
</div>

<div class="flex gap-1 p-2">
    <button type="button" id="btn-export-log" onclick="$.exportConvertLog()">Export to Excel</button>
    <button type="button" id="btn-search-log" onclick="$.searchConvertLog()">Search</button>
</div>

<div class="p-2">
    <div id="convert-log"></div>
</div>
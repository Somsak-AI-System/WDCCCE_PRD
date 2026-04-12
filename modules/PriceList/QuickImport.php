<?php
require_once('Smarty_setup.php');
require_once('include/database/PearDatabase.php');
require_once('include/utils/UserInfoUtil.php');
global $adb, $currentModule;

$parenttab = getParentTab();
$module = $currentModule;
?>
<link rel="stylesheet" href="asset/css/style-enigma.css" />
<link rel="stylesheet" href="asset/css/custom.css" />
<script src="asset/js/jquery-3.2.1.min.js"></script>
<div class="mt-2" style="font-family: 'PromptMedium'">
<div class='flex flex-col h-full w-full gap-2'>
    <div class="flex items-center justify-start p-4 gap-2 rounded-lg bg-white">
        <div><?php echo $parenttab; ?></div>
        <img src="asset/images/svg/chevron-right.svg" />
        <div class="font-black"><?php echo $module; ?></div>
    </div>

    <div class="w-full flex flex-col gap-4 bg-white rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div class="font-black text-xl">นำเข้า <?php echo $module; ?></div>
            <div class="hidden">
                <a href="index.php?module=<?php echo $module; ?>&action=QuickImportHistory&return_module=<?php echo $module; ?>&parenttab=Inventory" class="flex items-center gap-2 reports-btn-list reports-btn-default">
                    <img src="asset/images/svg/History.svg" />
                    <div>ประวัตินำเข้า</div>
                </a>
            </div>
        </div>

        <div class="quick-import-wizard">
            <div id="import-wizard-button-step-1" class="items current-state">
                <button class="button">1</button>
                <div class="title">อัปโหลด</div>
            </div>
            <div id="import-wizard-button-step-2" class="items">
                <button class="button">2</button>
                <div class="title">รีวิว</div>
            </div>
            <div id="import-wizard-button-step-3" class="items">
                <button class="button">3</button>
                <div class="title">นำเข้า</div>
            </div>
            <div id="import-wizard-button-step-4" class="items">
                <button class="button" onclick="jQuery.switchState(4)">4</button>
                <div class="title">เสร็จสิ้น</div>
            </div>
        </div>

        <div class="w-full">
            <?php include('QuickImport/state1.php'); ?>
            <?php include('QuickImport/state2.php'); ?>
            <?php include('QuickImport/state3.php'); ?>
            <?php include('QuickImport/state4.php'); ?>
        </div>
           
        <div class="w-full flex itemx-center justify-between pt-2 border-t border-slate-100">
            <div class="flex items-center gap-2">
                <a href="index.php?module=<?php echo $module; ?>&action=index&parenttab=Inventory" id="wizard-cancel-btn" class="custom-btn custom-btn-default">
                    ยกเลิก
                </a>
                <button type="button" id="wizard-prev-btn" class="hidden custom-btn custom-btn-default" onclick="jQuery.changeState(-1)">
                    ย้อนกลับ
                </button>
            </div>
            
            <div class="flex items-center gap-2">
                <button type="button" id="wizard-next-btn" class="custom-btn custom-btn-black hidden" onclick="jQuery.changeState(+1)">
                    ถัดไป
                </button>

                <div id="wizard-finish-btn" class="flex items-center gap-2 hidden">
                    <button type="button" id="wizard-prev-btn" class="custom-btn custom-btn-default" onclick="jQuery.switchState(1, 'redo')">
                        อัปโหลดอีกครั้ง
                    </button>
                    <a href="index.php?module=<?php echo $module; ?>&action=index&parenttab=Inventory" id="wizard-next-btn" class="custom-btn custom-btn-black">
                        ปิด
                    </a>
                </div>            
            </div>            
        </div>
    </div>
</div>
</div>
<?php
    $acceptType = [
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ]
?>
<script>
    var currentStep = 1;
    var acceptType = JSON.parse('<?php echo json_encode($acceptType); ?>')
    var selectedFile = null
    var selectedData = null
    let newData = 0
    let updateData = 0
    let errorData = 0
    jQuery.switchState = function(step, type = '') {
        if(selectedFile == null) return false
        if(type === 'redo') jQuery.clearSelectdFile()

        currentStep = step
        jQuery.changeStateEffect()

        jQuery('.quick-import-wizard-step').hide();
        jQuery(`#quick-import-wizard-step-${step}`).show()

        jQuery(`.quick-import-wizard .items`).removeClass('success-state current-state')

        for(var i=1; i<=4; i++){
            if(i < step)
            {
                jQuery(`#import-wizard-button-step-${i}`).addClass('success-state')
            }

            if(i == step)
            {
                jQuery(`#import-wizard-button-step-${i}`).addClass('current-state')
            }
        }
    }

    jQuery.uploadFile = (type) => {
        const form = new FormData()
        form.append('module', '<?php echo $module; ?>')
        form.append('action', '<?php echo $module; ?>Ajax')
        form.append('ajax', true)
        form.append('file', 'QuickImport/import')
        form.append('importFile', selectedFile)

        const req = new XMLHttpRequest()
        req.open('POST', 'index.php')
        req.upload.onprogress = (e) => {

        }
        req.onload = (e) => {
            let res = JSON.parse(req.responseText)
            console.log(res)
            selectedData = res
            newData = 0
            updateData = 0
            errorData = 0
            res.map(item => {
                if(item.error != ''){
                    errorData++
                } else {
                    newData++
                } 
            })

            var fileName = selectedFile.name
            var fileSize = formatFileSize(selectedFile.size);

            jQuery('#quick-import-wizard-step-2 #file-name').html(fileName)
            jQuery('#quick-import-wizard-step-2 #file-size').html(fileSize)
            jQuery('#quick-import-wizard-step-2 #new-items').html(newData)
            // jQuery('#quick-import-wizard-step-2 #update-items').html(updateData)
            jQuery('#quick-import-wizard-step-2 #error-items').html(errorData)

            jQuery('#quick-import-wizard-step-2 #loading').hide()  
            jQuery('#quick-import-wizard-step-2 #content').show()          
        }
        req.send(form);
    }

    jQuery.exportData = (type) => {
        jQuery.post('index.php', {
            module: '<?php echo $module; ?>',
            action: '<?php echo $module; ?>Ajax',
            ajax: true,
            file: 'QuickImport/history',
            check_import: type,
            data: selectedData
        }, function(rs){
            console.log(rs)
            const link = document.createElement('a');
			link.href = `<?php echo $site_URL; ?>/${rs}`;
			document.body.appendChild(link);
			link.click();
		
			// Clean up and remove the link
			link.parentNode.removeChild(link);
        })
    }
    
    jQuery.importData = () => {
        jQuery.post('index.php', {
            module: '<?php echo $module; ?>',
            action: '<?php echo $module; ?>Ajax',
            ajax: true,
            file: 'QuickImport/import',
            type: 'import',
            filename: selectedFile.name,
            newItems: newData,
            updateItems: updateData,
            errorItems: errorData,
            data: selectedData
        }, function(rs){
            console.log(rs)
            jQuery('#quick-import-wizard-step-4 #new-items').html(newData)
            jQuery('#quick-import-wizard-step-4 #update-items').html(updateData)
            jQuery.switchState(4)
        }, 'json')
    }

    jQuery.changeState = async function(step) {
        if(selectedFile == null) return false

        var newState = currentStep + step

        if(newState === 2){
            jQuery('#quick-import-wizard-step-2 #loading').show()  
            jQuery('#quick-import-wizard-step-2 #content').hide()  
            await jQuery.uploadFile('check')
        }

        if(newState === 3){
            jQuery.importData()
        }

        if(newState > 0 && newState <= 4){
            currentStep = newState
            jQuery.switchState(currentStep)
        }
        jQuery.changeStateEffect()
    }

    jQuery.changeStateEffect = function() {
        jQuery('#wizard-cancel-btn').hide()
        jQuery('#wizard-prev-btn').hide()
        jQuery('#wizard-next-btn').hide()
        jQuery('#wizard-finish-btn').hide()

        if(currentStep === 1){
            jQuery('#wizard-cancel-btn').show()
            if(selectedFile != null) jQuery('#wizard-next-btn').show()
        }else if(currentStep === 4){
            jQuery('#wizard-finish-btn').show()
        }else{
            if(currentStep <= 2) jQuery('#wizard-prev-btn').show()
            if(currentStep <= 2) jQuery('#wizard-next-btn').show()
        }
    }

    var dropArea = jQuery('#dropArea')

    dropArea.on('dragenter', function(e) {
        e.preventDefault()
        e.stopPropagation()
    })

    dropArea.on('dragover', function(e) {
        e.preventDefault()
        e.stopPropagation()
    })

    dropArea.on('dragleave', function(e) {
        e.preventDefault()
        e.stopPropagation()
    })

    dropArea.on('drop', function(e) {
        e.preventDefault()
        e.stopPropagation()

        var files = e.originalEvent.dataTransfer.files
        if(files.length > 1)
        {
            console.log('สามารถอัปโหลดได้คครังละ 1 ไฟล์เท่านั้น')
            return false
        }

        // Handle file upload here
        handleFiles(files)
    })

    jQuery('#fileInput').on('change', function() {
        var files = jQuery(this)[0].files
        // Handle file upload here
        handleFiles(files)
    })

    jQuery.selectUploadFile = function() {
        jQuery('#fileInput').click()
    }

    jQuery.clearSelectdFile = function(){
        jQuery('#fileInput').val('')
        jQuery('#quick-import-wizard-step-1 #file-selected').hide()
        jQuery('#quick-import-wizard-step-1 #file-name').html('')
        jQuery('#quick-import-wizard-step-1 #file-size').html('')

        jQuery('#wizard-next-btn').hide()
        selectedFile = null
        selectedData = null
        newData = 0
        updateData = 0
        errorData = 0
    }

    function handleFiles(files) {
        for (var i = 0; i < files.length; i++) {
            var file = files[i]
            // console.log(file.type)
            if(acceptType.includes(file.type)){
                var fileName = file.name
                var fileSize = formatFileSize(file.size);
                // console.log(file, fileName, fileSize)

                jQuery('#quick-import-wizard-step-1 #file-name').html(fileName)
                jQuery('#quick-import-wizard-step-1 #file-size').html(fileSize)
                jQuery('#quick-import-wizard-step-1 #file-selected').show()

                selectedFile = file
                jQuery('#wizard-next-btn').show()
            }
        }
    }

    function formatFileSize(bytes) {
        var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        if (bytes == 0) return '0 Byte';
        var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
    }
</script>

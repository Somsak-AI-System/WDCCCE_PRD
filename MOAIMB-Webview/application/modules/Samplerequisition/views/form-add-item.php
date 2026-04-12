<div id="modal-add-item" class="modal modal-bottom fade" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog" role="document">
        
        <div class="modal-content shadow-top-30">
            <div class="modal-header">
                <div class="modal-title">
                    เพิ่มรายการ
                </div>
                <span class="close" data-dismiss="modal" aria-label="Close">
                    <i class="ph-x font-18" onclick="$.closeAddItem();"></i>
                </span>
            </div>
            
            <div class="modal-body bg-white" style="height: 500px;overflow-y: auto;">
                <div class="flex width-full list-item-row p-5 mb-5">
                    <div class="flex-none">
                        <div class="list-item-icon bg-blue-1">
                            <i class="ph-rows v-align-middle"></i>
                        </div>
                    </div>
                    <div class="flex-1 pl-10">
                        <div class="font-16 font-bold text-line-clamp-1 add-item-name"></div>
                        <div class="font-12 text-gray-1 text-line-clamp-1 add-item-no"></div>
                    </div>
                </div>
                <input type="hidden" class="add-item-id">
                
                <?php echo inputGroup(['uitype' => '1', 'fieldClass' => 'add-item-name', 'fieldlabel' => 'ชื่อสินค้า', 'columnname' => 'productname', 'columnname' => 'productname', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~M']); ?>
                
                <?php echo inputView_Placeholder(['uitype' => '1', 'fieldClass' => 'add-item-remark', 'fieldlabel' => 'หมายเหตุ', 'columnname' => 'add_item_remark', 'columnname' => 'add_item_remark', 'value' => '', 'rows' => '', 'typeofdata' => 'V~O']); ?>

                <div class="input-group adjust-item-group" data-itemid="" data-ava="">
                    <div class="flex adjust-row" style="width: 100%">
                        <div class="flex-1">
                            <div class="font-16">จำนวน</div>
                        </div>
                        <div class="flex-none">
                            <div class="input-group input-group-custom adjust-item-group" style="width:100px;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text input-group-text-custom minus-amount" onclick="$.minusItemAmount(this)">
                                        <i class="ph-minus text-primary v-align-middle cursor-pointer"></i>
                                    </span>
                                </div>
                                <input type="number" id="line-item" class="form-control border-none text-center p-0 add-item-amount" onkeyup="$.isCalculates(event,this,'line-item');" value="">
                                <div class="input-group-append">
                                    <span class="input-group-text input-group-text-custom plus-amount" onclick="$.plusItemAmount(this)">
                                        <i class="ph-plus text-primary v-align-middle cursor-pointer"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="input-group adjust-item-group" data-itemid="" data-ava="">
                    <div class="flex adjust-row" style="width: 100%">
                        <div class="flex-1">
                            <div class="font-16">จำนวนที่คาดว่าจะใช้</div>
                        </div>
                        <div class="flex-none">
                            <div class="input-group input-group-custom adjust-item-group" style="width:100px;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text input-group-text-custom minus-amount-purchase" onclick="$.minusItemAmountPurchase(this)">
                                        <i class="ph-minus text-primary v-align-middle cursor-pointer"></i>
                                    </span>
                                </div>
                                <input type="number" id="line-item-purchase" class="form-control border-none text-center p-0 add-item-amount-purchase" onkeyup="$.isCalculatespurchase(event,this,'line-item-purchase');" value="">
                                <div class="input-group-append">
                                    <span class="input-group-text input-group-text-custom plus-amount-purchase" onclick="$.plusItemAmountPurchase(this)">
                                        <i class="ph-plus text-primary v-align-middle cursor-pointer"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                <div class="flex-none">
                    <?php echo inputGroup(['uitype' => '15', 'fieldClass' => 'add-item-sr_finish', 'fieldlabel' => 'ชนิดผิว', 'columnname' => 'sr_finish', 'columnname' => 'sr_finish', 'value' => '', 'readonly' => '0', 'rows' => '', 'typeofdata' => 'V~O', 'value_default'=> array(
                       0=>'--None--',
                       1=>'Standard',
                       2=>'BK-(sanded 2 sides)-Backer',
                       3=>'GL-Aligator-GAA',
                       4=>'GL-Anti Finger print-AFX',
                       5=>'GL-Aria-WAR',
                       6=>'GL-Ash-GSH',
                       7=>'GL-Blazing Delight-SLA',
                       8=>'GL-Burnished Wood-WBR',
                       9=>'GL-Cadiz-CDZ,PCD',
                       10=>'GL-Caravan Leather-GFC',
                       11=>'GL-Coast Line-CTL',
                       12=>'GL-Dazzle-GDZ',
                       13=>'GL-Embossed Fleur-EFL',
                       14=>'GL-Embossed Interweave-EW',
                       15=>'GL-Fawn-SFN',
                       16=>'GL-Gloss-GPG,GPL,MG,PCA,SGA,SGB,WGA',
                       17=>'GL-Handscraped-WQA',
                       18=>'GL-High Definition Gloss-HDG',
                       19=>'GL-High Gloss-GP,PCB',
                       20=>'GL-Jupiter-PJP,SJP',
                       21=>'GL-Leather-GSL',
                       22=>'GL-Matt-GPM,GSM',
                       23=>'GL-Microlie v-GSI',
                       24=>'GL-Microline V-GFI',
                       25=>'GL-microlines v-GWI',
                       26=>'GL-Onda Horizontal-GYA',
                       27=>'GL-onda V-GPN,GSN',
                       28=>'GL-Pacific Trail-PTR,SPP,WPP',
                       29=>'GL-Parallel streaks-WPA',
                       30=>'GL-Quarter Cut-GWK',
                       31=>'GL-Rafia-SRF,WRF',
                       32=>'GL-Raw silk-GSR,GWR',
                       33=>'GL-Raw Silk-GPR',
                       34=>'GL-Retro-SRT',
                       35=>'GL-Santhia-WSN',
                       36=>'GL-Satin-SAT',
                       37=>'GL-Scuff-Resistant Gloss-SR',
                       38=>'GL-Sierra-SIR',
                       39=>'GL-Soft Touch-GSS,',
                       40=>'GL-Soft Touch/Satin-PAT',
                       41=>'GL-Sparkle-SPR',
                       42=>'GL-Stone-GFO',
                       43=>'GL-Suede-GSA-D,GWA-E,GFA,GFS,GPA-C,PCS,WGE',
                       44=>'GL-Summer Bloom-SUA',
                       45=>'GL-Super Gloss-HGA, HGP,HGW',
                       46=>'GL-Super Suede-SSA,WGS',
                       47=>'GL-Synchro-SY1,SY2',
                       48=>'GL-Techno steel-GPT,GST',
                       49=>'GL-Techno Steel-GWT',
                       50=>'GL-Texmex-GFT,GTM',
                       51=>'GL-Trace-TRC',
                       52=>'GL-Veracious Bark-WVB,PVB,GCN',
                       53=>'GL-Vertical Line-GPV',
                       54=>'GL-Vertical Lines-GSV',
                       55=>'GL-Wackey Wicker-SWA',
                       56=>'GL-Zero Reflection-GWM',
                       57=>'GL-M-Matt-GCM',
                       58=>'GL-M-Metalics-GMA,GMC',
                       59=>'GL-M-MIrror-GM',
                       60=>'GL-M-Stone-GEO,GGO,GKO,GZO',
                       61=>'GL-M-Vertical-GEV',
                       62=>'GL-M-Zero Reflection-GEM,GGM,GKM,GZM',
                       63=>'NM-Buff Leather-BFL',
                       64=>'NM-Chiseled Wood-CHW',
                       65=>'NM-Classic Quilted-QLT',
                       66=>'NM-Cleaved Stone-CST',
                       67=>'NM-Cosmic Connection-COC',
                       68=>'NM-Disco-DSC',
                       69=>'NM-Engraved-ENG',
                       70=>'NM-Essentia-NPN',
                       71=>'NM-Extra Matt-XMT',
                       72=>'NM-Glazed-GLZ,PWD',
                       73=>'NM-Gloss-EGM',
                       74=>'NM-Khadi-KHD,TEX',
                       75=>'NM-Magical Flow-MF',
                       76=>'NM-Metal Spell-MTS',
                       77=>'NM-Novel Gloss-NGL',
                       78=>'NM-Painted Wood-NPW',
                       79=>'NM-Pure Grain-PGR',
                       80=>'NM-Quadro-QUD',
                       81=>'NM-Raw Bark-RBK',
                       82=>'NM-Simpatico-SMP',
                       83=>'NM-M-Soft Buff-SBF',
                       84=>'NM-Suede-NSB,NPA-B,NWA-B,NCC,NCG',
                       85=>'NM-Super Gloss-NCG',
                       86=>'NM-Torrent-TRN',
                       87=>'NM-Voodo-VOD',
                       88=>'SP-Crimp-CM',
                       89=>'SP-Cross Bar-CRB',
                       90=>'SP-Cross Lines-CRL',
                       91=>'SP-High Streak-HST',
                       92=>'SP-Illusion-ILS',
                       93=>'SP-M-Metal Brushed -NM',
                       94=>'SP-Microlines Vertical-MLV',
                       95=>'SP-Organic-OG',
                       96=>'SP-Paragon-PAR',
                       97=>'SP-Sculpted-SCT',
                       98=>'SP-Soft Brushed-NM9030S',
                       99=>'SP-Suede-U,-',
                       100=>'SP-Vertical Lines-VER',
                       101=>'SP-Ariz-ARZ',
                       102=>'SP-Atune-ATN',
                       103=>'SP-Brisk-BSK',
                       104=>'SP-Matt-MAT/P',
                       105=>'NM-Rocka-RKA',
                       106=>'NM-Roso-RSP',
                       107=>'NM-Taurus-TRS'
                        )]); ?>
                </div>

                <div class="flex-none">
                    <?php echo inputGroup(['uitype' => '15', 'fieldClass' => 'add-item-sr_size_mm', 'fieldlabel' => 'ขนาด (มม.)', 'columnname' => 'sr_size_mm', 'columnname' => 'sr_size_mm', 'value' => '', 'readonly' => '0', 'rows' => '', 'typeofdata' => 'V~O', 'value_default'=> array(
                        0=>'--None--',
                        1=>'Standard',
                        2=>'A5', 
                        3=>'A4', 
                        4=>'A3', 
                        5=>'44x67mm', 
                        6=>'64x128mm', 
                        7=>'89x127mm', 
                        8=>'300x300mm', 
                        9=>'300x600mm', 
                        10=>'100x100mm', 
                        11=>'600x1200mm', 
                        12=>'1220x2440mm', 
                        13=>'1300x3050mm', 
                        14=>'1525x3660mm', 
                        15=>'1830x3660mm', 
                        16=>'Customized'
                     )]); ?>
                </div>

                <div class="flex-none">
                    <?php echo inputGroup(['uitype' => '15', 'fieldClass' => 'add-item-sr_thickness_mm', 'fieldlabel' => 'ความหนา (มม.)', 'columnname' => 'sr_thickness_mm', 'columnname' => 'sr_thickness_mm', 'value' => '', 'readonly' => '0', 'rows' => '', 'typeofdata' => 'V~O', 'value_default'=> array(
                       0=>'--None--',
                       1=>'Standard',  
                       2=>'0.5PF', 
                       3=>'0.6', 
                       4=>'0.6PF', 
                       5=>'0.7', 
                       6=>'0.8', 
                       7=>'0.9', 
                       8=>'1', 
                       9=>'1.2', 
                       10=>'1.5', 
                       11=>'2', 
                       12=>'3', 
                       13=>'4', 
                       14=>'5', 
                       15=>'6', 
                       16=>'8', 
                       17=>'10', 
                       18=>'12', 
                       19=>'13', 
                       20=>'16', 
                       21=>'18', 
                       22=>'20', 
                       23=>'25', 
                       24=>'30'
                        )]); ?>
                </div>

                <div class="flex-none">
                    <?php echo inputGroup(['uitype' => '1', 'fieldClass' => 'add-item-sr_product_unit', 'fieldlabel' => 'หน่วยนับ', 'columnname' => 'sr_product_unit', 'columnname' => 'sr_product_unit', 'value' => '', 'readonly' => '0', 'rows' => '', 'typeofdata' => 'V~O']); ?>
                </div>

            </div>
            
            <div class="modal-footer" style="display: block !important;">
                <div class="card-box p-0 mt-10">
                    <div class="card-box-body px-10 pb-10">
                        <!-- <div class="flex">
                            <div class="flex-1">
                                รวมยอด
                            </div>
                            <div class="flex-none text-right width-150">
                                ฿ <span class="add-item-total-price"></span>
                            </div>
                        </div> -->
                        <button class="btn btn-primary btn-custom width-full mt-10 btn-update-item" data-type="" data-itemid="" onclick="$.updateSelectedItem(this)">
                            บันทึก
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
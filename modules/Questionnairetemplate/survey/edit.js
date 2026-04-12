if (!window["%hammerhead%"]) {
  //   var titleAdorner = {
  //     getCss: () => {
  //       return "mu_title";
  //     },
  //     afterRender: (domEl, model) => {
  //       var decoration = document.createElement("div");
  //       decoration.innerHTML = "";
  //       domEl.appendChild(decoration);
  //     }
  //   };

  //   SurveyEditor.registerAdorner("title", titleAdorner);

  // SurveyEditor.editorLocalization.currentLocale = "fr";

  // SurveyEditor.removeAdorners(["mainRoot"]);

  // Survey.Survey.cssType = "bootstrap";
  // Survey.defaultBootstrapCss.navigationButton = "btn btn-green";
  // SurveyEditor.editorLocalization.currentLocale = "es";\
  // SurveyEditor.StylesManager.applyTheme("winter");
  //Item Tool bar
  //remove a property to the page object. You can't set it in JSON as well
Survey
    .JsonObject
    .metaData
    .removeProperty("page", "visibleIf");
//remove a property from the base question class and as result from all questions
Survey
    .JsonObject
    .metaData
    .removeProperty("questionbase", "visibleIf");
	
  //"text", "checkbox","radiogroup", "dropdown",  "comment", "rating", "boolean","html", "expression","file", "matrix", "matrixdropdown", "matrixdynamic",  "multipletext", "panel" ,"paneldynamic"
var editorOptions = {
    questionTypes: ["text", "checkbox", "radiogroup", "dropdown"]
};
  //var editorOptions =  {};
  var editor = new SurveyEditor.SurveyEditor("editorElement" , editorOptions);
  //console.log(editor);


//Add all countries question into toolbox
  // SurveyEditor.StylesManager.applyTheme("orange");
  //editor.surveyId = '5af48e08-a0a5-44a5-83f4-1c90e8e98de1';
  //editor.surveyPostId = '3ce10f8b-2d8a-4ca2-a110-2994b9e697a1';
/*editor.saveSurveyFunc = function(saveNo, callback) {
    //save the survey JSON
    
	//var jsonEl = document.getElementById('surveyJSON');
	//console.log(jsonEl);
	//jsonEl.value = editor.text;
	var jsonEl = document.getElementById('surveyJSON');
    jsonEl.value = editor.text;
	
	editor.text = MySurveyJSON;
	console.log(saveNo);
	
	callback(saveNo, true);
}*/

/*editor.saveSurveyFunc = function(saveNo, callback) {
    //save the survey JSON
    var jsonEl = document.getElementById('surveyJSON');
	//Get data Json
	console.log(editor.text);
    //jsonEl.value = editor.text;
	callback(saveNo, true);
}*/

//editor.text = "{ }";
//Default Data
//editor.text = "{ pages: [{ name:\'page1\', questions: [{ type: \'text\', name:\"แบบทดสอบข้อที่1\" ,isRequired: true}]}]}";
/*editor.text = '{ "title": "แบบสอบถามลูกค้า Wall In", "pages": [ { "name": "หน้า 1", "elements": [ { "type": "radiogroup", "name": "เส้นทางหลักที่ท่านใช้เดินทางมาโครงการนี้", "title": "เส้นทางหลักที่ท่านใช้เดินทางมาโครงการนี้", "hasOther": true, "choices": [ "ถนนลำลูกกา-ขาเข้า", "ถนนลำลูกกา-ขาออก", "ถนนกาญจนาภิเษก", "ถนนวิภาวดี", "ถนนพหลโยธิน", "ถนนสายไหม", "ถนนรังสิต-นครนายก", "ถนนเสมาฟ้าคราม", "ถนนสุขาภิบาล 5" ], "otherText": "อื่นๆ" }, { "type": "radiogroup", "name": "จำนวนห้องนอนที่ต้องการ", "hasOther": true, "choices": [ "Studio", "1 ห้องนอน", "2 ห้องนอน" ], "otherText": "อื่นๆ " }, { "type": "radiogroup", "name": "ระยะเวลาในการตัดสินใจซื้อ", "choices": [ "ต่ำกว่า 1 เดือน", "1 - 3 เดือน", "3 - 6 เดือน", "6 เดือน - 1 ปี", "1 ปี ขึ้นไป" ] }, { "type": "radiogroup", "name": "ระดับราคาที่ตั้งงบประมาณไว้ (ล้านบาท)", "choices": [ "ต่ำกว่า 8 แสน", "8 แสน - 9.9 แสน", "1.0 - 1.19 ล้านบาท", "1.2 - 1.39 ล้านบาท", "1.4 - 1.59 ล้านบาท", "1.6 - 1.79 ล้านบาท", "1.8 - 2 ล้านบาท", "2 ล้านบาทขึ้นไป " ] }, { "type": "radiogroup", "name": "ประเภทที่อยู่อาศัยในปัจจุบัน", "title": "ประเภทที่อยู่อาศัยในปัจจุบัน", "hasOther": true, "choices": [ "บ้านเดี่ยว", "ทาวน์เฮ้าส์", "อาคารพาณิชย์", "คอนโดมิเนียม", "อพาร์ทเมนท์ (เช่า)" ], "otherText": "อื่นๆ" }, { "type": "radiogroup", "name": "ลักษณะการถือครองที่อยู่อาศัยในปัจจุบัน", "title": "ลักษณะการถือครองที่อยู่อาศัยในปัจจุบัน", "isRequired": true, "choices": [ "เช่าอยู่", "เป็นเจ้าของเอง/เจ้าของร่วม", "เป็นของพ่อแม่", "เป็นของญาติ พี่น้อง " ] }, { "type": "radiogroup", "name": "ขนาดห้องที่ต้องการ", "title": "ขนาดห้องที่ต้องการ", "choices": [ "ต่ำกว่า 24 ตรม.", "24 - 28 ตรม.", "28 - 32 ตรม.", "32 - 40 ตรม.", "40 - 50 ตรม.", "50 -60 ตรม.", "มากกว่า 60 ขึ้นไป" ] }, { "type": "checkbox", "name": "สาเหตุที่ต้องการซื้อบ้าน", "title": "สาเหตุที่ต้องการซื้อบ้าน", "hasOther": true, "choices": [ "ความสะดวกในการเดินทาง", "เปลี่ยนที่อยู่อาศัยให้ใหญ่ขึ้น", "แยกครอบครัว (เป็นส่วนตัว)", "แต่งงาน", "ลงทุน / ให้เช่า", "เป็นทรัพย์สินเพิ่มเติม", "ซื้อให้บุตรหลาน" ], "otherText": "อื่นๆ............. " }, { "type": "checkbox", "name": "เหตุผลที่สนใจเข้าเยี่ยมชมโครงการ", "title": "เหตุผลที่สนใจเข้าเยี่ยมชมโครงการ", "hasOther": true, "choices": [ "ใกล้ที่ทำงาน", "ใกล้บ้านเดิม", "ใกล้โรงเรียนลูก", "ราคา", "ทำเลใกล้รถไฟฟ้า", "รูปแบบห้อง", "โปรโมชั่น", "ที่่จอดรถ", "สิ่งอำนวยความสะดวก", "สภาพแวดล้อมในโครงการ", "ชื่อเสียงผู้ประกอบการ" ], "otherText": "อื่นๆ " }, { "type": "radiogroup", "name": "เหตุผลที่ยังไม่ตัดสินใจซื้อ", "title": "เหตุผลที่ยังไม่ตัดสินใจซื้อ", "hasOther": true, "choices": [ "ความคุ้มค่าของราคา", "สิ่งอำนวยความสะดวก", "การเดินทางเข้าถึงโครงการ", "รูปแบบพื้นที่ใช้สอย", "สภาพชุมชนภายนอก", "บรรยากาศในโครงการ", "รอปรึกษากับผู้ร่วมตัดสินใจ", "ร้านค้าในโครงการ" ], "otherText": "รอเปรียบเทียบกับโครงการอื่น ระบุ" } ], "title": "ข้อมูลทั่วไป" }, { "name": "หน้า 2", "elements": [ { "type": "radiogroup", "name": "เพศ", "choices": [ "ชาย", "หญิง" ] }, { "type": "radiogroup", "name": "สถานภาพ", "choices": [ "โสด ", "สมรส มีบุตร " ] }, { "type": "radiogroup", "name": "อายุ", "choices": [ "ต่ำกว่า 24 ปี", "25 - 29 ปี", "30 - 34 ปี", "35 - 39 ปี", "40 - 44 ปี", "45 - 49 ปี", "50 - 54 ปี", "55 - 59 ปี", "60 ปีขึ้นไป" ] }, { "type": "radiogroup", "name": "อาชีพ", "hasOther": true, "choices": [ "เจ้าของกิจการประเภท", "พนักงานเอกชน", "อาชีพอิสระ", "รับราชการ/รัฐวิสาหกิจ", "แพทย์", "พ่อบ้าน/แม่บ้าน", "เกษียณอายุ" ], "otherText": "อื่นๆ" }, { "type": "radiogroup", "name": "รายได้ส่วนตัว/เดือน (บาท)", "choices": [ "ไม่เกิน 15,000 บาท", "15,001 - 20,000 บาท", "20,001 - 30,000 บาท", "30,001 - 40,000 บาท", "40,001 - 50,000 บาท", "50,001 - 70,000 บาท", "70,001 บาท ขึ้นไป " ] }, { "type": "radiogroup", "name": "จำนวนสมาชิกที่อยู่อาศัยด้วยกัน", "isRequired": true, "hasOther": true, "otherText": "ระบุ" } ], "title": "ข้อมูลส่วนตัวลูกค้า" }, { "name": "หน้า 3", "elements": [ { "type": "radiogroup", "name": "ท่านเคยเห็นโฆษณาของบริษัทเสนาหรือไม่", "choices": [ "เคยเห็น ", "ไม่เคยเห็น " ] }, { "type": "checkbox", "name": "ท่ายเคยเห็นโฆษณาของบริษัทเสนาจากสื่อใด", "hasOther": true, "choices": [ "Facebook", "Youtube", "ช่อง 3", "ช่อง 7", "ช่อง one" ], "otherText": "อื่นๆ โปรดระบุ" }, { "type": "radiogroup", "name": "ท่านเคยเห็นโฆษณาของบริษัทเสนามีความรู้สึกอย่างไร", "choices": [ "ชอบ ", "ไม่ชอบ" ] }, { "type": "radiogroup", "name": "สื่อป้ายโฆษณา", "choices": [ "ป้ายถนนลำลูกกา", "ป้ายถนนรังสิต-นครนายก", "ป้ายถนนเสมาฟ้าคราม", "ป้ายริมรั้วที่โครงการ", "ป้ายถนนพหลโยธิน", "ป้ายรถแห่ / รถจอด", "ผ่านหน้าโครงการ" ], "otherText": "ป้ายบอกทางบริเวณอื่นๆ " }, { "type": "checkbox", "name": "งานสื่อสิ่งพิมพ์", "hasOther": true, "choices": [ "จดหมาย/ใบปลิว", "SENA PRIDE MAGAZINE", "หนังสือพิมพ์ ระบุ" ], "otherText": "นิตสาร ระบุ " } ], "title": "โฆษณาของบริษัทเสนา" } ] }';*/

 /* editor.saveSurveyFunc = function(saveNo, callback) {
    alert("ok");
	console.log(saveNo);
	console.log(callback);
    callback(saveNo, true);
  };*/

  //editor.loadSurvey("b2b56b2c-ad9e-4951-8f0e-c246d6b6a52a");
  editor.showOptions = false;
  editor.showState = true;
  
  
  
  //editor.loadSurvey("a0f7f132-eee4-42e4-b8c8-f8b16840a478");
  //editor.loadSurvey("65c74d4a-3b16-412f-8200-9ac53c8f5c0b");

  //ko.applyBindings(new SurveyEditor.SurveysManager("https://dxsurvey.com", "a797f29b53f8455e8b3ef317f8904dac", editor), document.getElementById("manage"));

 //window.editor = editor;
//Hide Tool Edit survey settings
editor
    .onCanShowProperty
    .add(function (sender, options) {
        if (options.obj.getType() == "survey") {
            options.canShow = options.property.name == "title";
        }
    });
	

}




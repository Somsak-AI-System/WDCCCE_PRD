/*var json = {
    pages: [
        {
            questions: [
                {
                    type: "matrix",
                    name: "Quality",
                    title: "Please indicate if you agree or disagree with the following statements",
                    columns: [
                        {
                            value: 1,
                            text: "Strongly Disagree"
                        }, {
                            value: 2,
                            text: "Disagree"
                        }, {
                            value: 3,
                            text: "Neutral"
                        }, {
                            value: 4,
                            text: "Agree"
                        }, {
                            value: 5,
                            text: "Strongly Agree"
                        }
                    ],
                    rows: [
                        {
                            value: "affordable",
                            text: "Product is affordable"
                        }, {
                            value: "does what it claims",
                            text: "Product does what it claims"
                        }, {
                            value: "better then others",
                            text: "Product is better than other products on the market"
                        }, {
                            value: "easy to use",
                            text: "Product is easy to use"
                        }
                    ]
                }, {
                    type: "rating",
                    name: "satisfaction",
                    title: "How satisfied are you with the Product?",
                    mininumRateDescription: "Not Satisfied",
                    maximumRateDescription: "Completely satisfied"
                }, {
                    type: "rating",
                    name: "recommend friends",
                    visibleIf: "{satisfaction} > 3",
                    title: "How likely are you to recommend the Product to a friend or co-worker?",
                    mininumRateDescription: "Will not recommend",
                    maximumRateDescription: "I will recommend"
                }, {
                    type: "comment",
                    name: "suggestions",
                    title: "What would make you more satisfied with the Product?"
                }
            ]
        }, {
            questions: [
                {
                    type: "radiogroup",
                    name: "price to competitors",
                    title: "Compared to our competitors, do you feel the Product is",
                    choices: ["Less expensive", "Priced about the same", "More expensive", "Not sure"]
                }, {
                    type: "radiogroup",
                    name: "price",
                    title: "Do you feel our current price is merited by our product?",
                    choices: ["correct|Yes, the price is about right", "low|No, the price is too low for your product", "high|No, the price is too high for your product"]
                }, {
                    type: "multipletext",
                    name: "pricelimit",
                    title: "What is the... ",
                    items: [
                        {
                            name: "mostamount",
                            title: "Most amount you would every pay for a product like ours"
                        }, {
                            name: "leastamount",
                            title: "The least amount you would feel comfortable paying"
                        }
                    ]
                }
            ]
        }, {
            questions: [
                {
                    type: "text",
                    name: "email",
                    title: "Thank you for taking our survey. Your survey is almost complete, please enter your email address in the box below if you wish to participate in our drawing, then press the 'Submit' button."
                }
            ]
        }
    ]
};*/
/*var json = { "title": "แบบสอบถามลูกค้า Wall In", "pages": [ { "name": "หน้า 1", "elements": [ { "type": "radiogroup", "name": "เส้นทางหลักที่ท่านใช้เดินทางมาโครงการนี้", "title": "เส้นทางหลักที่ท่านใช้เดินทางมาโครงการนี้", "hasOther": true, "choices": [ "ถนนลำลูกกา-ขาเข้า", "ถนนลำลูกกา-ขาออก", "ถนนกาญจนาภิเษก", "ถนนวิภาวดี", "ถนนพหลโยธิน", "ถนนสายไหม", "ถนนรังสิต-นครนายก", "ถนนเสมาฟ้าคราม", "ถนนสุขาภิบาล 5" ], "otherText": "อื่นๆ" }, { "type": "radiogroup", "name": "จำนวนห้องนอนที่ต้องการ", "hasOther": true, "choices": [ "Studio", "1 ห้องนอน", "2 ห้องนอน" ], "otherText": "อื่นๆ " }, { "type": "radiogroup", "name": "ระยะเวลาในการตัดสินใจซื้อ", "choices": [ "ต่ำกว่า 1 เดือน", "1 - 3 เดือน", "3 - 6 เดือน", "6 เดือน - 1 ปี", "1 ปี ขึ้นไป" ] }, { "type": "radiogroup", "name": "ระดับราคาที่ตั้งงบประมาณไว้ (ล้านบาท)", "choices": [ "ต่ำกว่า 8 แสน", "8 แสน - 9.9 แสน", "1.0 - 1.19 ล้านบาท", "1.2 - 1.39 ล้านบาท", "1.4 - 1.59 ล้านบาท", "1.6 - 1.79 ล้านบาท", "1.8 - 2 ล้านบาท", "2 ล้านบาทขึ้นไป " ] }, { "type": "radiogroup", "name": "ประเภทที่อยู่อาศัยในปัจจุบัน", "title": "ประเภทที่อยู่อาศัยในปัจจุบัน", "hasOther": true, "choices": [ "บ้านเดี่ยว", "ทาวน์เฮ้าส์", "อาคารพาณิชย์", "คอนโดมิเนียม", "อพาร์ทเมนท์ (เช่า)" ], "otherText": "อื่นๆ" }, { "type": "radiogroup", "name": "ลักษณะการถือครองที่อยู่อาศัยในปัจจุบัน", "title": "ลักษณะการถือครองที่อยู่อาศัยในปัจจุบัน", "isRequired": true, "choices": [ "เช่าอยู่", "เป็นเจ้าของเอง/เจ้าของร่วม", "เป็นของพ่อแม่", "เป็นของญาติ พี่น้อง " ] }, { "type": "radiogroup", "name": "ขนาดห้องที่ต้องการ", "title": "ขนาดห้องที่ต้องการ", "choices": [ "ต่ำกว่า 24 ตรม.", "24 - 28 ตรม.", "28 - 32 ตรม.", "32 - 40 ตรม.", "40 - 50 ตรม.", "50 -60 ตรม.", "มากกว่า 60 ขึ้นไป" ] }, { "type": "checkbox", "name": "สาเหตุที่ต้องการซื้อบ้าน", "title": "สาเหตุที่ต้องการซื้อบ้าน", "hasOther": true, "choices": [ "ความสะดวกในการเดินทาง", "เปลี่ยนที่อยู่อาศัยให้ใหญ่ขึ้น", "แยกครอบครัว (เป็นส่วนตัว)", "แต่งงาน", "ลงทุน / ให้เช่า", "เป็นทรัพย์สินเพิ่มเติม", "ซื้อให้บุตรหลาน" ], "otherText": "อื่นๆ............. " }, { "type": "checkbox", "name": "เหตุผลที่สนใจเข้าเยี่ยมชมโครงการ", "title": "เหตุผลที่สนใจเข้าเยี่ยมชมโครงการ", "hasOther": true, "choices": [ "ใกล้ที่ทำงาน", "ใกล้บ้านเดิม", "ใกล้โรงเรียนลูก", "ราคา", "ทำเลใกล้รถไฟฟ้า", "รูปแบบห้อง", "โปรโมชั่น", "ที่่จอดรถ", "สิ่งอำนวยความสะดวก", "สภาพแวดล้อมในโครงการ", "ชื่อเสียงผู้ประกอบการ" ], "otherText": "อื่นๆ " }, { "type": "radiogroup", "name": "เหตุผลที่ยังไม่ตัดสินใจซื้อ", "title": "เหตุผลที่ยังไม่ตัดสินใจซื้อ", "hasOther": true, "choices": [ "ความคุ้มค่าของราคา", "สิ่งอำนวยความสะดวก", "การเดินทางเข้าถึงโครงการ", "รูปแบบพื้นที่ใช้สอย", "สภาพชุมชนภายนอก", "บรรยากาศในโครงการ", "รอปรึกษากับผู้ร่วมตัดสินใจ", "ร้านค้าในโครงการ" ], "otherText": "รอเปรียบเทียบกับโครงการอื่น ระบุ" } ], "title": "ข้อมูลทั่วไป" }, { "name": "หน้า 2", "elements": [ { "type": "radiogroup", "name": "เพศ", "choices": [ "ชาย", "หญิง" ] }, { "type": "radiogroup", "name": "สถานภาพ", "choices": [ "โสด ", "สมรส มีบุตร " ] }, { "type": "radiogroup", "name": "อายุ", "choices": [ "ต่ำกว่า 24 ปี", "25 - 29 ปี", "30 - 34 ปี", "35 - 39 ปี", "40 - 44 ปี", "45 - 49 ปี", "50 - 54 ปี", "55 - 59 ปี", "60 ปีขึ้นไป" ] }, { "type": "radiogroup", "name": "อาชีพ", "hasOther": true, "choices": [ "เจ้าของกิจการประเภท", "พนักงานเอกชน", "อาชีพอิสระ", "รับราชการ/รัฐวิสาหกิจ", "แพทย์", "พ่อบ้าน/แม่บ้าน", "เกษียณอายุ" ], "otherText": "อื่นๆ" }, { "type": "radiogroup", "name": "รายได้ส่วนตัว/เดือน (บาท)", "choices": [ "ไม่เกิน 15,000 บาท", "15,001 - 20,000 บาท", "20,001 - 30,000 บาท", "30,001 - 40,000 บาท", "40,001 - 50,000 บาท", "50,001 - 70,000 บาท", "70,001 บาท ขึ้นไป " ] }, { "type": "radiogroup", "name": "จำนวนสมาชิกที่อยู่อาศัยด้วยกัน", "isRequired": true, "hasOther": true, "otherText": "ระบุ" } ], "title": "ข้อมูลส่วนตัวลูกค้า" }, { "name": "หน้า 3", "elements": [ { "type": "radiogroup", "name": "ท่านเคยเห็นโฆษณาของบริษัทเสนาหรือไม่", "choices": [ "เคยเห็น ", "ไม่เคยเห็น " ] }, { "type": "checkbox", "name": "ท่ายเคยเห็นโฆษณาของบริษัทเสนาจากสื่อใด", "hasOther": true, "choices": [ "Facebook", "Youtube", "ช่อง 3", "ช่อง 7", "ช่อง one" ], "otherText": "อื่นๆ โปรดระบุ" }, { "type": "radiogroup", "name": "ท่านเคยเห็นโฆษณาของบริษัทเสนามีความรู้สึกอย่างไร", "choices": [ "ชอบ ", "ไม่ชอบ" ] }, { "type": "radiogroup", "name": "สื่อป้ายโฆษณา", "choices": [ "ป้ายถนนลำลูกกา", "ป้ายถนนรังสิต-นครนายก", "ป้ายถนนเสมาฟ้าคราม", "ป้ายริมรั้วที่โครงการ", "ป้ายถนนพหลโยธิน", "ป้ายรถแห่ / รถจอด", "ผ่านหน้าโครงการ" ], "otherText": "ป้ายบอกทางบริเวณอื่นๆ " }, { "type": "checkbox", "name": "งานสื่อสิ่งพิมพ์", "hasOther": true, "choices": [ "จดหมาย/ใบปลิว", "SENA PRIDE MAGAZINE", "หนังสือพิมพ์ ระบุ" ], "otherText": "นิตสาร ระบุ " } ], "title": "โฆษณาของบริษัทเสนา" } ] };*/
var json = jQuery('.data_template').val();
window.survey = new Survey.Model(json);

survey
    .onComplete
    .add(function (result) {
        document
            .querySelector('#surveyResult')
            .innerHTML = "result: " + JSON.stringify(result.data);
    });

/*survey.data = {
    'Quality': {
        'affordable': '3',
        'does what it claims': '4',
        'better then others': '3',
        'easy to use': '5'
    },
    'satisfaction': '4',
    'recommend friends': '4',
    'suggestions': '24/7 support would help a lot.',
    'price to competitors': 'Not sure',
    'price': 'correct',
    'pricelimit': {
        'mostamount': '450',
        'leastamount': '200'
    },
    'email': 'jon.snow@nightwatch.org'
};*/
survey.mode = 'display';

$("#surveyElement").Survey({model: survey});

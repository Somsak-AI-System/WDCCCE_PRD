<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Email Alert</title>
    </head>
    <style type="text/css">
        .small {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000000;
        }

        .big {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            line-height: 18px;
            color: #000000;
            font-weight: bold;
        }

        .componentName {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 18px;
            line-height: 18px;
            color: #33338c;
            font-weight: bold;
            border-left: 2px dotted #fff;
            padding: 10px;
        }

        .genHeaderBig {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            line-height: 19px;
            color: #000000;
            font-weight: bold;
        }

        .genHeaderSmall {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            line-height: 16px;
            color: #000000;
            font-weight: bold;
        }

        .moduleName {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            color: #33338c;
            font-weight: bold;
        }

        .hdrNameBg {
            background: #ffffff url(https://moaistd.moai-crm.com/crm/themes/softed/images/header-bg.png) repeat-x;
        }

        .hdrTabBg {
            background: #ffffff url(https://moaistd.moai-crm.com/crm/themes/softed/images/toolbar-bg.png) bottom repeat-x ;
            background-color: #fff;
            border-top: 1px solid #83bbea;
        }

        .tabSelected {
            background: #fff url(https://moaistd.moai-crm.com/crm/themes/softed/images/toolbar-bg.png) bottom repeat-x;
            background-color: #fff;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            font-size: 11px;
            padding-left: 10px;
            padding-right: 10px;
            padding-top: 2px;
            padding-bottom: 2px;
            border-bottom: 0px solid #ffffff;
        }

        .tabSelected a {
            color: white;
            text-transform: uppercase;
            text-decoration: underline;
            font-size: 12px;
        }

        .tabUnSelected {
            background: #fff url(https://moaistd.moai-crm.com/crm/themes/softed/images/toolbar-bg.png) bottom repeat-x;
            background-color: #fff;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            padding-left: 10px;
            padding-right: 10px;
            padding-top: 2px;
            padding-bottom: 2px;
            border-bottom: 0px solid #ffffff;
        }

        .tabUnSelected a {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: white;
            font-weight: bold;
        }

        .tabSeperator {
            background: #fff url(https://moaistd.moai-crm.com/crm/themes/softed/images/toolbar-div.png) bottom no-repeat;
            background-color: #fff;
            width: 2px;
        }

        .level2text {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            padding-left: 15px;
            padding-right: 15px;
            padding-top: 5px;
            padding-bottom: 5px;
            font-weight: bold;
            color: white;
        }

        .level2text a {
            text-decoration: underline;
            color: #555555;
        }

        .level2text a:hover {
            text-decoration: underline;
            color: #555555;
        }

        .level2Bg {
            background: #fff url(https://moaistd.moai-crm.com/crm/themes/softed/images/level2Bg.gif) bottom repeat-x;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            border-top: 1px solid #b8b8b8;
            border-bottom: 1px solid #bfbfbf;
            color: white;
        }

        .level2Bg a {
            color: #33338C;
        }

        .level2SelTab {
            font-weight: bold;
            color: #000000;
            padding-left: 20px;
            padding-right: 20px;
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .level2UnSelTab {
            color: #000000;
            padding-left: 20px;
            padding-right: 20px;
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .sep1 {
            background: #fff;
        }

        .showPanelBg {
            background: url(https://moaistd.moai-crm.com/crm/themes/softed/images/homePageSeperator.gif) no-repeat;
        }

        a {
            text-decoration: none;
            color: #0070BA;
        }

        a:hover {
            text-decoration: underline;
        }

        .hdrLink {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            text-decoration: none;
            color: #0070BA;
            font-weight: bold;
        }

        #showPanelTopLeft {
            visibility: hidden;
        }

        #showPanelTopRight {
            visibility: hidden;
        }

        #border2pxpopup {
            border: 2px solid #dddddd;
        }

        .hdrLink:hover {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            text-decoration: underline;
            color: #33338c;
            font-weight: bold;
        }

        .searchBox {
            border: 0px solid #0000aa;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            padding-left: 2px;
            width: 100px;
            background-color: #ffffef;
        }

        .searchBtn {
            border : 0px #cccccc solid;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            font-weight: bold;
            width: 30px;
            height: 19px;
            background: #9d9d9d url(https://moaistd.moai-crm.com/crm/themes/softed/images/button_serach.gif) bottom repeat-x;
            color: white;
        }

        .lvt {
            background-color: #ddf;
            border: 0px solid #cce;
        }

        .lvtBg {
        }

        .lvtHeaderText {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            font-weight: bold;
        }

        .lvtCol {
            border-top: 1px solid #fff;
            border-left: 1px solid #ddd;
            border-right: 0px solid #fff;
            border-bottom: 0px solid #fff;
            background: #FFFFFF url(https://moaistd.moai-crm.com/crm/themes/softed/images/level2Bg.gif) repeat-x scroll center bottom;
            font-weight: bold;
        }

        .lvtColData {
            background-color: #ffffff;
        }

        .lvtColDataHover {
            background-color: #ffffcc;
        }

        .dvHeaderText {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            color: #000000;
            font-weight: bold;
        }

        .dvInnerHeader {
            font-family: tahoma;
            font-size: 11px;
            border-top: 1px solid #dedede;
            border-left: 1px solid #dedede;
            border-right: 1px solid #dedede;
            border-bottom: 1px solid #dedede;
            padding: 12px;
            background: #dddcdd url(https://moaistd.moai-crm.com/crm/themes/softed/images/inner.gif) bottom repeat-x;
            color: #000000;
        }

        .dvtSelectedCell {
            border-left: 1px solid #e0dddd;
            border-top: 1px solid #e0dddd;
            border-bottom: 0px solid #e0dddd;
            border-right: 1px solid #e0dddd;
            padding-left: 20px;
            padding-right: 20px;
            background: #ffffff url(https://moaistd.moai-crm.com/crm/themes/softed/images/tab.gif) top repeat-x;
            font-weight: bold;
            color: black;
            line-height: 20px;
        }

        .dvtUnSelectedCell {
            border-left: 1px solid #dedede;
            border-top: 1px solid #e0dddd;
            border-right: 1px solid #e0dddd;
            border-bottom: 1px solid #e0dddd;
            padding-left: 20px;
            padding-right: 20px;
            background: #FFF ;
            cursor: pointer;
            color: black;
        }

        .dvtTabCache {
            border-bottom: 1px solid #dedede;
        }

        .dvtContentSpace {
            border-left: 1px solid #dedede;
            border-right: 1px solid #dedede;
            border-bottom: 1px solid #dedede;
            padding-left: 0px;
        }

        .dvtCellLabel, .cellLabel {
            background: #F7F7F7 url(https://moaistd.moai-crm.com/crm/themes/softed/images/testsidebar.jpg) repeat-y scroll right center;
            font-family: tahoma;
            font-size: 11px;
            border-bottom: 1px solid #DEDEDE;
            border-left: 1px solid #DEDEDE;
            border-right: 1px solid #DEDEDE;
            color: #545454;
            padding-left: 10px;
            padding-right: 10px;
            white-space: nowrap;
        }

        .dvtCellInfo, .cellInfo {
            font-family: tahoma;
            font-size: 11px;
            padding-left: 10px;
            padding-right: 10px;
            border-bottom: 1px solid #dedede;
            border-right: 1px solid #dedede;
            border-left: 1px solid #dedede;
        }

        .rightMailMerge {
            border: 1px solid #dedede;
        }

        .rightMailMergeHeader {
            border-bottom: 1px solid #ffffff;
            padding: 5px;
            background-color: #000000;
            background: url(https://moaistd.moai-crm.com/crm/themes/softed/images/level2Bg.gif) bottom repeat-x;
            color: #555555;
        }

        .rightMailMergeContent {
            padding: 5px;
            background-color: #ffffff;
        }

        .detailedViewHeader {
            border: 1px solid #DDDDDD;
            padding: 12px;
            background: #dddcdd url(https://moaistd.moai-crm.com/crm/themes/softed/images/inner.gif) bottom repeat-x;
            color: #000000;
        }

        .detailedViewTextBox {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000000;
            border: 1px solid #bababa;
            padding-left: 5px;
            width: 90%;
            background-color: #ffffff;
        }

        .detailedViewTextBoxOn {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000000;
            border: 1px solid #bababa;
            padding-left: 5px;
            width: 90%;
            background-color: #ffffdd;
        }

        .detailedViewTextBoxacc {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000000;
            border: 1px solid #bababa;
            padding-left: 5px;
            width: 500px;
            height: 35px;
            background-color: #ffffff;
        }

        .detailedViewTextBoxOnacc {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000000;
            border: 1px solid #bababa;
            padding-left: 5px;
            width: 500px;
            height: 35px;
            ;background-color: #ffffdd;
        }

        .advSearch {
            padding: 10px;
            border-left: 1px solid #eaeaea;
            border-right: 1px solid #eaeaea;
            border-bottom: 1px solid #eaeaea;
            overflow: auto;
            width: 600px;
            height: 150px;
            background-color: #ffffef;
        }

        .searchAlph {
            border: 1px solid #dadada;
            background-color: #ffffff;
            cursor: pointer;
            width: 10px
        }

        .searchAlphselected {
            border: 1px solid #666666;
            background-color: #bbbbbb;
            cursor: pointer;
            width: 10px
        }

        .textbox {
            border: 1px solid #999999;
            background-color: #ffffff;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            width: 120px;
            padding: 2px;
        }

        .mx {
        }

        .calListTable td {
            border-bottom: 1px solid #efefef;
            border-left: 1px solid #efefef;
        }

        .calDIV {
            width: 100%;
            border: 1px solid #fff;
        }

        .calDayHour {
            border-top: 1px solid #c5d5ff;
            border-left: 1px solid #c5d5ff;
            border-bottom: 1px solid #c5d5ff;
            border-right: 1px solid #c5d5ff;
            background-color: #eae7da;
        }

        .calAddEvent {
            position: absolute;
            z-index: 10000;
            width: 500px;
            left: 200px;
            top: 150px;
            background-color: #ffffff;
        }

        .addEventInnerBox {
            border: 1px dotted #dadada;
        }

        .cellNormal {
            border: 0px;
            background-color: #ffffff;
        }

        .EventToDo {
        }

        .thumbnail {
            background-color: #ffffff;
            padding: 7px;
            border: 1px solid #ddd;
            float: left;
            margin-right: 10px;
            margin-bottom: 15px;
        }

        .padTab {
            padding-top: 0px;
            padding-left: 10px;
            padding-bottom: 0px;
            vertical-align: top;
        }

        #user {
            position: relative;
            left: 0px;
            top: 0px;
            width: 100%;
            display: block;
        }

        #communication {
            position: relative;
            left: 0px;
            top: 0px;
            width: 100%;
            display: block;
        }

        #config {
            position: relative;
            left: 0px;
            top: 0px;
            width: 100%;
            display: block;
        }

        #userTab {
            position: relative;
            left: 0px;
            top: 0px;
            width: 100%;
            display: none;
        }

        .calAddButton {
            border: 1px solid #ddd;
            background: #ffffff url(https://moaistd.moai-crm.com/crm/themes/softed/images/calAddButton.gif) repeat-x;
            cursor: pointer;
            height: 30px;
            line-height: 25px;
        }

        .calInnerBorder {
            border-left: 2px solid #ddd;
            border-right: 2px solid #ddd;
            border-bottom: 2px solid #ddd;
        }

        .calBorder {
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            background: #fff url(https://moaistd.moai-crm.com/crm/themes/softed/images/layerPopupBg.gif);
        }

        .calTopRight {
            border-right: 1px solid #ddd;
        }

        .calUnSel {
            color: #000000;
            font-weight: normal;
            text-align: center;
            width: 8%;
            border-right: 1px solid #DEDEDE;
        }

        a.calMnu {
            font-size: 11px;
            color: #0070BA;
            text-decoration: none;
            display: block;
            height: 20px;
            padding-left: 5px;
            padding-top: 3px;
        }

        a.calMnu:Hover {
            font-size: 11px;
            color: #0070BA;
            text-decoration: underline;
            display: block;
            height: 20px;
            padding-left: 5px;
            padding-top: 3px;
        }

        .bgwhite {
            background-color: #FFFFFF;
        }

        .copy {
            font-size: 9px;
            font-family: Verdana, Arial, Helvetica, Sans-serif;
        }

        #mnuTab {
            position: relative;
            width: 100%;
            display: block;
        }

        #mnuTab2 {
            position: relative;
            overflow: auto;
            width: 100%;
            display: none;
        }

        .style1 {
            color: #FF0000
        }

        .event {
            background-color: #fb802f;
            border: 2px solid #dddddd;
            text-align: left;
            width: 100%;
            position: relative;
            left: 0px;
            top: 0px;
            vertical-align: middle;
            padding: 1px;
        }

        #hrView {
            display: block;
        }

        .calendarNav {
            font-size: 16px;
            color: #33338C;
            white-space: nowrap;
            text-align: center;
            font-weight: bold;
            padding-left: 10px;
            padding-right: 10px;
            background: #FFFFFF none repeat scroll 0%;
        }

        #addEventDropDown {
            position: absolute;
            display: none;
            width: 150px;
            border: 1px solid #ddd;
            left: 0px;
            top: 0px;
            overflow: visible;
            z-index: 5000;
        }

        .calAction {
            width: 175px;
            background-color: #CCCCCC;
            border: 1px solid #DDDDDD;
            padding-top: 5px;
            position: absolute;
            display: none;
            z-index: 2000;
        }

        .calSettings {
            position: absolute;
            z-index: 20000;
            width: 500px;
            left: 200px;
            top: 150px;
            background-color: #ffffff;
        }

        .outer {
            border-bottom: 1px solid #CCCCCC;
            border-left: 1px solid #CCCCCC;
            border-right: 1px solid #CCCCCC;
        }

        .calTxt {
            width: 50%;
            border: 1px solid #CCCCCC;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            text-align: left;
            padding-left: 5px;
        }

        #leadLay {
            position: relative;
            width: 100%;
            float: left;
            visibility: hidden;
            padding: 5px;
            z-index: 10000;
        }

        .eventDay {
            background-color: #FF9966;
            font-weight: bold;
        }

        .currDay {
            background: #5774B0 url(https://moaistd.moai-crm.com/crm/themes/softed/images/toolbar-bg.png) repeat scroll 0%;
            border: 1px solid #DEDEDE;
            font-weight: bold;
            text-decoration: underline;
        }

        .currDay a {
            color: #FFFFFF;
            font-weight: bold;
            text-decoration: underline;
        }

        level2Sel {
            color: #000000;
            font-weight: bold;
            text-decoration: underline;
        }

        #DeleteLay {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            text-align: left;
            width: 300px;
            border: 3px solid #CCCCCC;
            background-color: #FFFFFF;
            padding: 5px;
        }

        #CurrencyDeleteLay {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            text-align: left;
            width: 350px;
        }

        .rptCellLabel {
            background-color: #f6f6f6;
            padding-right: 10px;
            border-right: 1px solid #DDDDDD;
            border-bottom: 1px solid #fff;
            color: #737373;
            font-weight: bold;
            white-space: nowrap;
        }

        .rptTable {
            border-left: 1px solid #DDDDDD;
            border-bottom: 1px solid #DDDDDD;
            border-top: 1px solid #DDDDDD;
        }

        .rptTitle, .rptHead, .rptData, .rptGrpHead {
            font-family: Verdana, Arial, Helvetica, Sans-serif;
            font-size: 11px;
            text-align: left;
            font-weight: normal;
            height: 20px;
            padding: 4px;
            border-right: 1px solid #DDDDDD;
            border-bottom: 1px solid #DDDDDD;
            background: #DDDDDD;
        }

        .rptGrp1Total, .rptGrp2Total, .rptTotal {
            font-family: Verdana, Arial, Helvetica, Sans-serif;
            font-size: 11px;
            text-align: center;
            font-weight: normal;
            background: #FFF;
            height: 20px;
            padding: 4px;
            border-right: 1px solid #DDDDDD;
            border-bottom: 1px solid #DDDDDD;
        }

        .rptGrpHead {
            background: #FFF;
            border-bottom: 1px solid #FFF;
            border-top: 1px solid #DDDDDD;
            font-weight: normal;
        }

        .rptData {
            background: #FFF;
            font-weight: normal;
        }

        .rptEmptyGrp {
            background: #FFF;
            border-right: 1px solid #DDDDDD;
        }

        .statechange {
            position: absolute;
            visibility: hidden;
            left: 10px;
            top: 20px;
            width: 300px;
            border: 3px solid #CCCCCC;
            background-color: #FFFFFF;
        }

        #PopupLay {
            position: absolute;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            text-align: left;
            width: 500px;
            border: 3px solid #CCCCCC;
            background-color: #FFFFFF;
            padding: 5px;
            display: none;
            left: 100px;
            top: 100px;
        }

        #folderLay {
            width: 175px;
            background-color: #CCCCCC;
            border: 1px solid #DDDDDD;
            padding-top: 5px;
            position: absolute;
            display: none;
        }

        .qcTransport {
        }

        #role_popup {
            position: relative;
            left: 0px;
            top: 0px;
            width: 95%;
            height: 300px;
            overflow: auto;
            border: 1px solid #999999;
            text-align: left;
            background-color: #FFFFFF;
        }

        .unread_email {
            font-weight: bold;
            background-color: #00FF00;
        }

        .delete_email {
            font-weight: bold;
            background-color: #FF5151;
        }

        .qualify_email {
            font-weight: bold;
            background-color: #CDB5CD;
        }

        .unread_email:hover {
            font-weight: bold;
        }

        .tagCloud {
            border: 1px solid #ddd;
        }

        .tagCloudTopBg {
            background: url(https://moaistd.moai-crm.com/crm/themes/softed/images/tagCloudBg.gif) repeat-x;
        }

        .tagCloudDisplay {
            background-color: #fff;
            padding: 10px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            line-height: 14px;
            color: #000000;
        }

        .h2 {
            font-size: 18px;
            line-height: 20px;
        }

        .gray {
            color: gray;
        }

        ul {
            list-style: circle;
            line-height: 20px;
            padding-left: 5px;
            margin-left: 20px;
            font-weight: normal;
        }

        .tagCloudTopBg {
            background-image: url(https://moaistd.moai-crm.com/crm/themes/softed/images/tagCloudBg.gif);
            background-repeat: repeat-x;
        }

        .tagCloudDisplay {
            background-color: #fff;
            padding: 5px;
        }

        .heading2 {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            line-height: 16px;
            font-weight: bold;
            color: #000000;
        }

        .settingsUI {
            text-align: left;
            background-color: #fff;
            background-image: url(https://moaistd.moai-crm.com/crm/themes/softed/images/layerPopupBg.gif);
            border: 2px solid #ddd;
        }

        .settingsTabHeader {
            text-align: left;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            line-height: 22px;
            font-weight: bold;
            color: #33338c;
            background-color: #efecec;
            padding-left: 10px;
            padding-right: 10px;
            border-top: #000000;
        }

        .settingsTabList {
            text-align: left;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            line-height: 20px;
            font-weight: normal;
            color: #000000;
            background-color: #fff;
            padding-left: 30px;
            border-top: 1px solid #fff;
            border-bottom: 1px solid #ddd;
            border-right: 1px solid #ddd;
            border-left: 1px solid #fff;
        }

        .settingsTabSelected {
            text-align: left;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            line-height: 20px;
            font-weight: bold;
            color: #000000;
            background-color: #ffffff;
            padding-left: 30px;
            border-left: 3px solid #ddd;
            border-bottom: 3px solid #ddd;
            border-top: 2px solid #ddd;
        }

        .settingsSelectedUI {
            padding-bottom: 5px;
            background-color: #ffffff;
            background: url(https://moaistd.moai-crm.com/crm/themes/softed/images/settingsSelUIBg.gif) repeat-x;
            padding: 15px 25px ;
        }

        .settingsIconDisplay {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            line-height: 14px;
            padding: 10px;
            color: #000000;
            background-color: #ffffff;
            padding-left: 10px;
        }

        .settingsSelUITopLine {
            border-bottom: 2px dotted #999999;
        }

        .tableHeading {
            background: #FFFFFF url(https://moaistd.moai-crm.com/crm/themes/softed/images/level2Bg.gif) repeat-x scroll center bottom;
            border: 1px solid #DEDEDE;
        }

        .colHeader {
            background-color: #fff;
            background-image: url(https://moaistd.moai-crm.com/crm/themes/softed/images/mailSubHeaderBg-grey.gif);
            border-left: 1px solid #fff;
            border-top: 1px solid #ddd;
            border-right: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .cellLabel {
            background-color: #f5f5ff;
            border-top: 1px solid #efefef;
            border-bottom: 1px solid #dadada;
            color: #555555;
        }

        .cellText {
            color: #333333;
            border-bottom: 1px solid #dadada;
        }

        .listTable {
            border-left: 1px solid #cccccc;
            border-right: 1px solid #cccccc;
            border-bottom: 1px solid #cccccc;
        }

        .listTableRow {
            border-bottom: 1px solid #eaeaea;
            border-right: 1px solid #eaeaea;
            border-bottom: 1px solid #eaeaea;
            border-bottom: 1px solid #eaeaea;
        }

        .listRow {
            border-bottom: 2px solid #eaeaea;
        }

        .listTableTopButtons {
            background-color: #efefff;
            background-image: url(https://moaistd.moai-crm.com/crm/themes/softed/images/layerPopupBg.gif);
        }

        .crmButton {
            border-left: 1px solid #ffffff;
            border-top: 1px solid #ffffff;
            border-right: 1px solid #555555;
            border-bottom: 1px solid #555555;
        }

        .create {
            background-color: #5774b0;
            color: #fff;
            font-weight: bold;
            background-image: url(https://moaistd.moai-crm.com/crm/themes/softed/images/toolbar-bg.png);
        }

        .delete {
            background-color: red;
            color: #fff;
            font-weight: bold;
            background-image: url(https://moaistd.moai-crm.com/crm/themes/softed/images/buttonred.png);
        }

        .edit {
            background-color: green;
            color: #fff;
            font-weight: bold;
            background-image: url(https://moaistd.moai-crm.com/crm/themes/softed/images/buttongreen.png);
        }

        .save {
            background-color: green;
            color: #fff;
            font-weight: bold;
            background-image: url(https://moaistd.moai-crm.com/crm/themes/softed/images/buttongreen.png);
        }

        .report {
            background-color: yellow;
            color: #333333;
            font-weight: bold;
            background-image: url(https://moaistd.moai-crm.com/crm/themes/softed/images/buttonyellow.png);
        }

        .cancel {
            background-color: orange;
            color: #fff;
            font-weight: bold;
            background-image: url(https://moaistd.moai-crm.com/crm/themes/softed/images/buttonorange.png);
        }

        .inactive {
            color: #999999;
        }

        .active {
            color: #229922;
        }

        textarea {
            width: 95%;
            height: 70px;
            border: 1px solid #dadada;
        }

        .treeTable1 {
            padding: 0px;
        }

        .cellBottomDotLine {
            border-bottom-width: 2px;
            border-bottom-style: dotted;
            border-bottom-color: #CCCCCC;
            background-color: #ededed;
        }

        .crmFormList {
            border: 1px solid #cccccc;
            width: 90%;
            height: 120px;
        }

        .cellBottomDotLinePlain {
            border-bottom-width: 2px;
            border-bottom-style: dotted;
            border-bottom-color: #CCCCCC;
        }

        .thickBorder {
            border: 2px solid #999999;
        }

        .trackerHeading {
            background-color: #efefef;
        }

        .trackerListBullet {
            border-right: 1px dotted #cccccc;
            background-color: #f9f9f9;
        }

        .trackerList {
            border-bottom: 1px solid #eeeeee;
        }

        .crmTable {
            border: 1px solid #dadada;
        }

        .crmTableRow {
            border-bottom: 1px dotted #dadada;
            border-right: 1px dotted #dadada
        }

        .lineOnTop {
            border-top: 1px solid #999999;
        }

        .discountUI {
            border: 3px solid #CCCCCC;
            width: 250px;
            padding: 5px;
            position: absolute;
            background-color: #FFFFFF;
            display: none;
        }

        .TaxShow {
            display: inline-table;
        }

        .TaxHide {
            display: none;
        }

        .emailSelected {
            background-color: #eaeaea;
            color: #000000;
            font-size: bold;
        }

        .mailClient {
            border-top: 0px solid #ddd;
            border-right: 0px solid #ddd;
            border-left: 0px solid #ddd;
            border-bottom: 0px solid #ddd;
        }

        .mailClientBg {
            background-color: #ddd;
            background-image: url(https://moaistd.moai-crm.com/crm/themes/softed/images/layerPopupBg.gif) ;
            border: 2px solid #dddddd;
        }

        .mailSubHeader {
            background: #FFFFFF url(https://moaistd.moai-crm.com/crm/themes/softed/images/level2Bg.gif) repeat-x scroll center bottom;
            border-top: 0px solid #ddd;
            padding: 4px;
        }

        .mailClientWriteEmailHeader {
            font-family: arial, helvetica, sans-serif;
            font-size: 20px;
            line-height: 24px;
            font-weight: bold;
            background: #ddd url(https://moaistd.moai-crm.com/crm/themes/softed/images/mailSubHeaderBg.gif) repeat-x;
            padding: 10px;
            border-left: 1px solid #eaeaea;
            border-top: 1px solid #eaeaea;
            border-right: 1px solid #939271;
            border-left: 1px solid #939271;
            ;}

        .mailClientCSSButton {
            border-left: 1px solid #fff;
            border-top: 1px solid #fff;
            border-right: 1px solid #333;
            border-bottom: 1px solid #333;
            padding: 2px;
            background-color: #c3c2b1;
        }

        .layerPopup {
            border: 2px solid #ddd;
            background: #fffff5 url(https://moaistd.moai-crm.com/crm/themes/softed/images/layerPopupBg.gif) ;
            position: absolute;
        }

        .layerPopupHeading {
            font-family: arial, helvetica, sans-serif;
            font-size: 16px;
            line-height: 24px;
            font-weight: bold;
        }

        .layerHeadingULine {
            border-bottom: 2px solid #717351;
        }

        .layerPopupTransport {
            background-color: #e2e5ff;
        }

        .homePageSeperator {
            border-right: 0px dotted #d3d2c1;
            background: #ffffff url(https://moaistd.moai-crm.com/crm/themes/softed/images/homePageSeperator.gif) no-repeat;
            padding-left: 15px;
            padding-top: 15px;
        }

        .homePageMatrixHdr {
            border-bottom: 0px solid #d3d2c1;
            background: #ffffff ;
        }

        .reportsListTable {
            background-color: white;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
        }

        .reportGenerateTable {
            background-image: url(https://moaistd.moai-crm.com/crm/themes/softed/images/layerPopupBg.gif);
            border-left: 2px dotted #a5b5ee;
            border-right: 2px dotted #a5b5ee;
            border-bottom: 2px dotted #a5b5ee;
        }

        .reportCreateBottom {
            background-color: #ddf;
            border-bottom: 2px solid #737251;
        }

        .importLeadUI {
            background-color: white;
        }

        a.customMnu {
            padding-left: 30px;
            padding-top: 5px;
            padding-bottom: 5px;
            display: block;
            background-repeat: no-repeat;
            background-position: left;
            width: 155px;
            color: #000000;
            text-decoration: none;
        }

        a.customMnuSelected {
            padding-left: 30px;
            padding-top: 5px;
            padding-bottom: 5px;
            display: block;
            background-repeat: no-repeat;
            background-position: left;
            width: 155px;
            background-color: #0099FF;
            color: #FFFFFF;
            text-decoration: none;
        }

        .drop_mnu {
            position: absolute;
            left: 0px;
            top: 0px;
            z-index: 1000000001;
            border-left: 1px solid #d3d3d3;
            border-right: 1px solid #d3d3d3;
            border-bottom: 1px solid #d3d3d3;
            width: 150px;
            display: none;
            padding: 0px;
            text-align: left;
            overflow-x: hidden;
            overflow-y: hidden;
            background-color: #ffffcc;
        }

        a.drop_down {
            width: 150px;
            text-align: left;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            line-height: 20px;
            font-weight: normal;
            color: #33338c;
            background-color: #fff;
            padding: 2px 5px 2px 5px;
            border-top: 1px solid #fafafa;
            border-bottom: 1px solid #d3d3d3;
            display: block;
        }

        a.drop_down:Hover {
            padding: 2px 5px 2px 5px;
            width: 150px;
            text-align: left;
            color: #0070BA;
            font-weight: normal;
            text-decoration: underline;
            background-color: #ffffcc;
        }

        .bgwhite {
            background-color: white;
        }

        .searchUIBasic {
            background-image: url(https://moaistd.moai-crm.com/crm/themes/softed/images/layerPopupBg.gif);
            border: 2px solid #a5b5ee;
        }

        .searchUIAdv1 {
            background-image: url(https://moaistd.moai-crm.com/crm/themes/softed/images/layerPopupBg.gif);
            border-top: 2px solid #a5b5ee;
            border-left: 2px solid #a5b5ee;
            border-right: 2px solid #a5b5ee;
        }

        .searchUIAdv2 {
            background-image: url(https://moaistd.moai-crm.com/crm/themes/softed/images/layerPopupBg.gif);
            border-left: 2px solid #a5b5ee;
            border-right: 2px solid #a5b5ee;
        }

        .searchUIAdv3 {
            background-image: url(https://moaistd.moai-crm.com/crm/themes/softed/images/layerPopupBg.gif);
            border-bottom: 2px solid #a5b5ee;
            border-left: 2px solid #a5b5ee;
            border-right: 2px solid #a5b5ee;
        }

        .searchUIName {
        }

        .veil {
            background: url(https://moaistd.moai-crm.com/crm/themes/softed/images/layerPopupBg.gif) ;
            height: 100%;
            width: 100%;
            top: 0px;
            left: 0px;
            overflow: hidden;
            z-index: 10000;
            Filter: Alpha(opacity = 70);
            -moz-opacity: 0.7;
            text-align: center;
            vertical-align: middle;
            position: absolute;
        }

        .veil_new {
            background: url(https://moaistd.moai-crm.com/crm/themes/softed/images/layerPopupBg.gif) ;
            height: 100%;
            width: 100%;
            top: 0px;
            left: 0px;
            overflow: hidden;
            z-index: 50000;
            Filter: Alpha(opacity = 70);
            -moz-opacity: 0.7;
            text-align: center;
            vertical-align: middle;
            position: absolute;
        }

        .optioncontainer {
            vertical-align: middle;
            height: 100%;
            width: 100%;
            position: absolute;
            z-index: 90000;
        }

        .options {
            vertical-align: middle;
            margin-left: 25%;
            margin-top: 16%;
            color: #FFFFFF;
            width: 650px;
            background-color: Black;
            border: 2px solid #222;
            position: relative;
            text-align: left;
            z-index: 80000;
        }

        .options h2 {
            color: White;
            font-family: Verdana, Arial, Helvetica, sans-serif;
            border-bottom: 1px solid #373D4C;
            margin: 0;
            font-weight: normal;
        }

        .mailSelected {
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            font-size: 11px;
            padding-left: 10px;
            padding-right: 10px;
            padding-top: 2px;
            padding-bottom: 2px;
        }

        .mailSelected_select {
            background: #E1DCB3 url(https://moaistd.moai-crm.com/crm/themes/softed/images/tabSelectedBg.gif) repeat-x;
            background-color: #1F5EFF;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            font-size: 11px;
            padding-left: 10px;
            padding-right: 10px;
            padding-top: 2px;
            padding-bottom: 2px;
        }

        .groupname {
            width: 125px;
        }

        .winmarkModulesdef {
            background-position: bottom left;
            background-repeat: repeat-x;
        }

        .headerrow {
            cursor: move;
        }

        .repBox {
            width: 100px;
            border: 1px solid #666666;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
        }

        .warning {
            color: #0070BA;
        }

        .button_add {
            background: #ffffff url(https://moaistd.moai-crm.com/crm/themes/softed/images/select.gif) no-repeat;
            border: 1px solid #D5D555;
            background-position: center;
        }
    </style>
    <body topmargin="0" leftmargin="0">
        <table width="800" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td background="">
                    <img src="https://moaistd.moai-crm.com/crm/themes/softed/images/header_alert.jpg" width="800" height="75"/>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="800" border="0" cellspacing="0" cellpadding="0" class="small">
                        <tr>
                            <td colspan="2" align="left" class="dvInnerHeader" style="font-weight: bold; font-size: 18px;">
                                <?php echo $dear; ?>
                            </td>
                        </tr>
                    </table>

                    <?php foreach($blockList as $block){ ?>
                        <table width="800" border="0" cellspacing="0" cellpadding="0" class="small">
                        <tr>
                            <td colspan="2" align="left" class="dvInnerHeader">
                                <strong><?php echo $block['blockLabel']; ?></strong>
                            </td>
                        </tr>
                        <?php foreach($block['blockFields'] as $field){ ?>
                            <tr>
                                <td width="30%" class="dvtCellLabel"><?php echo $field['fieldLabel']; ?></td>
                                <td width="70%" class="dvtCellInfo" height="20"><?php echo $field['fieldValue']; ?></td>
                            </tr>
                        <?php } ?>
                        </table>
                    <?php } ?>

                    <table width="1000" border="0" cellspacing="0" cellpadding="0" class="small">
                        <tr>
                            <td class="dvInnerHeader" align="left" colspan="7"><strong>Item Detail</strong></td>
                        </tr>
                        <tr>
                            <td class="dvtCellLabel" align="center"><strong>รายการสินค้า</strong></td>
                            <td class="dvtCellLabel" align="center"><strong>ชนิดผิว</strong></td>
                            <td class="dvtCellLabel" align="center"><strong>ขนาด (มม.)</strong></td>
                            <td class="dvtCellLabel" align="center"><strong>ความหนา (มม.)</strong></td>
                            <td class="dvtCellLabel" align="center"><strong>หน่วยนับ</strong></td>
                            <td class="dvtCellLabel" align="center"><strong>จำนวน (แผ่น)</strong></td>
                            <td class="dvtCellLabel" align="center"><strong>จำนวนที่คาดว่าจะใช้</strong></td>
                        </tr>

                        <?php 
                        $amount_of_sample = 0;
		                $amount_of_purchase = 0;
                        foreach($itemList as $item){ 
                            $amount_of_sample = $amount_of_sample+$item['amount_of_sample'];
			                $amount_of_purchase = $amount_of_purchase+$item['amount_of_purchase'];
                            ?>
                        <tr>
                            <td class="dvtCellInfo" align="left">
                                <strong><?php echo $item['productname']; ?></strong>
                            </td>
                            <td class="dvtCellInfo" align="center"><?php echo $item['sr_finish']; ?></td>
                            <td class="dvtCellInfo" align="center"><?php echo $item['sr_size_mm']; ?></td>
                            <td class="dvtCellInfo" align="center"><?php echo $item['sr_thickness_mm']; ?></td>
                            <td class="dvtCellInfo" align="center"><?php echo $item['sr_product_unit']; ?></td>
                            <td class="dvtCellInfo" align="right"><?php echo number_format($item['amount_of_sample'], 2); ?></td>
                            <td class="dvtCellInfo" align="right"><?php echo number_format($item['amount_of_purchase'], 2); ?></td>
                        </tr>
                        <?php } ?>

                        <tr>
                            <td class="dvtCellInfo" colspan="5" align="right"><strong>Total.</strong></td>
                            <td class="dvtCellInfo" align="right"><strong><?php echo number_format($amount_of_sample,2); ?></strong></td>
                            <td class="dvtCellInfo" align="right"><strong><?php echo number_format($amount_of_purchase,2); ?></strong></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2">
                    <img src="https://moaistd.moai-crm.com/crm/themes/softed/images/footer.jpg" width="800" height="51"/>
                </td>
            </tr>
        </table>
    </body>
</html>
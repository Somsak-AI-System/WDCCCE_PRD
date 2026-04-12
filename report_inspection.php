<?
session_start();
include("config.inc.php");
include("library/dbconfig.php");
include_once("library/genarate.inc.php");
require_once('include/tcpdf/tcpdf.php');
include_once("library/myFunction.php");

$config['url'] = $site_URL;
$url_image = $config['url'] . '/themes/softed/images/';

global $generate;
$generate = new genarate($dbconfig, "DB");

require_once("library/myLibrary_mysqli.php");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

/*data to header*/
$inspectionid = $_REQUEST['inspectionid'];
$pquery = " call rpt_inspection_and_preventive (" . $inspectionid . ");";
$data_header = $myLibrary_mysqli->select($pquery);

//$data_footer = dataTofooter();
//echo '<pre>'; print_r($data_footer); echo '</pre>';exit;

function siguser($jobid = '')
{
    include("config.inc.php");
    include("library/dbconfig.php");
    include_once("library/genarate.inc.php");
    require_once('include/tcpdf/tcpdf.php');
    include_once("library/myFunction.php");


    global $generate;
    $generate = new genarate($dbconfig, "DB");

    require_once("library/myLibrary_mysqli.php");
    $myLibrary_mysqli = new myLibrary_mysqli();
    $myLibrary_mysqli->_dbconfig = $dbconfig;

    //    $jobid = $data_header[0]['jobid'];
    $pquery_siguser = " call rpt_service_report_siguser (" . $jobid . ");";
    $data_siguser = $myLibrary_mysqli->select($pquery_siguser);

    //echo '<pre>'; print_r($data_siguser); echo '</pre>';exit;

    return $data_siguser;
}

function sigcus($jobid = '')
{
    include("config.inc.php");
    include("library/dbconfig.php");
    include_once("library/genarate.inc.php");
    require_once('include/tcpdf/tcpdf.php');
    include_once("library/myFunction.php");


    global $generate;
    $generate = new genarate($dbconfig, "DB");

    require_once("library/myLibrary_mysqli.php");
    $myLibrary_mysqli = new myLibrary_mysqli();
    $myLibrary_mysqli->_dbconfig = $dbconfig;

    //    $jobid = $data_header[0]['jobid'];
    $pquery_sigcus = " call rpt_service_report_sigcus (" . $jobid . ");";
    $data_sigcus = $myLibrary_mysqli->select($pquery_sigcus);
    //echo '<pre>'; print_r($data_sigcus); echo '</pre>';exit;

    return $data_sigcus;
}

function dataTofooter()
{
    include("config.inc.php");
    include("library/dbconfig.php");
    include_once("library/genarate.inc.php");
    require_once('include/tcpdf/tcpdf.php');
    include_once("library/myFunction.php");

    $config['url'] = $site_URL;
    $url_image = $config['url'] . '/themes/softed/images/';

    global $generate;
    $generate = new genarate($dbconfig, "DB");

    require_once("library/myLibrary_mysqli.php");
    $myLibrary_mysqli = new myLibrary_mysqli();
    $myLibrary_mysqli->_dbconfig = $dbconfig;

    $inspectionid = $_REQUEST['inspectionid'];
    $pquery = " call rpt_inspection_and_preventive (" . $inspectionid . ");";
    $data_header = $myLibrary_mysqli->select($pquery);
    return $data_header;
}


function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF
{

    protected $last_page_flag = false;

    public function Close()
    {
        $this->last_page_flag = true;
        parent::Close();
    }

    //Page header
    /*public function Header()
    {
        // Logo

        $image_file = K_PATH_IMAGES . 'logo_1.jpg';
        echo $image_file;
        $this->Image($image_file, 10, 10, 55, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('angsanaupc', 'B', 23);

        $this->SetXY(65, 9);
        $this->Cell(10, 15, 'Systems Dot Com Co., Ltd.', 0, false, 'L', 0, '', 0, false, 'M', 'M');

        $this->SetFont('angsanaupc', 'B', 12);
        $this->SetXY(65, 16);
        $this->Cell(10, 15, '825 Phairojkijja Tower 16th floor, Bangna-trad Rd., Bangna, Bangna 10260', 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $this->SetXY(65, 21);
        $this->Cell(10, 15, 'Tel : 0-2744-1600 (Office Hour), 0-2 Fax : 0-2744-1610, 0-2744-0216 Website : www.systems.co.th', 0, false, 'L', 0, '', 0, false, 'M', 'M');


        $this->SetFont('angsanaupc', 'B', 12);
        $this->SetY(10);
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');

        $this->Ln();
        $this->SetFont('angsanaupc', 'B', 19);
        $this->SetXY(130, 28);
        $this->Cell(10, 15, 'รายงานการใช้อะไหล่ CE Name : ' . $this->name1 . '', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->SetXY(133, 37);
        if ($_REQUEST['startdate'] != "") {
            $d = date('d', strtotime($_REQUEST['startdate']));
            $m = date('m', strtotime($_REQUEST['startdate']));
            $y = date('Y', strtotime($_REQUEST['startdate'])) + 543;
            $stt = $d . "/" . $m . "/" . $y;
        } else {
            $stt = "..................................................";
        }
        if ($_REQUEST['enddate'] != "") {
            $d = date('d', strtotime($_REQUEST['enddate']));
            $m = date('m', strtotime($_REQUEST['enddate']));
            $y = date('Y', strtotime($_REQUEST['enddate'])) + 543;
            $entt = $d . "/" . $m . "/" . $y;
        } else {
            $entt = "..................................................";
        }
        $this->Cell(10, 15, 'ตั้งแต่ วันที่ ' . $stt . ' ถึง ' . $entt . '', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }*/

    // Page footer
    public function Footer()
    {
        $data_footer = dataTofooter();
        //        echo '<pre>'; print_r($data_footer); echo '</pre>';exit;
        $jobid = $data_footer[0]['jobid'];

        $data_signaturcus = sigcus($jobid);
        $data_signaturuser = siguser($jobid);
        //        echo '<pre>'; print_r($data_signaturuser); echo '</pre>';exit;

        // Page number
        //$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

        //        $this->SetY(10);
        //        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');

        //        echo $this->getAliasNumPage();exit;

        if ($this->last_page_flag) {
            // ... footer for the last page ...

            $this->SetFont('thsarabun', 'n', 11);

            //signaturuser
            if (isset($data_signaturuser[0]['image1']) && $data_signaturuser[0]['image1'] != '') {

                $image_siguser = $data_signaturuser[0]['image1'];
                $this->Image($image_siguser, 40, 245, 55, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            } else {
                $this->Cell(10, 15, $this->writeHTML('Inspection By_________________', true, 0, true, 0), 0, false, 'C', 0, '', 0, false, 'M', 'M');
            }

            //signatursignaturcus
            if (isset($data_signaturcus[0]['image1']) && $data_signaturcus[0]['image1'] != '') {

                $image_sigcus = $data_signaturcus[0]['image1'];
                $this->Image($image_sigcus, 135, 245, 55, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            } else {
                $this->Cell(10, 15, $this->writeHTML('Inspection By_________________', true, 0, true, 0), 0, false, 'C', 0, '', 0, false, 'M', 'M');
            }

            // customer_name
            $this->SetXY(45, -30);
            if (isset($data_signaturuser[0]['customer_name']) && $data_signaturuser[0]['customer_name'] != '') {

                $customer_name = "Inspection By <u>" . $data_signaturuser[0]['customer_name'] . "</u>";

                $this->Cell(10, 15, $this->writeHTML($customer_name, true, 0, true, 0), 0, false, 'C', 0, '', 0, false, 'M', 'M');
            } else {
                $this->Cell(10, 15, $this->writeHTML('Inspection By_________________', true, 0, true, 0), 0, false, 'C', 0, '', 0, false, 'M', 'M');
            }

            // engineer_name
            $this->SetXY(140, -30);
            if (isset($data_signaturcus[0]['engineer_name']) && $data_signaturcus[0]['engineer_name'] != '') {

                $engineer_name = "Accepted By <u>" . $data_signaturcus[0]['engineer_name'] . "</u>";

                $this->Cell(10, 15, $this->writeHTML($engineer_name, true, 0, true, 0), 0, false, 'C', 0, '', 0, false, 'M', 'M');
            } else {
                $this->Cell(10, 15, $this->writeHTML('Accepted By_________________', true, 0, true, 0), 0, false, 'C', 0, '', 0, false, 'M', 'M');
            }

            // effective date
            $this->SetXY(180, -16);
            if (isset($data_footer[0]['effective_date']) && $data_footer[0]['effective_date'] != '') {

                $isono = "วันที่บังคับใช้ " . $data_footer[0]['effective_date'];

                $this->Cell(10, 15, $isono, 0, false, 'C', 0, '', 0, false, 'M', 'M');
            } else {
                $this->Cell(10, 15, 'วันที่บังคับใช้ dd/mm/yyyy', 0, false, 'C', 0, '', 0, false, 'M', 'M');
            }

            // ISO
            $this->SetXY(15, -16);
            if (isset($data_footer[0]['iso_no']) && $data_footer[0]['iso_no'] != '') {

                $isono = "ISO - " . $data_footer[0]['iso_no'];

                $this->Cell(10, 15, $isono, 0, false, 'C', 0, '', 0, false, 'M', 'M');
            } else {
                $this->Cell(10, 15, 'ISO - 000-000-00-000', 0, false, 'C', 0, '', 0, false, 'M', 'M');
            }
        } else {
            // ... footer for the normal page ...
        }
    }

    public function test()
    {
        $lastpage = $this->last_page_flag;
        return $lastpage;
    }
}

// create new PDF document
$pdf = new MYPDF('P', 'mm', PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
//$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Inspection Report');
//$pdf->SetSubject('TCPDF Tutorial');
//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins left,top, right
$pdf->SetMargins(10, 10, 10);


$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$lastpage = $pdf->lastpage();
if ($lastpage) {
    $pdf->SetAutoPageBreak(TRUE, 60);
} else {
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
}

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


// remove default header/footer
$pdf->setPrintHeader(false);
//$pdf->setPrintFooter(false);

// set font
$pdf->SetFont('angsanaupc', 'n', 12);

// add a page
$pdf->AddPage();
// set some text to print

/*data to header*/
$inspectionid = $_REQUEST['inspectionid'];
$pquery = " call rpt_inspection_and_preventive (" . $inspectionid . ");";
$data_header = $myLibrary_mysqli->select($pquery);

/*Start Header*/
$html = '
<table width="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td align="left" valign="top" width="100%">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
      
        <td style="width:100px; height:25px; text-align:center; "><img src="'.$root_directory.'themes/softed/images/Picture1.jpg" width="50" height="50"></img></td>
        
		<td align="center" colspan="2"><font size="+8"><strong>' . $data_header[0]['report_name'] . '</strong></font></td>
      </tr>
	  <tr>
        <td></td>
      </tr>
	  <tr>
        <td colspan="3">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" >
			  <tr>
				<td>
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
					  <tr>
					    <th width="18%" ><font size="+4">Customer Name :</font></th>
					    <td width="54%"  valign="middle"><font size="+4"><u>' . $data_header[0]['accountname'] . '</u></font></td>
					    <th width="10%"><font size="+4">Inspe No :</font></th>
					    <td width="18%"  valign="middle"><font size="+4"><u>' . $data_header[0]['inspection_no'] . '</u></font></td>
                      </tr>
					  <tr>
						<td width="18%"><font size="+4">Dept :</font></td>
						<td width="54%"  valign="middle"><font size="+4"><u>' . $data_header[0]['job_department'] . '</u></font></td>
					    <th width="10%"><font size="+4">Date :</font></th>
					    <td width="18%"  valign="middle"><font size="+4"><u>' . $data_header[0]['jobdate_operate'] . '</u></font></td>
					  </tr>
					  <tr>
						<th width="18%"><font size="+4">Model :</font></th>
					    <td width="10%" valign="middle"><font size="+4"><u>' . $data_header[0]['model'] . '</u></font></td>
					    <td width="10%"><font size="+4">Brand :</font></td>
						<td width="34%" valign="middle"><font size="+4"><u>' . $data_header[0]['brand'] . '</u></font></td>
						<th width="10%"><font size="+4">SN :</font></th>
					    <td width="30%" valign="middle"><font size="+4"><u>' . $data_header[0]['serial_name'] . '</u></font></td>
					  </tr>
                      <tr>
                        <td colspan="2" ></td>
                      </tr>
						';
/*END HEADER*/

$html .= '
					</table>
				</td>
			   </tr>
			</table>
			</td>
		</tr>
    </table>
    </td>
    <td colspan="2" align="left" valign="top" width="30%">

	</td>
  </tr>
  
    ';

//print_r($data);exit;

/*template_id*/
$pquery_templateid = "SELECT
	inspectiontemplateid 
FROM
	aicrm_inspection
	INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_inspection.inspectionid
AND aicrm_crmentity.deleted = 0 
WHERE
	aicrm_inspection.inspectionid = '" . $inspectionid . "'";

$templateid_temp = $myLibrary_mysqli->select($pquery_templateid);
$templateid = $templateid_temp[0]['inspectiontemplateid'];

/*data_to_template1*/
if (isset($templateid) && $templateid != 0) {

    $pquery = "SELECT  aicrm_inspectiontemplate_choice.*, aicrm_inspectiontemplate_choicedetail.* 
	FROM aicrm_inspectiontemplate
	INNER JOIN aicrm_inspectiontemplate_choice on aicrm_inspectiontemplate_choice.inspectiontemplateid = aicrm_inspectiontemplate.inspectiontemplateid 
	LEFT JOIN aicrm_inspectiontemplate_choicedetail on aicrm_inspectiontemplate_choicedetail.choiceid = aicrm_inspectiontemplate_choice.choiceid
	
	WHERE aicrm_inspectiontemplate.inspectiontemplateid = '" . $templateid . "' order by aicrm_inspectiontemplate_choice.sequence , aicrm_inspectiontemplate_choicedetail.sequence_detail asc ";

    $a_data = $myLibrary_mysqli->select($pquery);

    $pquery2 = "SELECT  aicrm_inspectiontemplate_choice.*, aicrm_inspectiontemplate_choicedetail.*, aicrm_inspectiontemplate_choicedetail2.* 
    FROM aicrm_inspectiontemplate
    INNER JOIN aicrm_inspectiontemplate_choice on aicrm_inspectiontemplate_choice.inspectiontemplateid = aicrm_inspectiontemplate.inspectiontemplateid 
    LEFT JOIN aicrm_inspectiontemplate_choicedetail on aicrm_inspectiontemplate_choicedetail.choiceid = aicrm_inspectiontemplate_choice.choiceid
    INNER JOIN aicrm_inspectiontemplate_choicedetail2 ON aicrm_inspectiontemplate_choicedetail.choicedetailid = aicrm_inspectiontemplate_choicedetail2.choiceid 
    WHERE aicrm_inspectiontemplate.inspectiontemplateid = '" . $templateid . "' order by aicrm_inspectiontemplate_choice.sequence , aicrm_inspectiontemplate_choicedetail.sequence_detail asc ";
    $a_data2 = $myLibrary_mysqli->select($pquery2);

    $pquery_answer = "SELECT  aicrm_inspection_answer.*
	FROM aicrm_inspection_answer
	INNER JOIN aicrm_inspection on aicrm_inspection.inspectionid = aicrm_inspection_answer.inspectionid 
	LEFT JOIN aicrm_inspectiontemplate on aicrm_inspectiontemplate.inspectiontemplateid = aicrm_inspection_answer.inspectiontemplateid
	WHERE aicrm_inspectiontemplate.inspectiontemplateid = '" . $templateid . "' and aicrm_inspection.inspectionid = '" . $inspectionid . "' order by aicrm_inspection_answer.choiceid , aicrm_inspection_answer.choicedetailid asc ";
    //    echo $pquery_answer;exit;
    $a_data_answer = $myLibrary_mysqli->select($pquery_answer);
    //    echo "<pre>"; print_r($a_data_answer); echo "</pre>"; exit;
    $answer = array();

    if (!empty($a_data_answer)) {
        foreach ($a_data_answer as $key => $value) {
            $average = 0;
            $averag_val = 0;
            if ($value['choice_type'] == 'calibrate') {
                if ($value['data_col1'] != '0.00') {
                    $average++;
                }
                if ($value['data_col2'] != '0.00') {
                    $average++;
                }
                if ($value['data_col3'] != '0.00') {
                    $average++;
                }
                if ($value['data_col4'] != '0.00') {
                    $average++;
                }
                if ($value['data_col5'] != '0.00') {
                    $average++;
                }
                if ($value['data_col6'] != '0.00') {
                    $average++;
                }
                if ($value['data_col7'] != '0.00') {
                    $average++;
                }
                if ($value['data_col8'] != '0.00') {
                    $average++;
                }

                $averag_val = (($value['data_col1'] + $value['data_col2'] + $value['data_col3'] + $value['data_col4'] + $value['data_col5'] + $value['data_col6'] + $value['data_col7'] + $value['data_col8']) / $average);
                //echo number_format($averag_val,2); echo "<br>";
            }
            $value['average'] = number_format($averag_val, 2);
            //echo $average; echo "<br>";
            $answer[$value['choiceid']][$value['choicedetailid']] = $value;
        }
    }
    //    echo "<pre>"; print_r($answer); echo "</pre>";exit;
    $data_template = array();

    //    echo "<pre>"; print_r($a_data); echo "</pre>";exit;
    if (!empty($a_data)) {

        $choiceid = '';
        $i = -1;
        foreach ($a_data as $key => $value) {

            if ($choiceid != $value['choiceid']) {

                $c = 0;
                $i++;
                $data_template[$i]['choice_type'] = $value['choice_type'];
                $data_template[$i]['choice_title'] = $value['choice_title'];
                $data_template[$i]['sequence'] = $value['sequence'];
                $data_template[$i]['tolerance_type'] = $value['tolerance_type'];
                $data_template[$i]['unit'] = $value['unit'];
                $data_template[$i]['check_tolerance_unit'] = $value['check_tolerance_unit'];
                $data_template[$i]['tolerance_unit'] = $value['tolerance_unit'];
                $data_template[$i]['check_tolerance_percent'] = $value['check_tolerance_percent'];
                $data_template[$i]['tolerance_percent'] = $value['tolerance_percent'];
                $data_template[$i]['check_tolerance_fso'] = $value['check_tolerance_fso'];
                $data_template[$i]['tolerance_fso_percent'] = $value['tolerance_fso_percent'];
                $data_template[$i]['tolerance_fso_val'] = $value['tolerance_fso_val'];
                $data_template[$i]['set_tole_amount'] = $value['set_tole_amount'];
                $data_template[$i]['uncer_setting'] = $value['uncer_setting'];
                $data_template[$i]['uncer_reading'] = $value['uncer_reading'];
                $data_template[$i]['head_col0'] = $value['head_col0'];
                $data_template[$i]['head_col1'] = $value['head_col1'];
                $data_template[$i]['head_col2'] = $value['head_col2'];
                $data_template[$i]['head_col3'] = $value['head_col3'];
                $data_template[$i]['head_col4'] = $value['head_col4'];
                $data_template[$i]['head_col5'] = $value['head_col5'];
                $data_template[$i]['head_col6'] = $value['head_col6'];
                $data_template[$i]['head_col7'] = $value['head_col7'];
                $data_template[$i]['head_col8'] = $value['head_col8'];
                $data_template[$i]['head_col9'] = $value['head_col9'];
                $data_template[$i]['head_col10'] = $value['head_col10'];
                $data_template[$i]['head_col11'] = $value['head_col11'];
                $data_template[$i]['head_col12'] = $value['head_col12'];
                $data_template[$i]['head_col13'] = $value['head_col13'];
                $data_template[$i]['head_col14'] = $value['head_col14'];
                $data_template[$i]['head_col15'] = $value['head_col15'];
                $data_template[$i]['head_col16'] = $value['head_col16'];
                $data_template[$i]['head_col17'] = $value['head_col17'];
                $data_template[$i]['head_col18'] = $value['head_col18'];
                $data_template[$i]['head_col19'] = $value['head_col19'];
                $data_template[$i]['head_col20'] = $value['head_col20'];

                $data_template[$i]['head_col21'] = $value['head_col21'];
                $data_template[$i]['head_col22'] = $value['head_col22'];
                $data_template[$i]['head_col23'] = $value['head_col23'];
                $data_template[$i]['head_col24'] = $value['head_col24'];

                $data_template[$i]['list'][$c]['choicedetailid'] = $value['choicedetailid'];
                $data_template[$i]['list'][$c]['sequence_detail'] = $value['sequence_detail'];
                $data_template[$i]['list'][$c]['col0'] = $value['col0'];
                $data_template[$i]['list'][$c]['col1'] = $value['col1'];
                $data_template[$i]['list'][$c]['std_resolution'] = $value['std_resolution'];
                $data_template[$i]['list'][$c]['min'] = $value['min'];
                $data_template[$i]['list'][$c]['max'] = $value['max'];
                $data_template[$i]['list'][$c]['list'] = $value['list'];
                $data_template[$i]['list'][$c]['sublist'] = $value['sublist'];

                $data_template[$i]['list'][$c]['list2'] = $value['list2_template10'];
                $data_template[$i]['list'][$c]['answer'] = $answer[$value['choiceid']][$value['choicedetailid']];
                $data_template[$i]['list'][$c]['accept_range'] = $value['accept_range'];
                $choiceid = $value['choiceid'];
            } else if ($choiceid == $value['choiceid']) {
                $c++;
                $data_template[$i]['list'][$c]['choicedetailid'] = $value['choicedetailid'];
                $data_template[$i]['list'][$c]['sequence_detail'] = $value['sequence_detail'];
                $data_template[$i]['list'][$c]['col0'] = $value['col0'];
                $data_template[$i]['list'][$c]['col1'] = $value['col1'];
                $data_template[$i]['list'][$c]['std_resolution'] = $value['std_resolution'];
                $data_template[$i]['list'][$c]['min'] = $value['min'];
                $data_template[$i]['list'][$c]['max'] = $value['max'];
                $data_template[$i]['list'][$c]['list'] = $value['list'];
                $data_template[$i]['list'][$c]['sublist'] = $value['sublist'];
                $data_template[$i]['list'][$c]['list2'] = $value['list2_template10'];

                $data_template[$i]['list'][$c]['answer'] = $answer[$value['choiceid']][$value['choicedetailid']];
                $data_template[$i]['list'][$c]['accept_range'] = $value['accept_range'];

                $choiceid = $value['choiceid'];
            }
        }
    }

    $choiceDetailList = [];
    if (!empty($a_data2)) {

        foreach ($a_data2 as $key => $value) {
            $choiceDetailList[$value['choiceid']][] = [
                'sequence_detail' => $value['sequence_detail'],
                'value' => $value['list2']
            ];
        }
    }
}

/*ptest*/
foreach ($data_template as $key => $value) {

    $table = 'table' . generateRandomString();

    if ($value['choice_type'] == 'template1') {

        foreach ($value['list'] as $key2 => $value2) {

            $html .= '<table width="100%" border="0" cellpadding="0" cellspacing="2">
                        <thead>
                            <tr style="background-color:#ccc; padding:10px;">
                                <th width="100%" align="left" ><font size="+2"><b>' . $value['choice_title'] . '</b></font></th>
                            </tr>
                        </thead>
	                    <tbody>
	                        <tr>
	                            <td>' . ': ' . $value2['answer']['detail_template1'] . '</td>
                            </tr>
                        </tbody>            
	                    
                        </table>
                        ';
        }
    } elseif ($value['choice_type'] == 'template2') {

        $html .= '<table width="100%" border="0" cellpadding="0" cellspacing="2" style="min-height:460px" >
                        <thead>
                            <tr style="background-color:#ccc; padding:10px;">
                                <th width="100%" align="left" ><font size="+2"><b>' . $value['choice_title'] . '</b></font></th>
                            </tr>
                        </thead>
                    ';

        foreach ($value['list'] as $key2 => $value2) {

            $status_list = $value2['answer']['status_list_template2'];

            $html .= '
            <tbody >
            <tr align="left" valign="middle" >';
            if ($status_list == 1) {
                $html .= '<td width="10%" align="center" >
                            <img src="'.$root_directory.'themes/softed/images/imageCheck.png" width="15" height="15"></img>
                        </td>';
            } else {
                $html .= '<td width="10%" align="center" >
                            <img src="'.$root_directory.'themes/softed/images/imageUncheck.png" width="15" height="15"></img>
                        </td>';
            }
            $html .= '<td ><font size="+2">' . iconv('tis-620', 'utf-8', $value2['list']) . '</font></td>';
            $html .= '</tr>
            </tbody>
            ';
        }
        $html .= '</table>';
    } elseif ($value['choice_type'] == 'template3') {

        $html .= '<table width="100%" border="0" cellpadding="0" cellspacing="2" style="min-height:460px" >
                        <thead>
                            <tr style="background-color:#ccc; padding:10px;">
                                <th width="100%" align="left" ><font size="+2"><b>' . $value['choice_title'] . '</b></font></th>
                            </tr>
                        </thead>
                    ';

        foreach ($value['list'] as $key2 => $value2) {

            $status_list = $value2['answer']['status_list_template3'];

            $html .= '
            <tbody >
            <tr align="left" valign="middle" >';
            if ($status_list == 1) {
                $html .= '<td width="10%" align="center" >
                            <img src="'.$root_directory.'themes/softed/images/blackCircle.png" width="15" height="15"></img>
                        </td>';
            } else {
                $html .= '<td width="10%" align="center" >
                            <img src="'.$root_directory.'themes/softed/images/Circle.png" width="13" height="13"></img>
                        </td>';
            }
            $html .= '<td ><font size="+2">' . iconv('tis-620', 'utf-8', $value2['list']) . '</font></td>';
            $html .= '</tr>
            </tbody>
            ';
        }
        $html .= '</table>';
    } elseif ($value['choice_type'] == 'template4') {

        $html .= '<table width="100%" border="0" cellpadding="0" cellspacing="2" style="min-height:460px" >
                        <thead>
                            <tr style="background-color:#ccc; padding:10px;">
                                <th width="100%" align="left" ><font size="+2"><b>' . $value['choice_title'] . '</b></font></th>
                            </tr>
                        </thead>
                    ';

        $status_list = [];
        foreach ($value['list'] as $key2 => $value2) {

            $status_list[] = $value2['answer']['status_list_template4'];
        }
        $html .= '<tbody>
	                <tr>
	                   <td>' . ': ' . $status_list[0] . '</td>
                    </tr>
                  </tbody>            
               </table>
               ';
    } elseif ($value['choice_type'] == 'template5') {

        foreach ($value['list'] as $key2 => $value2) {

            $html .= '<table width="100%" border="0" cellpadding="0" cellspacing="2">
                        <thead>
                            <tr style="background-color:#ccc; padding:10px;">
                                <th width="100%" align="left" ><font size="+2"><b>' . $value['choice_title'] . '</b></font></th>
                            </tr>
                        </thead>
	                    <tbody>
	                        <tr>
	                            <td>' . ': ' . $value2['answer']['comment'] . '</td>
                            </tr>
                        </tbody>            
	                    
                        </table>
                        ';
        }
    } elseif ($value['choice_type'] == 'template6') {

        $html .= '<table width="100%" border="1" cellpadding="0" cellspacing="2" style="min-height:460px" >
                        <thead>
                            <tr style="background-color:#ccc; padding:10px;">
                                <th width="100%" align="left" ><font size="+2"><b>' . $value['choice_title'] . '</b></font></th>
                            </tr>
                        </thead>
            
            <tbody width="100%" align="center" >
            <tr>
            ';

        foreach ($value['list'] as $key2 => $value2) {

            $statusCheck = $value2['answer']['status_list_template6'];

            if ($statusCheck == 1) {
                $bgcolor = '#0000FF';
                $style = 'color:white;';
            } else {
                $bgcolor = '';
                $style = 'color:blue;';
            }

            $html .= '<td width="10%" align="center" style="' . $style . '" bgcolor="' . $bgcolor . '"> 
                        <input type="input"  />' . $value2['list'] . '<br />
                       </td>';
        }

        $html .= '</td></tbody>
                </table>
                ';
    } elseif ($value['choice_type'] == 'template7') {

        $html .= '<table width="100%" border="0" cellpadding="0" cellspacing="2" style="min-height:460px" >
                        <thead>
                            <tr style="background-color:#ccc; padding:10px;">
                                <th width="100%" align="left" ><font size="+2"><b>' . $value['choice_title'] . '</b></font></th>
                            </tr>
                        </thead>
                    ';

        foreach ($value['list'] as $key2 => $value2) {

            $status_list = $value2['answer']['status_list_template7'];

            $html .= '
            <tbody >
            <tr align="left" valign="middle" >';

            if ($status_list == 0) {
                $html .= '<td width="10%" align="center" >
                            <img src="'.$root_directory.'themes/softed/images/imageCheck.png" width="15" height="15"></img>
                        </td>';
            } else {
                $html .= '<td width="10%" align="center" >
                            <img src="'.$root_directory.'themes/softed/images/imageUncheck.png" width="15" height="15"></img>

                        </td>';
            }
            if ($status_list == 1) {
                $html .= '<td width="10%" align="center" >
                            <img src="'.$root_directory.'themes/softed/images/imageCheck.png" width="15" height="15"></img>
                        </td>';
            } else {
                $html .= '<td width="10%" align="center" >
                            <img src="'.$root_directory.'themes/softed/images/imageUncheck.png" width="15" height="15"></img>
                        </td>';
            }

            $html .= '<td ><font size="+2">' . iconv('tis-620', 'utf-8', $value2['list']) . '</font></td>';
            $html .= '</tr>
            </tbody>
            ';
        }
        $html .= '</table>';
    } elseif ($value['choice_type'] == 'template8') {

        $html .= '<table width="100%" border="0" cellpadding="0" cellspacing="2" style="min-height:460px" >
                        <thead>
                            <tr style="background-color:#ccc; padding:10px;">
                                <th width="100%" align="left" ><font size="+2"><b>' . $value['choice_title'] . '</b></font></th>
                            </tr>
                        </thead>
                    ';

        foreach ($value['list'] as $key2 => $value2) {

            $status_list = $value2['answer']['status_list_template8'];

            $html .= '
            <tbody >
            <tr align="left" valign="middle" >';
            if ($status_list == 1) {
                $html .= '<td width="10%" align="center" >
                            <img src="'.$root_directory.'themes/softed/images/imageCheck.png" width="15" height="15"></img>
                        </td>';
            } else {
                $html .= '<td width="10%" align="center" >
                            <img src="'.$root_directory.'themes/softed/images/imageUncheck.png" width="15" height="15"></img>
                        </td>';
            }

            $html .= '<td ><font size="+2">';
            $html .= ' ' . iconv('tis-620', 'utf-8', $value2['list']) . '<br/>';
            foreach ($choiceDetailList[$value2['choicedetailid']] as $key2 => $value2) {
                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;' . iconv('tis-620', 'utf-8', $value2['value']) . '';
            }
            $html .= '</font></td>';
            $html .= '</tr>
            </tbody>
            ';
        }
        $html .= '</table>';
    } elseif ($value['choice_type'] == 'template9') {

        //HEADER

        $html .= '<table width="100%" border="1" cellpadding="0" cellspacing="0" style="min-height:460px" >
                        <thead>
                            <tr style="background-color:#ccc; ">
                                <th width="100%" align="left" ><font size="+2"><b>' . $value['choice_title'] . '</b></font></th>
                            </tr>
                         </thead>
                         
                         <thead>
                            <tr>
                                <th rowspan="4" width="20%" align="center">
                                    ' . $value['head_col0'] . '
                                </th>
                                <th colspan="6" width="80%" align="center">
                                    ' . $value['list'][0]['answer']['detail_template9'] . '
                                </th>
                            </tr>
                            <tr>
                                <th rowspan="3" width="20%" align="center">
                                    ' . $value['head_col2'] . '
                                </th>
                                <th colspan="2" width="20%" align="center">
                                    ' . $value['head_col3'] . '
                                </th>
                                <th colspan="2" width="20%" align="center">
                                    ' . $value['head_col4'] . '
                                </th>
                                <th rowspan="3" width="20%" align="center">
                                    ' . $value['head_col5'] . '
                                </th>
                            </tr>
                            <tr>
                                <th colspan="2" width="20%" align="center">
                                     ' . $value['head_col6'] . '
                                </th>
                                <th colspan="2" width="20%" align="center">
                                     ' . $value['head_col7'] . '
                                </th>
                            </tr>
                            <tr>
                                <th width="10%" align="center">
                                    ' . $value['head_col8'] . '
                                </th>
                                <th width="10%" align="center">
                                    ' . $value['head_col9'] . '
                                </th>
                                <th width="10%" align="center">
                                    ' . $value['head_col10'] . '
                                </th>
                                <th width="10%" align="center">
                                    ' . $value['head_col11'] . '
                                </th>
                            
                            </tr>
                            
                         </thead>
                         
                    ';

        //BODY
        $html .= '<tbody width="100%" align="center" >
            ';

        foreach ($value['list'] as $key2 => $value2) {
            $html .= '<tr align="left" valign="middle" >';
            $html .= '<td width="20%" align="center"><font size="+2">' . $value2['sequence_detail'] . '</font></td>';
            $html .= '<td width="20%" ><font size="+2">' . iconv('tis-620', 'utf-8', $value2['answer']['data_col1']) . '</font></td>';
            $html .= '<td width="10%" ><font size="+2">' . iconv('tis-620', 'utf-8', $value2['answer']['data_col2']) . '</font></td>';
            $html .= '<td width="10%" ><font size="+2">' . iconv('tis-620', 'utf-8', $value2['answer']['data_col5']) . '</font></td>';
            $html .= '<td width="10%" ><font size="+2">' . iconv('tis-620', 'utf-8', $value2['answer']['data_col6']) . '</font></td>';
            $html .= '<td width="10%" ><font size="+2">' . iconv('tis-620', 'utf-8', $value2['answer']['data_col7']) . '</font></td>';
            $html .= '<td width="20%" ><font size="+2">' . iconv('tis-620', 'utf-8', $value2['answer']['data_col8']) . '</font></td>';

            $html .= '</tr>';
        }

        $html .= '</tbody>
                   
                    </table>';
    } elseif ($value['choice_type'] == 'template10') {

        $html .= '<table width="100%" border="1" cellpadding="0" cellspacing="0" style="min-height:460px" >
                        <thead>
                            <tr style="background-color:#ccc; ">
                                <th width="100%" align="left" ><font size="+2"><b>' . $value['choice_title'] . '</b></font></th>
                            </tr>
                         </thead>
                    ';

        $html .= '<tbody width="100%" align="center" >';

        foreach ($value['list'] as $key2 => $value2) {
            $status_list1 = $value2['answer']['status_list_template10'];
            $status_list2 = $value2['answer']['status_list_template10'];
            $status_list3 = $value2['answer']['status_list_template10'];

            $html .= '<tr align="left" valign="middle" >';
            $html .= '<td width="20%"><font size="+2">' . iconv('tis-620', 'utf-8', $value2['list']) . '</font></td>';
            $html .= '<td width="20%"><font size="+2">' . iconv('tis-620', 'utf-8', $value2['answer']['detail_template10']) . '</font></td>';
            if ($status_list1 == 1) {
                $html .= '<td align="center" width="20%">
                            <img src="'.$root_directory.'themes/softed/images/imageCheck.png" width="15" height="15"></img>
                        </td>';
            } else {
                $html .= '<td align="center" width="20%"><img src="'.$root_directory.'themes/softed/images/imageUncheck.png" width="15" height="15"></img></td>';
            }
            if ($status_list2 == 2) {
                $html .= '<td align="center" width="20%">
                            <img src="'.$root_directory.'themes/softed/images/imageCheck.png" width="15" height="15"></img>
                        </td>';
            } else {
                $html .= '<td align="center" width="20%"><img src="'.$root_directory.'themes/softed/images/imageUncheck.png" width="15" height="15"></img></td>';
            }
            if ($status_list3 == 3) {
                $html .= '<td align="center" width="20%">
                            <img src="'.$root_directory.'themes/softed/images/imageCheck.png" width="15" height="15"></img>
                        </td>';
            } else {
                $html .= '<td align="center" width="20%"><img src="'.$root_directory.'themes/softed/images/imageUncheck.png" width="15" height="15"></img></td>';
            }

            $html .= '</tr>';
        }

        $html .= ' </tbody>
                </table>';
    } elseif ($value['choice_type'] == 'template11') {

        $html .= '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="min-height:460px" >
                        <thead>
                            <tr style="background-color:#ccc; ">
                                <th width="100%" align="left" ><font size="+2"><b>' . $value['choice_title'] . '</b></font></th>
                            </tr>
                         </thead>
                    ';

        $html .= '<tbody width="100%" align="center" >';

        foreach ($value['list'] as $key2 => $value2) {

            $status_list = $value2['answer']['status_list_template11'];

            $html .= '<tr align="left" valign="middle" >';
            $html .= '<td width="25%" align="left"><font size="+2">- ' . $value2['list'] . '</font></td>';
            if ($status_list == 1) {
                $html .= '<td width="50%" align="left"><img src="'.$root_directory.'themes/softed/images/imageCheck.png" width="15" height="15"></img></td>';
            } else {
                $html .= '<td width="50%" align="left"><img src="'.$root_directory.'themes/softed/images/imageUncheck.png" width="15" height="15"></img></td>';
            }

            $html .= '</tr>';
        }

        $html .= ' </tbody>
                </table>';
    } elseif ($value['choice_type'] == 'template12') {

        $html .= '<table width="100%" border="1" cellpadding="0" cellspacing="0" style="min-height:460px" >
                        <thead>
                            <tr style="background-color:#ccc; ">
                                <th width="100%" align="left" ><font size="+2"><b>' . $value['choice_title'] . '</b></font></th>
                            </tr>
                         </thead>
                         <thead>
                            <tr>
                                <td width="25%" align="center" >
                                
                                </td>
                                <td width="25%" align="center">
                                    ' . $value['head_col0'] . '
                                </td>
                                <td width="50%" align="center">
                                    ' . $value['head_col1'] . '
                                </td>
                                
                            </tr>
                         </thead>
                    ';

        $html .= '<tbody width="100%" align="center" >';

        foreach ($value['list'] as $key2 => $value2) {

            $status_list = $value2['answer']['status_list_template12'];


            $html .= '<tr align="left" valign="middle" >';
            $html .= '<td width="25%" align="center"><font size="+2">' . $value2['list'] . '</font></td>';
            if ($status_list == 1) {
                $html .= '<td width="25%" align="center"><img src="'.$root_directory.'themes/softed/images/imageCheck.png" width="15" height="15"></img></td>';
            } else {
                $html .= '<td width="25%" align="center"><img src="'.$root_directory.'themes/softed/images/imageUncheck.png" width="15" height="15"></img></td>';
            }
            $html .= '<td width="50%" align="center"><font size="+2">' . $value2['answer']['data_list_template12'] . '</font></td>';

            $html .= '</tr>';
        }
        $html .= ' </tbody>
                </table>';
    } elseif ($value['choice_type'] == 'template13') {

        $html .= '<table width="100%" border="1" cellpadding="0" cellspacing="0" style="min-height:460px" >
                        <thead>
                            <tr style="background-color:#ccc; ">
                                <th width="100%" align="left" ><font size="+2"><b>' . $value['choice_title'] . '</b></font></th>
                            </tr>
                         </thead>
                         <thead>
                            <tr>
                                <td width="25%" align="center" >
                                
                                </td>
                                <td width="25%" align="center">
                                    ' . $value['head_col0'] . '
                                </td>
                                <td width="50%" align="center">
                                    ' . $value['head_col1'] . '
                                </td>
                                
                            </tr>
                         </thead>
                    ';


        $html .= '<tbody width="100%" align="center" >';

        foreach ($value['list'] as $key2 => $value2) {

            $status_list = $value2['answer']['status_list_template13'];

            $html .= '<tr align="left" valign="middle" >';
            $html .= '<td width="25%"><font size="+2">' . iconv('tis-620', 'utf-8', $value2['list']) . '</font></td>';
            $html .= '<td width="25%"></td>';
            $html .= '<td width="25%"></td>';
            $html .= '<td width="25%"></td>';
            $html .= '</tr>';

            $html .= '<tr align="left" valign="middle" >';
            $html .= '<td width="25%"><font size="+2">' . iconv('tis-620', 'utf-8', $value2['sublist']) . '</font></td>';
            if ($status_list == 1) {
                $html .= '<td width="25%" align="center"><img src="'.$root_directory.'themes/softed/images/imageCheck.png" width="15" height="15"></img></td>';
            } else {
                $html .= '<td width="25%" align="center"><img src="'.$root_directory.'themes/softed/images/imageUncheck.png" width="15" height="15"></img></td>';
            }
            $html .= '<td width="25%"><font size="+2">' . iconv('tis-620', 'utf-8', $value2['answer']['data_list_template13']) . '</font></td>';
            $html .= '<td width="25%"></td>';
            $html .= '</tr>';
        }
        $html .= ' </tbody>
                </table>';
    } elseif ($value['choice_type'] == 'template14') {

        $html .= '<table width="100%" border="1" cellpadding="0" cellspacing="0" style="min-height:460px" >
                        <thead>
                            <tr style="background-color:#ccc; ">
                                <th width="100%" align="left" ><font size="+2"><b>' . $value['choice_title'] . '</b></font></th>
                            </tr>
                         </thead>
                         <thead>
                            <tr>
                                <td width="50%" align="center" >
                                
                                </td>
                                <td width="25%" align="center">
                                    ' . $value['head_col0'] . '
                                </td>
                                <td width="25%" align="center">
                                    ' . $value['head_col1'] . '
                                </td>
                                
                            </tr>
                         </thead>
                    ';


        $html .= '<tbody width="100%" align="center" >';

        foreach ($value['list'] as $key2 => $value2) {

            $status_list1 = $value2['answer']['status_list_template14_1'];
            $status_list2 = $value2['answer']['status_list_template14_2'];

            $html .= '<tr align="left" valign="middle" >';
            $html .= '<td width="50%"><font size="+2">' . iconv('tis-620', 'utf-8', $value2['list']) . '</font></td>';
            if ($status_list1 == 1) {
                $html .= '<td width="25%" align="center"><img src="'.$root_directory.'themes/softed/images/imageCheck.png" width="15" height="15"></img></td>';
            } else {
                $html .= '<td width="25%" align="center"><img src="'.$root_directory.'themes/softed/images/imageUncheck.png" width="15" height="15"></img></td>';
            }
            if ($status_list1 == 1) {
                $html .= '<td width="25%" align="center"><img src="'.$root_directory.'themes/softed/images/imageCheck.png" width="15" height="15"></img></td>';
            } else {
                $html .= '<td width="25%" align="center"><img src="'.$root_directory.'themes/softed/images/imageUncheck.png" width="15" height="15"></img></td>';
            }
            $html .= '</tr>';
        }
        $html .= ' </tbody>
                </table>';
    } elseif ($value['choice_type'] == 'template15') {

        $html .= '<table width="100%" border="1" cellpadding="0" cellspacing="0" style="min-height:460px" >
                        <thead>
                            <tr style="background-color:#ccc; ">
                                <th width="100%" align="left" ><font size="+2"><b>' . $value['choice_title'] . '</b></font></th>
                            </tr>
                         </thead>
                         <thead>
                            <tr>
                                <td width="20%" align="center">
                                    ' . $value['head_col0'] . '
                                </td>
                                <td width="20%" align="center">
                                    ' . $value['head_col1'] . '
                                </td>
                                <td width="20%" align="center">
                                    ' . $value['head_col2'] . '
                                </td>
                                <td width="20%" align="center">
                                    ' . $value['head_col3'] . '
                                </td>
                                <td width="20%" align="center">
                                    ' . $value['head_col4'] . '
                                </td>
                                
                            </tr>
                         </thead>
                    ';


        $html .= '<tbody width="100%" align="center" >';

        foreach ($value['list'] as $key2 => $value2) {

            $text = '';
            if ($value2['answer']['status_list_template15_1'] == 1) {
                $text = 'Yes';
            } else {
                $text = 'No';
            }

            $html .= '<tr align="left" valign="middle" >';
            $html .= '<td width="20%"><font size="+2">' . iconv('tis-620', 'utf-8', $value2['list']) . '</font></td>';
            $html .= '<td width="20%"><font size="+2">' . iconv('tis-620', 'utf-8', $value2['answer']['data_list_template15_1']) . '</font></td>';
            $html .= '<td width="20%"><font size="+2">' . iconv('tis-620', 'utf-8', $value2['answer']['data_list_template15_2']) . '</font></td>';

            $html .= '<td width="20%"><font size="+2">' . iconv('tis-620', 'utf-8', $value2['accept_range']) . '</font></td>';
            $html .= '<td width="20%" align="center"><font size="+2">' . $text . '</font></td>';


            $html .= '</tr>';
        }
        $html .= ' </tbody>
                </table>';
    } elseif ($value['choice_type'] == 'template16') {

        $html .= '<table width="100%" border="1" cellpadding="0" cellspacing="0" style="min-height:460px" >
                        <thead>
                            <tr style="background-color:#ccc; ">
                                <th width="100%" align="left" ><font size="+2"><b>' . $value['choice_title'] . '</b></font></th>
                            </tr>
                         </thead>
                         <thead>
                            <tr>
                                <td width="100%" align="center">
                                    ' . $value['head_col0'] . '
                                </td>
                            </tr>
                            <tr>
                                <td width="25%" align="left">
                                    ' . $value['head_col1'] . '
                                </td>
                                <td width="25%" align="center">
                                    ' . $value['head_col2'] . '
                                </td>
                                <td width="25%" align="center">
                                    ' . $value['head_col3'] . '
                                </td>
                                <td width="25%" align="center">
                                    ' . $value['head_col4'] . '
                                </td>
                            </tr>                            
                         </thead>
                    ';


        $html .= '<tbody width="100%" align="center" >';

        foreach ($value['list'] as $key2 => $value2) {

            $status_list1 = $value2['answer']['status_list_template16_1'];
            $status_list2 = $value2['answer']['status_list_template16_2'];
            $status_list3 = $value2['answer']['status_list_template16_3'];

            $html .= '<tr align="left" valign="middle" >';
            $html .= '<td width="25%"><font size="+2">' . iconv('tis-620', 'utf-8', $value2['list']) . '</font></td>';
            if ($status_list1 == 1) {
                $html .= '<td align="center" width="25%">
                            <img src="'.$root_directory.'themes/softed/images/imageCheck.png" width="15" height="15"></img>
                        </td>';
            } else {
                $html .= '<td align="center" width="25%"><img src="'.$root_directory.'themes/softed/images/imageUncheck.png" width="15" height="15"></img></td>';
            }
            if ($status_list2 == 1) {
                $html .= '<td align="center" width="25%">
                            <img src="'.$root_directory.'themes/softed/images/imageCheck.png" width="15" height="15"></img>
                        </td>';
            } else {
                $html .= '<td align="center" width="25%"><img src="'.$root_directory.'themes/softed/images/imageUncheck.png" width="15" height="15"></img></td>';
            }
            if ($status_list3 == 1) {
                $html .= '<td align="center" width="25%">
                            <img src="'.$root_directory.'themes/softed/images/imageCheck.png" width="15" height="15"></img>
                        </td>';
            } else {
                $html .= '<td align="center" width="25%"><img src="'.$root_directory.'themes/softed/images/imageUncheck.png" width="15" height="15"></img></td>';
            }
            $html .= '</tr>';
        }
        $html .= ' </tbody>
                </table>';
    } elseif ($value['choice_type'] == 'template17') {

        $html .= '<table width="100%" border="1" cellpadding="0" cellspacing="0" style="min-height:460px" >
                        <thead>
                            <tr style="background-color:#ccc; ">
                                <th width="100%" align="left" ><font size="+2"><b>' . $value['choice_title'] . '</b></font></th>
                            </tr>
                         </thead>
                         <thead>
                            <tr>
                                <td width="50%" align="center">
                                    ' . $value['head_col0'] . '
                                </td>
                                <td width="50%" align="center">
                                    ' . $value['head_col1'] . '
                                </td>
                            </tr>
                            <tr>
                                <td width="50%" align="left">
                                    ' . $value['head_col2'] . '
                                </td>
                                <td width="50%" align="left">
                                    ' . $value['head_col3'] . '
                                </td>                                
                            </tr>                            
                         </thead>
                    ';


        $html .= '<tbody width="100%" align="center" >';

        foreach ($value['list'] as $key2 => $value2) {

            $status_list1 = $value2['answer']['status_list_template17_1'];
            $status_list2 = $value2['answer']['status_list_template17_2'];

            $html .= '<tr align="left" valign="middle" >';
            if ($status_list1 == 1) {
                $html .= '<td align="left" width="50%">
                            <img src="'.$root_directory.'themes/softed/images/imageCheck.png" width="15" height="15"></img>          <font size="+2">' . iconv('tis-620', 'utf-8', $value2['list']) . '</font>
                        </td>';
            } else {
                $html .= '<td align="left" width="50%">
                            <img src="'.$root_directory.'themes/softed/images/imageUncheck.png" width="15" height="15"></img>          <font size="+2">' . iconv('tis-620', 'utf-8', $value2['list']) . '</font>
                        </td>';
            }
            if ($status_list2 == 1) {
                $html .= '<td align="left" width="50%">
                            <img src="'.$root_directory.'themes/softed/images/imageCheck.png" width="15" height="15"></img>          <font size="+2">' . iconv('tis-620', 'utf-8', $value2['sublist']) . '</font>
                        </td>';
            } else {
                $html .= '<td align="left" width="50%">
                            <img src="'.$root_directory.'themes/softed/images/imageUncheck.png" width="15" height="15"></img>          <font size="+2">' . iconv('tis-620', 'utf-8', $value2['sublist']) . '</font>
                        </td>';
            }

            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '<tfoot>
                <tr>
                    <td width="50%" align="left">
                        ' . $value['head_col4'] . '
                    </td>
                    <td width="50%" align="left">
                        ' . $value['head_col5'] . '
                    </td>
                </tr>
                <tr>
                    <td width="50%" align="left">
                        <u>' . 'Time ' . $value['answer']['data_list_template17_1'] . ' Minutes' . '</u>
                    </td>
                    <td width="50%" align="left">
                        <u>' . 'Time ' . $value['answer']['data_list_template17_2'] . ' Minutes' . '</u>
                    </td>                                
                </tr>
                ';


        $html .= '</tfoot> 
                </table>';
    } elseif ($value['choice_type'] == 'template18') {

        $html .= '<table width="100%" border="1" cellpadding="0" cellspacing="0" style="min-height:460px" >
                        <thead>
                            <tr style="background-color:#ccc; ">
                                <th width="100%" align="left" ><font size="+2"><b>' . $value['choice_title'] . '</b></font></th>
                            </tr>
                         </thead>
                         <thead>
                            <tr>
                                <td width="33.33%" align="center">
                                    ' . $value['head_col0'] . '
                                </td>
                                <td width="33.33%" align="center">
                                    ' . $value['head_col1'] . '
                                </td>
                                <td width="33.33%" align="center">
                                    ' . $value['head_col2'] . '
                                </td>
                            </tr>
                         </thead>
                    ';


        $html .= '<tbody width="100%" align="center" >';

        foreach ($value['list'] as $key2 => $value2) {

            $text = '';
            if ($value2['answer']['status_list_template18_1'] == 1) {
                $text = 'Yes';
            } else {
                $text = 'No';
            }

            $html .= '<tr align="left" valign="middle" >';
            $html .= '<td width="33.33%"><font size="+2">' . iconv('tis-620', 'utf-8', $value2['list']) . '</font></td>';
            $html .= '<td width="33.33%"><font size="+2">' . iconv('tis-620', 'utf-8', $text) . '</font></td>';
            $html .= '<td width="33.33%"><font size="+2">' . iconv('tis-620', 'utf-8', $value2['answer']['data_list_template18_2']) . '</font></td>';

            $html .= '</tr>';
        }

        $html .= '</tbody>
                </table>';
    }
}
//print_r($html);
//exit;
$pdf->SetFont('thsarabun', 'n', 11);
$pdf->writeHTML($html, true, 0, true, 0);

//Close and output PDF document
$pdf->Output($data_header[0]['inspection_no'] . '-' . date('Ymd') . '.pdf', 'I');


?>
<style>
    table,
    th,
    td {
        border: 1px solid black;
    }
</style>
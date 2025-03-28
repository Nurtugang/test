<?php
if (!file_exists($datadir.date('Y').'/')) {
    mkdir($datadir.date('Y').'/');
}
if (!file_exists($datadir.date('Y').'/'.$doctypeid.'/')) {
    mkdir($datadir.date('Y').'/'.$doctypeid.'/');
}
if (!file_exists($datadir.date('Y').'/'.$doctypeid.'/'.$_SESSION['personid'].'/')) {
    mkdir($datadir.date('Y').'/'.$doctypeid.'/'.$_SESSION['personid'].'/');
}

$sql = "select groupID from students where StudentID='".$_SESSION['personid']."' and isstudent>0";
$res = $con->query($sql) or die($sql);
list($groupID)=mysqli_fetch_row($res);

$sql = "select name from `groups` where groupid=$groupID";
$res = $con->query($sql);
list($group) = mysqli_fetch_row($res);

$sql = "select nameru from doctypes where doctypeid=$doctypeid limit 1";
$res = $condocs->query($sql) or die($sql);
list($namedoc) = mysqli_fetch_row($res);
$sql = "insert into documents(author,name,status,filename,dir,doctypeid,roleid) values(".$_SESSION['personid'].",'$namedoc (".$_SESSION['fio'].") $group',1,'".$_SESSION['personid'].".pdf','',$doctypeid,1)";
$condocs->query($sql) or die($sql);
$sql = "select last_insert_id()";
$res = $condocs->query($sql) or die($sql);
list($documentid) = mysqli_fetch_row($res);
$sql = "update doccoo set documentid=$documentid where doccooid=$doccooid";
$condocs->query($sql);
mkdir($datadir.date('Y').'/'.$doctypeid.'/'.$_SESSION['personid'].'/'.$documentid);
$dir = date('Y').'/'.$doctypeid.'/'.$_SESSION['personid'].'/'.$documentid.'/';
$sql = "update documents set dir='$dir' where documentid=$documentid";
$condocs->query($sql) or die($sql);
$sql = "insert into documentsignlists(documentid,personid,type,roleid) values($documentid,$coo,3,4)";
$condocs->query($sql);
$sql = "select auto from doctypes where doctypeid=$doctypeid";
$res = $condocs->query($sql);
list($signed) = mysqli_fetch_row($res);
$sql = "insert into documentsignlists(documentid,personid,type,roleid) values($documentid,$signed,1,2)";
$condocs->query($sql);
$dir = $datadir.date('Y').'/'.$doctypeid.'/'.$_SESSION['personid'].'/'.$documentid.'/';

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Removing Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */
// Include the main TCPDF library (search for installation path).
require_once('TCPDF/tcpdf.php');
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Madiyev Tursun');
$pdf->SetTitle('ved print');
$pdf->SetSubject('TCPDF');
$pdf->SetKeywords('AIS');
// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set margins
$pdf->SetMargins(20, 10, 10);
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 15);
// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
// set some language-dependent strings (optional)
$s=$pdf->getAliasNbPages();

// set font
//$pdf->SetFont('times', '', 10);
$pdf->SetFont('freeserif', '', 12);
// add a page
$pdf->AddPage();

// $pdf->Cell(285,5.1,'# '.$doccooid,0,1,'L');
/* ----------------- TITUL --------------------------*/
$sql = "select FullNameRU,FullNameKZ from university";
$res = $con->query($sql);
list($nameru,$namekz) = mysqli_fetch_row($res);

$pdf->Image('https://sdo.semgu.kz/emb.jpg', 100, 10, 22, 22, 'JPG', 'https://sdo.semgu.kz', '', true, 600, '', false, false, 0, false, false, true);

$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'phase' => 10, 'color' => array(0, 0, 0));
$pdf->Line(20, 35, 200, 35, $style);
$style = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'phase' => 10, 'color' => array(0, 0, 0));
$pdf->Line(20, 36, 200, 36, $style);

$pdf->MultiCell(70, 5, $namekz, 0, 'C', 0, 0, '', '', true);
$pdf->SetXY(130,8);
$pdf->MultiCell(70, 5, $nameru, 0, 'C', 0, 0, '', '', true);
/*------------------- END TITUL ---------------------------*/


$data = str_replace('#NUMBER#','№ '.$doctypeid.'-'.$documentid,$data);

$pdf->setY(50);
$pdf->writeHTML($data, true, false, true, false, '');
$style = array(
    'border' => false,
    'vpadding' => 'auto',
    'hpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255)
    'module_width' => 1, // width of a single module in points
    'module_height' => 1 // height of a single module in points
);
$pdf->write2DBarcode("https://sdo.semgu.kz/documents.php?id=$documentid&zid=$doccooid&code=$uuid", 'QRCODE,Q', 20, 235, 30, 30, $style, 'N');
//$pdf->SetY(5);
//$pdf->SetX(25);

$pdf->SetFont('freeserif', '', 7);
$foter='<p>2003 жылғы 7 қаңтардағы №370-ІІ «Электрондық құжат және электрондық цифрлық қолтаңба» туралы ҚР Заңының 7-бабы 1 тармағына сәйкес қол қоюға өкілеттілігі бар адамның электрондық цифрлық қолтаңбасы арқылы куәландырылған. Осы құжат қағаз жеткізгіштегі қол қойылған құжатпен бірдей. Данный документ согласно пункту 1 статьи 7 ЗРК от 7 января 2003 года №370-ІІ «Об электронном документе и электронной цифровой подписи», удостоверенный посредством электронной цифровой подписи лица, имеющего полномочия на его подписание, равнозначен подписанному документу на бумажном носителе.</p>';
$pdf->writeHTML($foter, true, false, true, false, '');
//die($data);
$pdf->Output($dir.$_SESSION['personid'].'.pdf', 'F');
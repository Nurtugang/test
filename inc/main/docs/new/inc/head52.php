<?php
$fileupload = false;
$url = 'https://ais.semgu.kz/mod.php?pa=student-index-transcript&c_stud=20151184';///.$_SESSION['studentid'];
//$html = file_get_contents($url);
//$database_64 = base64_encode($database_64);
$filename = 'transcript-'.$_SESSION['studentid'].'.pdf';

require_once('TCPDF/tcpdf.php');
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($fio);
$pdf->SetTitle($subjectname);
$pdf->SetSubject($subjectname);
$pdf->SetKeywords('AIS');
// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set margins
$pdf->SetMargins(10, 10, 10);
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 10);
// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
// set some language-dependent strings (optional)
$s=$pdf->getAliasNbPages();
// set font
$pdf->SetFont('dejavusans', '', 10, '', true);

$pdf->AddPage();
$pdf->SetY(10);
include('transcript.php');
$pdf->Output($datadir.'tmp/'.$filename, 'F');
$database_64 = file_get_contents($datadir.'tmp/'.$filename);
$database_64 = base64_encode($database_64);
?>
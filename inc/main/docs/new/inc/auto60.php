<?php

$lang = 'kz';
$year = $_SESSION['year'];
$sql = "select StudentID, concat(lastname,' ', left(firstname,1),'.', left(patronymic,1),'.'),ProfessionID,StudyLanguageID,TypCurriculumID,CourseNumber,studyformid, dopusk,groupID,specializationID,PaymentFormID,lastname,firstname,patronymic,DATE_FORMAT(BirthDate,'%d.%m.%Y'),TypCurriculumID,YEAR(startdate),iinplt,grant_type,YEAR(enroll_order_date) from students where StudentID='".$_SESSION['personid']."' and isstudent>0";
$res = $con->query($sql) or die($sql);
list($c_stud,$fio,$c_spec,$langid,$tup,$course,$c_form,$dopusk,$groupID,$specializationID,$pay,$s,$n,$p,$bd,$tid,$startdate,$iin,$grt,$y1)=mysqli_fetch_row($res);
$fiofull = "$s $n $p";
$sql = "select data$lang,date_format(NOW(),'%d.%m.%Y') from doctemplates where doctypeid = $doctypeid";
$res = $condocs->query($sql) or die($condocs->error.'<br>'.$sql);
list($data,$date) = mysqli_fetch_row($res);
$data = str_replace('#COURSE#', $course, $data);
$data = str_replace('#FIO#', $fiofull, $data);
$data = str_replace('#BD#', $bd, $data);
// $data = str_replace('#GT#', $grt, $data);
$sql = "select professionName$lang,professionCode,classifier from professions where professionID=$c_spec";
$res = $con->query($sql) or die($con->error.'<br>'.$sql);
list($specname,$speccode,$cl) = mysqli_fetch_row($res);
if ($cl==2) {
	$sql = "select name$lang from specializations where id=$specializationID";
	$res = $con->query($sql);
	list($speczname) = mysqli_fetch_row($res);
	$spec = "$speccode - $specname ($speczname)";
} else {
	$spec = "$speccode - $specname";
}
$data = str_replace('#SPEC#', $spec, $data);

$sql="select Name$lang from grant_types where id=$grt";
$res = $con->query($sql);
list($namegt) = mysqli_fetch_row($res);
$data = str_replace('#GT#', $namegt, $data);

$sql = "select name from `groups` where groupid=$groupID";
$res = $con->query($sql);
list($groupname) = mysqli_fetch_row($res);
$data = str_replace('#GROUP#', $groupname, $data);

if($course==1 && ($c_form==15 || $c_form==16 || $c_form==17 || $c_form==20 || $c_form==21)){
$sql = "select oName$lang,courseCount from studyforms where id=$c_form";
}else{
$sql = "select Name$lang,courseCount from studyforms where id=$c_form";
}
$res = $con->query($sql);
list($form,$courseCount) = mysqli_fetch_row($res);
$data = str_replace('#FORM#', $form, $data);
$data = str_replace('#YEAREDU#', $courseCount, $data);

$sql = "select facultyName$lang from faculties where FacultyID=(select FacultyID from students_full where `StudentID`=$c_stud limit 1)";
$res = $con->query($sql);
list($facname) = mysqli_fetch_row($res);
$data = str_replace('#FACULTY#', $facname, $data);

$yeare = $year+1;
$data = str_replace('#ACADEMICYEAR#', $year." - $yeare", $data);

$years = $yeare-$course;
$yeare = $years+$courseCount;


$y2 = $y1+$courseCount;
$data = str_replace('#DATASTART#', "01.09.$y1", $data);
$data = str_replace('#DATAEND#', "30.06.$y2", $data);
$startdate = explode('-',$_SESSION['startdate']);
$data = str_replace('#YEARENTER#', $startdate[0], $data);
//$input = '<input type="text" id="sc" size="80" style="background-color: #ffa;">';
$input = '<span class="sc"></span><input type="hidden" id="sc">';
$data = str_replace('#SC#', $input, $data);
$data = str_replace('#SCV#', $input, $data);
$data = str_replace('#DATE#', $date, $data);
//echo $data;
?>
<div class="card ml-5 mr-5">
	<div class="position-absolute top-5 end-5 m-3 product-discount"><span class="">Образец</span></div>
							<div class="p-3" id="data"><?=$data?></div>
							<div class="">

							</div>
							<div class="card-body">
								<div class="clearfix">
									<p class="mb-0 float-start"><button type="button" onclick="$('#main').load('modules.php?pa=docs-index-new')" class="btn btn-outline-secondary">Отменить</button></p>
									<p class="mb-0 float-end fw-bold"><button type="button" id="savebutton" class="btn btn-outline-primary">Подтвердить</button></p>
								</div>
							</div>
						</div>
<script type="text/javascript">
	$('#savebutton').click(function (e) {
		e.preventDefault();
		$.post("modules.php?pa=docs-newdoc-new", { personid: "<?=$_SESSION['personid']?>", data: $('#data').html(), doctypeid: <?=$doctypeid?>, saveautodoc: 'save' })
			.done(function(data) {
                $('#main').load('modules.php?pa=docs-index-sent');
			});
	});

</script>
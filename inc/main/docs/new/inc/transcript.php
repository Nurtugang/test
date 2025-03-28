<?php
$c_stud = 20151184;//$_SESSION['studentid'];
$c_stud = $_SESSION['studentid'];
////////////////////////
$trads[2] = 'Қанағат-сыз/Unsat/Неуд';
$trads[3] = 'Қанағат-лық/Sat/Удов';
$trads[4] = 'Жақсы/Good/Хорошо';
$trads[5] = 'Өте жақсы/Excellent/Отлично';

if (isset($ispol)) {
	$sql = "select concat(lastname,' ',left(firstname,1),'.',left(patronymic,1),'.') as fio from tutors where tutorid=$ispol";
	$res = $con->query($sql);
	list($ispol) = mysqli_fetch_row($res);
} else {
	$ispol = $_SESSION['fio'];
}

/////////////////////
  $sql = "SELECT studylanguages.NameRU
     , studylanguages.NameKZ
     , studylanguages.NameEN
     , studyforms.NameRu AS form
     , professions.professionNameRU
     , professions.professionNameKZ
     , professions.professionNameEN
     , professions.professionCode
     , `groups`.name AS `group`
     , cafedras.cafedraNameRU
     , faculties.facultyNameRU
     , faculties.facultyNameKZ
     , faculties.facultyNameEN
     , students.CourseNumber
     , professions.classifier
     , specializations.nameru
     , specializations.namekz
     , specializations.nameen
     , year(students.StartDate) AS expr1,students.isstudent
FROM
  studylanguages
INNER JOIN students
ON studylanguages.Id = students.StudyLanguageID
INNER JOIN studyforms
ON studyforms.Id = students.StudyFormID
INNER JOIN professions
ON professions.professionID = students.ProfessionID
INNER JOIN `groups`
ON students.groupID = `groups`.groupID
INNER JOIN profession_cafedra
ON profession_cafedra.professionID = professions.professionID
INNER JOIN cafedras
ON profession_cafedra.cafedraID = cafedras.cafedraID
INNER JOIN faculties
ON faculties.FacultyID = cafedras.FacultyID
INNER JOIN specializations
ON specializations.prof_caf_id = profession_cafedra.id AND students.specializationID = specializations.id
WHERE
  students.StudentID = $c_stud";
  //echo $sql;
  $conapps = $condocs;
$res = $con->query($sql) or die($sql);
list($langnameru,$langnamekz,$langnameen,$formname,$specnameru,$specnamekz,$specnameen,$code,$groupname,$cafname,$facnameru,$facnamekz,$facnameen,$course,$classifier,$specznameru,$specznamekz,$specznameen,$startdate,$isstudent) = mysqli_fetch_row($res);
if ($classifier==2) {
	$specnameru .= " $specznameru";
	$specnamekz .= " $specznamekz";
	$specnameen .= " $specznameen";
}
$sql = "select fullnameru,fullnamekz,fullnameen from university";
$res = $con->query($sql) or die($sql);
list($vuznameru,$vuznamekz,$vuznameen) = mysqli_fetch_row($res);
//$str = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/mod/transcript.php');
$sql = "select data from printsettigs where level='head' and tipe like '%,148,%' and ldiv=1";

$res = $conapps->query($sql) or die($sql);
list($str) = mysqli_fetch_row($res);
$str = str_replace('#vuznamekz#', $vuznamekz, $str);
$str = str_replace('#vuznameru#', $vuznameru, $str);
$str = str_replace('#vuznameen#', $vuznameen, $str);
$str = str_replace('#facnameru#', $facnameru, $str);
$str = str_replace('#facnamekz#', $facnamekz, $str);
$str = str_replace('#facnameen#', $facnameen, $str);
$str = str_replace('#specnameru#', $code.' - '.$specnameru, $str);
$str = str_replace('#specnamekz#', $code.' - '.$specnamekz, $str);
$str = str_replace('#specnameen#', $code.' - '.$specnameen, $str);
$str = str_replace('#studentid#', $c_stud, $str);
$str = str_replace('#yearenter#', $startdate, $str);
$str = str_replace('#langedu#', "$langnamekz / $langnameen / $langnameru", $str);
$sql = "select lastname,firstname,patronymic,lastname_en,firstname_en from students where studentid=$c_stud";
$res = $con->query($sql) or die($sql);
list($s,$n,$p,$se,$ne) = mysqli_fetch_row($res);
$str = str_replace('#fiodata#', "$s $n $p / $se $ne", $str);
$html = $str;
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->SetFont('dejavusans', 'B', 8, 'B', true);
$pdf->Cell(0,0,"$s $n $p / $se $ne",0,1,'L',0,'');
$pdf->SetFont('dejavusans', '', 6, '', true);
$pdf->Cell(0,0,'Т.А.Ә.(бар болған жағдайда) / Last Name, First Name, Patronymic (if any) / Ф.И.О. (при его наличии)',0,1,'L',0,'');
$pdf->SetFont('dejavusans', 'B', 8, 'B', true);
$pdf->Cell(0,0,"$facnamekz / $facnameen / $facnameru",0,1,'L',0,'');
$pdf->SetFont('dejavusans', '', 6, '', true);
$pdf->Cell(0,0,'(Факультеті / Department/School of / Факультет)',0,1,'L',0,'');
$pdf->SetFont('dejavusans', 'B', 8, 'B', true);
$pdf->Cell(0,0,"$code - $specnamekz",0,1,'L',0,'');
$pdf->SetFont('dejavusans', '', 6, '', true);
$pdf->Cell(0,0,'Мамандықтың және (немесе) білім беру бағдарламасының коды және атауы',0,1,'L',0,'');
$pdf->SetFont('dejavusans', 'B', 8, 'B', true);
$pdf->Cell(0,0,"$code - $specnameen",0,1,'L',0,'');
$pdf->SetFont('dejavusans', '', 6, '', true);
$pdf->Cell(0,0,'Code and name of the specialty and (or) educational program',0,1,'L',0,'');
$pdf->SetFont('dejavusans', 'B', 8, 'B', true);
$pdf->Cell(0,0,"$code - $specnameru",0,1,'L',0,'');
$pdf->SetFont('dejavusans', '', 6, '', true);
$pdf->Cell(0,0,'Код и наименование специальности и (или) образовательной программы',0,1,'L',0,'');
$pdf->SetFont('dejavusans', 'B', 8, 'B', true);
$pdf->Cell(0,0,"$startdate",0,1,'L',0,'');
$pdf->SetFont('dejavusans', '', 6, '', true);
$pdf->Cell(0,0,'Түскен жылы:/ Enter year / Год поступления',0,1,'L',0,'');
$pdf->SetFont('dejavusans', 'B', 8, 'B', true);
$pdf->Cell(0,0,"$langnamekz / $langnameen / $langnameru",0,1,'L',0,'');
$pdf->SetFont('dejavusans', '', 6, '', true);
$pdf->Cell(0,0,'Оқу тілі/ Language of study/ Язык обучения',0,1,'L',0,'');
$pdf->SetFont('dejavusans', '', 8, '', true);

$sql = "select data from printsettigs where level='tablehead' and tipe like '%,148,%' and ldiv=1";
$res = $conapps->query($sql) or die($sql);
list($tablehead) = mysqli_fetch_row($res);
$sql = "select data from printsettigs where level='tablebody' and tipe like '%,148,%' and ldiv=1";
$res = $conapps->query($sql) or die($sql);
list($tablebody) = mysqli_fetch_row($res);
//if (!isset($v)) $v=1;
$sql = "select data from printsettigs where level='footh0' and tipe like '%,148,%' and ldiv=1";
$res = $conapps->query($sql) or die($sql);
list($footh0) = mysqli_fetch_row($res);
$sql = "select data from printsettigs where level='footh' and tipe like '%,148,%' and ldiv=1";
if ($v==2) $sql = "select data from printsettigs where level='footh2' and tipe like '%,148,%' and ldiv=1";
$res = $conapps->query($sql) or die($sql);
list($footh) = mysqli_fetch_row($res);
if ($v==100) {$footh='</table><table width="100%" border="0"><tr><td>Игерілген кредиттер саны/ Number of assimilated loans/ Количество освоенных кредитов <strong>#credits#</strong></td></tr></table><br /><br />';}
$html = $tablehead;
$sql = "select max(coursenumber) from transcript where studentid=$c_stud";
$res = $con->query($sql);
list($max) = mysqli_fetch_row($res);
if ($isstudent==3) {
	$sql = "select max(coursenumber) from deletedtranscript where studentid=$c_stud";
	$res = $con->query($sql);
	list($max) = mysqli_fetch_row($res);
}
for ($i=1;$i<=$max;$i++) {
	$sql = "select subjectnameru,subjectnamekz,subjectnameen,subjectcode,credits,alphamark,numeralmark,totalmark,term,traditionalMark from transcript where type=0 and deleted<>1 and coursenumber=$i and studentid=$c_stud order by term,subjectnameru,totalmark";
	if ($isstudent==3) {
		$sql = "select subjectnameru,subjectnamekz,subjectnameen,subjectcode,credits,alphamark,numeralmark,totalmark,term,traditionalMark from deletedtranscript where type=0 and deleted<>1 and coursenumber=$i and studentid=$c_stud order by term,subjectnameru,totalmark";
	}
	$res = $con->query($sql) or die($con->error);
	$j=1;
	while (list($subjectnameru,$subjectnamekz,$subjectnameen,$subjectcode,$credits,$alphamark,$numeralmark,$totalmark,$term,$trad) = mysqli_fetch_row($res)) {
		$totalmark = round($totalmark);
		$tbl = str_replace('#i#', $j, $tablebody);
		$tbl = str_replace('#name#', "$subjectnamekz/$subjectnameen/$subjectnameru", $tbl);
		$tbl = str_replace('#code#', $subjectcode, $tbl);
		$tbl = str_replace('#credit#', $credits, $tbl);
		//$tbl = str_replace('#ects#', ects($credits), $tbl);
		$tbl = str_replace('#gradef#', $totalmark, $tbl);
		$tbl = str_replace('#gradeb#', $numeralmark, $tbl);
		$tbl = str_replace('#gradec#', $alphamark, $tbl);
		$tbl = str_replace('#trad#', $trads[$trad], $tbl);

		$html .= $tbl;
		$j++;
	}
	$sql = "select GET_GPAL($c_stud,$i)";
	$resgpa = $con->query($sql) or die($con->error);
	list($gpa) = mysqli_fetch_row($resgpa);
	$tbl = str_replace('#i#', '', $tablebody);
	$tbl = str_replace('#name#', "<strong>$i курсы GPA – $gpa/ $i course GPA – $gpa/ $i курс GPA – $gpa</strong>", $tbl);
	$tbl = str_replace('#code#', '', $tbl);
	$tbl = str_replace('#credit#', '', $tbl);
	$tbl = str_replace('#ects#', '', $tbl);
	$tbl = str_replace('#gradef#', '', $tbl);
	$tbl = str_replace('#gradeb#', '', $tbl);
	$tbl = str_replace('#gradec#', '', $tbl);
	$tbl = str_replace('#trad#', '', $tbl);
	$html .= $tbl;
}
$html .= '</table>';
$pdf->writeHTML($html, true, false, true, false, '');
$html = '<br />';
if ($isstudent==3) {
	$sql = "select GET_CREDITSLDEL($c_stud)";
}else{
$sql = "select GET_CREDITSL($c_stud)";
}
$res = $con->query($sql) or die($con->error);
list($creditstotal) = mysqli_fetch_row($res);
if ($isstudent==3) {
	$sql = "select GET_GPALDEL($c_stud,0)";
}else{
	$sql = "select GET_GPAL($c_stud,0)";
}
	$resgpa = $con->query($sql) or die($con->error);
	list($gpa) = mysqli_fetch_row($resgpa);
//$credits .= '<br />GPA / GPA / GPA '.$gpa;
$footh0 = str_replace('#credits#', $credits, $footh0);

$sql = "select subjectnameru,subjectnamekz,subjectnameen,subjectcode,credits,alphamark,numeralmark,totalmark,term,traditionalMark from transcript where type<>0 and deleted<>1 and studentid=$c_stud order by term,subjectnameru,totalmark";
$respr = $con->query($sql) or die($con->error);
if ($respr->num_rows>0) {
	$sql = "select data from printsettigs where level='tableheadpr' and tipe like '%,148,%' and ldiv=1";
	$res = $conapps->query($sql) or die($sql);
	list($tableheadpr) = mysqli_fetch_row($res);
	$html .= $tableheadpr;
	$sql = "select data from printsettigs where level='tablebodypr' and tipe like '%,148,%' and ldiv=1";
	$res = $conapps->query($sql) or die($sql);
	list($tablebodypr) = mysqli_fetch_row($res);
	$j=1;
	while (list($subjectnameru,$subjectnamekz,$subjectnameen,$subjectcode,$credits,$alphamark,$numeralmark,$totalmark,$term,$trad) = mysqli_fetch_row($respr)) {
		$totalmark = round($totalmark);
		$tbl = str_replace('#i#', $j, $tablebodypr);
		$tbl = str_replace('#name#', "$subjectnamekz/$subjectnameen/$subjectnameru", $tbl);
		$tbl = str_replace('#code#', $subjectcode, $tbl);
		$tbl = str_replace('#credit#', $credits, $tbl);
		//$tbl = str_replace('#ects#', ects($credits), $tbl);
		$tbl = str_replace('#gradef#', $totalmark, $tbl);
		$tbl = str_replace('#gradeb#', $numeralmark, $tbl);
		$tbl = str_replace('#gradec#', $alphamark, $tbl);
		$tbl = str_replace('#trad#', $trads[$trad], $tbl);

		$html .= $tbl;
		$j++;
	}
}
$html .= '</table>';
$pdf->writeHTML($html, true, false, true, false, '');
$html = '<br />';
$sql = "SELECT generalexamsmarks.ap_mark, GET_ALPHA_MARK(generalexamsmarks.ap_mark), GET_NUMERAL_MARK(generalexamsmarks.ap_mark), generalexamsmarks.credits, generalexamsmarks.course, generalexamsmarks.term, generalexamsmarks.ap_traditionalMark, generalexams.SubjectRU, generalexams.SubjectKZ, generalexams.SubjectEN FROM generalexamsmarks INNER JOIN generalexams ON generalexamsmarks.examID = generalexams.examID WHERE generalexamsmarks.studentID = $c_stud and generalexamsmarks.ap_mark>0";
$resgak = $con->query($sql);
if ($resgak->num_rows>0) {
	$sql = "select data from printsettigs where level='tableheadgak' and tipe like '%,148,%' and ldiv=1";
	$res = $conapps->query($sql) or die($sql);
	list($tableheadgak) = mysqli_fetch_row($res);
	$tableheadgak = '</table>
<br /><br />
<strong>Білім алушыларды қорытынды аттестаттау/ Result of state examination/ Итоговая аттестация обучающихся</strong>
<br />
<table width="500" border="1">
	<tr>
 		<th rowspan="2" width="50%" class="tb">Мемлекеттік емтиханды тапсырды/ State examination was passed on/ Сдал государственные экзамены</th>
		<th colspan="4" class="tb">Баға/ Mark/ Оценка</th>
	</tr>
	<tr>
		<th class="tb">Сандық эквивалент бойынша/ By digital equivalent/ По цифровому эквиваленту</th>
		<th class="tb">Балдық жүйе бойынша/ In the score system/ По бальной системе</th>
		<th class="tb">Әріптік жүйе бойынша/ According to the letter system/ По буквенной системе</th>
		<th class="tb">Дәстүрлі жүйе бойынша/ Grafe in the traditional system/ По традиционной системе</th>
	</tr>';
	$html .= $tableheadgak;
	$sql = "select data from printsettigs where level='tablebodygak' and tipe like '%,148,%' and ldiv=1";
	$res = $conapps->query($sql) or die($sql);
	list($tablebodygak) = mysqli_fetch_row($res);
	$j=1;
	while (list($totalmark,$alphamark,$numeralmark,$credits,$course,$term,$trad,$subjectnameru,$subjectnamekz,$subjectnameen) = mysqli_fetch_row($resgak)) {
		$totalmark = round($totalmark);
		$tbl = str_replace('#i#', $j, $tablebodygak);
		$tbl = str_replace('#name#', "$subjectnamekz/$subjectnameen/$subjectnameru", $tbl);
		$tbl = str_replace('#code#', $subjectcode, $tbl);
		$tbl = str_replace('#credit#', $credits, $tbl);
		//$tbl = str_replace('#ects#', ects($credits), $tbl);
		$tbl = str_replace('#gradef#', $totalmark, $tbl);
		$tbl = str_replace('#gradeb#', $numeralmark, $tbl);
		$tbl = str_replace('#gradec#', $alphamark, $tbl);
		$tbl = str_replace('#trad#', $trads[$trad], $tbl);
		$html .= $tbl;
		$j++;
	}
}
$html .= '</table>';
//$pdf->Cell(120,0,'Білім алушыларды қорытынды аттестаттау/ Result of state examination/ Итоговая аттестация обучающихся',0,1,'L',0,'');
$pdf->writeHTML($html, true, false, true, false, '');

$html = $footh0;
$footh = str_replace('#c_reg#', $c_reg, $footh);
$footh = str_replace('#ispol#', $ispol.' т. (+7 722 2) 360205', $footh);
$footh = str_replace('#qrcode#', $qrcode, $footh);
$html .= $footh;

$pdf->SetFont('dejavusans', '', 8, '', true);
$pdf->Cell(120,0,"Жалпы кредит саны/ Total number of credit/ Общее число кредитов",0,0,'L',0,'');
$pdf->SetFont('dejavusans', 'B', 8, 'B', true);
$pdf->Cell(120,0,$creditstotal,0,1,'L',0,'');
//$pdf->Cell(120,0,$isstudent,0,1,'L',0,'');
$pdf->SetFont('dejavusans', '', 8, '', true);
$pdf->Cell(120,0,"GPA / GPA / GPA",0,0,'L',0,'');
$pdf->SetFont('dejavusans', 'B', 8, 'B', true);
$pdf->Cell(120,0,$gpa,0,1,'L',0,'');

$pdf->SetFont('dejavusans', 'B', 8, 'B', true);
$pdf->Cell(0,0,"БАСҚАРМА МҮШЕСІ - АКАДЕМИЯЛЫҚ МӘСЕЛЕЛЕР ЖӨНІНДЕГІ ПРОРЕКТОР/",0,1,'L',0,'');
$pdf->Cell(0,0,"MEMBER OF THE BOARD - VICE RECTOR FOR ACADEMIC AFFAIRS/",0,1,'L',0,'');
$pdf->Cell(0,0,"ЧЛЕН ПРАВЛЕНИЯ - ПРОРЕКТОР ПО АКАДЕМИЧЕСКИМ ВОПРОСАМ",0,1,'L',0,'');
$pdf->SetFont('dejavusans', '', 6, '', true);
$pdf->Cell(0,0,'қолы/ Signature/ подпись',0,1,'L',0,'');
$pdf->SetFont('dejavusans', 'B', 8, 'B', true);
$pdf->Cell(0,0,"ДЕКАН/ DEAN/ ДЕКАН",0,1,'L',0,'');
$pdf->SetFont('dejavusans', '', 6, '', true);
$pdf->Cell(0,0,'қолы/ Signature/ подпись',0,1,'L',0,'');
$pdf->SetFont('dejavusans', 'B', 8, 'B', true);
$pdf->Cell(0,0,"ТІРКЕУ ОФИСІНІҢ БАСШЫСЫ/ HEAD OF THE REGISTRAR’S OFFICE/ РУКОВОДИТЕЛЬ ОФИСА РЕГИСТРАТОРА",0,1,'L',0,'');
$pdf->SetFont('dejavusans', '', 6, '', true);
$pdf->Cell(0,0,'қолы/ Signature/ подпись',0,1,'L',0,'');
$pdf->SetFont('dejavusans', '', 8, '', true);
$pdf->Cell(0,0,"М.О. М.П. Тіркеу №/ registration №/ регистрационный №",0,1,'R',0,'');

//$pdf->writeHTML($html, true, false, true, false, '');
?>

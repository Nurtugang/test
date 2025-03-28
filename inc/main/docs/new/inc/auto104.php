<?php
$lang = 'kz';
$year = $_SESSION['year'];

$sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic, tutors.job_title_int, tutors.departmentid FROM tutors WHERE tutors.has_access = 1 AND tutors.deleted = 0 AND tutors.tutorID='".$_SESSION['personid']."' ";
$sql = "SELECT
  tutors.TutorID,
  tutors.lastname,
  tutors.firstname,
  tutors.patronymic,
  tutor_positions.ID,
  tutor_structuralsubdivision.subdivisionid,tutor_positions.NameKZ,tutor_structuralsubdivision.type
FROM tutor_structuralsubdivision
  INNER JOIN tutors
    ON tutor_structuralsubdivision.TutorID = tutors.TutorID
  INNER JOIN tutor_positions
    ON tutor_positions.ID = tutor_structuralsubdivision.`position`
WHERE tutors.TutorID = ".$_SESSION['personid']."
AND tutors.deleted <> 1
AND tutors.has_access = 1 order by tutor_structuralsubdivision.type";
$res = $con->query($sql) or die($sql);
list($tutid, $s, $n, $p,$jb,$dp,$jobt,$typepos)=mysqli_fetch_row($res);
$fiofull = "$s $n $p";
$sql = "select data$lang,date_format(NOW(),'%d.%m.%Y') from doctemplates where doctypeid = $doctypeid";
$res = $condocs->query($sql) or die($condocs->error.'<br>'.$sql);
list($data,$date) = mysqli_fetch_row($res);
/*
$sql = "SELECT tutor_positions.Name$lang FROM tutor_positions WHERE tutor_positions.ID IN ($jb)";
$res = $con->query($sql) or die($sql);
list($jobt)=mysqli_fetch_row($res);
*/
$sql = "SELECT structural_subdivision.name$lang FROM structural_subdivision WHERE structural_subdivision.id IN ($dp)";
$res = $con->query($sql) or die($sql);
list($jobname)=mysqli_fetch_row($res);

$data = str_replace('#FIO#', $fiofull, $data);
$data = str_replace('#DATE#', $date, $data);
$data = str_replace('#DOLJ#', $jobt, $data);
$data = str_replace('#JOBN#', $jobname, $data);
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
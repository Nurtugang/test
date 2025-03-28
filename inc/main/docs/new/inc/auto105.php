<?php
$lang = 'kz';
$year = $_SESSION['year'];

$sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM tutors WHERE tutors.has_access = 1 AND tutors.deleted = 0 AND tutors.tutorID='".$_SESSION['personid']."' ";
$res = $con->query($sql) or die($sql);
list($tutid, $s, $n, $p)=mysqli_fetch_row($res);
$fiofull = "$s $n $p";
$sql = "select data$lang,date_format(NOW(),'%d.%m.%Y') from doctemplates where doctypeid = $doctypeid";
$res = $condocs->query($sql) or die($condocs->error.'<br>'.$sql);
list($data,$date) = mysqli_fetch_row($res);



$sql = "SELECT DISTINCT tutor_positions.Name$lang, tutor_cafedra.cafedraid FROM tutor_cafedra INNER JOIN tutor_positions ON tutor_cafedra.`position` = tutor_positions.ID WHERE tutor_cafedra.deleted = 0 AND tutor_cafedra.tutorID IN ($tutid)";
$res = $con->query($sql) or die($sql);
list($jobt,$cafid)=mysqli_fetch_row($res);

$sql = "SELECT cafedras.cafedraName$lang FROM cafedras WHERE cafedras.cafedraID = $cafid";
$res = $con->query($sql) or die($sql);
list($cafn)=mysqli_fetch_row($res);

$data = str_replace('#FIO#', $fiofull, $data);
$data = str_replace('#DATE#', $date, $data);
$data = str_replace('#DOLJ#', $jobt, $data);
$data = str_replace('#JOBN#', $cafn, $data);
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

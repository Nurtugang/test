<?php
/*
$sql = "select personid,curdate,date_format(curdate,'%d.%m.%Y') from perco.persontabel where tabelid=$tabelid";
$res = $con->query($sql);
list($personid,$curdate,$date) = mysqli_fetch_row($res);
*/
$curdate = date('Y-m-d');
$sql = "select lastname,firstname,patronymic,mobilePhone,mail from tutors where tutorid=$personid";
$res = $con->query($sql) or die($sql);
list($lastname,$firstname,$patronymic,$phone,$mail) = mysqli_fetch_row($res);
$sql = "SELECT
personcontrols.controlid,
personcontrols.inoutdata,
personcontrols.role,
personcontrols.type,
personcontrols.repl,
buildings.buildingName,
personcontrols.createdate
FROM perco.controls
INNER JOIN perco.personcontrols
  ON controls.turniketid = personcontrols.turniketid
INNER JOIN nitro.buildings
  ON buildings.buildingID = controls.buildingid
WHERE DATE(personcontrols.createdate) = '$curdate'
AND personcontrols.personid = $personid";
$res = $con->query($sql);
?>
<section class="invoice">

<div class="row">
<div class="col-xs-12">
<h2 class="page-header">
<i class="fa fa-globe"></i> Данные СКУД
<small class="pull-right"><?php echo date('d.m.Y')?></small>
</h2>
</div>

</div>

<div class="row invoice-info">
<div class="col-sm-4 invoice-col">
Информационная система
<address>
<strong>AIS SemGU</strong><br>

</address>
</div>

<div class="col-sm-4 invoice-col">
ДАННЫЕ ПО:
<address>
<strong><?=$lastname?> <?=$firstname?> <?=$patronymic?></strong><br>
Phone: <?=$phone?><br>
Email: <a href="mailto:<?=$mail?>"><?=$mail?></a><br>
</address>
</div>


<div class="card rounded-4">
								<div class="card-body">
									<div class="d-flex align-items-start justify-content-between mb-1">
										<div class="">
										  <h6 class="mb-4">Данные по регистрации</h6>
										</div>

									  </div>
									<div class="table-responsive">
									  <div class="d-flex flex-column gap-4">
<?php
$sql = "SELECT
personcontrols.controlid,
personcontrols.inoutdata,
personcontrols.role,
personcontrols.type,
buildings.buildingName,
buildings.address,
date_format(personcontrols.createdate,'%d.%m.%Y %H:%i:%s')
FROM perco.controls
INNER JOIN perco.personcontrols
  ON controls.turniketid = personcontrols.turniketid
INNER JOIN nitro.buildings
  ON buildings.buildingID = controls.buildingid
WHERE DATE(personcontrols.createdate) = '$curdate'
AND personcontrols.personid = $personid";
$res = $con->query($sql) or die($sql);
while (list($id,$inoutdata,$role,$type,$bname,$baddress,$datetime)=mysqli_fetch_row($res)) {
  if ($inoutdata=='in') {
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-box-arrow-in-right" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0z"/>
  <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
</svg>';
} else {
  $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
  <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
</svg>';
}
?>
										<div class="d-flex align-items-center gap-3">
										  <div class="social-icon d-flex align-items-center gap-3 flex-grow-1">
											<?=$svg?>
											<div>
											  <h6 class="mb-0"><?=$bname?></h6>
											  <p class="mb-0"><?=$datetime?></p>
											</div>
										  </div>
										  <div class="dash-lable bg-light-success text-success rounded-3">
											<p class="text-success mb-0"><?=$type?></p>
										  </div>
										</div>
<?php
}
?>
									  </div>
									</div>
								  </div>
							</div>

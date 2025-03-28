<?php
header('Content-Type: application/json');
$personid = $_SESSION['personid'];
$data = array();
$date = new DateTime($start);
$month = $date->format("m");
$day = $date->format("d");
$year = $date->format("Y");
if ($day!=1) {
  $month++;
  if ($month==1) {
    $year++;
  }
}
//if ($day<10) $day = '0'.$day;
//if ($month<10) $month = '0'.$month;
$days = date("t", strtotime("$year-$month-01"));
if (intval($month)==intval(date('m')))
{
  $ds = intval(date('d'));
} else {
  $ds = 1;
}
for ($i=$ds;$i<=$days;$i++) {
  $d = $i;
  $m = $month;
  //if ($month<10) $m = '0'.$month;
  if ($i<10) $d = '0'.$i;
  $data[] = array(
    'id' => $i,
    'start' => "$year-$m-$d 12:00:00",
    'end' => "$year-$m-$d 12:00:00",
    'title' => 'Добавить',
    'description' => "$year-$m-$d 12:00:00",
    'type' => 'add',
    'classNames' => ["add-event"]
  );
  //echo "$year-$m-$d 12:00:00\n";
}
$sql = "SELECT
  edoevents.events_id,
  edoevents.title,
  edoevents.event_date,
  edoevents.event_time_start,
  edoevents.event_time_end,
  edoevents.personid
FROM edoevents_person
  INNER JOIN edoevents
    ON edoevents_person.events_id = edoevents.events_id
WHERE edoevents_person.personid = $personid AND edoevents.event_date>='$start' AND edoevents.event_date<='$end'";
$res = $con->query($sql) or die($sql);
if ($res->num_rows>0) {
	while (list($eid,$title,$date,$dstart,$dend,$pid) = mysqli_fetch_row($res)) {
		$data[] = array(
			'id' => $eid,
			'start' => $date.' '.$dstart,
			'end' => $date.' '.$dend,
			'title' => $title,
			'description' => $pid,
      'type' => 'edoevents',
      'classNames' => ["important-event"]
		);
	}
} else {
	if (count($data)==0) $data = [];
}

$sql = "SELECT distinct
  plandocsdet.detid,
  plandocsdet.docid,
  plandocsdet.namepunct,
  plandocsdet.workdate,
  plandocs.status,
  plandocs.nameru,
  plandocsdet.status
FROM plandocsdet
  INNER JOIN plandocs
    ON plandocsdet.docid = plandocs.docid
  INNER JOIN planusers
    ON planusers.detid = plandocsdet.detid
WHERE /*plandocs.status = 1
AND */plandocsdet.workdate >= '$start'
AND plandocsdet.workdate <= '$end'
AND (planusers.personid = $personid
OR planusers.adduserid = $personid)";
$res = $condocs->query($sql) or die($sql);
if ($res->num_rows>0) {
	while (list($detid,$docid,$namepunct,$workdate,$nameru,$status) = mysqli_fetch_row($res)) {
		$data[] = array(
			'id' => $detid,
			'start' => $workdate,
			'end' => $workdate,
			'title' => $namepunct,
			'description' => $nameru,
      'type' => 'plandocs',
      'classNames' => ["important-event"]
		);
	}
} else {
  if (count($data)==0) $data = [];
}

$sql = "SELECT distinct
  edoevents.events_id,
  edoevents.title,
  edoevents.event_date,
  edoevents.event_time_start,
  edoevents.event_time_end,
  edoevents.personid
FROM edoevents_person
  INNER JOIN edoevents
    ON edoevents_person.events_id = edoevents.events_id
WHERE edoevents.personid = $personid AND edoevents.event_date>='$start' AND edoevents.event_date<='$end'";
$res = $con->query($sql) or die($sql."\n".$con->error);
if ($res->num_rows>0) {
	while (list($eid,$title,$date,$dstart,$dend,$pid) = mysqli_fetch_row($res)) {
		$data[] = array(
      'id' => $eid,
			'start' => $date.' '.$dstart,
			'end' => $date.' '.$dend,
			'title' => $title,
			'description' => $pid,
      'type' => 'edoeventsauthor',
      'classNames' => ["important-event"]
		);
	}
} else {
  if (count($data)==0) $data = [];
}

echo json_encode($data);

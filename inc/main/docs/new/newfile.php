<?php
if (isset($_FILES['uploadctl'])) {
	$ext = pathinfo($_FILES['uploadctl']['name'], PATHINFO_EXTENSION);
      $sql = "select dir from documents where documentid=$documentid";
      $res = $condocs->query($sql);
      list($dir) = mysqli_fetch_row($res);
	$sql = "insert into documentfiles(documentid,docname,filename,author) values($documentid,'$docname','".$_FILES['uploadctl']['name']."',".$_SESSION['personid'].")";
	if ($condocs->query($sql)) {
		$sql = "select last_insert_id()";
		$res = $condocs->query($sql);
		list($fileid) = mysqli_fetch_row($res);
		if (move_uploaded_file($_FILES['uploadctl']['tmp_name'], $datadir.$dir.'/att-'.$fileid.'.'.$ext)) {
			$sql = "update documentfiles set filename = 'att-".$fileid.'.'.$ext."' where fileid=$fileid";
			if ($condocs->query($sql)) {

			} else {
				echo 'Error: '.$condocs->error;
				$sql = "delete from documentfiles where fileid=$fileid";
				$condocs->query($sql);
			}
		}
	} else {
		echo 'Error: '.$condocs->error;
	}
}
if (isset($_GET['attdocid'])) {
	$sql = "select doctypeid from documents where documentid=$documentid";
	$res = $condocs->query($sql);
	list($doctypeid) = mysqli_fetch_row($res);
	$sql = "select doctypeid,author from documents where documentid=$attdocid";
	$res = $condocs->query($sql);
	list($attdoctypeid,$author) = mysqli_fetch_row($res);
	if ($doctypeid==2 && ($attdoctypeid==19 || $attdoctypeid==26 || $attdoctypeid==18)) {
		$sql = "insert into documentfiles(documentid,docname,atdocumentid) values($documentid,'',$attdocid)";
		if ($condocs->query($sql)) {} else {echo 'Error: '.$condocs->error;}
	} elseif ($author==$_SESSION['personid']) {
		$sql = "insert into documentfiles(documentid,docname,atdocumentid) values($documentid,'',$attdocid)";
		if ($condocs->query($sql)) {} else {echo 'Error: '.$condocs->error;}
	} else {
		$sql = "select * from documentsignlists where documentid=$attdocid and personid=".$_SESSION['personid'];
		$res = $condocs->query($sql);
		if ($res->num_rows>0) {
			$sql = "insert into documentfiles(documentid,docname,atdocumentid) values($documentid,'',$attdocid)";
			if ($condocs->query($sql)) {} else {echo 'Error: '.$condocs->error;}
		} else {
			echo '<b><span style="color:red">У Вас нет доступа к прикреплению данного документа '.$doctypeid.'</span></b><br />';
		}
	}
}
$sql = "select fileid,docname,filename,atdocumentid,date_format(createdate,'%d.%m.%Y %H:%i:%s'),author,roleid from documentfiles where documentid=$documentid";
$res = $condocs->query($sql) or die($condocs->error);
//$Tutor = new Author();
//$Tutor->con = $condocs;
while (list($fileid,$docname,$filename,$atdocumentid,$createdate,$author,$roleid) = mysqli_fetch_row($res)) {
	if ($filename=='') {
		$sql = "select author,name,dir,filename,sigexid,date_format(createdate,'%d.%m.%Y %H:%i:%s'),roleid from documents where documentid=$atdocumentid";
		$resa = $condocs->query($sql);
		list($author,$name,$dir,$filename,$sigexid,$createdate,$roleid) = mysqli_fetch_row($resa);
		//$Tutor->tutorid = $author;
		$sql = "select concat(lastname,' ',left(firstname,1),'.',left(patronymic,1),'.') from users where personid = $author and roleid=$roleid";
		$resa = $condocs->query($sql);
		list($fio) = mysqli_fetch_row($resa);
		echo "<span onclick=\"fileload($atdocumentid)\" style=\"cursor:pointer;\">$name. От: ".$fio." <a href=\"https://sigex.kz/show/?id=$sigexid\" target=\"_blank\">(подписано $createdate)</a></span><br />";
	} else {
		//$Tutor->tutorid = $author;
		$sql = "select concat(lastname,' ',left(firstname,1),'.',left(patronymic,1),'.') from users where personid = $author and roleid=$roleid";
		$resa = $condocs->query($sql);
		list($fio) = mysqli_fetch_row($resa);
		echo "<span onclick=\"fileloadext($documentid,$fileid,$author)\" style=\"cursor:pointer;\">$docname От: ".$fio." (загружено $createdate)</span><br />";
	}
}
?>
<script type="text/javascript">
	function fileload(documentid) {
		$('#pdfframe').load('modules.php?pa=docs-docview-sent&documentid='+documentid);
	}
	function fileloadext(documentid,fileid,author) {
		$('#pdfframe').load('modules.php?pa=docs-docview-sent&documentid='+documentid+'&fileid='+fileid+'&author='+author);
	}
</script>
      
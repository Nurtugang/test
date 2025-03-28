<?php
$sql = "select filename,author,dir,signature from documents where documentid=$documentid";
$res = $condocs->query($sql);
list($filename,$author,$dir,$sign[]) = mysqli_fetch_row($res);

$sql = "select sigexsignid,personid,signature from documentsignlists where documentid=$documentid";
$res = $condocs->query($sql);
while (list($sigexsignid,$author,$signature) = mysqli_fetch_row($res)) {
	if ($sigexsignid!='') {
		$sign[] = $signature;
	}
}
for ($i=0;$i<count($sign);$i++) {
?>
<textarea id="sign-<?=$i?>"><?=$sign[$i]?></textarea>
<?php
}
?>	
<input type="hidden" id="signcount" value="<?=count($sign)?>">
<input type="hidden" id="filename" value="<?=$filename?>">
<?php
exit;
$sql = "select filename,author,dir from documents where documentid=$documentid";
$res = $condocs->query($sql);
list($filename,$author,$dir) = mysqli_fetch_row($res);

$sign[] = file_get_contents($datadir.$dir.$documentid.'/Sigex-Base64-'.$author.'.cms');
$sql = "select sigexsignid,personid from documentsignlists where documentid=$documentid";
$res = $condocs->query($sql);
while (list($sigexsignid,$author) = mysqli_fetch_row($res)) {
	if ($sigexsignid!='') {
		$sign[] = file_get_contents($datadir.$dir.$documentid.'/Sigex-Base64-'.$author.'.cms');
	}
}
for ($i=0;$i<count($sign);$i++) {
?>
<textarea id="sign-<?=$i?>"><?=$sign[$i]?></textarea>
<?php
}
?>	
<input type="hidden" id="signcount" value="<?=count($sign)?>">
<input type="hidden" id="filename" value="<?=$filename?>">

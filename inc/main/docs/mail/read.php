<?php
$sql = "SELECT date_format(emails.maildate,'%d.%m.%Y %h:%i'),emails.mailtheme,tutors.lastname,tutors.firstname,tutors.patronymic,emailsto.mailtoid,emails.mailbody,tutors.TutorID,emailsto.maildateread FROM nitroapps.emailsto INNER JOIN nitroapps.emails ON emailsto.mailid = emails.mailid INNER JOIN nitro.tutors ON tutors.TutorID = emails.mailfrom WHERE emails.mailstatus = 1 AND emails.mailid = $mailid AND emailsto.mailto = ".$_SESSION['personid'];
if (isset($send)) {
  $sql = "SELECT date_format(emails.maildate,'%d.%m.%Y %h:%i'),emails.mailtheme,tutors.lastname,tutors.firstname,tutors.patronymic,emailsto.mailtoid,emails.mailbody,tutors.TutorID,emailsto.maildateread FROM nitroapps.emailsto INNER JOIN nitroapps.emails ON emailsto.mailid = emails.mailid INNER JOIN nitro.tutors ON tutors.TutorID = emails.mailfrom WHERE emails.mailstatus = 1 AND emails.mailid = $mailid";
  $res = $con->query($sql);
  list($maildate,$mailtheme,$s,$n,$p,$mailtoid,$mailbody,$mailfromtutorid,$maildateread) = mysqli_fetch_row($res);
  $fio = "$s $n $p";
} else {
  $sql = "SELECT date_format(emails.maildate,'%d.%m.%Y %h:%i'),emails.mailtheme,tutors.lastname,tutors.firstname,tutors.patronymic,emailsto.mailtoid,emails.mailbody,tutors.TutorID,emailsto.maildateread FROM nitroapps.emailsto INNER JOIN nitroapps.emails ON emailsto.mailid = emails.mailid INNER JOIN nitro.tutors ON tutors.TutorID = emails.mailfrom WHERE emails.mailstatus = 1 AND emails.mailid = $mailid AND emailsto.mailto = ".$_SESSION['personid'];
  $res = $con->query($sql);
  list($maildate,$mailtheme,$s,$n,$p,$mailtoid,$mailbody,$mailfromtutorid,$maildateread) = mysqli_fetch_row($res);
  $fio = "$s $n $p";
  if ($maildateread=='') {
    $sql = "update emailsto set maildateread=now() where mailtoid=$mailtoid";
    $conapps->query($sql);
  }
}
$sql = "select group_concat(mailto) from emailsto where mailid=$mailid";
$res = $conapps->query($sql);
list($pps_group) = mysqli_fetch_row($res);
$sql = "select concat(lastname,' ',left(firstname,1),'.',left(patronymic,1),'.'),tutorid from tutors where tutorid in ($pps_group)";
$res = $con->query($sql);
$tousers = 'Получатели: ';
while (list($fffio,$tid) = mysqli_fetch_row($res)) {
  $sql = "select maildateread from emailsto where mailid=$mailid and mailto=$tid";
  $rest = $conapps->query($sql);
  list($mr) = mysqli_fetch_row($rest);
  if ($mr=='') {
    $style = 'red';
  } else {
    $style = 'green';
  }
  $tousers .= '<span style="color:'.$style.'">'.$fffio.'</span>';
}
?>
<div class="card">
  <div class="card-body">
<div class="email-read-box2 p-3">
							<h4>Тема: <?=$mailtheme?></h4>
              <hr>
              <small><?=$tousers?></small>
							<hr>
							<div class="d-flex align-items-center">
								<img src="https://ais.semgu.kz/mod/photos.php?personid=<?=$mailfromtutorid?>&person=tutor" width="42" height="42" class="rounded-circle" alt="">
								<div class="flex-grow-1 ms-2">
									<p class="mb-0 font-weight-bold"><?=$fio?></p>
									<div class="dropdown">
										<div class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Действия</div>
										<div class="dropdown-menu" style="">
                      <a class="dropdown-item" href="javascript:;">Ответить</a>
                      <a class="dropdown-item" href="javascript:;">Переслать</a>
											<a class="dropdown-item" href="javascript:;">Удалить</a>
										</div>
									</div>
								</div>
								<p class="mb-0 chat-time ps-5 ms-auto"><?=$maildate?></p>
							</div>
							<div class="email-read-content px-md-5 py-5">
                <?=$mailbody?>
							</div>
              <div class="box-footer" id="afiles"></div>
</div>

</div>
</div>


<script type="text/javascript">
  $('#afiles').load('modules.php?pa=docs-attach-mail&div=1&mailid=<?=$mailid?>');
  function reply() {
    $('#mailbox').load('modules.php?pa=edonew-newmail-mail&reply=<?=$mailid?>');
  };
  function forward() {
    $('#mailbox').load('modules.php?pa=edonew-newmail-mail&forward=<?=$mailid?>');
  };
  function deletemail() {
    $('.mtbli').removeClass('active');
    $('#atrash-<?=date('Y')?>-li').addClass('active');
    $('#mailbox').load('modules.php?pa=edonew-atrash-mail&delete=<?=$mailid?>');
  };
</script>

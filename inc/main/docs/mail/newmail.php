<?php
if (isset($_POST['to'])) {
  header("Content-type: application/json; charset=utf-8");
  $tutors = explode(',',$to);
  for ($i=0;$i<count($tutors);$i++) {
    $sql = "insert into emailsto(mailid,mailto) values($mailid,$tutors[$i])";
    if ($conapps->query($sql)) {
      $error=0;
    } else {
      $error=$conapps->error;
      //echo $error;
    }
  }
  if ($error==0) {
    $sql = "update emails set mailstatus=$status,mailtheme='$themas',mailbody='$textarea' where mailid=$mailid";
    if ($conapps->query($sql)) {
      echo '{data:"OK"}';
    } else {
      echo '{data:"'.$error.'"}';
    }
  } else {
    echo '{data:"'.$conapps->error.'"}';
  }
  exit;
}
if (isset($_FILES['uploadctl'])) {
  header("Content-type: application/json; charset=utf-8");
  if (!file_exists($_SESSION['emaildir'].date('Y'))) {
    mkdir($_SESSION['emaildir'].date('Y'));
  }
  if (!file_exists($_SESSION['emaildir'].date('Y').'/'.$_SESSION['personid'])) {
    mkdir($_SESSION['emaildir'].date('Y').'/'.$_SESSION['personid']);
  }
  //die($_SESSION['emaildir'].date('Y').'/'.$_SESSION['personid']);
  $dir = date('Y').'/'.$_SESSION['personid'].'/';
  $ext = pathinfo($_FILES['uploadctl']['name'], PATHINFO_EXTENSION);
  $sql = "insert into emaila(mailid,mailfiles) values($mailid,'')";
  $conapps->query($sql) or die($sql."<br>".$conapps->error);
  $sql = "select last_insert_id()";
  $res = $conapps->query($sql);
  list($aid) = mysqli_fetch_row($res);
  //echo $aid;
  $sql = "update emaila set mailfiles = '".$dir.$aid.'-'.$_FILES['uploadctl']['name']."' where aid = $aid";
  if ($conapps->query($sql)) {
    if (move_uploaded_file($_FILES['uploadctl']['tmp_name'], $_SESSION['emaildir'].$dir.''.$aid.'-'.$_FILES['uploadctl']['name'])) {
       //echo json_encode($data);
      echo 'ok';
     } else {
      $sql = "delete from emaila where aid=$aid";
      $conapps->query($sql);
     }
  } else {
    echo $conapps->error.'<br>'.$sql;
  }


  exit;
}

$sql = "select mailid from emails where mailfrom =".$_SESSION['personid']." and mailstatus=-1";
$res = $conapps->query($sql) or die($sql);
if ($res->num_rows>0) {
  list($mailiddel) = mysqli_fetch_row($res);
  $sql = "select mailfiles from emaila where mailid=$mailiddel";
  $res = $conapps->query($sql) or die($sql);
  while (list($mailfiles) = mysqli_fetch_row($res)) {
    $m = explode('/', $mailfiles);
    if ($m[1]==$_SESSION['personid']) unlink($_SESSION['emaildir'].$mailfiles);
  }
  $sql = "delete from emails where mailid=$mailiddel";
  $conapps->query($sql);
}
if (isset($forward)) {
  $sql = "select mailfrom,mailtheme,mailbody from emails where mailid=$forward";
  $res = $conapps->query($sql);
  list($tutorid,$mailtheme,$mailbody) = mysqli_fetch_row($res);
  $sql = "select concat(tutors.lastname, ' ',left(tutors.firstname,1),'. ',left(tutors.patronymic,1),'. (',structural_subdivision.nameru,')') FROM tutors INNER JOIN structural_subdivision ON tutors.departmentid = structural_subdivision.id where tutors.tutorid=$tutorid";
  $res = $con->query($sql);
  list($from) = mysqli_fetch_row($res);
  $sql = "insert into emails(mailfrom,mailtheme,mailbody) values(".$_SESSION['personid'].",'Forward from: $from. Thems: $mailtheme','$mailbody')";
  $conapps->query($sql);
  $sql = "select last_insert_id()";
  $res = $conapps->query($sql);
  list($mailid) = mysqli_fetch_row($res);
  $sql = "insert into emaila(mailid,mailfiles) (select $mailid,mailfiles from emaila where mailid=$forward)";
  $conapps->query($sql);
}
if (!isset($mailid)) {
  $sql = "insert into emails(mailfrom) values(".$_SESSION['personid'].")";
  $conapps->query($sql);
  $sql = "select last_insert_id()";
  $res = $conapps->query($sql);
  list($mailid) = mysqli_fetch_row($res);
} else {
  $sql = "select mailtheme,mailbody from emails where mailid=$mailid";
  $res = $conapps->query($sql);
  list($mailtheme,$mailbody) = mysqli_fetch_row($res);
  $sql = "select group_concat(mailto) from emailsto where mailid=$mailid";
  $res = $conapps->query($sql);
  list($tutorsto) = mysqli_fetch_row($res);
}
if (isset($reply)) {
  $sql = "select mailfrom,mailtheme,mailbody from emails where mailid=$reply";
  $res = $conapps->query($sql);
  list($tutorsto,$mailtheme,$mailbody) = mysqli_fetch_row($res);
  $mailtheme = 'Re: '.$mailtheme;
  $mailbody = '<p></p><p></p><p>Оригинал сообщения: </p><small>'.$mailbody.'</small>';
}
$sql = "select id,nameru from tutor_positions";
$res = $con->query($sql);
while (list($id,$name) = mysqli_fetch_row($res)) {
  $doljn[$id]=$name;
}
$sql = "SELECT tutors.TutorID, concat(tutors.lastname, ' ',left(tutors.firstname,1),'. ',left(tutors.patronymic,1),'. (',structural_subdivision.nameru,')'),structural_subdivision.dean,structural_subdivision.subdivision_type,tutors.job_title_int FROM tutors INNER JOIN structural_subdivision ON tutors.departmentid = structural_subdivision.id where tutors.deleted = 0  AND structural_subdivision.id>0";
$resto = $con->query($sql);
//die($sql);
?>
<div class="card">
  <div class="card-body">
    <h5>Создать новое сообщение # <?=$mailid?></h5>

              <div class="form-group">
                <label class="form-label">Получатели</label>
                <select class="multiple-select form-control" multiple="multiple" id="to" data-placeholder="Выберите получателей сообщения" style="width: 100%;">
                  <?php
                  $sql = "select tutorid,concat(lastname,' ',left(firstname,1),'.',left(patronymic,1),'.') from tutors where viceRector=1";
                  $respr = $con->query($sql);
                  while (list($totutorid,$todata) = mysqli_fetch_row($respr)) {
                    ?>
                    <option value="<?=$totutorid?>"><?=$todata?></option>
                    <?php
                  }
                    while (list($totutorid,$todata,$dean,$type,$titleint) = mysqli_fetch_row($resto)) {
                      if ($dean==$totutorid) {
                        if ($type==1) {
                          $tt=$doljn[$titleint];
                        } elseif ($type==2) {
                          $tt='Декан факультета';
                        } else {
                          $tt='Заведующий кафедрой';
                        }
                      } else {
                        $tt='';
                      }
                  ?>
                    <option value="<?=$totutorid?>"><?=$todata?> <?=$tt?></option>
                  <?php
                    }
                  ?>

                </select>
              </div>
              <div class="form-group">
                <label class="form-label">Тема сообщения</label>
                <input class="form-control" id="themas" placeholder="Тема:" value="<?=$mailtheme?>">
              </div>
              <div class="form-group">


                <textarea id="textarea" name="textarea" placeholder="Enter text ..." style="width: 100%; height: 300px"><?=$mailbody?></textarea>
              </div>
              <div class="form-group">

                  <i class="fa fa-paperclip"></i> Прикрепить
                  <input type="file" name="uploadctl" class="form-control" id="uploadctl">
                  <p class="help-block"><small>Max. 32MB</small></p>

                <div id="afiles"></div>
              </div>

            <!-- /.box-body -->
              <div class="float-right">
                <button type="button" class="btn btn-primary sent" id="sent-1"><i class="fa fa-envelope-o"></i> Отправить</button>
              </div>

</div>
</div>
<script type="text/javascript">
$('.multiple-select').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
        });


          ClassicEditor
              .create(document.querySelector('#textarea'))
              .then(editor => {
                  console.log('Редактор инициализирован', editor);
              })
              .catch(error => {
                  console.error('Ошибка инициализации:', error);
              });

  //CKEDITOR.replace( 'textarea' );
  $('#to').val([<?=$tutorsto?>]);
  //$('.select2').select2();

  $('#afiles').load('modules.php?pa=edonew-attach-mail&mailid=<?=$mailid?>');

  $('.sent').click(function(event) {
    if ($('#themas').val().length>6) {
      if ($('#to').val().length>0) {
          //$('#textarea').val(CKEDITOR.instances.textarea.getData());
          event.stopPropagation(); // Остановка происходящего
          event.preventDefault();  // Полная остановка происходящего
          var data = new FormData();
          data.append('mailid','<?=$mailid?>');
          data.append('to',$('#to').val());
          data.append('themas',$('#themas').val());
          data.append('textarea',$('#textarea').val());
          data.append('status',$(this).attr('id').split('-')[1]);
          $.ajax({
              url: 'modules.php?pa=edonew-newmail-mail',
              type: 'POST',
              data: data,
              cache: false,
              dataType: 'text',
              processData: false, // Не обрабатываем файлы (Don't process the files)
              contentType: false, // Так jQuery скажет серверу что это строковой запрос
              success: function( data ){
                if (data=='{data:"OK"}') {
                  $('#mailbox').load('modules.php?pa=edonew-asend-mail');
                }
              },
              error: function( jqXHR, textStatus, errorThrown ){
                  console.log('ОШИБКИ AJAX запроса: ' + textStatus + errorThrown);
              }
          });
      } else {
        alert('Не указаны получатели сообщения!');
      }
    } else {
      alert('Тема сообщения должна содержать не менее 6 символов!');
    }
  });

  var files;

  $('#uploadctl').change(function(event) {
    files = this.files;
    event.stopPropagation(); // Остановка происходящего
    event.preventDefault();  // Полная остановка происходящего
    var data = new FormData();
    data.append('mailid','<?=$mailid?>');
    $.each( files, function( key, value ){
        data.append( 'uploadctl', value );
    });
    $.ajax({
        url: 'modules.php?pa=edonew-newmail-mail',
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'text',
        processData: false, // Не обрабатываем файлы (Don't process the files)
        contentType: false, // Так jQuery скажет серверу что это строковой запрос
        success: function( data ){
          //var obj = jQuery.parseJSON( data );
          //$('#afiles').html(obj.filename);
          $('#afiles').load('modules.php?pa=edonew-attach-mail&mailid=<?=$mailid?>');
        },
        error: function( jqXHR, textStatus, errorThrown ){
            console.log('ОШИБКИ AJAX запроса: ' + textStatus + errorThrown);
        }
    });
  });

</script>

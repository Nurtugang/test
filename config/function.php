<?php
$secret = 'N&S1$25^*I^0VDo3iMTBjI4ReLxuehU8';
$headers = ['alg'=>'HS256','typ'=>'JWT'];

function getjwt($headers,$payload,$secret) {
	$payload_encoded = base64url_encode(json_encode($payload));
	$headers_encoded = base64url_encode(json_encode($headers));
	$signature = hash_hmac('SHA256',"$headers_encoded.$payload_encoded",$secret,true);
	$signature_encoded = base64url_encode($signature);
	return "$headers_encoded.$payload_encoded.$signature_encoded";
}
function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function verefyjwt($con,$person,$personid,$jwt,$headers,$payload,$secret) {
  switch ($person) {
    case 'tutor':
          $sql = "select tutorid,lastname,firstname,patronymic from tutors where tutorid='$personid'";
          //echo $sql;
          $res = $con->query($sql);
          list($d['personid'],$d['lastname'],$d['firstname'],$d['patronymic']) = mysqli_fetch_row($res);
          $payload = ["person=>"=>'tutor',"personid"=>$d['personid'],"lastname"=>$d['lastname'],"firstname"=>$d['firstname'],"patronymic"=>$d['patronymic']];
          //print_r($payload);
          $token = getjwt($headers,$payload,$secret);
          //echo "$token<br />$jwt";
          return $token == $jwt;
    break;

    default:
      // code...
      break;
  }
}

function extfa ($filename) {
	$ext = pathinfo($filename, PATHINFO_EXTENSION);
    switch ($ext) {
        case 'doc':
        echo 'far fa-fw fa-file-word';
        break;
        case 'docx':
        echo 'far fa-fw fa-file-word';
        break;
        case 'xls':
        echo 'far fa-fw fa-file-excel';
        break;
        case 'xlsx':
        echo 'far fa-fw fa-file-excel';
        break;
        case 'pdf':
        echo 'far fa-fw fa-file-pdf';
        break;
        case 'jpg':
        echo 'far fa-fw fa-image';
        break;
        case 'png':
        echo 'far fa-fw fa-image';
        break;

        default:
        # code...
        break;
    }
}
function nomenclature($podrid) {
    global $con,$condocs;
    ?>
        <div class="form-group">
            <label>Выберите папку номенклатуры дел</label>
            <select class="form-control" id="nomenclatureid">
                <?php
                $sql = "select nomenclatureid,concat(code,'-',nameru) from nomenclature where podr = $podrid";
                $sql = "select nomenclatureid,concat(code,'-',nameru) from nomenclature where podr = $podrid or str='all' or str='".$_SESSION['role']."' order by nomenclatureid";
                $res = $condocs->query($sql);
                while (list($nomenclatureid,$name) = mysqli_fetch_row($res)) {
                ?>
                    <option value="<?=$nomenclatureid?>"><?=$name?></option>
                <?php
                }
                ?>
            </select>
        </div>
    <?php
}
?>


<?php
function signpersons($documentid,$Tutor,$condocs,$Student) {
            $sql = "SELECT documents.author,documents.documentid,documents.name,date_format(documents.createdate,'%d.%m.%Y %H:%i'),documents.filename,documents.dir,documents.sigexid,documentsignlists.personid,date_format(documentsignlists.signdate,'%d.%m.%Y %H:%i'),documentsignlists.signid,documents.authorsignid FROM documentsignlists INNER JOIN documents ON documentsignlists.documentid = documents.documentid WHERE documents.documentid=$documentid and documents.author = ".$_SESSION['personid'];
            $sql = "SELECT documents.author,documents.documentid,documents.name,date_format(documents.createdate,'%d.%m.%Y %H:%i:%s'),documents.filename,documents.dir,documents.sigexid,documentsignlists.personid,date_format(documentsignlists.signdate,'%d.%m.%Y %H:%i:%s'),documentsignlists.signid,documents.authorsignid,documentsignlists.status,documentsignlists.rezolution,documents.roleid,documentsignlists.sigexsignid,documentsignlists.signpersonid,documents.doctypeid FROM documentsignlists INNER JOIN documents ON documentsignlists.documentid = documents.documentid WHERE documents.documentid=$documentid and documentsignlists.type=1";
            //echo $sql;
            $res = $condocs->query($sql) or die($condocs->error);
            if ($res->num_rows>0) {
            list($author,$documentid,$name,$createdate,$filename,$dir,$sigexid,$to,$signdate,$signid,$authorsignid,$status,$rezolution,$roleid,$sigexsignid,$signpersonid,$doctypeid) = mysqli_fetch_row($res);
                    $Tutor->tutorid = $signpersonid;
                    $fiosign = $Tutor->concatfio();
                      $url = '<a href="download.php?documentid='.$documentid.'" target="_blank">'.$filename.'</a>';
                      $Tutor->tutorid = $to;
                    if ($sigexsignid=='') {
                      $sign = '<span style="color:red;">Документ не подписан<?=$sigexsignid?></span>';
                      if ($to == $_SESSION['personid']) {
                        $sign .= '<span style="cursor:pointer" onclick="signdoc('.$documentid.')"> Подписать документ</span>';
                      }
                    } else {
                      $sign = '<span style="color:green;">Документ подписан '.$fiosign.', '.$signdate.'<br /><span class="float-right" style="color:blue"><u>'.$rezolution.'</u></span></span>';
                      if ($status==0) {
                        if ($to == $_SESSION['personid']) {
                          $sign = '<span style="cursor:pointer" onclick="signdoc('.$documentid.')"> Подписать документ</span><br />';
                        }
                        $sign .= '<span style="color:red;">Отказано - '.$signdate.'<br /><span class="float-right" style="color:red"><u>'.$rezolution.'</u></span></span>';
                        if ($tit == $_SESSION['personid']) {
                          $sign .= ' <a style="color:green;cursor:pointer;" onclick="signdoc('.$documentid.')"><i class="fas fa-pen"></i><b> Подписать документ</b></a>';
                        }
                      }
                    }
                    $sql = "select messages,status from documentsignmessages where signid=$signid";
                    $res = $condocs->query($sql);
            ?>
            <p>
                <strong>Кому:</strong> <?=$Tutor->concatfio()?> <?=$sign?><br />
                <?php
                  $sql = "select auto from doctypes where doctypeid=$doctypeid";
                  $rr = $condocs->query($sql);
                  list($auto) = mysqli_fetch_row($rr);
                while (list($messages,$status) = mysqli_fetch_row($res)) {
                  echo "$messages<br />";
                }
                $sql = "select concat(lastname,' ',left(firstname,1),'.',left(patronymic,1)) from users where personid=$author and roleid=$roleid";
                $res = $condocs->query($sql) or die($sql);
                list($fio) = mysqli_fetch_row($res);
                if ($authorsignid>'' && $auto==0) {
                  $sign = '<span style="color:green;">Документ подписан '.$createdate.'</span>';
                } else {
                  if ($rr>0) {
                    $sign = '<span style="color:green;"> Заявка подана '.$createdate.'</span>';
                  } else {
                    $sign = '<span style="color:red;">Документ не подписан</span>';
                  }
                }
                ?>
                <strong>От:</strong> <?=$fio?> <?=$sign?><br />
                <strong>Согласование:</strong><br />
                <?php
                //echo  $sigexsignid;
                $sql = "select personid,date_format(signdate,'%d.%m.%Y %H:%i:%s'),signid,status,rezolution,sigexsignid from documentsignlists where documentid=$documentid and type in (2,3) order by type";
                $res = $condocs->query($sql);
                while (list($tit,$signdate,$signid,$status,$rezolution,$sigexsignid) = mysqli_fetch_row($res)) {
                  if ($rezolution!='') $rezolution =  '<br />'.$signdate.' '.$rezolution;
                  //echo $rezolution;
                    if ($sigexsignid=='') {
                      $sign = '<span style="color:red;">Документ не подписан</span>'.$rezolution;
                      if ($tit == $_SESSION['personid']) {
                        $sign .= ' <a style="color:green;cursor:pointer;" onclick="signdoc('.$documentid.')"><i class="fas fa-pen"></i><b> Подписать документ</b></a>'.$rezolution;
                      }
                    } else {
                      $sign = '<span style="color:green;">Документ подписан '.$signdate.$rezolution.'</span>';
                      if ($status==0) {
                        if ($tit == $_SESSION['personid']) {
                          $sign = ' <a style="color:green;cursor:pointer;" onclick="signdoc('.$documentid.')"><i class="fas fa-pen"></i><b> Подписать документ</b></a><br />';
                        }
                        $sign .= '<span style="color:red;">Отказано - '.$signdate.$rezolution.'</span>';
                      }
                    }
                    $Tutor->tutorid = $tit;
                    $sql = "select messages,status from documentsignmessages where signid=$signid";
                    $res2 = $condocs->query($sql);
                    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$Tutor->concatfio().' '.$sign.'<br />';
                    while (list($messages,$status) = mysqli_fetch_row($res2)) {
                      echo "$messages<br />";
                    }
                }
                $sql = "select personid,date_format(signdate,'%d.%m.%Y %H:%i:%s'),signid from documentsignlists where documentid=$documentid and type = 4";
                $res = $condocs->query($sql);
                ?>
                <strong>Передано для исполнения:</strong><br />
                <?php
                while (list($tit,$signdate,$signid) = mysqli_fetch_row($res)) {
                  $Tutor->tutorid = $tit;
                  echo "<span style=\"color:green;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$Tutor->concatfio().'  '.$signdate.'</span><br />';
                }
              }
                ?>
            </p>
<script>
  function signdoc(documentid) {
		sendMessageToFlutter('https://sdk.semgu.kz/modules.php?pa=docs-signframe-inbox&documentid=<?=$documentid?>');
    //$('#pdfframe').load('modules.php?pa=docs-signframe-inbox&documentid=<?=$documentid?>');
    //$('#Modal').modal('show');
  }
</script>
<?php
}
function extdocs($documentid,$Tutor,$condocs) {
            $sql = "select author from documents where documentid=$documentid";
            $res = $condocs->query($sql);
            list($a) = mysqli_fetch_row($res);
            if ($a == $_SESSION['personid']) {
?>
             <div class="row">
              <div class="col-lg-6">
                <?=inputtextdom('docname','docname','Наименование документа','');?>
                <div class="form-group" id="uploadctldiv" style="display: none;">
                    <div class="btn btn-default btn-file btn-xs">
                        <label for="formFile" class="form-label text-white">Выберите сопроводительный документ</label>
                        <input class="form-control" type="file" id="uploadctl" name="uploadctl" accept=".pdf">

                    </div>
                </div>
              </div>
              <div class="col-lg-6">
                <?=inputtextdom('document','document','Код ранее подписанного документа','');?>
                <button type="button" id="attdoc" class="btn btn-outline-secondary btn-xs float-right" style="display: none;"><i class="fas fa-paperclip"></i> Прикрепить сопроводительный документ</button>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                <?=inputtextdom('sigex','sigex','Ссылка на подписанный в системе Sigex документ','');?>
                <div id="sigexrez"></div>
                <div class="btn btn-default btn-file btn-xs" id="sigexdiv" style="display: none;">
                  <i class="fas fa-paperclip"></i> Выберите файл подписанный в системе Sigex
                  <input type="file" id="uploadctls" name="uploadctls">
                </div>
              </div>
            </div>
            <?php
            }
            ?>
            <div id="attachfile"></div>
<script type="text/javascript">
  $('#attachfile').load('modules.php?pa=docs-newfile-new&documentid=<?=$documentid?>');
  $('#docname').change(function(event) {
    if ($('#docname').val()!='') {
      $('#uploadctldiv').show();
    } else {
      $('#uploadctldiv').hide();
    }
  });
  $('#document').change(function(event) {
    if ($('#document').val()!='' && $('#document').val()>0) {
      $('#attdoc').show();
    } else {
      $('#attdoc').hide();
    }
  });
  $('#sigex').change(function(event) {
    if ($('#sigex').val()!='') {
      $.post( "modules.php?pa=docs-pdf-sent", { url: $('#sigex').val(), action: "proverka" })
          .done(function( data ) {
            if (data=='ok') {
              $('#sigexrez').text('');
              $('#sigexdiv').show();
            } else {
              $('#sigexrez').html(data);
              $('#sigexdiv').hide();
            }
          });

    } else {
      $('#sigexdiv').hide();
    }
  });
  $('#uploadctls').change(function(event) {
    if ($('#sigex').val()!='') {
        var files;
        var data = new FormData();
        event.stopPropagation(); // Остановка происходящего
        event.preventDefault();  // Полная остановка происходящего
        files = this.files;
        $.each( files, function( key, value ){
            data.append( 'uploadctls', value );
        });
        //data.append('docname',$('#docname').val());
        data.append('documentid',"<?=$documentid?>");
        data.append('dir',"<?=$dir?>");
        data.append('action','save');
        data.append('url',$('#sigex').val());
        $.ajax({
            url: 'modules.php?pa=docs-pdf-sent',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'text',
            processData: false, // Не обрабатываем файлы (Don't process the files)
            contentType: false, // Так jQuery скажет серверу что это строковой запрос
            success: function( data ){
              $('#attachfile').load('modules.php?pa=docs-newfile-new&documentid=<?=$documentid?>');
              //$('#attachfile').html(data);
            },
            error: function( jqXHR, textStatus, errorThrown ){
                console.log('ОШИБКИ AJAX запроса: ' + textStatus + errorThrown);
            }
        });
    }
  });
  $('#attdoc').click(function(event) {
    $('#attachfile').load('modules.php?pa=docs-newfile-new&documentid=<?=$documentid?>&attdocid='+$('#document').val());
  });

    $('#uploadctl').change(function(event) {
        var files;
        var data = new FormData();
        event.stopPropagation(); // Остановка происходящего
        event.preventDefault();  // Полная остановка происходящего
        files = this.files;
        $.each( files, function( key, value ){
            data.append( 'uploadctl', value );
        });
        data.append('docname',$('#docname').val());
        data.append('documentid',"<?=$documentid?>");
        data.append('dir',"<?=$dir?>");
        $.ajax({
            url: 'modules.php?pa=docs-newfile-new',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'text',
            processData: false, // Не обрабатываем файлы (Don't process the files)
            contentType: false, // Так jQuery скажет серверу что это строковой запрос
            success: function( data ){
              $('#attachfile').html(data);
            },
            error: function( jqXHR, textStatus, errorThrown ){
                console.log('ОШИБКИ AJAX запроса: ' + textStatus + errorThrown);
            }
        });

    });

</script>
<?php
}
function file_force_download($file,$name='') {
    if (file_exists($file)) {
      // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
      // если этого не сделать файл будет читаться в память полностью!
      if (ob_get_level()) {
        ob_end_clean();
      }
      if ($name=='') {
        $f = basename($file);
      } else {
        $f = $name.'.'.pathinfo(basename($file),PATHINFO_EXTENSION);
      }

      // заставляем браузер показать окно сохранения файла
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename=' . $f);
  //    header('Content-Disposition: attachment; filename=' . basename($file));
      header('Content-Transfer-Encoding: binary');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: ' . filesize($file));
      // читаем файл и отправляем его пользователю
      if ($fd = fopen($file, 'rb')) {
        while (!feof($fd)) {
          print fread($fd, 1024);
        }
        fclose($fd);
      }
      exit;
    }
  }
  function page_force_download($file,$name='') {
    if (file_exists($file)) {
      // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
      // если этого не сделать файл будет читаться в память полностью!
      if (ob_get_level()) {
        ob_end_clean();
      }
      if ($name=='') {
        $f = basename($file);
      } else {
        $f = $name.'.'.pathinfo(basename($file),PATHINFO_EXTENSION);
      }

      // заставляем браузер показать окно сохранения файла

  //    header('Content-Disposition: attachment; filename=' . basename($file));
      header('Content-Transfer-Encoding: binary');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: ' . filesize($file));
      header('Content-Type: application/pdf');
      // читаем файл и отправляем его пользователю
      if ($fd = fopen($file, 'rb')) {
        while (!feof($fd)) {
          print fread($fd, 1024);
        }
        fclose($fd);
      }
      exit;
    }
  }

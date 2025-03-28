<?php
function getpodrid($personid) {
    global $con;
    $sql = "select id from structural_subdivision where dean = $personid";
    $res = $con->query($sql);// or die($sql);
    if ($res->num_rows==0) {
        return 0;
    } else {
        list($id) = mysqli_fetch_row($res);
        return $id;
    }
}


if (isset($_GET['signlist'])) {
  $signs = explode(',',$signlist);
  for ($i=0;$i<count($signs);$i++) {
    $todata = $signs[$i];
    $pid = getpodrid($todata);
    $sql = "insert into documentsignlists(documentid,personid,type,podrid) values($documentid,$todata,3,$pid)";
    $condocs->query($sql) or die($sql."<br>".$conapps->error);
  }
}
if (isset($_POST['action'])) {
  if ($action=='proverka') {
    $sigex = explode('=', $url);
    $sigex = $sigex[1];
    $data = json_decode(file_get_contents('https://sigex.kz/api/'.$sigex));
    if (isset($data->signatures[0]->signId)) {
      echo 'ok';
    } else {
      echo 'По заданной ссылке документ не найден';
    }

  } elseif ($action=='save') {
    $sigex = explode('=', $url);
    $sigex = $sigex[1];
    $data = json_decode(file_get_contents('https://sigex.kz/api/'.$sigex));
    if (isset($data->signatures[0]->signId)) {
        // Запрос на подтверждение регистрации
        // с отправкой оригинального файла
        $ch = curl_init("https://sigex.kz/api/".$sigex."/verify");
        move_uploaded_file($_FILES['uploadctls']['tmp_name'],'/tmp/'.$_FILES['uploadctls']['name']);
        $handle = fopen('/tmp/'.$_FILES['uploadctls']['name'], "rb");
        $datafile = fread($handle, filesize('/tmp/'.$_FILES['uploadctls']['name']));
        $datareport = base64_encode($datafile);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/octet-stream'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $datafile);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $r = json_decode($response);

        echo $r->documentId.'<br>';
        if (isset($r->documentId)) {
          $sign = json_decode(file_get_contents('https://sigex.kz/api/'.$sigex.'/signature/'.$data->signatures[0]->signId.'?signFormat=0'));
      echo '<pre>';
      echo 'https://sigex.kz/api/signature/'.$data->signatures[0]->signId.'?signFormat=0<br>';
      echo $sign->signature.'<br>';
      print_r($sign);
      echo '</pre>';          
          $title = $data->title;
          $iin = substr($data->signatures[0]->userId,3,12);
          $subject = explode(',', $data->signatures[0]->subject);
          $role = 5;$ou = '';
          for ($i=0;$i<count($subject);$i++) {
            $k = explode('=', $subject[$i]);
            if ($k[0]=='CN') {
              $fi = $k[1];
            } elseif ($k[0]=='SURNAME') {
              $f = $k[1];
            } elseif ($k[0]=='GIVENNAME') {
              $p = $k[1];
            } elseif ($k[0]=='OU') {
              $ou = $k[1];
              $role = 6;
            }
          }
          $doctypeid = -100;
          $n = str_replace($f.' ', '', $fi);
          echo "$f $n $p<br>";
          $sql = "select personid,roleid from users where iin='$iin' order by roleid";
          $resu = $condocs->query($sql) or die($sql);
          if ($resu->num_rows==0) {
            $sql = "insert into extusers(lastname,firstname,patronymic,iin) values('$f','$n','$p','$iin')";
            $condocs->query($sql) or die($sql);
            $sql = "select last_insert_id()";
            $res = $condocs->query($sql) or die($sql);
            list($personid) = mysqli_fetch_row($res);
            $sql = "insert into users(personid,roleid,lastname,firstname,patronymic,iin,bin) values($pesonid,$roleid,'$f','$n','$p','$iin','$ou')";
            $condocs->query($sql);
          } else {
            list($personid,$roleid) = mysqli_fetch_row($resu);
          }
          echo "$personid $roleid<br>";
          $sql = "insert into documents(author,name,filename,dir,doctypeid,podrid,roleid,sigexid) values($personid,'$title','".$_FILES['uploadctls']['name']."','$dir',-100,0,$roleid,'$sigex')";
          $condocs->query($sql) or die($sql);
          $sql = "select last_insert_id()";
          $res = $condocs->query($sql) or die($sql);
          list($documentid) = mysqli_fetch_row($res);
          echo "Docs - $documentid<br>";
          if (!file_exists($datadir.date('Y'))) {
            mkdir($datadir.date('Y'));
          }
          if (!file_exists($datadir.date('Y').'/'.$doctypeid)) {
            mkdir($datadir.date('Y').'/'.$doctypeid);
          }
          if (!file_exists($datadir.date('Y').'/'.$doctypeid.'/'.$personid)) {
            mkdir($datadir.date('Y').'/'.$doctypeid.'/'.$personid);
          }
          if (!file_exists($datadir.date('Y').'/'.$doctypeid.'/'.$personid.'/'.$documentid.'/')) {
            mkdir($datadir.date('Y').'/'.$doctypeid.'/'.$personid.'/'.$documentid.'/');
          }
          $dir = date('Y').'/'.$doctypeid.'/'.$personid.'/'.$documentid.'/';
          echo "$datadir$dir<br>";
          echo $datadir.$dir.$_FILES['uploadctls']['name'].'<br>';
          rename('/tmp/'.$_FILES['uploadctls']['name'], $datadir.$dir.$_FILES['uploadctls']['name']);
          if (file_exists($datadir.$dir.$_FILES['uploadctls']['name'])) {
            $sql = "update documents set dir='$dir' where documentid=$documentid";
            $condocs->query($sql) or die($sql);
            file_put_contents($datadir.$dir.'/Sigex-Base64-'.$personid.'.cms', $sign->signature);
            $sql = "insert into documentfiles(documentid,docname,atdocumentid,author,roleid) values(".$_POST['documentid'].",'',$documentid,$personid,$roleid)";
            $condocs->query($sql) or die($sql);
          } else {
            echo 'No file - '.$_FILES['uploadctls']['tmp_name'].'<br>';
          }
        }
    } else {
      echo 'По заданной ссылке документ не найден';
    }
  }
  exit;
}
//include('function.php');
$Tutor = new Tutor();
$Tutor->con = $con;
$Student = new Student();
$Student->con = $con;
//echo '<pre>';
//print_r($_SERVER);
//echo '</pre>';

//$documentidcrypt = mc_encrypt($documentid, $key);
$sql = "select doctypeid from documents where documentid=$documentid";
$res = $condocs->query($sql);
list($doctypeid) = mysqli_fetch_row($res);
?>
<div class="row">
  <div class="col-lg-7" id="pdfframe">
    <?php include('docview.php');?>   
  </div>
  <div class="col-lg-5">
    <div class="card card-row card-default">
      <div class="card-header bg-info">
        <h3 class="card-title">
          Состояние
        </h3>
      </div>
      <div class="card-body">
        <div class="card card-light card-outline">
          <div class="card-header">
            <h5 class="card-title">Подписание документ № <?=$documentid?></h5>
            <div class="card-tools">
                      <a href="#" class="btn btn-tool" id="ezsignerexport">
                        <i class="fas fa-pen"></i> Формат ezSigner 
                      </a>
                      <a href="https://doc.semgu.kz/mod/pdf/pdf.php?documentid=<?=$documentid?>&s=<?=session_id()?>" target="_blank" class="btn btn-tool" id="cardexport">
                        <i class="fas fa-copy"></i> Карта документа 
                      </a>
            </div>
          </div>
          <div class="card-body">
            <?php
            if ($doctypeid!=19) {
              echo signpersons($documentid,$Tutor,$condocs,$Student);
            }
            ?>
          </div>
        </div>


        <div class="card card-light card-outline">
          <div class="card-header">
            <h5 class="card-title">Сопроводительные документы</h5>
            <div class="card-tools">
            </div>
          </div>
          <div class="card-body">
            <?php
            if ($doctypeid!=19) {
              echo extdocs($documentid,$Tutor,$condocs);
            }
            ?>
          </div>
        </div>
        <?php          
        if ($sign=='0' || $_SESSION['HR']==1) {  
        ?>
        <div class="card card-light card-outline">
          <div class="card-header">
            <h5 class="card-title">Дополнить лист согласования <?=$sign?></h5>
            <div class="card-tools">
            </div>
          </div>


        
          <div class="card-body">
                    <div class="form-group">
                        <label>Добавить в лист согласования следующих сотрудников:</label>
                        <select class="select2" multiple="multiple" id="todatadop" data-placeholder="Выберите сотрудников" style="width: 100%;">
                            <?php
                            $lang = $_SESSION['lang'];
                            //$sql = "select TutorID,lastname,firstname,patronymic from tutors where vicerector=1";
                            $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic, structural_subdivision.nameru FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE structural_subdivision.subdivision_type = 0 ";
                            $res = $con->query($sql);
                            while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
                                $fio = "$podr $s $n $p";
                            ?>
                            <option value="<?=$tid?>"><?=$fio?></option>
                            <?php    
                            }
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.name$lang,tutors.viceRector,structural_subdivision.subdivision_type,structural_subdivision.pre FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE (tutors.viceRector <> 1 OR tutors.viceRector IS NULL) AND structural_subdivision.subdivision_type = 2";
                            $res = $con->query($sql) or die($con->error);
                            while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
                                $fio = "$podr $s $n $p";
                            ?>
                            <option value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.name$lang,tutors.viceRector,structural_subdivision.subdivision_type,structural_subdivision.pre FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE (tutors.viceRector <> 1 OR tutors.viceRector IS NULL) AND structural_subdivision.subdivision_type = 3";
                            $res = $con->query($sql) or die($sql);
                            while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
                                $fio = "$podr $s $n $p";
                            ?>
                            <option value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.name$lang,tutors.viceRector,structural_subdivision.subdivision_type,structural_subdivision.pre FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE (tutors.viceRector <> 1 OR tutors.viceRector IS NULL) AND structural_subdivision.subdivision_type = 1";
                            $res = $con->query($sql) or die($sql);
                            while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
                                $fio = "$podr $s $n $p";
                            ?>
                            <option value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM tutors  WHERE not tutors.iinplt IS NULL";
                            $res = $con->query($sql) or die($sql);
                            while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
                                $fio = "$podr $s $n $p";
                            ?>
                            <option value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }

                            $sql = "SELECT personid,lastname,firstname,patronymic FROM users  WHERE personid>30000000";
                            $res = $condocs->query($sql) or die($sql);
                            while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
                                $fio = "$podr $s $n $p";
                            ?>
                            <option value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            ?>
                        </select>
                        <button type="button" class="btn btn-outline-info btn-sm btn-block mt-4" id='addsign'>Добавить в список согласующих документ</button>
                        <script type="text/javascript">
                          $('#todatadop').select2();
                          $('#addsign').click(function(event) {
                            var w = $(window).width()-220;
                            $('#pdfdiv').load('modules.php?pa=docs-pdf-sent&sign=<?=$sign?>&documentid=<?=$documentid?>&signlist='+$('#todatadop').val());
                          });
                        </script>
                    </div>
          </div>

        </div>
          <?php
          }
          ?>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"></div>
<div id="scriptdiv" style="display: none;"></div>

<?php
/*
      <script src="https://sigex.kz/js/deps/vue.min.js"></script>
      <script src="https://sigex.kz/js/deps/axios.min.js"></script>

      <script src="https://sigex.kz/js/helpers.js"></script>
      <script src="https://sigex.kz/js/id-utils.js"></script>

  <script src="https://sigex.kz/js/deps/browserified-asn1.js"></script>
  <script src="https://sigex.kz/js/server-messages.js"></script>
*/
?>

  <script type="text/javascript">

  var files;
  var error = null;
    var loading = true;
    var title = null;
    var recommendedTitle = null;
    var description = null;
    var fullUrl = null;
    var signatures = null;
    var signaturesTotal = null;
    var operationInProgress = false;
    var fileDragging = false;
    var fileDraggingError = false;
    var fileMouseOver = false;
    var fileUrl = null;
    var data = null;
    var hiddenDownloadLink = null;
    var signature;

  var urlParams = new URLSearchParams(window.location.search);
  var id = urlParams.get('id');


  function buildCms(data) {
    var cms = null;
    for (i=0;i<$('#signcount').val();i++) {
      console.log('start1');
        var newCmsBuffer = asn1.Buffer.from($('#sign-'+i).val(), 'base64');
      console.log('start2');
        var newCms = asn1.rfc5652.ContentInfo.decode(newCmsBuffer, 'der');
      console.log('start3');
        if (cms === null) {
          cms = newCms;
          cms.content.encapContentInfo.eContent = asn1.Buffer.from(data);
        } else {
          cms.content.digestAlgorithms.push(newCms.content.digestAlgorithms[0]);
          cms.content.certificates.push(newCms.content.certificates[0]);
          cms.content.signerInfos.push(newCms.content.signerInfos[0]);
        }
    }


    var cmsDer;
    cmsDer = asn1.rfc5652.ContentInfo.encode(cms, 'der');
    var cmsB64 = cmsDer.toString('base64');

    initiateFileDownload(cmsB64, $('#filename').val()+`.cms`, true, 'application/pkcs7-signature');
    $.ajax ({
        url: 'modules.php?pa=docs-upload-sent&documentid=<?=$documentid?>',
        type: "POST",
        data: cmsDer,
        processData: false,
        contentType: "application/octet-stream",
        success: function(){
            $('#1').html('<a href="data/data.cms" target="_blank">cms</a>');
        }
    });
  }


$('#ezsignerexport').on('click', function(){
  $.ajax({
    url: 'download.php?documentid=<?=$documentid?>',
//    url: '1001.pdf',
    dataType: 'binary',
    xhrFields: {
      'responseType': 'blob'
    },
    success: function(fdata, status, xhr) {
      new Response(fdata).arrayBuffer().then(result => {data = result});
      $.ajax({
        url: 'modules.php?pa=docs-signatures-sent&documentid=<?=$documentid?>',
        dataType: 'text',
        success: function(fdata, status, xhr) {
          $('#scriptdiv').html(fdata);
          buildCms(data);
        }
      });
    }
  });
});


    function initiateFileDownload(data, fileName, isBase64, mimeType) {
      if (!this.hiddenDownloadLink) {
        this.hiddenDownloadLink = document.createElement('a');
        document.body.appendChild(this.hiddenDownloadLink);
      }

      const base64Token = isBase64 ? ';base64' : '';

      this.hiddenDownloadLink.href = `data:${mimeType}${base64Token},${data}`;
      this.hiddenDownloadLink.target = '_self';
      this.hiddenDownloadLink.download = fileName;
      this.hiddenDownloadLink.click();
    }

function arrayBufferToBase64( buffer ) {
    var binary = '';
    var bytes = new Uint8Array( buffer );
    var len = bytes.byteLength;
    for (var i = 0; i < len; i++) {
        binary += String.fromCharCode( bytes[ i ] );
    }
    return window.btoa( binary );
}
</script>

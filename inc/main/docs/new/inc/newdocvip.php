<?php
if (isset($saveautodoc)) {
    $uuid = uniqid('', true);
    $data = $_POST['data'];
    $sql = "insert into doccoo(doctypeid,personid,uuid,data) values($doctypeid,$personid,'$uuid','$data')";
    //echo 'inc/create'.$doctypeid.'.php';
    $condocs->query($sql);
    $sql = "select last_insert_id()";
    $res = $condocs->query($sql);
    list($doccooid) = mysqli_fetch_row($res);
    if ($condocs->errno) {
        echo 'no';
    } else {
        //echo 'inc/create'.$doctypeid.'.php';
        include('inc/create'.$doctypeid.'.php');
        
    }
    exit;
}
$sql = "select auto from doctypes where doctypeid=$doctypeid";
$res = $condocs->query($sql);
list($auto) = mysqli_fetch_row($res);
if ($auto>0) {
    include('autocreate.php');
    exit;
}

    $txtext = '';
    if (file_exists('../pdf/pdfvip.php')) {
        include('../pdf/pdfvip.php');
    } else {
        die('no file pdf/pdfvip.php');
    }
    //die($base64);
    $filename = 'z'.$doctypeid.'.pdf';
    $txtext1 = 'display: none;';
    $txtext2 = '';

?>
<link href="assets/plugins/select2/css/select2.min.css" rel="stylesheet" />
<link href="assets/plugins/select2/css/select2-bootstrap4.css" rel="stylesheet" />
<script src="assets/plugins/ecp/js/ncalayer.js"></script>
<script src="assets/plugins/ecp/js/process-ncalayer-calls.js"></script>
<script src="assets/plugins/select2/js/select2.min.js"></script>
<?php
$fileupload = true;

    $title = 'Подписание нового документа';
    $doc = 0;

include('inc/head'.$doctypeid.'.php');
?>
<body>
<div class="card card-outline card-default">
    <div class="card-header">
        <h3 class="card-title"><?=$title?></h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-5">
                <div class="callout callout-info">
                    <small>
                    Если вы используете токены для хранения регистрационных свидетельств нажмите на кнопку "Добавить доступные токены", и выберите его в списке "Размещение регистрационного свидетельства".<br />Если регистрационные свидетельства размещены на Вашем компьютере (в том числе на внешних USB носителях) в списке "Размещение регистрационного свидетельства" должно быть выбрано "Ваш компьютер".
                    </small>
                </div>
                <input value="Добавить доступные токены" onclick="getActiveTokensCall();"  type="button" class="mt-1" />
                <div class="form-group">
                    <label>Размещение регистрационного свидетельства</label>
                    <select id="storageSelect" class="single-select">
                        <option value="PKCS12" selected>Ваш компьютер</option>
                    </select> 
                </div>               
                <div class="callout callout-info">
                    <small>
                    Заполните поле "Наименование документа" и нажмите клавишу "Enter", затем загрузите пописываемый докумен.
                    </small>
                </div>
                <?php
                if (!isset($lang))  $lang = $_SESSION['lang'];
                if (isset($doctypeid)) {
                    $sql = "select nameru,comru from doctypes where doctypeid=$doctypeid";
                    $res = $condocs->query($sql);
                    list($docname,$doccom) = mysqli_fetch_row($res);
                }
                if (isset($c_rup)) {
                    $sql = "select specializationID,description from typcurriculums where CurriculumID= $c_rup";
                    $res = $con->query($sql);
                    list($specializationID,$a2) = mysqli_fetch_row($res);
                    $sql = "select specializationCode from specializations where id=$specializationID";
                    $res = $con->query($sql);
                    list($a3) = mysqli_fetch_row($res);
                    $docname .= " $id ($a3 $a2)";
                    switch ($id) {
                        case 'rupkz':

                            break;
                    }
                }
                inputtextdom('docname','docname','Наименование документа',$docname.'. '.$_SESSION['fio']);
                ?>
                <?php
                if ($fileupload) {
                ?>
				<div class="mb-3 mt-3" id="uploadfilediv" style="<?=$txtext2?>">
					<label for="formFile" class="form-label">Загрузите подписываемый документ</label>
					<input class="form-control" type="file" id="uploadctl" name="uploadctl" accept=".pdf">
					<p class="help-block"><small>Для повышения удобства работы Вас и Ваших коллег, рекомендуем загружать файлы в формате Adobe Acrobat *.pdf</small></p>
				</div>
                <?php
                }
                ?>
            </div>
            <div class="col-lg-7">
                <div id="signdiv" style="<?=$txtext?>">
                    <div class="callout callout-info">
                        <small>
                            <?php
                            if ($doccom=='') {
                            ?>
                                Укажите маршрут прохождения документа.<br />Сотрудники указанные в поле "Согласование:" получают документ сразу после подписания и подписывают документ параллельно независимо друг от друга.<br />Сотрудник указанный в поле "Окончательное согласование:" получает документ только после подписания документа всеми сотрудниками указанными в поле "Согласование:".<br />Сотрудник указанный в поле "Кому:" получит документ после подписания документа всеми сотрудниками.<br />Поле "Окончательное согласование:" может иметь значение "нет", поле "Согласование:" может быть пустым.
                            <?php
                            } else {
                                echo $doccom;
                            }
                            ?>
                        </small>
                    </div>
                    <?php
                    $error = array();
                    include('inc/doc'.$doctypeid.'.php');
                    if ($_SESSION['iin']!='640701301160') $txt = 'style="display: none;"';
                    ?>
                    <div class="callout callout-info">
                        <small>
                        Проверьте заполнение всех данных, и усли Вы действительно желаете подписать и отпрвить дукумент на сервер нажмите кнопку "Подписать документ"
                        </small>
                    </div> 

                </div>
            </div>
            <?php
            if (count($error)==0) {
            ?>
            <button type="button" style="<?=$txtext1?>" class="btn btn-outline-info px-5 float-right" id="createbutton">Подписать документ</button>
            <?php
            } else {
                echo '<h3>Ошибка</h3>';
                for ($i=0;$i<count($error);$i++) {
                    echo $error[$i].'<br />';
                }
            }
            ?>
        </div>

        <div <?=$txt?>>
            <?php
            $parameter = '';
            if ($doctypeid>=83 && $doctypeid<=86) {
                $parameter = "$year-$lang-$c_rup";
            }
            ?>
            <input type="text" value="<?=$parameter?>" id="parameter">
            <?=textareadom('inDocB64','inDocB64','In Doc Base 64',$database_64)?>
            <?=textareadom('signature','signature','In Doc Signature','')?>
        </div>
        <div id="rez"></div>
    </div>
</div>
	<script>
		$('.single-select').select2({
			theme: 'bootstrap4',
			width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
			placeholder: $(this).data('placeholder'),
			allowClear: Boolean($(this).data('allow-clear')),
		});
		$('.multiple-select').select2({
			theme: 'bootstrap4',
			width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
			placeholder: $(this).data('placeholder'),
			allowClear: Boolean($(this).data('allow-clear')),
		});
	</script>
<script type="text/javascript">
    <?php
    if (isset($base64)) {
        ?>
        $("#inDocB64").val('<?=$base64?>');
        //$('#createbutton').show();
        $('#uploadfilediv').hide();
        <?php
    }
    ?>
        if ($('#docname').val()!='') {
            $('#uploadctldiv').show();
        }
function createCAdESFromBase64CallLogin() {
    var selectedStorage = $('#storageSelect').val();
    var flag = false;
    var base64ToSign = '';
    if ($("#inDocB64").text() != '') {
        base64ToSign = $("#inDocB64div").text();
    } else {

    }
    base64ToSign = $("#inDocB64").val();
    if (base64ToSign !== null && base64ToSign !== "") {
        createCAdESFromBase64(selectedStorage, "SIGNATURE", base64ToSign, flag, "createCAdESFromBase64BackLogin");
//        createCMSSignatureFromBase64(selectedStorage, "SIGNATURE", base64ToSign, flag, "createCAdESFromBase64BackLogin");
    } else {
        alert("Нет данных для подписи!");
    }
}
function createCAdESFromBase64BackLogin(result) {
  if (result['code'] === "500") {
        alert(result['message']);
      //$.unblockUI();
    } else if (result['code'] === "200") {
        var res = result['responseObject'];
        $('#signature').val(res);
        data.append('parameter',$('#parameter').val());
        data.append('doctypeid',"<?=$doctypeid?>");
        data.append('docname',$('#docname').val());
        <?php
        if ($doctypeid!='19') {
            ?>        
        //data.append('todata',$('#todata').val());
        //data.append('todatals1',$('#todatals1').val());
        //data.append('todatals2',$('#todatals2').val());
              <?php
      }
      
      ?>
        if(typeof($('#todata').val()) != "undefined" && $('#todata').val() !== null) {
            data.append('todata',$('#todata').val());
        }
        if(typeof($('#todatals1').val()) != "undefined" && $('#todatals1').val() !== null) {
            data.append('todatals1',$('#todatals1').val());
        }
        if(typeof($('#todatals2').val()) != "undefined" && $('#todatals2').val() !== null) {
            data.append('todatals2',$('#todatals2').val());
        }  
      <?php
      if (isset($typeid)) {
          ?>
      data.append('typeid',"<?=$typeid?>");
          <?php
      }
      ?>
        <?php
        if (($doctypeid=='1' || $doctypeid==78) && isset($tupsubjectid)) {
            ?>
      data.append('tupsubjectid',"<?=$tupsubjectid?>");
      data.append('langid',"<?=$langid?>");
      data.append('formid',"<?=$formid?>");
            <?php
        }
        ?>
        if(typeof($('#todatals3').val()) != "undefined" && $('#todatals3').val() !== null) {
            data.append('todatals3',$('#todatals3').val());
        }
        data.append('cms',$('#signature').val());
        var url = 'modules.php?pa=docs-register2-new';
        <?php
        if (!$fileupload || $doctypeid==39 || $doctypeid==80) {
            if ($doctypeid==80) $filename='doc80.json';
        ?>
        data.append('file64',$('#inDocB64').val());
        //data.append('file642',$('#inDocB642').val());
        data.append('filename',"<?=$filename?>");
        //data.append('filename',"<?=$filename?>");
        url = 'modules.php?pa=docs-register3-new';
        <?php
        }
        ?>
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'text',
            processData: false, // Не обрабатываем файлы (Don't process the files)
            contentType: false, // Так jQuery скажет серверу что это строковой запрос
            success: function( data ){
                if (data!='') {
                    //$('#Modal').modal('hide');
                    //$('#main').load('modules.php?pa=docs-index-sent');
                    //alert(data);
                    $('#rez').html(data);
                } else {
                    $('#main').html('<h3><b>Документ подписан, перейдите в папку "Исходящие"</b></h3>');
                }
                //$('#rez').html(data);
            },
            error: function( jqXHR, textStatus, errorThrown ){
                console.log('ОШИБКИ AJAX запроса: ' + textStatus + errorThrown);
            }
        });
    }
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

    //$('.select2').select2();

    var files;
    var files2;
    var data = new FormData();
    var data2 = new FormData();

<?php
if (!$fileupload) {
?>
$('#signdiv').show();
$('#createbutton').show();
<?php    
}
?>

    $('#docname').change(function(event) {
        if ($('#docname').val()!='') {
            $('#uploadctldiv').show();
        } else {
            $('#uploadctldiv').hide();
        }
    });
    $('#uploadctl').change(function(event) {
            files = this.files;
            var file = files[0];
            $.each( files, function( key, value ){
                data.append( 'uploadctl', value );
            });
              var reader = new FileReader();
              var binary = '';
              reader.readAsArrayBuffer(file);
              reader.onload = function() {
                var base64 = arrayBufferToBase64(reader.result);
                $('#inDocB64').val(base64);
                $('#signdiv').show();
                $('#createbutton').show();
              };
              reader.onerror = function() {
                console.log(reader.error);
              };
    });


    $('#uploadctl2').change(function(event) {
            files2 = this.files;
            var file = files2[0];
            $.each( files2, function( key, value ){
                data.append( 'uploadctl2', value );
            });
              var reader = new FileReader();
              var binary = '';
              reader.readAsArrayBuffer(file);
              reader.onload = function() {
                var base64 = arrayBufferToBase64(reader.result);
                $('#inDocB642').val(base64);
                $('#signdiv').show();
                $('#createbutton').show();
              };
              reader.onerror = function() {
                console.log(reader.error);
              };
    });


  $('#createbutton').click(function(event) {
    event.stopPropagation(); // Остановка происходящего
    event.preventDefault();  // Полная остановка происходящего
    createCAdESFromBase64CallLogin();
  });
</script>
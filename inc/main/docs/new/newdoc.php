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
  //echo 'inc/auto'.$doctypeid.'.php';
  //print_r($_SESSION);
  $lang = $_SESSION['lang'];
  include($_SERVER['DOCUMENT_ROOT'].'/../inc/main/docs/new/inc/auto'.$doctypeid.'.php');
    //include('autocreate.php');
    exit;
}
?>
<link href="assets/plugins/select2/css/select2.min.css" rel="stylesheet" />
<link href="assets/plugins/select2/css/select2-bootstrap4.css" rel="stylesheet" />

<script src="assets/plugins/select2/js/select2.min.js"></script>
<?php
$fileupload = true;
if (isset($doc)) {
    switch ($doc) {
        case 'sillabus':
            $title = 'Подписание силлабуса';
            $doc = 1;
            break;

        default:
            # code...
            break;
    }
} else {
    $title = 'Подписание нового документа';
    $doc = 0;
}
include('inc/head'.$doctypeid.'.php');
?>
<body>
<div class="card card-outline card-default">
    <div class="card-body">
      <h3 class="card-title"><?=$title?></h3>
        <div class="row">
            <div class="col-lg-5">
                <div class="callout callout-info">
                    <small>
                    Заполните поле "Наименование документа" и нажмите клавишу "Enter", затем загрузите пописываемый докумен.
                    </small>
                </div>
                <?php
                $lang = $_SESSION['lang'];
                if (isset($doctypeid)) {
                    $sql = "select nameru,comru from doctypes where doctypeid=$doctypeid";
                    $res = $condocs->query($sql);
                    list($docname,$doccom) = mysqli_fetch_row($res);
                }
                //inputtextdom('docname','docname','Наименование документа',$docname.'. '.$_SESSION['fio']);
                ?>
                <input type="text" id="docname" value="<?=$docname?> <?=$_SESSION['fio']?>" class="form-control" />
                <?php
                if ($fileupload) {
                ?>
				<div class="mb-3 mt-3">
					<label for="formFile" class="form-label">Загрузите подписываемый документ</label>
					<input class="form-control" type="file" id="uploadctl" name="uploadctl" accept=".pdf">
					<p class="help-block"><small>Для повышения удобства работы Вас и Ваших коллег, рекомендуем загружать файлы в формате Adobe Acrobat *.pdf</small></p>
				</div>
                <?php
                }
                ?>
            </div>
            <div class="col-lg-7">
                <div id="signdiv" style="display: none;">

                    <?php
                    include('inc/doc'.$doctypeid.'.php');
                    if ($_SESSION['iin']!='640701301160') $txt = 'style="display: none;"';
                    ?>


                </div>
            </div>
            <div class="col-lg-7"></div>
            <br /><br />
            <button type="button" style="display: none;" class="btn btn-outline-info px-5 float-right" id="createbutton">Подписать документ</button>
        </div>


        <div <?=$txt?>>
          <textarea id="inDocB64" name="inDocB64" class="form-control"><?=$database_64?></textarea>
          <textarea id="signature" name="signature" class="form-control"></textarea>
            <?php/*textareadom('inDocB64','inDocB64','In Doc Base 64',$database_64)
            textareadom('signature','signature','In Doc Signature','')*/?>
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
        if ($('#docname').val()!='') {
            $('#uploadctldiv').show();
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
    var filename;
    var data = new FormData();
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
      console.log('upload click')
            files = this.files;
            var file = files[0];
            filename = file.name;
            /*
            $.each( files, function( key, value ){
                data.append( 'uploadctl', value );
            });
            */
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

  $('#createbutton').click(function(event) {
    event.stopPropagation(); // Остановка происходящего
    event.preventDefault();  // Полная остановка происходящего
    $('#createbutton').hide();
    Object.keys(data).forEach(key => delete data[key]);
    data.append('filename',filename);
    data.append('base64file',$('#inDocB64').val());
    data.append('personid',"<?=$personid?>");
    data.append('doctypeid',"<?=$doctypeid?>");
    data.append('docname',$('#docname').val());
    data.append('sign1',$('#todata').val());
    data.append('sign2',$('#todatals1').val());
    data.append('sign3',$('#todatals2').val());
    url = 'modules.php?pa=docs-sign-new&div=1';
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'text',
        processData: false, // Не обрабатываем файлы (Don't process the files)
        contentType: false, // Так jQuery скажет серверу что это строковой запрос
        success: function( data ){
          $('body').html(data);
        },
        error: function( jqXHR, textStatus, errorThrown ){
          console.log('ОШИБКИ AJAX запроса: ' + textStatus + errorThrown);
        }
    });
  });
</script>

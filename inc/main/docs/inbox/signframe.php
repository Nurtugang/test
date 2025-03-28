

<!-- ECP -->
<!--
<script src="assets/plugins/ecp/js/ncalayer.js"></script>
<script src="assets/plugins/ecp/js/process-ncalayer-calls.js"></script>
-->
	<body style="width: 98%">
		<?php
		$sql = "select iv from users where personid=".$_SESSION['personid'];
		$res = $condocs->query($sql);
		list($_SESSION['iv']) = mysqli_fetch_row($res);
		//echo $_SESSION['iin'];
    $lang=$_SESSION['lang'];
		$sql = "select author,name,date_format(createdate,'%d.%m.%Y %H:%i:%s'),sigexid,dir,doctypeid,filename from documents where documentid=$documentid";
		$res = $condocs->query($sql);
		list($author,$name,$createdate,$sigexid,$dir,$doctypeid,$filename) = mysqli_fetch_row($res);
    $sql = "select auto from doctypes where doctypeid=$doctypeid";
    $res = $condocs->query($sql);
    $dr = $datadir.$dir.$filename;
    list($auto) = mysqli_fetch_row($res);
    //if ($_SESSION['iin']=='640701301160' || $_SESSION['iin']=='770819401899') {
      $file = file_get_contents($datadir.$dir.$filename);
      $filebase64 = base64_encode($file);
    //}

		$Tutor = new Tutor();
		$Tutor->con = $con;
		$Tutor->tutorid = $author;
    $sql = "select signid from documentsignlists where documentid=$documentid and type=1 and personid=".$_SESSION['personid'];
    $res = $condocs->query($sql);
    $typesign = $res->num_rows;
		?>
		<br />
		<div class="card radius-10">
					<div class="card-body">
						<div class="list-group">
							<a href="javascript:;" class="list-group-item list-group-item-action">
								<div class="d-flex w-100 justify-content-between">
									<h6 class="mb-1">Подписание документа</h6>
									<small class="text-muted"><?=$createdate?></small>
								</div>
								<p class="mb-1"><?=$name?></p>	<small class="text-muted">Отправитель: <?=$Tutor->concatfio()?></small>
							</a>
						</div>
					</div>
				</div>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <?php
      //if ($_SESSION['iin']!='640701301160' && $_SESSION['iin']!='770819401899') {
      if ($_SESSION['iin']=='64070130116000000') {
      ?>
		<h6>1. Вам необходимо скачать файл с сервера. Ознакомьтесь с подписываемым документом.</h6>
		<a type="button" href="download.php?documentidext=<?=$documentid?>" target="_blank" class="btn btn-outline-info btn-block btn-flat"><i class="fas fa-paperclip"></i> Скачать документ</a>
		<hr />
		<h6>2. Выберите загруженный документ для подписания.</h6>
		<div class="form-group">
                  <div class="btn btn-outline-info btn-file btn-block">
                    <i class="fas fa-paperclip"></i> Выбрать подписываемый документ
                    <input type="file" name="uploadctl" id="uploadctl" accept=".pdf">
                  </div>
                  <p class="help-block" style="color:red;">загруженный с сервера документ не должен быть изменен</p>
                </div>
    <?php
    } else {
      ?>

      <?php
    }
    ?>
		<hr />

		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="card radius-10" id="creteblock">
					<div class="card-body">
                <h3 style="text-align: center;" class="card-title">Резолюция</h3>


				<div class="row">
					<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
					</div>
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <input type="hidden" id="rezop" value="1">
						<div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" id="customRadio1" onclick="$('#rezop').val(1)" name="rez" value="1" checked="">
                          <label for="customRadio1" class="custom-control-label">Согласиться</label>
                        </div>
					</div>
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
						<div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" id="customRadio2" onclick="$('#rezop').val(0)" name="rez" value="0">
                          <label for="customRadio2" class="custom-control-label">Отказать</label>
                        </div>
					</div>
					<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
					</div>
				</div>
				<textarea class="form-control" id="rezolution"></textarea>
        <?php/*textareadom('rezolution','rezolution','Текст резолюции','')*/?>
        <div>
                        <label>Направить для исполнения:</label>

                        <select class="multiple-select" multiple="multiple" id="todatals2" data-placeholder="Направить для исполнения" style="width: 100%;">
                            <?php
                            if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/../inc/main/docs/inbox/inc/sql'.$doctypeid.'.php')) {
                              $sql = "select TutorID,lastname,firstname,patronymic from tutors where vicerector=1";
                              $res = $con->query($sql) or die($sql);
                              while (list($tid,$s,$n,$p) = mysqli_fetch_row($res)) {
                                  $fio = "Проректор $s $n $p";
                              ?>
                              <option value="<?=$tid?>"><?=$fio?></option>
                              <?php
                              }
                              $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.name$lang,tutors.viceRector,structural_subdivision.subdivision_type,structural_subdivision.pre FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE (tutors.viceRector <> 1 OR tutors.viceRector IS NULL) AND structural_subdivision.subdivision_type = 2";
                              $res = $con->query($sql) or die($sql);
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
                            } else {
                              include('inc/sql'.$doctypeid.'.php');

                            }

                            if ($_SESSION['iin']!='64070130116000') $txt = 'style="display: none;"';
                            ?>
                        </select>
        </div><br />
				<button type="button" class="btn btn-outline-info btn-block btn-flat btn-sm" id="createbutton"><i class="fas fa-pen"></i> Подписать</button>
              </div>
            </div>
		</div>
	</div>
<?php

?>
	<div <?=$txt?>>
		<textarea id="inDocB64"></textarea>
		<textarea id="signature"></textarea>
	</div>
</body>
</html>
<div class="row">
</div>
<div id="sssssss"></div>
<script type="text/javascript">

function autodocsign() {
    $.post("mod/api/signdoc.php", { dir: "<?=$dr?>", documentid: "<?=$documentid?>", iin: "<?=$_SESSION['iin']?>", personid: "<?=$_SESSION['personid']?>", name: "<?=$name?>" })
      .done(function(data) {
          $("#FullScreenModal").modal("hide");
      });
}

/*
    $('.multiple-select').select2({
      theme: 'bootstrap4',
      width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
      placeholder: $(this).data('placeholder'),
      allowClear: Boolean($(this).data('allow-clear')),
    });
*/
	var rez = 1;

function remotesign() {
      var data = new FormData();
        //var res = result['responseObject'];
        //$('#signature').val(res);
        data.append('cms','');
        data.append('remote','<?=$filebase64?>');
        data.append('documentid',"<?=$documentid?>");
        data.append('sigexid',"<?=$sigexid?>");
        data.append('status',rez);
        data.append('rezolution',$('#rezolution').val());
        data.append('todatals',$('#todatals2').val());
        data.append('dir',"<?=$dir?>");
        $.ajax({
            url: 'modules.php?pa=docs-register-inbox',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'text',
            processData: false, // Не обрабатываем файлы (Don't process the files)
            contentType: false, // Так jQuery скажет серверу что это строковой запрос
            success: function( data ){
                if (data!='') {
                    //$('#body').html(data);
                }
                //$('#main').load('modules.php?pa=docs-nosign-sent');
                $('#pdfdiv').load('modules.php?pa=docs-newfile-new&documentid=<?=$documentid?>');

            },
            error: function( jqXHR, textStatus, errorThrown ){
                console.log('ОШИБКИ AJAX запроса: ' + textStatus + errorThrown);
            }
        });
}


    //$('.select2').select2();



  $('#createbutton').click(function(event) {
    rez = $('#rezop').val();
    //alert(rez);
  	if (rez==0 && $('#rezolution').val()=='') {
      $(document).Toasts('create', {
        class: 'bg-maroon',
        title: 'Подпись документа',
        subtitle: 'Ошибка',
        body: 'При отказе, необходимо указать причину отказа.'
      })
  	} else {
      if (rez==0) {


        var data = new FormData();
          data.append('documentid',"<?=$documentid?>");
          data.append('status',rez);
          data.append('rezolution',$('#rezolution').val());
          data.append('todatals',$('#todatals2').val());
          data.append('dir',"<?=$dir?>");
          $.ajax({
              url: 'modules.php?pa=docs-register-inbox',
              type: 'POST',
              data: data,
              cache: false,
              dataType: 'text',
              processData: false, // Не обрабатываем файлы (Don't process the files)
              contentType: false, // Так jQuery скажет серверу что это строковой запрос
              success: function( data ){
                  $("#FullScreenModal").modal("hide");
              },
              error: function( jqXHR, textStatus, errorThrown ){
                  console.log('ОШИБКИ AJAX запроса: ' + textStatus + errorThrown);
              }
          });



      } else {
        <?php
        if ($auto>0) {
          ?>
          autodocsign();
          <?php
        } else if ($_SESSION['iv']!='') {
          ?>
          remotesign();
          <?php
        } else {
        ?>

        <?php
        }
        ?>
      }
  	}
  });
</script>

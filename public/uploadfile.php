<?php
include('../config/config.php');
?>
<!doctype html>
<html lang="en" data-bs-theme="light">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="assets/images/favicon-32x32.png" type="image/png">
	<!--plugins-->
	<link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet">
	<link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
	<link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet">
	<!-- loader-->
	<link href="assets/css/pace.min.css" rel="stylesheet">
	<script src="assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/plugins/select2/css/select2.min.css" rel="stylesheet" />
  <link href="assets/plugins/select2/css/select2-bootstrap4.css" rel="stylesheet" />
  <!--
	<link href="assets/css/bootstrap-extended.css" rel="stylesheet">
-->
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="assets/css/app.css" rel="stylesheet">
	<link rel="stylesheet" href="assets/css/dark-theme.css">
	<link href="assets/css/icons.css" rel="stylesheet">
  <!--plugins-->
  <link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <!--plugins-->
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
  <script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
  <script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
  <script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>

  <!--Password show & hide js -->
<!--
  <script src="assets/js/app.js"></script>
-->
	<title>Shakarim University</title>
	<style>
	        /* Родительский контейнер */
	        .parent {
	            /*width: 80px;*/ /* Ширина родителя */
	            height: 80px; /* Высота родителя */
	            border: 0.3px solid white;
	            display: flex;
	            justify-content: center; /* Центрируем содержимое по горизонтали */
	            align-items: center; /* Центрируем содержимое по вертикали */
							font-size: 10px;
							text-align: center;
							color: #002C52;
	        }

	        /* Блок с иконкой и текстом */
	        .icon-box {
	            width: 60px;
	            height: 60px;
	            border: 0.3px solid white;
	            border-radius: 12px;
	            box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);
	            background-color: white;
	            display: flex;
	            flex-direction: column; /* Размещаем элементы вертикально */
	            align-items: center; /* Центрируем по горизонтали */
	            justify-content: center; /* Центрируем по вертикали */
	            text-align: center;

	            /*font-weight: bold;*/
	            padding: 10px;
	        }

	        /* Стили для иконки */
	        .icon-box i {
	            font-size: 30px;
	            margin-bottom: 2px;
	            color: #002C52;
	        }
					.cardmtb {
						margin-top: 40px;
						padding: 0px;
						margin-bottom: 0px;
						color:#002C52;
					}
	    </style>
</head>

<body class="">
	<!--wrapper-->
	<div class="wrapper">

		<div class="">
			<div class="container" id="main">
        <!--
        ////////////////////////////////////////////////////
      -->
                  <div class="card">
      							<div class="card-body">

        								<div class="mb-3">
        									<label for="formFile" class="form-label">Default file input example</label>
        									<input class="form-control" type="file" id="formFile">
                          <p class="help-block"><small>Для повышения удобства работы Вас и Ваших коллег, рекомендуем загружать файлы в формате Adobe Acrobat *.pdf</small></p>
        								</div>
                        <div class="mb-3">
                          <label class="form-label">Наименование документа</label>
                          <input class="form-control" type="text" id="docname" />
                        </div>
                        <div class="mb-3">
                          <input class="form-control" type="text" id="inDocB64" />
                        </div>
                        <div class="mb-3">
                          <a href="/word.docx" >Download</a>
                        </div>
                        <div class="mb-3">
                          <button class="btn btn-outline-primary" type="" id="upload">Подписать</button>
                        </div>

                      <div id="result">###</div>
      							</div>
      						</div>
                  <!--
                  ////////////////////////////////////////////////////
                -->
      </div>
		</div>
		<footer class="bg-light shadow-none border-top p-2 text-center fixed-bottom">
			<p class="mb-0">Copyright © 2024 - <?=date('Y')?>. All right reserved.</p>
		</footer>
	</div>
	<!--end wrapper-->
	<!-- Bootstrap JS -->

</body>

</html>
<script>
/*$("#myForm").submit(function (e) {
        e.preventDefault(); // Предотвращаем стандартную отправку формы

        let formData = new FormData(this); // Создаём объект FormData

        $.ajax({
            url: "up.php", // Файл обработки на сервере
            type: "POST",
            data: formData,
            processData: false, // Не обрабатывать данные
            contentType: false, // Не устанавливать заголовок contentType
            success: function (response) {
                $("#result").html("<p>" + response + "</p>");
            },
            error: function () {
                $("#result").html("<p>Ошибка при отправке</p>");
            }
        });
    });
*/
function arrayBufferToBase64( buffer ) {
    var binary = '';
    var bytes = new Uint8Array( buffer );
    var len = bytes.byteLength;
    for (var i = 0; i < len; i++) {
        binary += String.fromCharCode( bytes[ i ] );
    }
    return window.btoa( binary );
}
var files;
var data = new FormData();
$('#formFile').change(function(event) {
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
$('#upload').click(function(){
  data.append('docname',$('#docname').val());
  data.append('doctypeid',"<?=$doctypeid?>");
  data.append('file64',$('#inDocB64').val());
  data.append('filename',"<?=$filename?>");
  url = 'up.php';
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
              $('#result').html(data);
          } else {
              $('#result').html(data);
          }
          //$('#rez').html(data);
      },
      error: function( jqXHR, textStatus, errorThrown ){
          console.log('ОШИБКИ AJAX запроса: ' + textStatus + errorThrown);
      }
});
})
/*
function sendMessageToFlutter(message) {
  if (window.FlutterChannel) {
      window.FlutterChannel.postMessage(message);
  }
}*/
</script>

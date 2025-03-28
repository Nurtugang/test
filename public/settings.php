<?php
include('../inc/config/config.php');
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap 5</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
  <div class="card">
    <div class="card-body">
      <h3>Настройки<br /><small>В разработке</small></h3>
      <?php
      if ($_SESSION['personid']==566) {
      ?>
      <input class="form-control form-control-lg" type="text" placeholder=".form-control-lg" aria-label=".form-control-lg example">
      <input class="form-control" type="text" placeholder="Default input" aria-label="default input example">
      <input class="form-control form-control-sm" type="text" placeholder=".form-control-sm" aria-label=".form-control-sm example">

      <select class="form-select" multiple aria-label="Default select example">
        <option selected>Open this select menu</option>
        <option value="1">One</option>
        <option value="2">Two</option>
        <option value="3">Three</option>
      </select>
      <?php
      }
      ?>
      <a href="https://sdk.semgu.kz/word.docx">word</a>
      <button class="btn btn-outline-success" id="download">Download</button>
    </div>
  </div>
<script>
const jsonData = {
  type: "download",
  url: "https://hub.shakarim.kz/pdf/45.docx",
  filename: "myword.docx"
};
document.getElementById("download").addEventListener("click", function() {
  window.flutter_inappwebview.callHandler('flutterHandler', jsonData)
});
/*window.flutter_inappwebview.callHandler("flutterHandler", "Привет, Flutter!")
  .then(response => {
    console.log("Ответ от Flutter:", response);
  });*/
</script>
</body>
</html>

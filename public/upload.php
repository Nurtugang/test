<?php
echo '<pre>';
print_r($_FILES);
$cat = explode('-',$_GET['cat']);
if (count($_FILES)==0) {
  include('../inc/page/header.php');
  switch ($cat[1]) {
    case 'DOCS':
      ?>
      <div class="wrapper">
          <div class="page-wrapper">
            <div class="card">
              <div class="card-body">
                <h5>Подписание документа</h5>
                <p>Выберите файл или сните документ камерой</p>
              </div>
            </div>
          </div>

        </div>
      <?php
      break;

    default:
      // code...
      break;
  }
  include('../inc/page/foother.php');
  ?>

  <?php
} else {
  switch ($cat[1]) {
    case 'DOCS':
      echo 'https://semgu.kz';
      break;

    default:
      // code...
      break;
  }
}
?>

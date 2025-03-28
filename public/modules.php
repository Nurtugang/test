<?php
include('../config/config.php');
if (!isset($_SESSION['jwt'])) {
  if (verefyjwt($con,$person,$personid,$jwt,$headers,$payload,$secret)) {
  	if ($person=='tutor') {
  		$sql = "select lastname,firstname,patronymic from tutors where tutorid=$personid";
  		$res = $con->query($sql);
  		list($lastname,$firstname,$patronymic) = mysqli_fetch_row($res);
  		$sql = "SELECT structural_subdivision.nameru,structural_subdivision.namekz,structural_subdivision.nameen,structural_subdivision.subdivision_type,
              structural_subdivision.dean,tutor_positions.NameRU,tutor_positions.NameKZ,tutor_positions.NameEN FROM nitrosgu.tutor_structuralsubdivision
              INNER JOIN nitro.tutors ON tutor_structuralsubdivision.TutorID = tutors.TutorID INNER JOIN nitro.structural_subdivision ON tutor_structuralsubdivision.subdivisionid = structural_subdivision.id
              INNER JOIN nitro.tutor_positions ON tutor_positions.ID = tutor_structuralsubdivision.`position` WHERE tutors.TutorID = $personid order by tutor_structuralsubdivision.type limit 1";
  		$res = $con->query($sql);
  		list($sdnameru,$sdnamekz,$sdnameen,$type,$sddean,$posnameru,$posnamekz,$posnameen) = mysqli_fetch_row($res);

  	} elseif ($person=='student') {
  		$sql = "select lastname,firstname,patronymic from students where studentid=$personid";
  		$res = $con->query($sql);
  		list($lastname,$firstname,$patronymic) = mysqli_fetch_row($res);
  	} else {
  		exit;
  	}


  } else {
  	exit;
  }
} else {
  $jwt = $_SESSION['jwt'];
  $personid = $_SESSION['personid'];
  $person = $_SESSION['person'];
}
if (!isset($_GET['div'])) {
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
  <script src="assets/plugins/select2/js/select2.min.js"></script>
  <!-- fullCalendar -->
  <link href="assets/plugins/fullcalendar/css/main.min.css" rel="stylesheet" />
  <script src="assets/plugins/fullcalendar/js/main.min.js"></script>
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
        <?php
}//$_GET['div']
				if (isset($pa)) {
					$pa = explode('-',$pa);
					include("../inc/main/$pa[0]/$pa[2]/$pa[1].php");
				}
if (!isset($_GET['div'])) {
				?>

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
function sendMessageToFlutter(message) {
  if (window.FlutterChannel) {
      window.FlutterChannel.postMessage(message);
  }
}
</script>
<?php
}
?>

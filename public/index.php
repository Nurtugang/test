<?php
include('../config/config.php');
//echo '<pre';
//print_r($_SERVER);
if (verefyjwt($con,$person,$personid,$jwt,$headers,$payload,$secret)) {
	if ($person=='tutor') {
		$sql = "select lastname,firstname,patronymic,iinplt from tutors where tutorid=$personid";
		$res = $con->query($sql);
		list($lastname,$firstname,$patronymic,$_SESSION['iin']) = mysqli_fetch_row($res);
		$sql = "SELECT structural_subdivision.nameru,structural_subdivision.namekz,structural_subdivision.nameen,structural_subdivision.subdivision_type,
            structural_subdivision.dean,tutor_positions.NameRU,tutor_positions.NameKZ,tutor_positions.NameEN,tutor_structuralsubdivision.subdivisionid FROM nitrosgu.tutor_structuralsubdivision
            INNER JOIN nitro.tutors ON tutor_structuralsubdivision.TutorID = tutors.TutorID INNER JOIN nitro.structural_subdivision ON tutor_structuralsubdivision.subdivisionid = structural_subdivision.id
            INNER JOIN nitro.tutor_positions ON tutor_positions.ID = tutor_structuralsubdivision.`position` WHERE tutors.TutorID = $personid order by tutor_structuralsubdivision.type limit 1";
		$res = $con->query($sql);
		list($sdnameru,$sdnamekz,$sdnameen,$type,$sddean,$posnameru,$posnamekz,$posnameen,$subdivisionid) = mysqli_fetch_row($res);
		$_SESSION['jwt'] = $jwt;
		$_SESSION['personid'] = $personid;
		$_SESSION['person'] = $person;
		$_SESSION['role'] = 'sotr';
		$_SESSION['idpodr'] = $subdivisionid;
	} elseif ($person=='student') {
		$sql = "select lastname,firstname,patronymic from students where studentid=$personid";
		$res = $con->query($sql);
		list($lastname,$firstname,$patronymic) = mysqli_fetch_row($res);
		$_SESSION['jwt'] = $jwt;
		$_SESSION['personid'] = $personid;
		$_SESSION['person'] = $person;
		$_SESSION['role'] = 'student';
	} else {
		exit;
	}


} else {
	exit;
}
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
	<link href="assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="assets/css/app.css" rel="stylesheet">
	<link rel="stylesheet" href="assets/css/dark-theme.css">
	<link href="assets/css/icons.css" rel="stylesheet">
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
		<header class="login-header shadow">
      <nav class="navbar navbar-expand-lg navbar-light bg-light rounded-0 bg-white fixed-top rounded-0 shadow-none border-bottom">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <img src="img/logo3.png" width="140" alt="" />
    </a>
		<span onclick="sendMessageToFlutter('ReLoad')" class="navbar-toggler1" type="" >
			<span class="btn float-right">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
				  <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z"/>
				</svg>
			</span>
		</span>
    <span onclick="sendMessageToFlutter('QRCode')" class="navbar-toggler1" type="" >
      <span class="btn float-right">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-qr-code-scan" viewBox="0 0 16 16">
          <path d="M0 .5A.5.5 0 0 1 .5 0h3a.5.5 0 0 1 0 1H1v2.5a.5.5 0 0 1-1 0zm12 0a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0V1h-2.5a.5.5 0 0 1-.5-.5M.5 12a.5.5 0 0 1 .5.5V15h2.5a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5v-3a.5.5 0 0 1 .5-.5m15 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1 0-1H15v-2.5a.5.5 0 0 1 .5-.5M4 4h1v1H4z"/>
          <path d="M7 2H2v5h5zM3 3h3v3H3zm2 8H4v1h1z"/>
          <path d="M7 9H2v5h5zm-4 1h3v3H3zm8-6h1v1h-1z"/>
          <path d="M9 2h5v5H9zm1 1v3h3V3zM8 8v2h1v1H8v1h2v-2h1v2h1v-1h2v-1h-3V8zm2 2H9V9h1zm4 2h-1v1h-2v1h3zm-4 2v-1H8v1z"/>
          <path d="M12 9h2V8h-2z"/>
        </svg>
      </span>
    </span>
  </div>
</nav>


    </header>
		<div class="">
			<div class="container">
				<br /><br />
				<div class="card cardmtb">
									<div class="card-body">
										<div class="row">
											<div class="col-3">
												<div class="d-flex flex-column align-items-center text-center">
													<img src="https://ais.semgu.kz/mod/photos.php?personid=<?=$personid?>&person=<?=$person?>" alt="Photos" class="rounded-circle p-1 bg-white" width="80">
												</div>
											</div>
											<div class="col-9">

													<h6 style="color:#002C52;"><?=$lastname?> <?=$firstname?></h6>
													<p style="font-size:12px;"><?=$sdnameru?><br />
													<?=$posnameru?></p>

											</div>
										</div>
									</div>
								</div>

							<div class="card-body">
								<div class="p-1">
                  <div class="row">
                    <div class="col-3 parent">
											<div class="container-center" onclick="sendMessageToFlutter('https://semgu.kz/')">
											    <div class="icon-box">
											       <img width="40" src="https://sdk.semgu.kz/img/logo100.png" />
													 </div>
													Сайт
											 </div>
                    </div>
										<div class="col-3 parent">
											<div class="container-center" onclick="sendMessageToFlutter('inpage2#https://sdk.semgu.kz/settings.php')">
													<div class="icon-box">
<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tool"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
													 </div>
													Настройки
											 </div>
                    </div>
                    <div class="col-4">

                    </div>
                  </div>
                </div>
							</div>

							<div class="card">
												<div class="card-body">
													<h6>Сервисы</h6>
													<div class="row">
														<div class="col-3 parent">
															<div class="container-center" onclick="sendMessageToFlutter('https://sdk.semgu.kz/modules.php?pa=edo-poruch-por')">
																	<div class="icon-box">
																		<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-calendar-date" viewBox="0 0 16 16">
																		  <path d="M6.445 11.688V6.354h-.633A13 13 0 0 0 4.5 7.16v.695c.375-.257.969-.62 1.258-.777h.012v4.61zm1.188-1.305c.047.64.594 1.406 1.703 1.406 1.258 0 2-1.066 2-2.871 0-1.934-.781-2.668-1.953-2.668-.926 0-1.797.672-1.797 1.809 0 1.16.824 1.77 1.676 1.77.746 0 1.23-.376 1.383-.79h.027c-.004 1.316-.461 2.164-1.305 2.164-.664 0-1.008-.45-1.05-.82zm2.953-2.317c0 .696-.559 1.18-1.184 1.18-.601 0-1.144-.383-1.144-1.2 0-.823.582-1.21 1.168-1.21.633 0 1.16.398 1.16 1.23"/>
																		  <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
																		</svg>
																	 </div>
																	Календарь
															 </div>
														</div>
														<div class="col-3 parent">
															<div class="container-center" onclick="sendMessageToFlutter('https://sdk.semgu.kz/modules.php?pa=docs-index-inbox')">
																	<div class="icon-box">
																		<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-file-earmark" viewBox="0 0 16 16">
																		  <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5z"/>
																		</svg>
																	 </div>
																	СДО
															 </div>
														</div>
														<div class="col-3 parent">
															<div class="container-center" onclick="sendMessageToFlutter('inpage2#https://sdk.semgu.kz/modules.php?pa=docs-index-mail')">
																	<div class="icon-box">
																		<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
																		  <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/>
																		</svg>
																	 </div>
																	 Сообщения
															 </div>
														</div>
														<div class="col-3 parent">
															<div class="container-center" onclick="sendMessageToFlutter('https://sdk.semgu.kz/modules.php?pa=tutor-index-perco')">
																	<div class="icon-box">
																		<svg width="40" height="40" viewBox="0 0 61 55" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<g clip-path="url(#clip0_4000_537)">
																			<path d="M22.81 5.84996L12.27 0.979961C11.27 0.519961 10.08 0.949961 9.62001 1.95996L0.690013 21.28C0.230013 22.28 0.660013 23.47 1.67001 23.93L12.21 28.8C13.21 29.26 14.4 28.83 14.86 27.82L23.79 8.50996C24.25 7.50996 23.82 6.31996 22.81 5.85996V5.84996Z" fill="#214288" class="hover-fill"></path>
																			<path d="M13.68 7.48999L16.95 8.99999" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
																			<path d="M39.19 14.23H58.65" stroke="#214288" stroke-width="3" stroke-miterlimit="10" stroke-linecap="round" class="hover-stroke"></path>
																			<path d="M37.78 15.33L40.25 34.64" stroke="#214288" stroke-width="3" stroke-miterlimit="10" stroke-linecap="round" class="hover-stroke"></path>
																			<path d="M45.65 7.32002C46.66 6.20002 46.36 4.06002 44.9 2.36002C43.35 0.570017 41.11 -0.0399827 39.89 1.02002L38.23 2.45002L24.8 13.94C24.69 14.03 24.63 14.17 24.63 14.31V53.28C24.63 53.57 24.87 53.79 25.16 53.77C28.07 53.77 40.89 53.8 43.6 53.86C43.88 53.86 44.1 53.64 44.1 53.37V52.49C44.1 52.24 43.91 52.03 43.67 52L30.53 50.46C30.28 50.43 30.2 50.2 30.21 49.95V20.8C30.21 20.66 30.34 20.52 30.45 20.43L38.08 13.87V13.89L45.49 7.52002C45.49 7.52002 45.52 7.48002 45.54 7.46002L45.88 7.02002L45.66 7.32002H45.65Z" fill="#214288" stroke="#214288" class="hover-fill hover-stroke"></path>
																			</g>
																			<defs>
																			<clipPath id="clip0_4000_537">
																			<rect width="59.65" height="54.36" fill="white" transform="translate(0.5)"></rect>
																			</clipPath>
																			</defs>
																			</svg>
																	 </div>
																	Perco
															 </div>
														</div>
													</div>
												</div>
											</div>
				<!--end row-->
			</div>
		</div>
		<footer class="bg-light shadow-none border-top p-2 text-center fixed-bottom">
			<p class="mb-0">Copyright © 2024 - <?=date('Y')?>. All right reserved.</p>
		</footer>
	</div>
	<!--end wrapper-->
	<!-- Bootstrap JS -->
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<!--Password show & hide js -->

	<script src="assets/js/app.js"></script>
</body>

</html>
<script>
function sendMessageToFlutter(message) {
  //window.flutter_inappwebview.callHandler('flutterHandler', {type:"webpage",url:"https://sdk.semgu.kz/settings.php",page:"current"});
	if (window.FlutterChannel) {
      window.FlutterChannel.postMessage(message);
  }
}
</script>

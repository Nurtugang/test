<?php
$condocs->query('SET SESSION group_concat_max_len = 500000;');
$sql = "select
(SELECT COUNT(documents.documentid) FROM documentsignlists INNER JOIN documents ON documentsignlists.documentid = documents.documentid WHERE documents.createdate>'2022-01-01 00:00:00' AND  documentsignlists.type = 1 AND documentsignlists.personid = ".$_SESSION['personid']." AND documents.status = 1 AND documentsignlists.sigexsignid IS NULL AND (documentsignlists.status is NULL)),
(SELECT COUNT(documents.documentid) FROM documentsignlists INNER JOIN documents ON documentsignlists.documentid = documents.documentid WHERE documents.createdate>'2022-01-01 00:00:00' AND  documentsignlists.type in (2,3) AND documentsignlists.personid = ".$_SESSION['personid']." AND documents.status = 1 AND documentsignlists.status IS NULL AND documentsignlists.sigexsignid IS NULL AND (documentsignlists.status = 1 OR documentsignlists.status is NULL)),
(SELECT COUNT(documents.documentid) FROM documentsignlists INNER JOIN documents ON documentsignlists.documentid = documents.documentid WHERE documents.createdate>'2022-01-01 00:00:00' AND documentsignlists.type < 4 AND documentsignlists.personid = ".$_SESSION['personid']." AND documents.status = 1 AND NOT documentsignlists.sigexsignid IS NULL AND (documentsignlists.status = 1 OR documentsignlists.status is NULL)),
(SELECT COUNT(documents.documentid) FROM documentsignlists INNER JOIN documents ON documentsignlists.documentid = documents.documentid WHERE documents.createdate>'2022-01-01 00:00:00' AND documentsignlists.type < 4 AND documentsignlists.personid = ".$_SESSION['personid']." AND documents.status = 1 AND documentsignlists.status =0 AND documentsignlists.sigexsignid IS NULL)";
$res = $condocs->query($sql);
list($fulldoc,$nodoc,$fullpodp,$otkaz) = mysqli_fetch_row($res);
$sql = "SELECT COUNT(documents.documentid) FROM documentsignlists INNER JOIN documents ON documentsignlists.documentid = documents.documentid WHERE documents.createdate>'2022-01-01 00:00:00' AND documentsignlists.personid = ".$_SESSION['personid']." AND documentsignlists.type = 1 AND documentsignlists.sigexsignid IS NULL AND documents.status = 1 AND (documentsignlists.status = 1 OR documentsignlists.status is NULL)";
$res = $condocs->query($sql);
list($docs) = mysqli_fetch_row($res);
$sql = "SELECT COUNT(DISTINCT documents.documentid) FROM documentsignlists INNER JOIN documents ON documentsignlists.documentid = documents.documentid WHERE documents.createdate>'2022-01-01 00:00:00' AND documents.status = 1 AND documentsignlists.type IN (2,3) AND documentsignlists.personid = ".$_SESSION['personid']." AND documentsignlists.sigexsignid IS NULL  AND (documentsignlists.status is NULL)";
$res = $condocs->query($sql);
list($nosogl) = mysqli_fetch_row($res);
?>
<style>
h6 {
  font-size: 14px;
  color:#002C52;
}
p {
  font-size: 12px;
}

</style>

<div class="col">
						<div class="card">
							<img src="img/edo.jpg" class="card-img-top" alt="...">
							<div class="card-body">
								<h6 class="card-title text-secondary">Система электронного документооборота</h6>
							</div>
						</div>
					</div>
<div class="row">


                        <div class="col-6" style="cursor:pointer;" onclick="window.open('https://sdk.semgu.kz/modules.php?pa=docs-inboxtable-inbox&type=1')">
                            <div class="card radius-10">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary">На подпись</p>
                                            <h6 class="my-1">Всего <?=$fulldoc?></h6>
                                            <p class="mb-0 font-13 text-danger"><i class="bx bxs-up-arrow align-middle"></i><?=$docs?> не подписано</p>
                                        </div>
                                        <!--
                                        <div class="widgets-icons bg-light-success text-success ms-auto" style="font-size:32px">
                                          <i class="fadeIn animated bx bx-pencil"></i>
                                        </div>
                                      -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6" style="cursor:pointer;" onclick="window.open('https://sdk.semgu.kz/modules.php?pa=docs-inboxtable-inbox&type=2')">
                            <div class="card radius-10">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary">На согласовании</p>
                                            <h6 class="my-1">Всего <?=$nodoc?></h6>
                                            <p class="mb-0 font-13 text-warning"><i class="bx bxs-up-arrow align-middle"></i><?=$nosogl?> не согласовано</p>
                                        </div>
                                        <!--<div class="widgets-icons bg-light-warning text-warning ms-auto"><i class="fadeIn animated bx bx-walk"></i></i>
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6" style="cursor:pointer;" onclick="window.open('https://sdk.semgu.kz/modules.php?pa=docs-inboxtable-inbox&type=3')">
                            <div class="card radius-10">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary">Подписано</p>
                                            <h6 class="my-1">Всего <?=$fullpodp?></h6>
                                            <p class="mb-0 font-13 text-success"> С начала года</p>
                                        </div>
                                        <!--<div class="widgets-icons bg-light-success text-success ms-auto"><i class="lni lni-checkmark-circle"></i>
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6" style="cursor:pointer;" onclick="window.open('https://sdk.semgu.kz/modules.php?pa=docs-inboxtable-inbox&type=4')">
                            <div class="card radius-10">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">

                                        <div>
                                            <p class="mb-0 text-secondary">Отказано</p>
                                            <h6 class="my-1">Всего <?=$otkaz?></h6>
                                            <p class="mb-0 font-13 text-danger">С начала года</p>
                                        </div>
                                        <!--<div class="widgets-icons bg-light-danger text-danger ms-auto"><i class="fadeIn animated bx bx-x-circle"></i>
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6" style="cursor:pointer;" onclick="window.open('https://sdk.semgu.kz/modules.php?pa=docs-index-new')">
                            <div class="card radius-10">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">

                                        <div>
                                            <p class="mb-0 text-secondary">Создать документ</p>
                                        </div>
                                        <!--<div class="widgets-icons bg-light-danger text-danger ms-auto"><i class="fadeIn animated bx bx-x-circle"></i>
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                        </div>
</div>

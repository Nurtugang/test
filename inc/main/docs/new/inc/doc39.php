                <div class="mb-3 mt-3" id="uploadfilediv2" style="<?=$txtext2?>">
					<label for="formFile" class="form-label">Загрузите квитанцию об оплате</label>
					<input class="form-control" type="file" id="uploadctl2" name="uploadctl2" accept=".pdf, .png, .jpeg, .jpg">
					<p class="help-block"><small>Для повышения удобства работы Вас и Ваших коллег, рекомендуем загружать файлы в формате Adobe Acrobat *.pdf или в формате изображений .png, .jpeg, .jpg</small></p>
				</div>
                <?=textareadom('inDocB642','inDocB642','In Doc Base 64','')?>

<div class="form-group">
    <?php
    $sql = "select dean,nameru from structural_subdivision where id=100";
    $res = $con->query($sql);
    list($pps,$nameru) = mysqli_fetch_row($res);
    ?>
    <label><?=$nameru?>:</label>
    <select class="single-select" id="todata" data-placeholder="<?=$nameru?>" style="width: 100%;">
        <?php

        $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM  tutors where tutorid=".$pps;
        $res = $con->query($sql) or die($sql);
        list($tid,$s,$n,$p) = mysqli_fetch_row($res);
        $fio = "$s $n $p";
        ?>
        <option selected="" value="<?=$tid?>"><?=$fio?></option>
    </select>
</div>

<div class="form-group">
                        <label>Декан факультета:</label>
                        <select class="single-select" id="todatals1" data-placeholder="Декан факультета" style="width: 100%;">
                            <?php
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM  tutors where tutorid=".$_SESSION['cafedramanager'];
                            $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM faculties INNER JOIN tutors ON faculties.facultyDean = tutors.TutorID INNER JOIN cafedras ON cafedras.FacultyID = faculties.FacultyID WHERE cafedras.cafedraID = ".$_SESSION['cafedraid'];
                            $res = $con->query($sql);
                            list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                            $fio = "$s $n $p";
                            ?>
                            <option selected="" value="<?=$tid?>"><?=$fio?></option>
                       </select>
                    </div>

<div class="form-group">
    <?php
    $sql = "select dean,nameru from structural_subdivision where id=86";
    $res = $con->query($sql);
    list($pps,$nameru) = mysqli_fetch_row($res);
    ?>
    <label><?=$nameru?>:</label>
    <select class="single-select" id="todatals2" data-placeholder="<?=$nameru?>" style="width: 100%;">
        <?php
        $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM  tutors where tutorid=".$pps;
        $res = $con->query($sql);
        list($tid,$s,$n,$p) = mysqli_fetch_row($res);
        $fio = "$s $n $p";
        ?>
        <option selected="" value="<?=$tid?>"><?=$fio?></option>
    </select>
</div>



<div class="form-group">
    <label>Директор ДАВ:</label>
    <select class="single-select"  id="todata" data-placeholder="Директор ДАВ" style="width: 100%;">
        <?php
        $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM university INNER JOIN tutors ON university.rectorID = tutors.TutorID";
        //$sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM faculties INNER JOIN tutors ON faculties.facultyDean = tutors.TutorID INNER JOIN cafedras ON cafedras.FacultyID = faculties.FacultyID WHERE cafedras.cafedraID = ".$_SESSION['cafedraid'];
        $sql = "SELECT
        structural_subdivision.dean,
        tutors.lastname,
        tutors.firstname,
        tutors.patronymic
      FROM tutors
        INNER JOIN structural_subdivision
          ON tutors.TutorID = structural_subdivision.dean
      WHERE structural_subdivision.id = 42";
        $res = $con->query($sql);
        list($tid,$s,$n,$p) = mysqli_fetch_row($res);
        $fio = "Директор ДАВ $s $n $p";
        $result[] = $fio;
        ?>
        <option value="<?=$tid?>"><?=$fio?></option>
    </select>
</div>
<div class="form-group">
    <label>Руководитель АК:</label>
    <select  multiple="multiple" class="multiple-select" id="todatals1" data-placeholder="Согласования" style="width: 100%;">
        <?php
        $sql = "select iin from opsign where c_specz=$specializationID and yearedu=$year and type=1";
        $res = $conapps->query($sql);
        list($iinsign) = mysqli_fetch_row($res);
        if ($iinsign=='') {
            $error[] = 'Нет ИИН руководителя АК';
        } else {
            $sql = "select personid,lastname,firstname,patronymic from users where iin='$iinsign'";
            $res = $condocs->query($sql);
            if ($res->num_rows==0) {
                $error[] = 'Пользователь с ИИН '.$iinsign.' в системе не зарегистрирован';
            }
            list($tid,$s,$n,$p) = mysqli_fetch_row($res);
            $fio = "$s $n $p";
            $result[] = $fio;
        }
        ?>
        <option selected value="<?=$tid?>"><?=$fio?></option>
    </select>
</div>
<div class="form-group">
    <label>Члены комиссии: <?=$_SESSION['cafedraid']?></label>
    <select  multiple="multiple" class="multiple-select"  id="todatals2" data-placeholder="Члены комиссии:" style="width: 100%;">
        <?php
        $sql = "select iin,fio from opsign where c_specz=$specializationID and yearedu=$year and type>1 and type NOT IN (3,4,5,6) and (not fio is null and fio>'') order by c_opsign limit 2";
        //echo $sql;
        $res = $conapps->query($sql);
        while (list($iinsign,$fio) = mysqli_fetch_row($res)) {
            if ($iinsign=='') {
                $error[] = 'Нет ИИН '.$fio;
            } else {
                $sql = "select personid,lastname,firstname,patronymic from users where iin='$iinsign'";
                $res1 = $condocs->query($sql);
                if ($res1->num_rows==0) {
                    $error[] = 'Пользователь с ИИН '.$iinsign.' в системе не зарегистрирован';
                }
                list($tid,$s,$n,$p) = mysqli_fetch_row($res1);
                $fio = "$s $n $p";
                $result[] = $fio;
                ?>
                <option value="<?=$tid?>" selected><?=$fio?></option>
                <?php

            }
        }
        ?>
    </select>
</div>
<?php
if (count(count($result)>0)) {
    echo '<h3>Отправка на подпись</h3>';
    for ($i=0;$i<count($result);$i++) {
        echo $result[$i].'<br />';
    }
}

?>
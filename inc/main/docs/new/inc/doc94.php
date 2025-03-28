<div class="form-group">
    <label>Член Правления - проректор по академическим вопросам:</label>
    <select class="single-select"  id="todata" data-placeholder="Член Правления - проректор по академическим вопросам" style="width: 100%;">
        <?php
        $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.name$lang FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE structural_subdivision.id IN (99)";

        
        //$sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM faculties INNER JOIN tutors ON faculties.facultyDean = tutors.TutorID INNER JOIN cafedras ON cafedras.FacultyID = faculties.FacultyID WHERE cafedras.cafedraID = ".$_SESSION['cafedraid'];
        $res = $con->query($sql);
        list($tid,$s,$n,$p) = mysqli_fetch_row($res);
        $fio = "Член Правления - проректор по академическим вопросам $s $n $p";
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
    <select  multiple="multiple" class="multiple-select"  id="todatals2" data-placeholder="" style="width: 100%;">
        <?php
        $sql = "select iin,fio,type from opsign where c_specz=$specializationID and yearedu=$year and type not IN (1,3,4,6) and (not fio is null and fio>'')";
        //echo $sql;
        $res = $conapps->query($sql);
        while (list($iinsign,$fio,$type) = mysqli_fetch_row($res)) {
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
                if ($type=='2' || $type=='5') {
                    $sel = 'selected';
                } else {
                    $sel = '';
                }
                ?>
                <option value="<?=$tid?>" <?=$sel?>><?=$fio?></option>
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
$error = array();
?>
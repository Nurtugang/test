
<div class="form-group">
    <label>Кому:</label>
    <select class="select2 single-select"  id="todata" data-placeholder="Кому адресован документ" style="width: 100%;">
        <?php

        $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,university.dname$lang FROM university INNER JOIN tutors ON university.rectorID = tutors.TutorID";
        $res = $con->query($sql);
        list($tid,$s,$n,$p,$dname) = mysqli_fetch_row($res);
        $fio = "$dname $s $n $p";
        ?>
        <option value="<?=$tid?>"><?=$fio?></option>
        <?php
        $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic, structural_subdivision.nameru FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE structural_subdivision.subdivision_type = 0 ";
        //$sql = "select TutorID,lastname,firstname,patronymic from tutors where vicerector=1";
        $res = $con->query($sql);
        while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
            $fio = "$podr $s $n $p";
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
        ?>
    </select>
</div>
<div class="form-group">
    <label>Окончательное согласование:</label>
    <select class="single-select" id="todatals1" data-placeholder="Окончательное согласование" style="width: 100%;">
        <option value="0">Нет</option>
        <?php
        //$sql = "select TutorID,lastname,firstname,patronymic from tutors where vicerector=1";
        $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic, structural_subdivision.nameru FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE structural_subdivision.subdivision_type = 0 ";
        $res = $con->query($sql);
        while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
            $fio = "$podr $s $n $p";
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
        ?>
    </select>
</div>
<div class="form-group">
    <label>Согласование:</label>
    <select class="multiple-select" multiple="multiple" id="todatals2" data-placeholder="Согласование" style="width: 100%;">
        <?php
        //$sql = "select TutorID,lastname,firstname,patronymic from tutors where vicerector=1";
        $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic, structural_subdivision.nameru FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE structural_subdivision.subdivision_type = 0 ";
        $res = $con->query($sql);
        while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
            $fio = "$podr $s $n $p";
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
        $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM tutors WHERE tutorid=4215 and deleted=0";
        $res = $con->query($sql) or die($sql);
        while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
            $fio = "$podr $s $n $p";
            ?>
            <option value="<?=$tid?>"><?=$fio?></option>
            <?php
        }

        $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM tutors WHERE tutorid=4225 and deleted=0";
        $res = $con->query($sql) or die($sql);
        while (list($tid,$s,$n,$p) = mysqli_fetch_row($res)) {
            $fio = "$s $n $p";
            ?>
            <option value="<?=$tid?>"><?=$fio?></option>
            <?php
        }

        $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM tutors WHERE tutorid=4151 and deleted=0";
        $res = $con->query($sql) or die($sql);
        while (list($tid,$s,$n,$p) = mysqli_fetch_row($res)) {
            $fio = "$s $n $p";
            ?>
            <option value="<?=$tid?>"><?=$fio?></option>
            <?php
        }


        ?>

        ?>
    </select>
</div>
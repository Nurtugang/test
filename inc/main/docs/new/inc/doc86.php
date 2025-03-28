<?php
$sql = "SELECT DISTINCT
  cafedras.cafedraID,
  cafedras.FacultyID,
  faculties.facultyDean,
  cafedras.cafedraManager
FROM typcurriculums
  INNER JOIN specializations
    ON typcurriculums.specializationID = specializations.id
  INNER JOIN profession_cafedra
    ON profession_cafedra.id = specializations.prof_caf_id
  INNER JOIN cafedras
    ON cafedras.cafedraID = profession_cafedra.cafedraID
  INNER JOIN faculties
    ON faculties.FacultyID = cafedras.FacultyID
WHERE typcurriculums.CurriculumID = $c_rup";
$res = $con->query($sql);
list($cafedraid,$facultyid,$dekan,$zkaf) = mysqli_fetch_row($res);
?>
<div class="form-group">
    <label>Член Правления - проректор по академическим вопросам:</label>
    <select class="single-select"  id="todata" data-placeholder="Член Правления - проректор по академическим вопросам" style="width: 100%;">
        <?php
        $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.name$lang FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE structural_subdivision.id IN (99)";

    
        //$sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM faculties INNER JOIN tutors ON faculties.facultyDean = tutors.TutorID INNER JOIN cafedras ON cafedras.FacultyID = faculties.FacultyID WHERE cafedras.cafedraID = ".$_SESSION['cafedraid'];
        $res = $con->query($sql);
        list($tid,$s,$n,$p) = mysqli_fetch_row($res);
        $fio = "Член Правления - проректор по академическим вопросам $s $n $p";
        ?>
        <option value="<?=$tid?>"><?=$fio?></option>
    </select>
</div>
<div class="form-group">
    <label>Согласования:</label>
    <select  multiple="multiple" class="multiple-select" id="todatals1" data-placeholder="Согласования" style="width: 100%;">
        <?php
        $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM faculties INNER JOIN tutors ON faculties.facultyDean = tutors.TutorID INNER JOIN cafedras ON cafedras.FacultyID = faculties.FacultyID WHERE cafedras.cafedraID = ".$_SESSION['cafedraid'];
        $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM  tutors where tutorid=$dekan";
        $res = $con->query($sql);
        list($tid,$s,$n,$p) = mysqli_fetch_row($res);
        $fio = "$s $n $p";
        ?>
        <option selected value="<?=$tid?>"><?=$fio?></option>

    </select>
</div>
<div class="form-group">
    <label>Комиссия:</label>
    <select  multiple="multiple" class="multiple-select"  id="todatals2" data-placeholder="Соруководитель, консультант, нормоконтролер:" style="width: 100%;">
        <?php
        $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM faculties INNER JOIN cafedras ON cafedras.FacultyID = faculties.FacultyID INNER JOIN tutors ON tutors.TutorID = faculties.sapacom WHERE cafedras.cafedraID = ".$_SESSION['cafedraid'];
        $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM  tutors where tutorid in ($zkaf)";
        $res = $con->query($sql);
        while (list($tid,$s,$n,$p) = mysqli_fetch_row($res)) {
            $fio = "$s $n $p";
            ?>
            <option selected value="<?=$tid?>"><?=$fio?></option>
            <?php
        }
        $sql = "select dean,nameru from structural_subdivision where id=43";
                            $res = $con->query($sql);
                            list($pps,$nameru) = mysqli_fetch_row($res);
                            $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM tutors WHERE tutors.TutorID = ".$pps;
                            $res = $con->query($sql);
                            list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                            $fio = "$s $n $p";
        ?>
        <option selected value="<?=$tid?>"><?=$fio?></option>
    </select>
</div>
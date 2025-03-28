<div class="form-group">
    <label> </label>
    <select class="single-select"  id="todata" data-placeholder=" " style="width: 100%;">
        <?php
        $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM  tutors where tutorid=3665";
        //$sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM faculties INNER JOIN tutors ON faculties.facultyDean = tutors.TutorID INNER JOIN cafedras ON cafedras.FacultyID = faculties.FacultyID WHERE cafedras.cafedraID = ".$_SESSION['cafedraid'];
        $res = $con->query($sql);
        list($tid,$s,$n,$p) = mysqli_fetch_row($res);
        $fio = " $s $n $p";
        $result[] = $fio;
        ?>
        <option value="<?=$tid?>"><?=$fio?></option>
    </select>
</div>
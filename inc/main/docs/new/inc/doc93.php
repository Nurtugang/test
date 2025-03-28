<div class="form-group">
    <label> </label>
    <select class="single-select"  id="todata" data-placeholder=" " style="width: 100%;">
        <?php
        $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM  tutors where tutorid=3665";
        $res = $con->query($sql);
        list($tid,$s,$n,$p) = mysqli_fetch_row($res);
        $fio = "$s $n $p";
        ?>
        <option selected value="<?=$tid?>"><?=$fio?></option>
    </select>
</div>
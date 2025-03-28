                    <div class="form-group">
                        <?php
                        $sql = "select dean,nameru from structural_subdivision where id=99";
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
                        <?php
                        $sql = "select dean,nameru from structural_subdivision where id=42";
                        $res = $con->query($sql);
                        list($pps,$nameru) = mysqli_fetch_row($res);
                        ?>
                        <label><?=$nameru?>:</label>
                        <select class="single-select" id="todatals1" data-placeholder="<?=$nameru?>" style="width: 100%;">
                            <?php
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM  tutors where tutorid=".$pps;
                            $res = $con->query($sql);
                            list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                            $fio = "$s $n $p";
                            ?>
                            <option selected="" value="<?=$tid?>"><?=$fio?></option>
                       </select>
                    </div>
                    <div class="form-group" style="display: none1;">
                        <label>Согласование:</label>
                        <select multiple="" class="multiple-select" id="todatals2" data-placeholder="<?=$nameru?>:" style="width: 100%;">
                            <?php
                            $sql = "select dean,nameru from structural_subdivision where id=46";
                            $res = $con->query($sql);
                            list($pps,$nameru) = mysqli_fetch_row($res);
                            $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM tutors WHERE tutors.TutorID = ".$pps;
                            $res = $con->query($sql);
                            list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                            $fio = "ОР $s $n $p";
                            ?>
                            <option selected value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            $sql = "select dean,nameru from structural_subdivision where id=45";
                            $res = $con->query($sql);
                            list($pps,$nameru) = mysqli_fetch_row($res);
                            $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM tutors WHERE tutors.TutorID = ".$pps;
                            $res = $con->query($sql);
                            list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                            $fio = "ЦОО $s $n $p";
                            ?>
                            <option selected value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM faculties INNER JOIN tutors ON faculties.facultyDean = tutors.TutorID INNER JOIN cafedras ON cafedras.FacultyID = faculties.FacultyID WHERE cafedras.cafedraID = ".$_SESSION['cafedraid'];
                            $res = $con->query($sql);
                            list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                            $fio = "Декан факультета $s $n $p";
                            ?>
                            <option selected="" value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM cafedras INNER JOIN tutors ON cafedras.cafedraManager = tutors.TutorID WHERE cafedras.cafedraID = ".$_SESSION['cafedraid'];
                            $res = $con->query($sql);
                            list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                            $fio = "Заведующий кафедрой $s $n $p";
                            ?>
                            <option selected value="<?=$tid?>"><?=$fio?></option>

                            <?php
                             $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM tutors WHERE tutors.TutorID IN(4099,4212)";

                            $res = $con->query($sql);
                            while (list($tid,$s,$n,$p) = mysqli_fetch_row($res)) {
                                $fio = "$s $n $p";
                            ?>
                            <option value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

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
                            // $sql = "select dean,nameru from structural_subdivision where id=92";
                            // $res = $con->query($sql);
                            // list($pps,$nameru) = mysqli_fetch_row($res);
                            // $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM tutors WHERE tutors.TutorID = ".$pps;
                            // $res = $con->query($sql);
                            // list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                            // $fio = "ОБУ $s $n $p";
                            ?>
                     <!--        <option selected value="<?=$tid?>"><?=$fio?></option> -->
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
                            // if ($_SESSION['payid']!=1) {
                            $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM tutors WHERE tutors.TutorID = 4100";
                            $res = $con->query($sql);
                            list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                            $fio = "ОБУ $s $n $p";
                            ?>
                            <option selected value="<?=$tid?>"><?=$fio?></option>


                             <?php
                            $sql="SELECT students.studyformid from students WHERE students.studentid =".$_SESSION['studentid'];
                            $res = $con->query($sql);
                            list($stfid) = mysqli_fetch_row($res);

                            if($stfid==7 || $stfid==10){

                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.name$lang FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE structural_subdivision.id in (80,98)";
                            $res = $con->query($sql) or die($sql);
                            while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
                                $fio = "$podr $s $n $p";
                            ?>
                            <option selected value="<?=$tid?>"><?=$fio?></option>
                            <?php
                        }
                    }
                            ?>

                        </select>
                    </div>

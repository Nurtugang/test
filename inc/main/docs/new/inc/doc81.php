                    <div class="form-group">
                        <label>Член Правления - Проректор по академическим вопросам:</label>
                        <select class="single-select" id="todata" data-placeholder="Член Правления - Проректор по академическим вопросам" style="width: 100%;">
                            <?php
                            $sql = "select dean from structural_subdivision where id=99";
                            $res = $con->query($sql);
                            list($d) = mysqli_fetch_row($res);
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM  tutors where tutorid=$d";
                            $res = $con->query($sql) or die($sql);
                            list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                            $fio = "Член Правления - Проректор по академическим вопросам $s $n $p";
                            ?>
                            <option selected="" value="<?=$tid?>"><?=$fio?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Декан, заведующий кафедрой. руководитель:</label>
                        <select class="multiple-select" multiple="" id="todatals2" data-placeholder="Заведующий кафедрой. Эдвайзер:" style="width: 100%;">
                            <?php
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM  tutors where tutorid=".$_SESSION['cafedramanager'];
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
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.name$lang,tutors.viceRector,structural_subdivision.subdivision_type,structural_subdivision.pre FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE structural_subdivision.id in (45,46,81)";
                            $res = $con->query($sql) or die($sql);
                            while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
                                $fio = "$podr $s $n $p";
                            ?>
                            <option selected="" value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

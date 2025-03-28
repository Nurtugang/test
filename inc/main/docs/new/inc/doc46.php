                    <div class="form-group">
                        <label>Член Правления - проректор по науке и инновациям:</label>
                        <select class="single-select" id="todata" data-placeholder="Член Правления - проректор по науке и инновациям" style="width: 100%;">
                            <?php
                            $sql = "select dean from structural_subdivision where id=98";
                            $res = $con->query($sql);
                            list($d) = mysqli_fetch_row($res);
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM  tutors where tutorid=$d";
                            $res = $con->query($sql) or die($sql);
                            list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                            $fio = "Член Правления - проректор по науке и инновациям $s $n $p";
                            ?>
                            <option selected="" value="<?=$tid?>"><?=$fio?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Руководитель ОПВО:</label>
                        <select class="single-select" id="todatals1" data-placeholder="Декан факультета" style="width: 100%;">
                            <?php
                            $sql = "select dean from structural_subdivision where id=80";
                            $res = $con->query($sql);
                            list($d) = mysqli_fetch_row($res);
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM  tutors where tutorid=$d";
                            $res = $con->query($sql) or die($sql);
                            list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                            $fio = "Руководитель ОПВО $s $n $p";
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
                            $sql = "select p_rukid from students_person where studentid=".$_SESSION['personid'];
                            $res = $con->query($sql);
                            list($p_rukid) = mysqli_fetch_row($res);
                            $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM tutors WHERE tutors.TutorID = $p_rukid";
                            $res = $con->query($sql);
                            if ($res->num_rows!=0) {
                                list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                                $fio = "Руководитель $s $n $p";
                                ?>
                                <option selected value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

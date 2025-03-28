                    <div class="form-group">
                        <label>Заведующий кафедрой:</label>
                        <select class="single-select"  id="todata" data-placeholder="Утверждение дипломного проекта/работы" style="width: 100%;">
                            <?php
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM  tutors where tutorid=".$_SESSION['cafedramanager'];
                            $res = $con->query($sql);
                            list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                            $fio = "Заведующий кафедрой $s $n $p";
                            ?>
                            <option value="<?=$tid?>"><?=$fio?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Руководитель ДП/ДР/МД:</label>
                        <select class="single-select" id="todatals1" data-placeholder="Руководитель ДП/ДР/МД" style="width: 100%;">
                            <?php
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM tutor_cafedra INNER JOIN tutors ON tutor_cafedra.tutorID = tutors.TutorID WHERE tutor_cafedra.cafedraid = ".$_SESSION['cafedraid']." AND tutor_cafedra.deleted = 0 order by tutors.lastname,tutors.firstname,tutors.patronymic";
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM tutors WHERE deleted = 0 order by tutors.lastname,tutors.firstname,tutors.patronymic";
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
                    <div class="form-group" style="display: none;">
                        <label>Нормоконтролер:</label>
                        <select class="multiple-select" multiple="multiple" id="todatals2" data-placeholder="Нормоконтролер:" style="width: 100%;">

                        </select>
                    </div>
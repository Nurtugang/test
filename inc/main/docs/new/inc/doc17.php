                    <div class="form-group">
                        <label>Руководитель практики от университета:</label>
                        <select class="single-select"  id="todata" data-placeholder="Руководитель практики" style="width: 100%;">
                            <?php
                            // $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM tutor_cafedra INNER JOIN tutors ON tutor_cafedra.tutorID = tutors.TutorID WHERE tutor_cafedra.cafedraid = ".$_SESSION['cafedraid']." AND tutor_cafedra.deleted = 0 order by tutors.lastname,tutors.firstname,tutors.patronymic";
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM tutor_cafedra INNER JOIN tutors ON tutor_cafedra.tutorID = tutors.TutorID WHERE  tutor_cafedra.deleted = 0 order by tutors.lastname,tutors.firstname,tutors.patronymic";
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
                        <label>Эдвайзер:</label>
                        <select class="single-select" id="todatals1" data-placeholder="Подпись руководителя проекта/работы" style="width: 100%;">
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Руководитель с базы практики:</label>
                        <select class="multiple-select" multiple="multiple" id="todatals2" data-placeholder="Руководитель практики от предприятия:" style="width: 100%;">
                       <?php
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM tutor_cafedra INNER JOIN tutors ON tutor_cafedra.tutorID = tutors.TutorID WHERE tutor_cafedra.cafedraid = ".$_SESSION['cafedraid']." AND tutor_cafedra.deleted = 0 order by tutors.lastname,tutors.firstname,tutors.patronymic";
                            $sql = "select personid,lastname,firstname,patronymic from users where personid>30000000";
                            $res = $condocs->query($sql);
                            while (list($tid,$s,$n,$p) = mysqli_fetch_row($res)) {
                                $fio = "$s $n $p";
                            ?>
                            <option value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
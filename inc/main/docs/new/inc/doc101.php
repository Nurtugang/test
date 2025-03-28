                    <div class="form-group">
                        <label>Член Правления - проректор по академическим вопросам:</label>
                        <select class="single-select"  id="todata" data-placeholder="Член Правления - проректор по академическим вопросам" style="width: 100%;">
                            <?php
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.name$lang FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE structural_subdivision.id IN (99)";
                            // $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM  tutors where tutorid=3343";
                            //$sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM faculties INNER JOIN tutors ON faculties.facultyDean = tutors.TutorID INNER JOIN cafedras ON cafedras.FacultyID = faculties.FacultyID WHERE cafedras.cafedraID = ".$_SESSION['cafedraid'];
                            $res = $con->query($sql);
                            list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                            $fio = "Член Правления - проректор по академическим вопросам $s $n $p";
                            ?>
                            <option value="<?=$tid?>"><?=$fio?></option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Заведующий кафедрой: <?=$_SESSION['cafedraid']?></label>
                        <select  multiple="multiple" class="multiple-select"  id="todatals2" data-placeholder="Соруководитель, консультант, нормоконтролер:" style="width: 100%;">
                            <?php
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.name$lang,tutors.viceRector,structural_subdivision.subdivision_type,structural_subdivision.pre FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE tutors.TutorID in (".$_SESSION['cafman'].",".$_SESSION['decan'].") ";
                            $res = $con->query($sql);
                            while (list($tid,$s,$n,$p) = mysqli_fetch_row($res)) {
                                $fio = "$s $n $p";
                            ?>
                            <option value="<?=$tid?>" selected><?=$fio?></option>
                            <?php
                            }
                            $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic, structural_subdivision.nameru FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE structural_subdivision.id in (46,42,124)";
                            $res = $con->query($sql) or die($sql);
                            while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
                                $fio = "$podr $s $n $p";

                            ?>

                            <option value="<?=$tid?>" selected="selected"><?=$fio?></option>
                            <?php
                            }

                            $sql = "SELECT distinct tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM specializations INNER JOIN profession_cafedra ON specializations.prof_caf_id = profession_cafedra.id INNER JOIN cafedras ON profession_cafedra.cafedraID = cafedras.cafedraID INNER JOIN tutors ON specializations.advisorID = tutors.TutorID Where cafedras.cafedraid =".$_SESSION['cafedraid'];
                            $res = $con->query($sql) or die($sql);
                            while (list($tid,$s,$n,$p) = mysqli_fetch_row($res)) {
                                $fio = "$s $n $p";

                            ?>

                            <option value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            ?>

                        </select>
                    </div>
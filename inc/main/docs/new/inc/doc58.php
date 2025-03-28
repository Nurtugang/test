                    <div class="form-group">
                        <label>Отдел управления персоналом и документооборота:</label>
                        <select class="single-select"  id="todata" data-placeholder="Кому адресован документ" style="width: 100%;">
                            <?php
                            $sql = "select TutorID,lastname,firstname,patronymic from tutors where tutorid in (3720)";
                            $res = $con->query($sql);
                            while (list($tid,$s,$n,$p) = mysqli_fetch_row($res)) {
                                $fio = "$s $n $p";
                            ?>
                            <option selected="selected" value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            ?>


                            </select>
                    </div>
                   <div class="form-group">
                        <label>Заведующий кафедрой: <?=$_SESSION['cafedraid']?></label>
                        <select  multiple="multiple" class="multiple-select"  id="todatals1" data-placeholder="Заведующий кафедрой:" style="width: 100%;">
                            <?php
                            $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic, cafedras.cafedraNameRU FROM cafedras INNER JOIN tutors ON cafedras.cafedraManager = tutors.TutorID WHERE cafedras.cafedraID = ".$_SESSION['cafedraid'];
                            $res = $con->query($sql);
                            while (list($tid,$s,$n,$p,$caf) = mysqli_fetch_row($res)) {
                                $fio = "$caf $s $n $p";
                            ?>
                            <option value="<?=$tid?>" selected><?=$fio?></option>
                            <?php
                            }
                            ?>

                            <!-- Кафедры -->
                            <?php
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.name$lang,tutors.viceRector,structural_subdivision.subdivision_type,structural_subdivision.pre FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE (tutors.viceRector <> 1 OR tutors.viceRector IS NULL) AND structural_subdivision.subdivision_type = 3";
                            $res = $con->query($sql) or die($sql);
                            while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
                                $fio = "$podr $s $n $p";
                            ?>
                           <option value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            ?>


                        </select>
                    </div>
                    <div class="form-group">
                        <label>Согласование:</label>
                        <select class="multiple-select" multiple="multiple" id="todatals2" data-placeholder="Согласование" style="width: 100%;">


                            <!-- Библиотека -->

                            <?php
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.name$lang,tutors.viceRector,structural_subdivision.subdivision_type,structural_subdivision.pre FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE (tutors.viceRector <> 1 OR tutors.viceRector IS NULL) AND tutors.TutorID in (4453)";
                            $res = $con->query($sql) or die($sql);
                            while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
                                $fio = "$podr $s $n $p";
                            ?>
                            <option selected value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            ?>

                            <!-- ВУС -->

                            <?php
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM  tutors where tutorid in (4038)";
                            $res = $con->query($sql);
                            while (list($tid,$s,$n,$p) = mysqli_fetch_row($res)) {
                                $fio = "ВУС $s $n $p";
                            ?>
                            <option selected value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            ?>

                            <!-- Бух.мат. группы -->
                            <?php
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM  tutors where tutorid in (4213)";
                            $res = $con->query($sql);
                            while (list($tid,$s,$n,$p) = mysqli_fetch_row($res)) {
                                $fio = "Бухгалтер материальной группы $s $n $p";
                            ?>
                             <option selected value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            ?>

                            <!-- Бух. расчет. группы -->
                            <?php
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM  tutors where tutorid in (4222,4206)";
                            $res = $con->query($sql);
                            while (list($tid,$s,$n,$p) = mysqli_fetch_row($res)) {
                                $fio = "Бухгалтер расчетной группы $s $n $p";
                            ?>
                            <option selected value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            ?>


                            <!-- Архив -->

                            <?php
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM  tutors where tutorid in (3591)";
                            $res = $con->query($sql);
                            while (list($tid,$s,$n,$p) = mysqli_fetch_row($res)) {
                                $fio = "Ведомственный архив $s $n $p";
                            ?>
                            <option selected value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            ?>

                            <!-- Комендант -->
                            <?php
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM  tutors where tutorid in (4218,4254,4374,4227,4511)";
                            $res = $con->query($sql);
                            while (list($tid,$s,$n,$p) = mysqli_fetch_row($res)) {
                                $fio = "Комендант $s $n $p";
                            ?>
                            <option value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            ?>

                        </select>
                    </div>

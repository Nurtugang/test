                    <div class="form-group">
                        <label>Отдел управления персоналом и документооборота:</label>
                        <select class="single-select"  id="todata" data-placeholder="Кому адресован документ" style="width: 100%;">
                            <?php
                            $sql = "select TutorID,lastname,firstname,patronymic from tutors where tutorid in (4207)";
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
                        <label>Руководитель отдела:</label>
                        <select class="single-select" id="todatals1" data-placeholder="Согласование департамента или учебного отдела" style="width: 100%;">
                            <?php
                            if ($_SESSION['subdivision_type']!=1) {
                                    $sql = "select pre from structural_subdivision where id=".$_SESSION['idpodr'];
                                    //echo "<option value=\"0\">$sql</option>";
                                    $res = $con->query($sql);
                                    list($pre) = mysqli_fetch_row($res);
                                    if ($pre!=0 || $pre!='') {
                                        $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.name$lang,tutors.viceRector,structural_subdivision.subdivision_type,structural_subdivision.pre FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE (tutors.viceRector <> 1 OR tutors.viceRector IS NULL) AND structural_subdivision.subdivision_type = 1 and structural_subdivision.id=$pre and not structural_subdivision.id in (98,99,100,101)";
                                        $res = $con->query($sql) or die($sql);
                                        list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res);
                                        $fio = "$podr $s $n $p";
                                        ?>
                                        <option selected="" value="<?=$tid?>"><?=$fio?></option>
                                        <?php
                                    }
                            }else {
                                    $sql = "select dean from structural_subdivision where id=".$_SESSION['idpodr'];
                                    //echo "<option value=\"0\">$sql</option>";
                                    $res = $con->query($sql);
                                    list($pre) = mysqli_fetch_row($res);
                                    if ($pre!=0 || $pre!='') {
                                        $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.name$lang,tutors.viceRector,structural_subdivision.subdivision_type,structural_subdivision.pre FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE tutors.TutorID=$pre";
                                        $res = $con->query($sql) or die($sql);
                                        list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res);
                                        $fio = "$podr $s $n $p";
                                        ?>
                                        <option selected="" value="<?=$tid?>"><?=$fio?></option>
                                        <?php
                                    }
                            }
                            ?>

                            <!-- Структура вуза -->

                            <?php
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.name$lang,tutors.viceRector,structural_subdivision.subdivision_type,structural_subdivision.pre FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE (tutors.viceRector <> 1 OR tutors.viceRector IS NULL) AND structural_subdivision.subdivision_type = 1";
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
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.name$lang,tutors.viceRector,structural_subdivision.subdivision_type,structural_subdivision.pre FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE structural_subdivision.id = 79";
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
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM  tutors where tutorid in (4040)";
                            $res = $con->query($sql);
                            while (list($tid,$s,$n,$p) = mysqli_fetch_row($res)) {
                                $fio = "ВУС $s $n $p";
                            ?>
                            <option selected value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            ?>

                            <!-- Бух.мат. группы --> <!-- 4214- Алтаева М.К. --> <!-- 4213- Желдыбаева Д.С. -->
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
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM  tutors where tutorid in (4222)";
                            $res = $con->query($sql);
                            while (list($tid,$s,$n,$p) = mysqli_fetch_row($res)) {
                                $fio = "Бухгалтер расчетной группы $s $n $p";
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

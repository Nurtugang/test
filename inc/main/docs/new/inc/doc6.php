                    <div class="form-group">
                        <label>Утверждаю:</label>
                        <select class="single-select"  id="todata" data-placeholder="Кому адресован документ" style="width: 100%;">
                            <option value="0">Выберите курирующего проректора</option>
                            <?php
                            // if ($_SESSION['subdivision_type']!=1) {
                            //     $t=3720;
                            // } else {
                            //     $t=4207;
                            // }
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.name$lang,tutors.viceRector,structural_subdivision.subdivision_type,structural_subdivision.pre FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE structural_subdivision.id in (98,99,100,113,142)";
                            $res = $con->query($sql);
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
                            <?php
                            if ($_SESSION['subdivision_type']!=1) {
                                    $sql = "select pre from structural_subdivision where id=".$_SESSION['idpodr'];
                                    //echo "<option value=\"0\">$sql</option>";
                                    $res = $con->query($sql);
                                    list($pre) = mysqli_fetch_row($res);
                                    if ($pre!=0 || $pre!='') {
                                        $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.name$lang,tutors.viceRector,structural_subdivision.subdivision_type,structural_subdivision.pre FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE (tutors.viceRector <> 1 OR tutors.viceRector IS NULL) AND structural_subdivision.subdivision_type = 1 and not structural_subdivision.id in (98,99,100,101)";
                                        $res = $con->query($sql) or die($sql);
                                        list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res);
                                        $fio = "$podr $s $n $p";
                                        ?>
                                        <option value="<?=$tid?>"><?=$fio?></option>
                                        <?php
                                    }
                            } else {
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
                                        <option value="<?=$tid?>"><?=$fio?></option>
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
                           <!-- Деканы -->
                            <?php
                             $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic, faculties.facultyNameRU FROM faculties INNER JOIN tutors ON faculties.facultyDean = tutors.TutorID WHERE FacultyID=1";
                            $res = $con->query($sql) or die($sql);
                            while (list($tid,$s,$n,$p,$fac) = mysqli_fetch_row($res)) {
                                $fio = "$fac - $s $n $p";
                            ?>
                            <option value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            ?>
                            <?php
                             $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic, faculties.facultyNameRU FROM faculties INNER JOIN tutors ON faculties.facultyDean = tutors.TutorID WHERE FacultyID=4";
                            $res = $con->query($sql) or die($sql);
                            while (list($tid,$s,$n,$p,$fac) = mysqli_fetch_row($res)) {
                                $fio = "$fac - $s $n $p";
                            ?>
                            <option value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            ?>
                            <?php
                             $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic, faculties.facultyNameRU FROM faculties INNER JOIN tutors ON faculties.facultyDean = tutors.TutorID WHERE FacultyID=6";
                            $res = $con->query($sql) or die($sql);
                            while (list($tid,$s,$n,$p,$fac) = mysqli_fetch_row($res)) {
                                $fio = "$fac - $s $n $p";
                            ?>
                            <option value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            ?>
                            <?php
                             $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic, faculties.facultyNameRU FROM faculties INNER JOIN tutors ON faculties.facultyDean = tutors.TutorID WHERE FacultyID=7";
                            $res = $con->query($sql) or die($sql);
                            while (list($tid,$s,$n,$p,$fac) = mysqli_fetch_row($res)) {
                                $fio = "$fac - $s $n $p";
                            ?>
                            <option value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            ?>
                            <?php
                             $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic, faculties.facultyNameRU FROM faculties INNER JOIN tutors ON faculties.facultyDean = tutors.TutorID WHERE FacultyID IN (8,9,10,11,12)";
                            $res = $con->query($sql) or die($sql);
                            while (list($tid,$s,$n,$p,$fac) = mysqli_fetch_row($res)) {
                                $fio = "$fac - $s $n $p";
                            ?>
                            <option value="<?=$tid?>"><?=$fio?></option>
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
                            <?php
                             $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic, structural_subdivision.nameru FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE structural_subdivision.subdivision_type = 0 ";
                            //$sql = "select TutorID,lastname,firstname,patronymic from tutors where vicerector=1";
                            $res = $con->query($sql);
                            while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
                                $fio = "$podr $s $n $p";
                            ?>
                            <option value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                           
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.name$lang,tutors.viceRector,structural_subdivision.subdivision_type,structural_subdivision.pre FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE (tutors.viceRector <> 1 OR tutors.viceRector IS NULL) AND structural_subdivision.subdivision_type = 1";
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic from tutors where tutorid in (4206,4222,4207,2395)";
                            $res = $con->query($sql) or die($sql);
                            while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
                                $fio = "$podr $s $n $p";
                            ?>
                            <option selected value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }

                            $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM tutors WHERE tutors.TutorID = 5211 AND tutors.deleted = 0  ";
                            //$sql = "select TutorID,lastname,firstname,patronymic from tutors where vicerector=1";
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

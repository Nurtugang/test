                    <div class="form-group">
                        <label>Кому:</label>
                        <select class="single-select"  id="todata" data-placeholder="Кому адресован документ" style="width: 100%;">
                            <?php

                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,university.dname$lang FROM university INNER JOIN tutors ON university.rectorID = tutors.TutorID";
                            $res = $con->query($sql);
                            list($tid,$s,$n,$p,$dname) = mysqli_fetch_row($res);
                            $fio = "$dname $s $n $p";
                            ?>
                            <option value="<?=$tid?>"><?=$fio?></option>
                            <?php

                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Окончательное согласование:</label>
                        <select class="single-select" id="todatals1" data-placeholder="Окончательное согласование" style="width: 100%;">
                            <?php
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.name$lang,tutors.viceRector,structural_subdivision.subdivision_type,structural_subdivision.pre FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE structural_subdivision.id = 99";
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

                            <?php
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.name$lang,tutors.viceRector,structural_subdivision.subdivision_type,structural_subdivision.pre FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE structural_subdivision.id in (92)";
                            $res = $con->query($sql) or die($sql);
                            while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
                                $fio = "$podr $s $n $p";
                            ?>
                            <option selected="" value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }

                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.nameru FROM tutors INNER JOIN structural_subdivision ON tutors.departmentid = structural_subdivision.id WHERE tutors.TutorID IN (5211)";
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
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
                    <div class="form-group" style="display: none;">
                        <label>Окончательное согласование:</label>
                        <select class="single-select" id="todatals1" data-placeholder="Окончательное согласование" style="width: 100%;">
                            <option value="0">Нет</option>
                        </select>
                    </div>
                    <div class="form-group" style="display: none;">
                        <label>Согласование:</label>
                        <select class="multiple-select" multiple="multiple" id="todatals2" data-placeholder="Согласование" style="width: 100%;">
                        </select>
                    </div>
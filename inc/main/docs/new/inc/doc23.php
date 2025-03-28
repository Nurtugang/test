                    <div class="form-group">
                        <label>Главный бухгалтер:</label>
                        <select class="single-select"  id="todata" data-placeholder="" style="width: 100%;">
                            <?php
                            $sql = "select dean from structural_subdivision where id=126";
                            $res = $con->query($sql);
                            list($tid) = mysqli_fetch_row($res);
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM  tutors where tutorid=$tid";
                            $res = $con->query($sql);
                            list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                            $fio = "Главный бухгалтер $s $n $p";
                            ?>
                            <option value="<?=$tid?>"><?=$fio?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Принял:</label>
                        <select class="single-select"  id="todatals1" data-placeholder="" style="width: 100%;">
                            <?php
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM  tutors where tutorid in (4214,4213)";
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
                    <div class="form-group">
                        <label>Сдал/Принял:</label>
                        <select class="multiple-select" id="todatals2" multiple="multiple" data-placeholder="" style="width: 100%;">
                            <?php
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM  tutors where deleted = 0 and not iinplt is null and tutorid not in (4151)";
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
                    <div class="form-group">
                        <?php
                        $sql = "select dean,nameru from structural_subdivision where id=99";
                        $res = $con->query($sql);
                        list($pps,$nameru) = mysqli_fetch_row($res);
                        ?>
                        <label><?=$nameru?>:</label>
                        <select class="single-select" id="todata" data-placeholder="<?=$nameru?>" style="width: 100%;">
                            <?php

                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM  tutors where tutorid=".$pps;
                            $res = $con->query($sql) or die($sql);
                            list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                            $fio = "$s $n $p";
                            ?>
                            <option selected="" value="<?=$tid?>"><?=$fio?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <?php
                        $sql = "select dean,nameru from structural_subdivision where id=42";
                        $res = $con->query($sql);
                        list($pps,$nameru) = mysqli_fetch_row($res);
                        ?>
                        <label><?=$nameru?>:</label>
                        <select class="single-select" id="todatals1" data-placeholder="<?=$nameru?>" style="width: 100%;">
                            <?php
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM  tutors where tutorid=".$pps;
                            $res = $con->query($sql);
                            list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                            $fio = "$s $n $p";
                            ?>
                            <option selected="" value="<?=$tid?>"><?=$fio?></option>
                       </select>
                    </div>
                    <div class="form-group">
                        <?php
                        $sql = "select dean,nameru from structural_subdivision where id=45";
                        $res = $con->query($sql);
                        list($pps,$nameru) = mysqli_fetch_row($res);
                        ?>
                        <label><?=$nameru?>:</label>
                        <select class="single-select" id="todatals2" data-placeholder="<?=$nameru?>:" style="width: 100%;">
                            <?php
                            $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM tutors WHERE tutors.TutorID = ".$pps;
                            $res = $con->query($sql);
                            while (list($tid,$s,$n,$p) = mysqli_fetch_row($res)) {
                                $fio = "$s $n $p";
                            ?>
                            <option selected value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

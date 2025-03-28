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
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Окончательное согласование:</label>
                        <select class="single-select" id="todatals1" data-placeholder="Окончательное согласование" style="width: 100%;">
                            <?php
                            //$sql = "select TutorID,lastname,firstname,patronymic from tutors where vicerector=1";
                            $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic, structural_subdivision.nameru FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE tutors.TutorID = 4208 ";
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
                            $sql = "select id,pre,subdivision_type,faculty_cafedra_id from structural_subdivision where deleted=0 and dean=".$_SESSION['personid'];
                            $res = $con->query($sql) or die($sql);
                            if ($res->num_rows==0) {
                                die('Вас нет в списке руководителей подразделений');
                            }
                            list($id,$pre,$subdivision_type,$faculty_cafedra_id) = mysqli_fetch_row($res);
                            $sql = "select id,pre,subdivision_type,faculty_cafedra_id,dean from structural_subdivision where deleted=0 and id=$pre";
                            $res = $con->query($sql) or die($sql);
                            if ($res->num_rows!=0) {
                                list($id,$pre,$subdivision_type,$faculty_cafedra_id,$dean) = mysqli_fetch_row($res);
                                $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic, structural_subdivision.nameru FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE tutors.TutorID = $dean ";
                                $res = $con->query($sql) or die($sql);
                                list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res);
                                $fio = "$podr $s $n $p";
                                ?>
                                <option  selected="selected" value="<?=$tid?>"><?=$fio?></option>
                                <?php
                                if ($pre>0) {
                                    $sql = "select id,pre,subdivision_type,faculty_cafedra_id,dean from structural_subdivision where deleted=0 and id=$pre";
                                    $res = $con->query($sql) or die($sql);
                                    if ($res->num_rows!=0) {
                                        list($id,$pre,$subdivision_type,$faculty_cafedra_id,$dean) = mysqli_fetch_row($res);
                                        $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic, structural_subdivision.nameru FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE tutors.TutorID = $dean ";
                                        $res = $con->query($sql) or die($sql);
                                        list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res);
                                        $fio = "$podr $s $n $p";
                                        ?>
                                        <option  selected="selected" value="<?=$tid?>"><?=$fio?></option>
                                        <?php
                                    }
                                }
                            }
                            //$sql = "select TutorID,lastname,firstname,patronymic from tutors where vicerector=1";
                            $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic, structural_subdivision.nameru FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE structural_subdivision.id in (2,92)";
                            $res = $con->query($sql);
                            while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
                                $fio = "$podr $s $n $p";
                            ?>
                            <option selected="selected" value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
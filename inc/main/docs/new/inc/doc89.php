                    <div class="form-group">
                        <label>Согласования:</label>
                        <select class="single-select"  id="todata" data-placeholder="1" style="width: 100%;">
                            <?php
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.name$lang,tutors.viceRector,structural_subdivision.subdivision_type,structural_subdivision.pre FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE structural_subdivision.id = 42";
                            $res = $con->query($sql) or die($sql);
                            while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
                                $fio = "$podr $s $n $p";
                            ?>
                            <option selected value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Согласования:</label>
                        <select multiple="multiple" class="multiple-select" id="todatals2" data-placeholder="2" style="width: 100%;">
                            <?php
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.name$lang,tutors.viceRector,structural_subdivision.subdivision_type,structural_subdivision.pre FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE structural_subdivision.id IN (70,86,124,84)";
                            $res = $con->query($sql) or die($sql);
                            while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
                                $fio = "$podr $s $n $p";
                            ?>
                            <option selected value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            ?>
                            <?php
                            $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM cafedras INNER JOIN tutors ON cafedras.cafedraManager = tutors.TutorID WHERE cafedras.cafedraID = ".$_SESSION['cafedraid'];
                            $res = $con->query($sql);
                            while (list($tid,$s,$n,$p) = mysqli_fetch_row($res)) {
                                $fio = "$s $n $p";
                            ?>
                            <option value="<?=$tid?>" selected><?=$fio?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

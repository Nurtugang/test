   <div class="form-group">
                        <label>Руководитель офиса Регистратора:</label>
                        <select class="single-select"  id="todata" data-placeholder="Руководитель офиса Регистратора" style="width: 100%;">
                            <?php
                            $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic, structural_subdivision.nameru FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE structural_subdivision.subdivision_type = 1 and structural_subdivision.id=46";
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
                        <select multiple="multiple" class="multiple-select" id="todatals2" data-placeholder="Согласование" style="width: 100%;">
                            <?php
                            $sql = "SELECT DISTINCT
  tutors.TutorID,
  CONCAT(tutors.lastname, ' ', tutors.firstname, ' ', tutors.patronymic) AS fio,
  cafedras.cafedraNameRU
FROM tutor_cafedra
  INNER JOIN tutors
    ON tutor_cafedra.tutorID = tutors.TutorID
  INNER JOIN cafedras
    ON tutor_cafedra.cafedraid = cafedras.cafedraID
WHERE tutors.deleted = 0 and tutors.tutorid = ".$_SESSION['adviserid'];
                            $res = $con->query($sql);
                            // die($sql);
                            list($tid,$f,$k) = mysqli_fetch_row($res);
                                $fio = "$k $f";
                            ?>
                            <option selected value="<?=$tid?>"><?=$fio?></option>
                            

                            <?php
                            $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM cafedras INNER JOIN tutors ON cafedras.cafedraManager = tutors.TutorID WHERE cafedras.cafedraID = ".$_SESSION['cafedraid'];
                            $res = $con->query($sql);
                            list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                            $fio = "Заведующий кафедрой $s $n $p";
                            ?>
                            <option selected value="<?=$tid?>"><?=$fio?></option>

                        </select>
                    </div>
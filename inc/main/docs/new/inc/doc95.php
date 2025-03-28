                     <div class="form-group">
                        <label>Декан факультета:</label>
                        <select class="single-select"  id="todata" data-placeholder="Декан факультета" style="width: 100%;">
                            <?php
                            $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic, structural_subdivision.nameru FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE structural_subdivision.subdivision_type = 2 ";
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
                        <label>Менеджер ОП:</label>
                        <select class="single-select" id="todatals1" data-placeholder="Менеджер ОП" style="width: 100%;">
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
WHERE tutors.deleted = 0
ORDER BY tutors.lastname, tutors.firstname, tutors.patronymic";
                            $res = $con->query($sql);
                            while (list($tid,$f,$k) = mysqli_fetch_row($res)) {
                                $fio = "$k $f";
                            ?>
                            <option value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

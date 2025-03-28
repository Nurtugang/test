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
                        <label>Председатель комиссии по обеспечению качества:</label>
                        <select class="single-select" id="todatals1" data-placeholder="Председатель комиссии по обеспечению качества" style="width: 100%;">
                            <?php
                            $sql = "SELECT DISTINCT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic, faculties.facultynameru FROM faculties INNER JOIN cafedras ON cafedras.FacultyID = faculties.FacultyID INNER JOIN tutors ON tutors.TutorID = faculties.sapacom ";
                            $res = $con->query($sql);
                            while (list($tid,$s,$n,$p,$fac) = mysqli_fetch_row($res)) {
                                $fio = "$fac $s $n $p";
                            ?>
                            <option value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Председатель комиссии по обеспечению качества:</label>
                        <select class="single-select" id="todata" data-placeholder="Председатель комиссии по обеспечению качества" style="width: 100%;">
                            <?php
                            $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM faculties INNER JOIN cafedras ON cafedras.FacultyID = faculties.FacultyID INNER JOIN tutors ON tutors.TutorID = faculties.sapacom WHERE cafedras.cafedraID = ".$_SESSION['cafedraid'];
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
                        <label>Заведующий кафедрой: <?=$_SESSION['cafedraid']?></label>
                        <select class="multiple-select" multiple="multiple" id="todatals2" data-placeholder="Заведующий кафедрой:" style="width: 100%;">
                            <?php
                            $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM cafedras INNER JOIN tutors ON cafedras.cafedraManager = tutors.TutorID WHERE cafedras.cafedraID = ".$_SESSION['cafedraid'];
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
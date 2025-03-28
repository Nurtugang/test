                    <div class="form-group">
                        <label>Руководитель центра обслуживания обучающихся:</label>
                        <select class="single-select"  id="todatals3" data-placeholder="Руководитель центра обслуживания обучающихся" style="width: 100%;">
                            <?php
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM  tutors where tutorid=(select dean from structural_subdivision where id=45 limit 1)";
                            //$sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM faculties INNER JOIN tutors ON faculties.facultyDean = tutors.TutorID INNER JOIN cafedras ON cafedras.FacultyID = faculties.FacultyID WHERE cafedras.cafedraID = ".$_SESSION['cafedraid'];
                            $res = $con->query($sql);
                            list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                            $fio = "Руководитель центра обслуживания обучающихся $s $n $p";
                            ?>
                            <option value="<?=$tid?>"><?=$fio?></option>
                        </select>
                    </div>
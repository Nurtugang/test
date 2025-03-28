                    <div class="form-group">
                        <label>Офис регистратора:</label>
                        <select class="single-select" id="todata" data-placeholder="Офис регистратора" style="width: 100%;">
                            <?php
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM  tutors where tutorid=".$_SESSION['ordean'];
                            $res = $con->query($sql) or die($sql);
                            list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                            $fio = "Руководитель ОР $s $n $p";
                            ?>
                            <option selected="" value="<?=$tid?>"><?=$fio?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Декан факультета:</label>
                        <select class="single-select" id="todatals1" data-placeholder="Декан факультета" style="width: 100%;">
                            <?php
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic FROM  tutors where tutorid=".$_SESSION['cafedramanager'];
                            $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM faculties INNER JOIN tutors ON faculties.facultyDean = tutors.TutorID INNER JOIN cafedras ON cafedras.FacultyID = faculties.FacultyID WHERE cafedras.cafedraID = ".$_SESSION['cafedraid'];
                            $res = $con->query($sql);
                            list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                            $fio = "Декан факультета $s $n $p";
                            ?>
                            <option selected="" value="<?=$tid?>"><?=$fio?></option>
                       </select>
                    </div>
                    <div class="form-group">
                        <label>Заведующий кафедрой. Эдвайзер:</label>
                        <select class="multiple-select" multiple="" id="todatals2" data-placeholder="Заведующий кафедрой. Эдвайзер:" style="width: 100%;">
                            <?php
                            $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM tutors WHERE tutors.deleted NOT in (1) AND tutors.TutorID = ".$_SESSION['adviserid'];
                            $res = $con->query($sql);
                            while (list($tid,$s,$n,$p) = mysqli_fetch_row($res)) {
                                $fio = "$s $n $p";
                            ?>
                            <option selected value="<?=$tid?>"><?=$fio?></option>
                            <?php
                            }
                            ?>
                            <?php
                            $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM cafedras INNER JOIN tutors ON cafedras.cafedraManager = tutors.TutorID WHERE tutors.deleted NOT in (1) AND cafedras.cafedraID = ".$_SESSION['cafedraid'];
                            $res = $con->query($sql);
                            list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                            $fio = "Заведующий кафедрой $s $n $p";
                            ?>
                            <option selected value="<?=$tid?>"><?=$fio?></option>

                            <?php
                            $sql="SELECT faculties.FacultyID FROM faculties INNER JOIN cafedras ON cafedras.FacultyID = faculties.FacultyID WHERE cafedras.cafedraID =".$_SESSION['cafedraid'];
                            $res = $con->query($sql);
                            list($facid) = mysqli_fetch_row($res);

                            if($facid==7){

                                $sql="SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM tutors WHERE tutors.deleted NOT in (1) AND tutors.TutorID = 5859";
                                $res = $con->query($sql);
                                list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                                $fio = "$s $n $p";
                                ?>

                                 <option selected value="<?=$tid?>"><?=$fio?></option>

                                 <?php
                            }

                            if($facid==4){

                                $sql="SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM tutors WHERE tutors.deleted NOT in (1) AND tutors.TutorID = 5843";
                                $res = $con->query($sql);
                                list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                                $fio = "$s $n $p";
                                ?>

                                 <option selected value="<?=$tid?>"><?=$fio?></option>

                                 <?php
                            }

                            if($facid==8 || $facid==12){

                                $sql="SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM tutors WHERE tutors.deleted NOT in (1) AND tutors.TutorID = 5308";
                                $res = $con->query($sql);
                                list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                                $fio = "$s $n $p";
                                ?>

                                 <option selected value="<?=$tid?>"><?=$fio?></option>

                                 <?php
                            }

                            if($facid==6){

                                $sql="SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM tutors WHERE tutors.deleted NOT in (1) AND tutors.TutorID = 5213";
                                $res = $con->query($sql);
                                list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                                $fio = "$s $n $p";
                                ?>

                                 <option selected value="<?=$tid?>"><?=$fio?></option>

                                 <?php
                            }

                            if($facid==1 || $facid==10){

                                $sql="SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM tutors WHERE tutors.deleted NOT in (1) AND tutors.TutorID = 5318";
                                $res = $con->query($sql);
                                list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                                $fio = "$s $n $p";
                                ?>

                                 <option selected value="<?=$tid?>"><?=$fio?></option>

                                 <?php
                            }

                             if($facid==9){

                                $sql="SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM tutors WHERE tutors.deleted NOT in (1) AND tutors.TutorID = 6000";
                                $res = $con->query($sql);
                                list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                                $fio = "$s $n $p";
                                ?>

                                 <option selected value="<?=$tid?>"><?=$fio?></option>

                                 <?php


                            }




                            ?>

                             <?php
                            
                             if($facid==11){

                                $sql="SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic FROM tutors WHERE tutors.deleted NOT in (1) AND tutors.TutorID = 6136";
                                $res = $con->query($sql);
                                list($tid,$s,$n,$p) = mysqli_fetch_row($res);
                                $fio = "$s $n $p";
                                ?>

                                 <option selected value="<?=$tid?>"><?=$fio?></option>

                                 <?php

                                 
                            }
                              ?>
                        </select>
                    </div>

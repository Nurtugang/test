                    <div class="form-group">
                        <p align="center"><h2><b>Хабарландыру<br />

Университеттің профессор-оқытушылар құрамының жазғы,күзгі,қысқы семестрде өткізілген пәндер үшін ақы төлеу өтінішін қабылдау әр жұма күні сағат 16.00 ден 18.00 дейін 303 кабинетте  есеп бөлімінде қабылданады.
</b></h2></p>
                        <label>Кому:</label>
                        <select class="single-select"  id="todata" data-placeholder="Кому адресован документ" style="width: 100%;">
                            <?php

                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,university.dname$lang FROM university INNER JOIN tutors ON university.rectorID = tutors.TutorID";
                            
                            $res = $con->query($sql);
                            list($tid,$s,$n,$p,$dname) = mysqli_fetch_row($res);
                            $fio = "$dname $s $n $p";
                            ?>
                            <option value="<?=$tid?>"><?=$fio?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Окончательное согласование:</label>
                        <select class="single-select" id="todatals1" data-placeholder="Окончательное согласование" style="width: 100%;">
                            <?php
                            //$sql = "select TutorID,lastname,firstname,patronymic from tutors where vicerector=1";
                            $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic, structural_subdivision.nameru FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE structural_subdivision.id = 99";
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
                            //$sql = "select TutorID,lastname,firstname,patronymic from tutors where vicerector=1";
                            $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic, structural_subdivision.nameru FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE structural_subdivision.id in (126,127,46,".$_SESSION['cafman'].",".$_SESSION['decan'].")";
                            //die($sql);
                            $res = $con->query($sql);
                            while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
                                $fio = "$podr $s $n $p";
                            ?>
                            <option value="<?=$tid?>" selected="selected"><?=$fio?></option>
                            <?php
                            }
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.name$lang,tutors.viceRector,structural_subdivision.subdivision_type,structural_subdivision.pre FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE tutors.TutorID in (".$_SESSION['cafman'].",".$_SESSION['decan'].")";
                            $res = $con->query($sql) or die($sql);
                            while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
                                $fio = "$podr $s $n $p";
                            ?>
                            <option value="<?=$tid?>" selected="selected"><?=$fio?></option>
                            <?php
                            }
                             // $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.name$lang,tutors.viceRector,structural_subdivision.subdivision_type,structural_subdivision.pre FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE tutors.TutorID in ()";
                            $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic from tutors where tutorid in (4100,4222)";
                            $res = $con->query($sql) or die($sql);
                            while (list($tid,$s,$n,$p) = mysqli_fetch_row($res)) {
                                 $fio = "Бухгалтерия $s $n $p";

                            ?>

                            <option value="<?=$tid?>" selected="selected"><?=$fio?></option>
                            <?php
                            }
                            ?>

                        </select>
                    </div>
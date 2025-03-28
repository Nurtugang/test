<?php
                              $sql = "SELECT tutors.TutorID,tutors.lastname,tutors.firstname,tutors.patronymic,structural_subdivision.name$lang,tutors.viceRector,structural_subdivision.subdivision_type,structural_subdivision.pre FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE (tutors.viceRector <> 1 OR tutors.viceRector IS NULL) AND structural_subdivision.subdivision_type = 1";
                              $res = $con->query($sql) or die($sql);
                              while (list($tid,$s,$n,$p,$podr) = mysqli_fetch_row($res)) {
                                  $fio = "$podr $s $n $p";
                              ?>
                              <option value="<?=$tid?>"><?=$fio?></option>
                              <?php
                              }
                              $sql = "select TutorID,lastname,firstname,patronymic from tutors where not iinplt is null and deleted<>1";
                              $res = $con->query($sql);
                              while (list($tid,$s,$n,$p) = mysqli_fetch_row($res)) {
                                  $fio = "$s $n $p";
                              ?>
                              <option  value="<?=$tid?>"><?=$fio?></option>
                              <?php    
                              }
?>
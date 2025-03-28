<?php
                              $sql = "SELECT tutors.TutorID, tutors.lastname, tutors.firstname, tutors.patronymic, structural_subdivision.nameru FROM structural_subdivision INNER JOIN tutors  ON structural_subdivision.dean = tutors.TutorID WHERE structural_subdivision.id in (2)";
                              $res = $con->query($sql);
                              while (list($tid,$s,$n,$p) = mysqli_fetch_row($res)) {
                                  $fio = "$s $n $p";
                              ?>
                              <option value="<?=$tid?>"><?=$fio?></option>
                              <?php    
                              }
?>
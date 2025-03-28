<?php
                              $sql = "select TutorID,lastname,firstname,patronymic from tutors where tutorid in (4206,4222)";
                              $res = $con->query($sql);
                              while (list($tid,$s,$n,$p) = mysqli_fetch_row($res)) {
                                  $fio = "$s $n $p";
                              ?>
                              <option selected="selected" value="<?=$tid?>"><?=$fio?></option>
                              <?php    
                              }
?>
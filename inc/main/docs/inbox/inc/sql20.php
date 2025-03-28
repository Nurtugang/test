<?php
                              $sql = "select TutorID,lastname,firstname,patronymic from tutors where tutorid in (4214,4213,4210)";
                              $res = $con->query($sql);
                              while (list($tid,$s,$n,$p) = mysqli_fetch_row($res)) {
                                  $fio = "$s $n $p";
                              ?>
                              <option value="<?=$tid?>"><?=$fio?></option>
                              <?php    
                              }
?>
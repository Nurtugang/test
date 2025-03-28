<?php
                              $sql = "select TutorID,lastname,firstname,patronymic from tutors where deleted = 0 and not iinplt is null";
                              $res = $con->query($sql);
                              while (list($tid,$s,$n,$p) = mysqli_fetch_row($res)) {
                                  $fio = "$s $n $p";
                              ?>
                              <option value="<?=$tid?>"><?=$fio?></option>
                              <?php    
                              }
?>
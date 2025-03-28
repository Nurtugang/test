<?php
function select2dom($con,$sql1,$sql2,$name,$id,$label,$value=0) {
  if ($sql1>'') {$res = $con->query($sql1) or die($sql2);}
  echo '<div class="form-group">';
  if ($label!='') echo '<label for="'.$id.'">'.$label.'</label><br />';
  echo '<select class="form-control select2" multiple name="'.$name.'" id="'.$id.'" style="width: 100%">';
  if ($sql1>'') {
      if (!isset($cc)) {
          $cc = $c;
      }
      while (list($c,$n)=mysqli_fetch_row($res)) {
          echo "<option value='$c'>$n</option>";
      }
  }
  if ($sql2>'') {$res = $con->query($sql2) or die($sql2);}
  if ($sql2>'') {
      if (!isset($cc)) {
          $cc = $c;
      }
      while (list($c,$n)=mysqli_fetch_row($res)) {
          echo "<option value='$c'>$n</option>";
      }
  }
  echo '</select></div>';
  if ($value>0) {
      echo '<script>$("#'.$id.'").val("'.$value.'");</script>';
  } else {
      ?>
      <script>
          $('#<?=$id?>').val(<?=$cc?>);
      </script>
      <?php
  }
}
function inputcheckboxdom($name,$id,$label,$value,$r='') {
  if ($value==1) {$v=' checked';} else {$v='';}
  echo '<div class="form-group"><div class="checkbox"><label><input type="checkbox" '.$r.' id="'.$id.'c" '.$v.'><b>'.$label.'</b></label></div></div>';
  echo '<input type="hidden" id="'.$id.'" value="'.$value.'" name="'.$name.'">';
?>
<script>$('#<?=$id?>c').click(function() {$('#<?=$id?>').val($("#<?=$id?>c").is(':checked') ? 1 : 0);});</script>
<?php
}
function textareadom($name,$id,$label,$value) {
  echo '        <div class="form-group">
                  <label>'.$label.'</label>
                  <textarea class="form-control form-control-sm" name="'.$name.'" id="'.$id.'" rows="3" placeholder="'.$label.' ...">'.$value.'</textarea>
                </div>';
}
function inputdatetimedom($name,$id,$label,$value) {
  echo  '<div>
         <div class="form-group">
                <label>'.$label.'</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input style="z-index: 100000000" class="form-control pull-right form-control-sm" name="'.$name.'" id="'.$id.'" value="'.$value.'" type="text">
                </div>
                <!-- /.input group -->
              </div>
    </div>
<script>
    $("#'.$id.'").datetimepicker({
      //autoclose: true,dateFormat: "yy-mm-dd",language: "ru"
      locale: "ru",
      format: "YYYY-MM-DD HH:mm:ss"
    });

</script>';
}
function inputdatedom($name,$id,$label,$value,$action='') {
echo '<div class="form-group">
                  <label>'.$label.'</label>
                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                        <input type="text" class="form-control form-control-sm datetimepicker-input" '.$action.' data-target="#reservationdate" name="'.$name.'" id="'.$id.'" value="'.$value.'">
                        <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
    <script>$("#'.$id.'").daterangepicker({
      timePicker: false,
      singleDatePicker: true,
      "autoApply": true,
      locale: {
        format: "YYYY-MM-DD",
        "separator": " - ",
        "applyLabel": "Выбрать",
        "cancelLabel": "Отменить",
        "fromLabel": "From",
        "toLabel": "To",
        "customRangeLabel": "Custom",
        "weekLabel": "Н",
        "daysOfWeek": [
            "Вс",
            "Пн",
            "Вт",
            "Ср",
            "Чт",
            "Пт",
            "Сб"
        ],
        "monthNames": [
            "Январь",
            "Февраль",
            "Март",
            "Апрель",
            "Май",
            "Июнь",
            "Июль",
            "Август",
            "Сентябрь",
            "Октябрь",
            "Ноябрь",
            "Декабрь"
        ],
        "firstDay": 1
      }
    });</script>';
}
function inputdatedomonelevel($name,$id,$label,$value) {
  echo  '<div class="form-group">
                <label>'.$label.'</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input class="form-control-sm form-control pull-right" name="'.$name.'" id="'.$id.'" value="'.$value.'" type="text">
                </div>
                <!-- /.input group -->
              </div>
<script>
    $("#'.$id.'").datepicker({
      autoclose: true,dateFormat: "yy-mm-dd"
    });

</script>';
}
function inputtextdom($name,$id,$label,$value,$par='') {
        echo '<div class="form-group">
                  <label for="lastname" class="control-label">'.$label.'</label>
                    <input class="form-control-sm form-control" '.$par.' name="'.$name.'" value="'.$value.'" id="'.$id.'" placeholder="'.$label.'" type="text">
                </div>';
}
function inputpassworddom($name,$id,$label,$value,$par='') {
        echo '<div class="form-group">
                  <label for="lastname" class="control-label">'.$label.'</label>
                    <input class="form-control-sm form-control" '.$par.' name="'.$name.'" value="'.$value.'" id="'.$id.'" placeholder="'.$label.'" type="password">
                </div>';
}
function selectdom($con,$sql,$name,$id,$label,$value=0) {
  if ($sql>'') {$res = $con->query($sql) or die($sql);}
    echo '<div class="form-group">';
    if ($label!='') echo '<label for="'.$id.'">'.$label.'</label><br />';
    echo '<select class="form-control single-select select2" name="'.$name.'" id="'.$id.'" style="width: 100%">';
    if ($sql>'') {
      if (!isset($cc)) {
        $cc = $c;
      }
      while (list($c,$n)=mysqli_fetch_row($res)) {
        echo "<option value='$c'>$n</option>";
      }
    }
  echo '</select></div>';
  if ($value>0) {
    echo '<script>$("#'.$id.'").val("'.$value.'");</script>';
  } else {
?>
<script>
$('#<?=$id?>').val(<?=$cc?>);
</script>
<?php
  }
}
function selectdomaction($con,$sql,$name,$id,$label,$value=0,$action='') {
  if ($sql>'') {$res = $con->query($sql) or die($sql);}
    echo '<div class="form-group">';
    if ($label!='') echo '<label for="'.$id.'">'.$label.'</label><br />';
    echo '<select class="form-control single-select" name="'.$name.'" id="'.$id.'" '.$action.' style="width: 100%">';
    if ($sql>'') {
      if (!isset($cc)) {
        $cc = $c;
      }
      while (list($c,$n)=mysqli_fetch_row($res)) {
        echo "<option value='$c'>$n</option>";
      }
    }
  echo '</select></div>';
  if ($value>0) {
    echo '<script>$("#'.$id.'").val("'.$value.'");</script>';
  } else {
?>
<script>
$('#<?=$id?>').val(<?=$cc?>);
</script>
<?php
  }
}
function selectmultidom($con,$sql,$name,$id,$label,$value=0) {
  if ($sql>'') {$res = $con->query($sql) or die($sql);}
    echo '<div class="form-group">';
    if ($label!='') echo '<label for="'.$id.'">'.$label.'</label><br />';
    echo '<select class="form-control select2" multiple name="'.$name.'" id="'.$id.'" style="width: 100%">';
    if ($sql>'') {
      if (!isset($cc)) {
        $cc = $c;
      }
      while (list($c,$n)=mysqli_fetch_row($res)) {
        echo "<option value='$c'>$n</option>";
      }
    }
  echo '</select></div>';
  if ($value>0) {
    echo '<script>$("#'.$id.'").val("'.$value.'");</script>';
  } else {
?>
<script>
$('#<?=$id?>').val(<?=$cc?>);
</script>
<?php
  }
}
function selectdomtable($con,$sql,$name,$id,$label,$value) {
  if ($sql>'') {$res = $con->query($sql) or die($sql);}
    echo '<select class="form-control" name="'.$name.'" id="'.$id.'" style="width: 100%">';
    if ($sql>'') {
//      while (list($c,$n)=$res->fetch_row()) {
      while (list($c,$n)=mysqli_fetch_row($res)) {
        echo "<option value='$c'>$n</option>";
      }
    }
  echo '</select>';
  if ($value!=0) {
    echo '<script>$("#'.$id.'").val("'.$value.'");</script>';
  } else {
?>
<script>
$('#<?=$id?>').prepend( $('<option value="0" selected>Выберите</option>'));
</script>
<?php
  }
}
function selectdommin($con,$sql,$name,$id,$label,$value) {
  if ($sql>'') {$res = $con->query($sql) or die($sql);}
    echo '<div class="form-group"><label for="'.$id.'">'.$label.'</label><br />';
    echo '<select  name="'.$name.'" id="'.$id.'" style="width: 100%">';
    if ($sql>'') {
//      while (list($c,$n)=$res->fetch_row()) {
      while (list($c,$n)=mysqli_fetch_row($res)) {
        echo "<option value='$c'>$n</option>";
      }
    }
  echo '</select></div>';
  echo '<script>$("#'.$id.'").val("'.$value.'");</script>';
}
function selectarraydom($con,$data,$name,$id,$label,$value,$disabled) {
    echo '<div class="form-group"><label for="'.$id.'">'.$label.'</label><br />';
    echo '<select class="form-control" name="'.$name.'" id="'.$id.'" style="width: 100%" '.$disabled.'>';
      for ($i=0;$i<count($data['key']);$i++) {
        echo "<option value='".$data['key'][$i]."'>".$data['name'][$i]."</option>";
      }
  echo '</select></div>';
  echo '<script>$("#'.$id.'").val("'.$value.'");</script>';
}
function selectarraydommin($con,$data,$name,$id,$label,$value,$disabled) {
    echo '<div class="form-group"><label for="'.$id.'">'.$label.'</label><br />';
    echo '<select name="'.$name.'" id="'.$id.'" style="width: 100%" '.$disabled.'>';
      for ($i=0;$i<count($data['key']);$i++) {
        echo "<option value='".$data['key'][$i]."'>".$data['name'][$i]."</option>";
      }
  echo '</select></div>';
  echo '<script>$("#'.$id.'").val("'.$value.'");</script>';
}

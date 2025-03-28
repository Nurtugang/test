<?php
//echo $_SESSION['personid'];
//if (!($_SESSION['personid']==3665 || $_SESSION['personid']==566 || $_SESSION['personid']==2395)) exit;

$sql = "select oid,status,dates,trud,ecol,kolday,zpersonid from otpusks where status in (0,1) and personid=".$_SESSION['personid'];
$res = $condocs->query($sql);
if ($res->num_rows==0) {
    $sqlins = "insert into otpusks(status,dates,personid) values(0,now(),".$_SESSION['personid'].")";
    $condocs->query($sqlins);
    $res = $condocs->query($sql);
}
list($oid,$status,$dates,$trud,$ecol,$kolday,$zpersonid) = mysqli_fetch_row($res);
if ($trud==1) {
    $ch1 = 'checked=""';
} else {
    $ch1 = '';
}
if ($ecol==1) {
    $ch2 = 'checked=""';
} else {
    $ch2 = '';
}
if ($fin3==1) {
    $ch3 = 'checked=""';
} else {
    $ch3 = '';
}
switch ($_SESSION['subdivision_type']) {
    case '1':
        $sql = "select group_concat(dean) from structural_subdivision where pre=".$_SESSION['idpodr']." and subdivision_type=1";
        $res = $con->query($sql);
        list($d) = mysqli_fetch_row($res);
        if ($d=='') {
            $sqltutor = "SELECT
            tutors.TutorID,
            CONCAT(tutors.lastname, ' ', LEFT(tutors.firstname, 1), '.', LEFT(tutors.patronymic, 1), '.') AS expr1
            FROM tutor_structuralsubdivision
            INNER JOIN structural_subdivision
                ON tutor_structuralsubdivision.subdivisionid = structural_subdivision.id
            INNER JOIN tutors
                ON tutors.TutorID = tutor_structuralsubdivision.TutorID
            WHERE tutor_structuralsubdivision.deleted <> 1
            AND structural_subdivision.id = ".$_SESSION['idpodr']."
            AND tutor_structuralsubdivision.type = 1";
        } else {
            $sqltutor = "SELECT
  tutors.TutorID,
  CONCAT(tutors.lastname, ' ', LEFT(tutors.firstname, 1), '.', LEFT(tutors.patronymic, 1), '.') AS expr1
FROM tutor_structuralsubdivision
  INNER JOIN structural_subdivision
    ON tutor_structuralsubdivision.subdivisionid = structural_subdivision.id
  INNER JOIN tutors
    ON tutors.TutorID = tutor_structuralsubdivision.TutorID
    AND tutors.TutorID = structural_subdivision.dean
WHERE tutor_structuralsubdivision.deleted <> 1
AND tutors.tutorid IN ($d)
AND tutor_structuralsubdivision.type = 1";
        }
    break;
    case '2':
        $sqltutor = "SELECT
  tutors.TutorID,
  CONCAT(tutors.lastname, ' ', tutors.firstname, ' ', tutors.patronymic) AS expr1
FROM tutor_cafedra
  INNER JOIN tutors
    ON tutor_cafedra.tutorID = tutors.TutorID
  INNER JOIN cafedras
    ON cafedras.cafedraID = tutor_cafedra.cafedraid
WHERE tutors.TutorID <> ".$_SESSION['personid']."
AND tutor_cafedra.type = 0
AND tutor_cafedra.deleted <> 1
AND cafedras.FacultyID = ".$_SESSION['faculty_cafedra_id']."
ORDER BY tutors.lastname, tutors.firstname, tutors.patronymic";
    break;
    case '3':
        $sqltutor = "SELECT
  tutors.TutorID,
  concat(tutors.lastname,' ',tutors.firstname,' ',tutors.patronymic)
FROM tutor_cafedra
  INNER JOIN tutors
    ON tutor_cafedra.tutorID = tutors.TutorID
WHERE tutor_cafedra.cafedraid = ".$_SESSION['faculty_cafedra_id']."
AND tutors.TutorID <> ".$_SESSION['personid']."
AND tutor_cafedra.type = 0
AND tutor_cafedra.deleted <> 1
ORDER BY tutors.lastname, tutors.firstname, tutors.patronymic";
    break;
            
    default:
        # code...
        break;
}
//echo $status;
?>
<div class="card">
    <div class="card-body">
        <h4>Заявление на отпуск</h4>
        <hr />
        <div class="row">
            <div class="col-4">
                <h5>Дата начала отпуска</h5>
                <input type="date" class="form-control" id="dates" value="<?=$dates?>" />
            </div>
            <div class="col-4">
                <h5>Виды отпуска</h5>
                <div class="form-check">
                    <input class="form-check-input" onclick="chsave('trud')" type="checkbox" id="trud" <?=$ch1?>>
                    <label class="form-check-label" for="trud">Трудовой</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" onclick="chsave('ecol')" type="checkbox" id="ecol" <?=$ch2?>>
                    <label class="form-check-label" for="ecol">Экологический</label>
                </div>
            </div>
            <div class="col-4">
                <h5>Виды отпуска</h5><small>0 для получения полного отпуска</small>
                <input type="text" value="<?=$kolday?>" class="form-control" id="kolday" />
            </div>
        </div>
        <br />

        <hr />
        <?php
        if (isset($sqltutor)) {
            selectdom($con,$sqltutor,'ztutorid','ztutorid','Замещение',$zpersonid);
        }
        ?>
        <div id="komplacediv"></div>
        <?php

        if ($status==10) {
        ?>
        <button class="btn btn-outline-primary float-left" id="savekoms">Сохранить заявление</button>
        <?php
        }
        if ($status==0) {
        ?>
        <button class="btn btn-outline-primary float-left" id="viewotp">Просмотреть</button>
        <?php
        }
        ?>
    </div>
</div>
<div id="script"></div>
<script>
    $('#ztutorid').change(function(){
        $.post( 'modules.php?pa=docs-newdoc-new', { 
            save107: "save", 
            field: 'zpersonid',
            keyfield: 'oid',
            keydata: <?=$oid?>,
            table: 'otpusks',
            data: $(this).val()
        })
            .done(function( data ) {
                $('#script').html(data)
            });
    })
    function chsave(fin) {
        if ($('#'+fin).is(':checked')) {
            var ch = 1;
        } else {
            var ch = 0;
        }
        $.post( 'modules.php?pa=docs-newdoc-new', { 
            save107: "save", 
            field: fin,
            keyfield: 'oid',
            keydata: <?=$oid?>,
            table: 'otpusks',
            data: ch
        })
            .done(function( data ) {
                $('#script').html(data)
            });
    }
    if ($('#finid').val()=='016') {
        $('#fin').show();
    }
    $('#viewotp').click(function(){
        $('#FullScreenModal').modal('show');
        $('#modalBody').load('modules.php?pa=docs-docview-new&doctypeid=108&oid=<?=$oid?>')
    })
    $('#savekoms').click(function(){
        $.post( 'modules.php?pa=docs-newdoc-new', { 
            save107: "save", 
            field: 'status',
            keyfield: 'oid',
            keydata: <?=$oid?>,
            table: 'otpusks',
            data: 1
        })
            .done(function( data ) {
                $('#script').html(data)
            });
    })
    $('#dates').change(function(){
        $('#rdates').val($(this).val());
        $.post( 'modules.php?pa=docs-newdoc-new', { 
            save107: "save", 
            field: 'dates',
            keyfield: 'oid',
            keydata: <?=$oid?>,
            table: 'otpusks',
            data: $(this).val()
        })
            .done(function( data ) {
                $('#script').html(data)
            });
    })






</script>
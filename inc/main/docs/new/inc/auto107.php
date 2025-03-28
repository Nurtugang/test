<?php
//echo $_SESSION['personid'];
//if (!($_SESSION['personid']==3665 || $_SESSION['personid']==566 || $_SESSION['personid']==2395)) exit;
$sql = "select komid,status,dates,datee,header,finid,finpay,fin1,fin2,fin3,zpersonid from koms where status in (0,1) and personid=".$_SESSION['personid'];
$res = $condocs->query($sql);
if ($res->num_rows==0) {
    $sqlins = "insert into koms(status,dates,datee,personid,header,finid) values(0,now(),now(),".$_SESSION['personid'].",'','008')";
    $condocs->query($sqlins);
    $res = $condocs->query($sql);
}
list($komid,$status,$dates,$datee,$header,$finid,$finpay,$fin1,$fin2,$fin3,$zpersonid) = mysqli_fetch_row($res);
if ($fin1==1) {
    $ch1 = 'checked=""';
} else {
    $ch1 = '';
}
if ($fin2==1) {
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
        <h4>Іссапар туралы өтініш</h4>
        <hr />
        <div class="row">
            <div class="col-4">
            <h5>Іссапар мерзімі</h5>
                <div class="row">
                    <div class="col-6">
                    <input type="date" class="form-control" id="dates" value="<?=$dates?>" />
                    </div>
                    <div class="col-6">
                    <input type="date" class="form-control" id="datee" value="<?=$datee?>" />
                    </div>
                </div>
            </div>
            <div class="col-8">
                <?php
                $sql = "select id,namekz from komfintypes where tutorid>=0 order by id";
                selectdom($condocs,$sql,'finid','finid','Источник финансирования',$finid);
                ?>
                <div style="display:none" id="fin">
                    <div class="form-check">
                        <input class="form-check-input" onclick="chsave('fin1')" type="checkbox" id="fin1" <?=$ch1?>>
                        <label class="form-check-label" for="fin1">тәуліктік шығындар</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" onclick="chsave('fin2')" type="checkbox" id="fin2" <?=$ch2?>>
                        <label class="form-check-label" for="fin2">жол шығындары</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" onclick="chsave('fin3')" type="checkbox" id="fin3" <?=$ch3?>>
                        <label class="form-check-label" for="fin3">жатын орын шығындары</label>
                    </div>
                </div>
            </div>

        </div>
        <br />
        <?=textareadom('header','header','Іссапар мақсаты',$header)?>
        <hr />
        <?php
        if (isset($sqltutor)) {
            selectdom($con,$sqltutor,'ztutorid','ztutorid','Замещение',$zpersonid);
        }
        ?>
        <h4>Іссапар бағыты</h4>
        <div style="display:none;" id="routeadddiv">
            <div class="row">
                <div class="col-3">
                <?php
                $sql = "select id,nameru from center_countries";
                selectdom($con,$sql,'country','country','Страна',113);
                ?>
                </div>
                <div class="col-9">
                    <div id="kaz">
                        <?php $sql = "select code,full_nameru from center_kato where deep in(0,2,3)";?>
                        <?php selectdom($con,$sql,'city','city','Населенный пункт назначения (като)',$city);?>
                    </div>
                    <div id="nokaz" style="display:none;">
                        <label class="form-label" for="cityi">Населенный пункт назначения</label>
                        <input type="text" class="form-control" id="cityi" value="<?=$city?>" />
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label" for="uchr">Организация назначения</label>
                    <input type="text" class="form-control" id="uchr" value="<?=$uchr?>" />
                </div>
                <div class="col-3">
                <label class="form-label" for=""></label>
                <input type="date" class="form-control" id="rdates" value="<?=$dates?>" />
                </div>
                <div class="col-3">
                <label class="form-label" for=""></label>
                <input type="date" class="form-control" id="rdatee" value="<?=$datee?>" />
                </div>
            </div>
            <button class="btn btn-outline-success float-right" id="addroute">Добавить</button>
            <hr />
        </div>
        <div id="komplacediv"></div>
        <?php
        if ($status==0) {
        ?>
        <button class="btn btn-outline-primary float-right" id="addroutes">Добавить точку маршрута</button>
        <?php
        }
        if ($status==0) {
        ?>
        <button class="btn btn-outline-primary float-left" id="savekoms">Сохранить заявление</button>
        <?php
        }
        if ($status==1) {
        ?>
        <button class="btn btn-outline-primary float-left" id="viewkoms">Просмотреть</button>
        <?php
        }
        ?>
    </div>
</div>
<div id="script"></div>
<script>
    $('#fin1').click(function(){

        console.log('fin1 = '+$('#fin1').is(':checked'))
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
            keyfield: 'komid',
            keydata: <?=$komid?>,
            table: 'koms',
            data: ch
        })
            .done(function( data ) {
                $('#script').html(data)
            });
    }
    if ($('#finid').val()=='016') {
        $('#fin').show();
    }
    $('#viewkoms').click(function(){
        $('#FullScreenModal').modal('show');
        $('#modalBody').load('modules.php?pa=docs-docview-new&doctypeid=107&komid=<?=$komid?>')
    })
    $('#savekoms').click(function(){
        $.post( 'modules.php?pa=docs-newdoc-new', { 
            save107: "save", 
            field: 'status',
            keyfield: 'komid',
            keydata: <?=$komid?>,
            table: 'koms',
            data: 1
        })
            .done(function( data ) {
                $('#script').html(data)
            });
    })
    $('#ztutorid').change(function(){
        $.post( 'modules.php?pa=docs-newdoc-new', { 
            save107: "save", 
            field: 'zpersonid',
            keyfield: 'komid',
            keydata: <?=$komid?>,
            table: 'koms',
            data: $(this).val()
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
            keyfield: 'komid',
            keydata: <?=$komid?>,
            table: 'koms',
            data: $(this).val()
        })
            .done(function( data ) {
                $('#script').html(data)
            });
    })
    $('#datee').change(function(){
        $('#rdatee').val($(this).val());
        $.post( 'modules.php?pa=docs-newdoc-new', { 
            save107: "save", 
            field: 'datee',
            keyfield: 'komid',
            keydata: <?=$komid?>,
            table: 'koms',
            data: $(this).val()
        })
            .done(function( data ) {
                $('#script').html(data)
            });
    })
    $('#finid').change(function(){
        if ($('#finid').val()=='016') {
            $('#fin').show();
        } else {
            $('#fin').hide();
        }
        $.post( 'modules.php?pa=docs-newdoc-new', { 
            save107: "save", 
            field: 'finid',
            keyfield: 'komid',
            keydata: <?=$komid?>,
            table: 'koms',
            data: $(this).val()
        })
            .done(function( data ) {
                $('#script').html(data)
            });
    })
    $('#header').change(function(){
        $.post( 'modules.php?pa=docs-newdoc-new', { 
            save107: "save", 
            field: 'header',
            keyfield: 'komid',
            keydata: <?=$komid?>,
            table: 'koms',
            data: $(this).val()
        })
            .done(function( data ) {
                $('#script').html(data)
            });
    })
    $('#addroutes').click(function(){
        $('#uchr').val('');
        $('#country').val(113);
        $('#routeadddiv').show();
    })
    $('#addroute').click(function(){
        if ($('#country').val()==113) {
            var city = $('#city').val()
        } else {
            var city = $('#cityi').val()
        }
        $.post( "modules.php?pa=docs-komplacetable-new&komid=<?=$komid?>", { 
            country: $('#country').val(), 
            city: city,
            uchr: $('#uchr').val(),
            rdates: $('#rdates').val(),
            rdatee: $('#rdatee').val(),
            save: "router"
        })
            .done(function( data ) {
                $('#routeadddiv').hide();
                $('#komplacediv').html(data);
            });
        
    })
    $('#city').select2();$('#country').select2();
    $('#country').change(function(){
        if ($(this).val()==113) {
            $('#kaz').show();
            $('#nokaz').hide();
        } else {
            $('#nokaz').show();
            $('#kaz').hide();
        }
    })
    $('#komplacediv').load('modules.php?pa=docs-komplacetable-new&komid=<?=$komid?>');
</script>
<?php
$sql = "select oid,status,date_format(dates,'%d.%m.%Y'),trud,ecol,kolday,year(dates),zpersonid from otpusks where oid=$oid";
$res = $condocs->query($sql);
list($oid,$status,$dates,$trud,$ecol,$kolday,$year,$zpersonid) = mysqli_fetch_row($res);

$sign[3] = 0;$sign[2] = 0;
$sql = "select group_concat(dean) from structural_subdivision where id in (2,126)";
$res = $con->query($sql);
list($tid) = mysqli_fetch_row($res);
$sign[3] = $tid;
switch ($_SESSION['subdivision_type']) {
    case '1':
        $podrid = $_SESSION['idpodr'];
        $sql = "SELECT
                    tutor_positions.NameKZ
                    FROM tutor_positions
                    INNER JOIN tutor_structuralsubdivision
                        ON tutor_positions.ID = tutor_structuralsubdivision.`position`
                    WHERE tutor_structuralsubdivision.deleted <> 1
                    AND tutor_structuralsubdivision.TutorID = ".$_SESSION['personid']."
                    AND tutor_structuralsubdivision.type = 1";
        $res = $con->query($sql);
        list($doljn) = mysqli_fetch_row($res);
        $sql = "select dean,pre,namekz from structural_subdivision where id=$podrid";
        $res = $con->query($sql);
        list($dean,$pre,$name) = mysqli_fetch_row($res);
        if ($dean!=$_SESSION['personid']) {
            if ($pre==0) {
                $sign[2] = $dean;
                //echo $sql;
            } else {
                $sign[3] .= ','.$dean;
            }
        }
        //echo $sql;
        if ($pre!=0) {
            $sql = "select dean,pre,namekz from structural_subdivision where id=$pre";
            $res = $con->query($sql);
            list($dean,$pre,$namekz) = mysqli_fetch_row($res);
            if ($pre==0) {
                $sign[2] = $dean;
                $signd[2] = $namekz;
                //echo $sql;
            } else {
                $sign[3] .= ','.$dean;
            }
            //echo $sql;
            if ($pre!=0) {
                $sql = "select dean,pre,namekz from structural_subdivision where id=$pre";
                $res = $con->query($sql);
                list($dean,$pre,$namekz) = mysqli_fetch_row($res);
                if ($pre==0) {
                    $sign[2] = $dean;
                    $signd[2] = $namekz;
                    //echo $sign[2];
                } else {
                    $sign[3] .= ','.$dean;
                }
                //echo $sql;
            }
        }
        break;
    case '2':
        $sql = "select dean,pre,namekz from structural_subdivision where id=100";
        $res = $con->query($sql);
        list($dean,$pre,$signd[2]) = mysqli_fetch_row($res);
        $sign[2] = $dean;
        break;
    case '3':
        $sql = "select dean,pre,namekz from structural_subdivision where id=100";
        $res = $con->query($sql);
        list($dean,$pre,$signd[2]) = mysqli_fetch_row($res);
        $sign[2] = $dean;
        $sql = "SELECT
            faculties.facultyDean,cafedras.cafedraNameKZ
            FROM cafedras
            INNER JOIN faculties
                ON cafedras.FacultyID = faculties.FacultyID
            WHERE cafedras.cafedraID = ".$_SESSION['faculty_cafedra_id'];
            $res = $con->query($sql);
            list($dean,$name) = mysqli_fetch_row($res);
            $sign[3] .= ','.$dean;
        break;
    default:
        //echo '<pre>';
        //print_r($_SESSION);
        //echo '</pre>';
        break;
}
//echo $sign[3];
if ($_SESSION['idpodr']==0) {
    $sql = "select dean,pre from structural_subdivision where id=100";
    $res = $con->query($sql);
    list($dean,$pre) = mysqli_fetch_row($res);
    //$sign[2] = $dean;
    if ($_SESSION['cafman']!=$_SESSION['personid']) {
        $sign[3] .= ','.$_SESSION['cafman'];
    }
    if ($_SESSION['decan']!=$_SESSION['personid']) {
        $sign[3] .= ','.$_SESSION['decan'];
    }
}
/*$sql = "select tutorid from komfintypes where id='016'";
$res = $condocs->query($sql);
list($s) = mysqli_fetch_row($res);
$sign[3] .= ','.$s;*/
$sql = "select dean from structural_subdivision where id=129";
$res = $con->query($sql);
list($s) = mysqli_fetch_row($res);
$sign[3] .= ','.$s;
if ($zpersonid>0) {
    $sign[3] .= ','.$zpersonid;
}
function fiokz($tutorid,$con) {
    $sql = "select concat(lastname,' ',left(firstname,1),'.',left(patronymic,1),'.') from tutors where tutorid=$tutorid";
    $res = $con->query($sql);
    list($fio) = mysqli_fetch_row($res);
    return $fio;
} 
?>
<div class="row">
    <div class="col-8">
        <div id="document">
            <table width="800">
                <tr>
                    <td width="500"></td>
                    <td width="300">
                    «Семей қаласының Шәкәрім<br />
            атындағы университеті» КеАҚ<br />
            Басқарма төрағасы – ректор<br />
            Д.Орынбековке<br />
            <?=$name?><br />
            <?=$doljn?><br />
            <?=$_SESSION['fio']?>

                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;">
                        <br /><br /><b>ӨТІНІШ</b><br /><br />
                    </td>
                </tr>
            </table>
            <?php
            if ($trud==1) {
                $t = 'жұмыс істеген уақытым үшін еңбек  демалысымды';
            }
            if ($ecol==1) {
                $e = $year.' жылғы экологиялық демалысымды';
            }
            if ($ecol==1 && $trud==1) {
                $j = ' және ';
            }
            ?>
            Маған  <?=$dates?> бастап <?=$t?> <?=$j?> <?=$e?> беруіңізді сұраймын.<br />
            <?php
            if ($zpersonid>0) {
            ?>
            Демалыс уақытында басшылық міндетін <?=fiokz($zpersonid,$con)?> жүктеуді сұраймын.
            <?php
            }
            ?>
            <br />
            <br />
            <br />
            <table width="800">
                <tr>
                    <td width="600">
                        <?=$signd[2]?>
                    </td>
                    <td>
                        <?=fiokz($sign[2],$con)?>
                    </td>
                </tr>
                <?php
                //echo $sign[3];
                $ss = explode(',',$sign[3]);
                for ($i=0;$i<count($ss);$i++) {
                    $sql = "SELECT
                        tutor_positions.NameKZ,
                        structural_subdivision.namekz,concat(lastname,' ',left(firstname,1),'.',left(patronymic,1),'.')
                        FROM tutor_positions
                        INNER JOIN tutor_structuralsubdivision
                            ON tutor_positions.ID = tutor_structuralsubdivision.`position`
                        INNER JOIN structural_subdivision
                            ON tutor_structuralsubdivision.subdivisionid = structural_subdivision.id
                        INNER JOIN tutors
                            ON tutors.TutorID = tutor_structuralsubdivision.TutorID
                        WHERE tutor_structuralsubdivision.deleted <> 1
                        AND tutor_structuralsubdivision.TutorID = $ss[$i]
                        AND tutor_structuralsubdivision.type = 1";
                    $res = $con->query($sql);
                    if ($res->num_rows==0) {
                        echo $sql.'<br />';
                    }
                    while (list($poskz,$sdkz,$fio) = mysqli_fetch_row($res)) {
                ?>
                <tr>
                    <td>
                        <?=$sdkz?>, <?=$poskz?>
                    </td>
                    <td>
                        <?=$fio?>
                    </td>
                </tr>
                <?php
                    }
                }
                ?>
            </table>
        </div>
    </div>
    <div class="col-4">
        <h3>Маршрут заявления</h3>
        <?php
        $sql = "select rectorID from university";
        $res = $con->query($sql);
        list($rectorid) = mysqli_fetch_row($res);
        $sql = "select lastname,firstname,patronymic from tutors where tutorid=$rectorid";
        $res = $con->query($sql);
        list($s,$n,$p) = mysqli_fetch_row($res);
        $person = 'Председатель правления, ректор '."$s $n $p";
        ?>
        <label class="form-label">Кому:</label>
        <select class="form-control" id="sign1">
            <option value="<?=$rectorid?>"><?=$person?></option>
        </select>
        <label class="form-label">Окончательное согласование:</label>
        <select class="form-control" id="sign2">
            <?php
            $sql = "select tutorid,lastname,firstname,patronymic from tutors where tutorid in($sign[2])";
            $res = $con->query($sql);
            while (list($tid,$s,$n,$p) = mysqli_fetch_row($res)) {
                $fio = "$s $n $p";
            ?>
            <option value="<?=$tid?>" selected><?=$fio?></option>
            <?php
            }
            ?>
        </select>
        <label class="form-label">Согласование:</label>
        <select class="multiple-select" multiple="multiple" id="sign3">
            <?php
            $sql = "select tutorid,lastname,firstname,patronymic from tutors where tutorid in($sign[3])";
            $res = $con->query($sql);
            while (list($tid,$s,$n,$p) = mysqli_fetch_row($res)) {
                $fio = "$s $n $p";
            ?>
            <option value="<?=$tid?>" selected><?=$fio?></option>
            <?php
            }
            /*$sql = "select group_concat(dean) from structural_subdivision where id in (2,126)";
            $res = $con->query($sql);
            list($tid) = mysqli_fetch_row($res);
            $sql = "select tutorid,lastname,firstname,patronymic from tutors where tutorid in ($tid)";
            $res = $con->query($sql);
            while (list($tid,$s,$n,$p) = mysqli_fetch_row($res)) {
            $fio = "$s $n $p";
            ?>
            <option value="<?=$tid?>" selected><?=$fio?></option>
            <?php
            }*/
            ?>
        </select>
    </div>
</div>




<button class="btn btn-outline-success" id="createbutton">Подписать</button>
<link href="assets/plugins/select2/css/select2.min.css" rel="stylesheet"/>
<link href="assets/plugins/select2/css/select2-bootstrap4.css" rel="stylesheet"/>
<script src="assets/plugins/ecp/js/ncalayer.js"></script>
<script src="assets/plugins/ecp/js/process-ncalayer-calls.js"></script>
<script src="assets/plugins/select2/js/select2.min.js"></script>
<input type="text" class="form-control" id="inDocB64" />
<input type="text" class="form-control" id="signature" />
<script>
    $('.multiple-select').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
        });
</script>


    <script type="text/javascript">
        function createCAdESFromBase64CallLogin() {
            var selectedStorage = 'PKCS12';//$('#storageSelect').val();
            var flag = false;
            var base64ToSign = '';
            if ($("#inDocB64").text() != '') {
                base64ToSign = $("#inDocB64div").text();
            } else {
            }
            base64ToSign = $("#inDocB64").val();
            if (base64ToSign !== null && base64ToSign !== "") {
                createCAdESFromBase64(selectedStorage, "SIGNATURE", base64ToSign, flag, "createCAdESFromBase64BackLogin");
                //        createCMSSignatureFromBase64(selectedStorage, "SIGNATURE", base64ToSign, flag, "createCAdESFromBase64BackLogin");
            } else {
                alert("Нет данных для подписи!");
            }
        }
        function createCAdESFromBase64BackLogin(result) {
            if (result['code'] === "500") {
                alert(result['message']);
                //$.unblockUI();
            } else if (result['code'] === "200") {
                var res = result['responseObject'];
                $('#signature').val(res);
                //data.append('parameter', $('#parameter').val());
                data.append('doctypeid', "108");
                data.append('docname', 'Заявление на отпуск <?=$_SESSION['lastname']?> <?=$_SESSION['firstname']?> <?=$_SESSION['patronymic']?>');
                //if (typeof ($('#todata').val()) != "undefined" && $('#todata').val() !== null) {
                    data.append('todata', $('#sign1').val());
                //}
                //if (typeof ($('#todatals1').val()) != "undefined" && $('#todatals1').val() !== null) {
                    data.append('todatals1', $('#sign2').val());
                //}
                //if (typeof ($('#todatals2').val()) != "undefined" && $('#todatals2').val() !== null) {
                    data.append('todatals2', $('#sign3').val());
                //}
                //if (typeof ($('#todatals3').val()) != "undefined" && $('#todatals3').val() !== null) {
                    //data.append('todatals3', $('#todatals3').val());
                //}
                data.append('cms', $('#signature').val());
                data.append('file64',$("#inDocB64").val());
                data.append('filename','document108.html');
                var url = 'modules.php?pa=docs-register3-new';
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    cache: false,
                    dataType: 'text',
                    processData: false,
                    // Не обрабатываем файлы (Don't process the files)
                    contentType: false,
                    // Так jQuery скажет серверу что это строковой запрос
                    success: function(data) {
                        if (data != '') {
                            //$('#Modal').modal('hide');
                            //$('#main').load('modules.php?pa=docs-index-sent');
                            //alert(data);
                            $('#rez').html(data);
                        } else {
                            //$('#main').html('<h3><b>Документ подписан, перейдите в папку "Исходящие"</b></h3>');
                        }
                        //$('#rez').html(data);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('ОШИБКИ AJAX запроса: ' + textStatus + errorThrown);
                    }
                });
            }
        }
        function arrayBufferToBase64(buffer) {
            var binary = '';
            var bytes = new Uint8Array(buffer);
            var len = bytes.byteLength;
            for (var i = 0; i < len; i++) {
                binary += String.fromCharCode(bytes[i]);
            }
            return window.btoa(binary);
        }

        var data = new FormData();



        $('#createbutton').click(function(event) {
            event.stopPropagation();
            // Остановка происходящего
            event.preventDefault();
            // Полная остановка происходящего
            createCAdESFromBase64CallLogin();
        });
        var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/\r\n/g,"\n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}

        $('#inDocB64').val(Base64.encode($('#document').html()));
    </script>
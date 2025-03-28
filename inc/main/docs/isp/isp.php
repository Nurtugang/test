<?php
if (isset($action)) {
    if ($action=='nom') {
        $sql = "update documentsignlists set nomenclatureid=$nomid,podrid=".$_SESSION['idpodr']." where documentid=$did and signid=$signid";
        //echo $sql;
        $condocs->query($sql);
    }
}
if (!isset($_GET['startdate'])) {
    $startdate = date("Y-m-d",strtotime("-15 day"));
}
?>
<div class="card card-outline card-default">
    <div class="card-header">
        <h3 class="card-title">Входящие документы</h3>
    </div>
    <div class="card-body" id="nosigndiv">
        <div class="row">
            <div class="col-lg-6">
                <?=inputdatedom('startdate','startdate','Показать с:',$startdate)?>
            </div>
            <div class="col-lg-6">
                <?=inputdatedom('enddate','enddate','Показать по:','')?>
            </div>
        </div>
        <div id="pdfdiv"></div>
        <?php
        $sqlstart = '';$sqlend = '';
        if (isset($startdate)) {
            $sqlstart = " and documents.createdate>='$startdate'";
        }
        if (isset($enddate)) {
            $sqlend = " and documents.createdate<='$enddate'";
        }
        ?>

        <div class="dataTables_wrapper dt-bootstrap4">
            <table class="table table-bordered table-hover dataTable dtr-inline display compact" role="grid" id="tabledata">
                <thead>
                <tr role="row">
                    <th>№</th>
                    <th>Наименование документа</th>
                    <th>Исполнение</th>
                    <th>Дата загрузки</th>
                    <th>Документ</th>
                    <th>Просмотр</th>
                    <th>SigexID</th>
                    <th>Номенклатура</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sqlnom = "select nomenclatureid,concat(code,'-',nameru) from nomenclature where podr = ".$_SESSION['idpodr'];
                $sqlnom = "select nomenclatureid,concat(code,'-',nameru) from nomenclature where podr = ".$_SESSION['idpodr']." or str='all' or str='".$_SESSION['role']."' order by nomenclatureid";
                //die($sqlnom);
                $sql = "SELECT documents.author,documents.documentid,documents.name,date_format(documents.createdate,'%d.%m.%Y'),documents.sigexid,documentsignlists.nomenclatureid,documentsignlists.signid FROM documentsignlists INNER JOIN documents ON documentsignlists.documentid = documents.documentid WHERE documentsignlists.status=2 and accepted = 0 and  documentsignlists.type = 4 $sqlstart $sqlend AND documentsignlists.personid = ".$_SESSION['personid'];
                $res = $condocs->query($sql);
                //echo $sql;
                while (list($author,$documentid,$name,$createdate,$sigexid,$nomenclatureid,$signid) = mysqli_fetch_row($res)) {
                    $url = '<a href="download.php?documentidext='.$documentid.'" target="_blank" class="btn btn-block btn-outline-primary btn-xs">Скачать</a>';
                    $Tutor = new Tutor();
                    $Tutor->con = $con;
                    $Tutor->tutorid = $author;
                    $sigexid = '<a href="https://sigex.kz/show/?id='.$sigexid.'" target="_blank">Проверить</a>';
                    ?>

                    <tr role="row" class="odd" data-widget="expandable-table" aria-expanded="false">
                        <td><?=$documentid?></td>
                        <td><?=$name?></td>
                        <td><?=$Tutor->concatfio()?></td>
                        <td><?=$createdate?></td>
                        <td><?=$url?></td>
                        <td>
                            <button type="button" class="btn btn-block btn-outline-primary btn-xs" onclick="pdfopen(<?=$documentid?>)">Просмотреть</button>
                        </td>
                        <td><?=$sigexid?></td>
                        <td>
                            <?=selectdomtable($condocs,$sqlnom,'nom-'.$documentid.'-'.$signid,'nom-'.$documentid.'-'.$signid,'',$nomenclatureid)?>
                        </td>
                    </tr>

                    <?php

                }

                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $('#startdate').change(function(event) {
        $('#main').load('modules.php?pa=docs-isp-isp&startdate='+$('#startdate').val()+'&enddate='+$('#enddate').val());
    });
    $('#enddate').change(function(event) {
        $('#main').load('modules.php?pa=docs-isp-isp&startdate='+$('#startdate').val()+'&enddate='+$('#enddate').val());
    });
    $('#tabledata').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Russian.json"
        },
        "scrollCollapse": true,
        "paging":         true,
        "order": [[ 0, 'desc' ]],
        "lengthMenu": [[2, 3, 5, -1], [2, 3, 5, "All"]]
    });
    $('#tabledata tbody').on('change', 'select', function(event) {
        event.preventDefault();
        var id = $(this).attr('id').split('-');
        if (id[0]=='nom') {
            $('#main').load('modules.php?pa=docs-isp-isp&action=nom&did='+id[1]+'&nomid='+$(this).val()+'&signid='+id[2]);
        }
    });
    function docsign(documentid) {
        var h = $(window).height()-220;
        $('#nosigndiv').load('modules.php?pa=docs-isign-new&documentid='+documentid+'&h='+h);
    };
    function pdfopen(documentid) {
        var w = $(window).width()-220;
        $('#pdfdiv').load('modules.php?pa=docs-pdf-sent&documentid='+documentid)
    };


</script>
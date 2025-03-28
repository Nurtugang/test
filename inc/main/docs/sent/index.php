<?php
if (isset($delete)) {
	$sql = "update documents set status=0 where documentid=$documentid";
	$condocs->query($sql);
}
$type = 5;
?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="breadcrumb-title pe-3">Исходящие</div>
</div>
<style>
#tabledata {
  font-size: 12px;
}
</style>
        <table class="table display compact" role="grid" id="tabledata">
          <thead>
            <tr role="row">
              <th>№</th>
              <th>Наименование документа</th>
              <th>Отправитель</th>
              <th>Дата загрузки</th>
              <th>Документ</th>
              <th>Просмотр</th>
              <th>SigexID</th>
              <th>Номенклатура</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>

                                            <div class="modal fade" id="FullScreenModal" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-fullscreen">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Просмотр документа</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body" id="pdfdiv"></div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="lni lni-close"></i> Закрыть</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


<script>

    $('#tabledata').DataTable( {
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Russian.json"
        },
        "scrollCollapse": true,
        "paging":         true,
        "order": [[ 0, 'desc' ]],
        "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, "All"]],
        "ajax": 'modules.php?pa=docs-inboxjson-inbox&type=<?=$type?>'
    } );

  function pdfopen(documentid) {
    var w = $(window).width()-220;
    $('#pdfdiv').load('modules.php?pa=docs-pdf-inbox&sign=<?=$sign?>&documentid='+documentid)
  };
  function trash(documentid) {
    //var w = $(window).width()-220;
    $('#main').load('modules.php?pa=docs-index-sent&delete=delete&documentid='+documentid)
  };


</script>
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Подпись документа № <?=$documentid?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="height: 700" id="modalbody">
        <iframe width="100%" height="600" frameborder="0" src="modules.php?pa=docs-signframe-inbox&documentid=<?=$documentid?>" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="ModalClose">Close</button>
      </div>
    </div>
  </div>
<script>

</script>
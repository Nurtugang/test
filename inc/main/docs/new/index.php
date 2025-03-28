<div class="pricing-table">
                        <h6 class="mb-0 text-uppercase">Новый документ</h6>
                        <hr>
                        <div class="row row-cols-1 row-cols-lg-3">
                            <!-- Free Tier -->
                            <?php
                            $sql = "select groupid,nameru from doctypesgroups where role like '%".$_SESSION['menurole']."%' or role like '%".$_SESSION['role']."%' order by pos";
                            $resgr = $condocs->query($sql) or die($sql);
                            while (list($groupid,$grname) = mysqli_fetch_row($resgr)) {
                            ?>
                            <div class="col">
                                <div class="card mb-5 mb-lg-0">
                                    <div class="card-header bg-info py-3">
                                        <h5 class="card-title text-white text-uppercase text-center"><?=$grname?></h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush p-0">
                                        	<?php
                                        	$sql = "select doctypeid,nameru,icon from doctypes where deleted=0 and groupid=$groupid and (role like '%".$_SESSION['menurole']."%' or role like '%".$_SESSION['role']."%') order by pos";
                                        	$res = $condocs->query($sql);
                                        	while (list($doctypeid,$nameru,$icon) = mysqli_fetch_row($res)) {
                                        	?>
                                            <li class="list-group-item p-1" style="cursor:pointer;" onclick="sendMessageToFlutter('inpage#https://sdk.semgu.kz/modules.php?pa=docs-newdoc-new&doctypeid=<?=$doctypeid?>&lang=<?=$_SESSION['lang']?>')"><i class="bx bx-check me-2 font-14"></i><small><?=$nameru?> (<?=$doctypeid?>)</small></li>
                                            <?php
                                        	}
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>




<script type="text/javascript">
var userAgent = navigator.userAgent.toLowerCase();
  function sendMessageToFlutter(message) {
    if (window.FlutterChannel) {
        window.FlutterChannel.postMessage(message);
    }
  }
	function loadzayavl(doctypeid) {
    var deviceType = "Desktop";
    if (/android/.test(userAgent)) {
        deviceType = "Android";
    } else if (/iphone|ipad|ipod/.test(userAgent)) {
        deviceType = "iOS";
    }
    if (deviceType=="Desktop") {
      window.open('modules.php?pa=docs-newdoc-new&doctypeid='+doctypeid+'&lang=<?=$_SESSION['lang']?>')
    } else if (userAgent=="iOS") {
	   //sendMessageToFlutter('UPLOAD-DOCS-'+doctypeid)
     sendMessageToFlutter('https://sdk.semgu.kz/modules.php?pa=docs-newdoc-new&doctypeid='+doctypeid+'&lang=<?=$_SESSION['lang']?>')
    } else if (userAgent=="Android") {
     sendMessageToFlutter('https://sdk.semgu.kz/modules.php?pa=docs-newdoc-new&doctypeid='+doctypeid+'&lang=<?=$_SESSION['lang']?>')
    }
	 }
</script>

<button onclick=sendMessageToFlutter("https://sdo.semgu.kz/download.php?documentidext=<?=$documentid?>)">Скачать документ</button>
<?php
$sql = "select filename,dir from documents where documentid=$documentid";
$res = $condocs->query($sql);
list($filename,$dir) = mysqli_fetch_row($res);
//echo $filename;
$im = new Imagick();
$im->pingImage($datadir.$dir.$filename);
$im->setResolution(300, 300);
$pages = $im->getNumberImages();
?>
<div class="card">
    <div class="card-body">
        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
            <ol class="carousel-indicators">
                <?php
                for ($i=0;$i<$pages;$i++) {
                    $active = '';
                    if ($i==0) $active = 'active';
                ?>
                <li data-bs-target="#carouselExampleCaptions" data-bs-slide-to="<?=$i?>" class="<?=$active?>"></li>
                <?php
                }
                ?>
            </ol>
            <div class="carousel-inner">
                <?php
                for ($i=0;$i<$pages;$i++) {
                    $active = '';
                    if ($i==0) $active = 'active';
                    $im->readImage($datadir.$dir.$filename."[".$i."]");
                    $im->setImageFormat('jpg');
                    $image = base64_encode($im);
                ?>
                <div class="carousel-item <?=$active?>">
                    <img src="data:image/png;base64, <?=$image?>" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Second slide label</h5>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </a>
        </div>
    </div>
</div>
<script>
function sendMessageToFlutter(message) {
  if (window.FlutterChannel) {
      window.FlutterChannel.postMessage(message);
  }
}
</script>

<?php
include('inboxjson.php');
//echo '<pre>';print_r($data);echo '</pre>';
$typemes[1] = 'На подпись';
$typemes[2] = 'На согласование';
$typemes[3] = 'Подписано';
$typemes[4] = 'Отказано';
?>
<div class="col">
						<div class="card">
							<img src="img/edo.jpg" class="card-img-top" alt="...">
							<div class="card-body">
								<h6 class="card-title text-secondary"><?=$typemes[$type]?></h6>
							</div>
						</div>
					</div>
    <div class="card radius-10 w-100">
        <div class="card-body">

        <div class=" p-0 mb-0 ps ps--active-y">
        <?php
        for ($i=0;$i<count($data['data']);$i++) {
          $file = $_SERVER['DOCUMENT_ROOT'].'/docs/'.$data['data'][$i][8].$data['data'][$i][9];
          $finfo = finfo_open(FILEINFO_MIME_TYPE);
          $mimeType = finfo_file($finfo, $file);
          finfo_close($finfo);
          //echo "MIME-тип файла: " . $mimeType;
        ?>

            <div class="customers-list-item align-items-center border-top border-bottom p-0 cursor-pointer">
                <div class="ms-2">
                  <div class="row">
                    <div class="col-9">
                      <h6 class="mt-1" style="color:#002C52;font-size:14px;" ><?=$data['data'][$i][3]?> <?=$data['data'][$i][2]?></h6>
                      <p class="mb-0" style="color:#002C52;font-size:12px;"><?=$data['data'][$i][1]?></p>
                    </div>
                    <div class="col-3">
                      <?php if ($mimeType=='application/pdf') {?>
                      <button class="btn" style="color:#002C52" onclick="sendMessageToFlutter('https://sdk.semgu.kz/docs/<?=$data['data'][$i][8]?><?=$data['data'][$i][9]?>')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                          <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z"/>
                        </svg>
                      </button>
                      <?php }?>
                      <div class="btn" style="color:#002C52" onclick="pdfopen(<?=$data['data'][$i][0]?>)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-feather" viewBox="0 0 16 16">
                          <path d="M15.807.531c-.174-.177-.41-.289-.64-.363a3.8 3.8 0 0 0-.833-.15c-.62-.049-1.394 0-2.252.175C10.365.545 8.264 1.415 6.315 3.1S3.147 6.824 2.557 8.523c-.294.847-.44 1.634-.429 2.268.005.316.05.62.154.88q.025.061.056.122A68 68 0 0 0 .08 15.198a.53.53 0 0 0 .157.72.504.504 0 0 0 .705-.16 68 68 0 0 1 2.158-3.26c.285.141.616.195.958.182.513-.02 1.098-.188 1.723-.49 1.25-.605 2.744-1.787 4.303-3.642l1.518-1.55a.53.53 0 0 0 0-.739l-.729-.744 1.311.209a.5.5 0 0 0 .443-.15l.663-.684c.663-.68 1.292-1.325 1.763-1.892.314-.378.585-.752.754-1.107.163-.345.278-.773.112-1.188a.5.5 0 0 0-.112-.172M3.733 11.62C5.385 9.374 7.24 7.215 9.309 5.394l1.21 1.234-1.171 1.196-.027.03c-1.5 1.789-2.891 2.867-3.977 3.393-.544.263-.99.378-1.324.39a1.3 1.3 0 0 1-.287-.018Zm6.769-7.22c1.31-1.028 2.7-1.914 4.172-2.6a7 7 0 0 1-.4.523c-.442.533-1.028 1.134-1.681 1.804l-.51.524zm3.346-3.357C9.594 3.147 6.045 6.8 3.149 10.678c.007-.464.121-1.086.37-1.806.533-1.535 1.65-3.415 3.455-4.976 1.807-1.561 3.746-2.36 5.31-2.68a8 8 0 0 1 1.564-.173"/>
                        </svg>
                      </div>
                    </div>
                  </div>
                </div>
            </div>

        <?php
        }
        ?>

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
<script>
    function pdfopen(documentid) {

        window.open('https://sdk.semgu.kz/modules.php?pa=docs-pdf-inbox&sign=<?=$sign?>&documentid='+documentid);

    };
</script>

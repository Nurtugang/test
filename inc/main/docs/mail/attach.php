<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<div class="card">
    <div class="card-body">
      <div class="mt-3"></div>
  <?php
    $sql = "select aid,mailfiles from emaila where mailid=$mailid";
    //echo $sql;
    $res = $conapps->query($sql);

    while (list($aid,$mailfiles) = mysqli_fetch_row($res)) {
      $buffer = file_get_contents($_SESSION['emaildir'].$mailfiles);
      $finfo = new finfo(FILEINFO_MIME_TYPE);
      $filename = explode('/', $mailfiles);
      $fsize = filesize($_SESSION['emaildir'].$mailfiles);
      $ext = pathinfo($_SESSION['emaildir'].$mailfiles, PATHINFO_EXTENSION);
      $isimg = explode('/',$finfo->buffer($buffer));
  ?>

    <?php
      if ($isimg[0] == 'image') {
    ?>
    <span class="mailbox-attachment-icon has-img"><img src="mod/download.php?aidimage=<?=$aid?>" alt="Attachment"></span>
    <?php
    } else {
      switch ($ext) {
        case 'zip':
          $fa = 'fa fa-file-zip-o';
          break;
        case 'docx':
          $fa = 'bi bi-filetype-docx';
          break;
          case 'doc':
            $fa = 'bi bi-filetype-docx';
            break;
        case 'xlsx':
          $fa = 'bi bi-filetype-xlsx';
          break;
        case 'xls':
          $fa = 'bi bi-filetype-xlsx';
          break;
        case 'ppt':
          $fa = 'bi bi-filetype-ppt';
          break;
          case 'pptx':
            $fa = 'bi bi-filetype-ppt';
            break;
        case 'pdf':
          $fa = 'bi bi-file-earmark-pdf';
          break;

        default:
          $fa = 'bi bi-file';
          break;
      }
    ?>
    <?php
    }
    ?>
    <div class="d-flex align-items-center">

        <div class="fm-file-box bg-light-primary text-primary"><i class="<?=$fa?>"></i>
        </div>
        <div class="flex-grow-1 ms-2">
          <a id="download<?=$aid?>" class="mailbox-attachment-name">
          <h6 class="mb-0"><?=$aid?>-attachment.<?=$ext?></h6>
        </a>
          <!--<p class="mb-0 text-secondary">1,756 files</p>-->
        </div>
        <h6 class="text-primary mb-0"><?=$fsize?></h6>
          <script>
          const jsonData<?=$aid?> = {
            type: "download",
            url: "https://sdk.semgu.kz/mod/download.php?aid=<?=$aid?>",
            filename: "<?=$filename[2]?>"
          };
          document.getElementById("download<?=$aid?>").addEventListener("click", function() {
            window.flutter_inappwebview.callHandler('flutterHandler', jsonData<?=$aid?>)
          });
          </script>
    </div>
  <?php
    }
  ?>


  							</div>
  						</div>

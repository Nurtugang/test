    <?php
    if (isset($_GET['author'])) {
      $sql = "select filename from documentfiles where fileid=$fileid";
      $res = $condocs->query($sql);
      list($filename) = mysqli_fetch_row($res);
      $sql = "select dir from documents where documentid=$documentid";
      $res = $condocs->query($sql);
      list($dir) = mysqli_fetch_row($res);
    } else {
      $sql = "select dir,filename from documents where documentid=$documentid";
      $res = $condocs->query($sql);
      list($dir,$filename) = mysqli_fetch_row($res);
    }

    $file = $datadir.$dir.$filename;
    $mime = mime_content_type($file);
    //echo "$file<br>$mime<br>";
    $m = explode('/',$mime);
    if ($mime=='application/pdf') {
      if (isset($_GET['author'])) {
    ?>
        <div><object data='download.php?fileid=<?=$fileid?>' type="application/pdf" width="600" height="750"><iframe src='download.php?fileid=<?=$fileid?>' width="600" height="750"><p>This browser does not support PDF!</p></iframe></object></div>
    <?php
      } else {
      ?>
        <div><object data='download.php?documentid=<?=$documentid?>' type="application/pdf" width="600" height="750"><iframe src='download.php?documentid=<?=$documentid?>' width="600" height="750"><p>This browser does not support PDF!</p></iframe></object></div>
      <?php  
      }
    } elseif ($m[0]=='image') {
      $txt = file_get_contents($file);
      $img = "data:$mime;base64,".base64_encode($txt);
      ?>
      <img src="<?=$img?>" width="100%" />
      <?php
    } elseif ($mime=='text/plain') {
      $txt = str_replace("\n",'<br />',file_get_contents($file));
      ?>
      <style>
        #textdoc{
         width: 100%;
         border: solid 1px;
         border-color:#999;
         border-radius: 8px;
         background-color:#fff;
         padding: 10px;
         text-align:justify;
         font-family:Verdana, Geneva, sans-serif;
         font-size:12px;
        }
      </style>
      <div id="textdoc"><?=$txt?></div>
      <?php
      //echo "<textarea style='width:100%' rows='30'>$txt</textarea>";
    } else {
      ?>
      Данный документ не может быть представлен для просмотраю<br />
      Для того чтобы ознакомиться с ним Вам необходимо его скачать<br />
      <a href="download.php?documentid=<?=$documentid?>" target="_blank">Скачать</a>
      <?php
    }
    ?> 
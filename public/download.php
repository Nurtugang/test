<?php
function file_force_downloadPDF($file,$name='') {
  if (file_exists($file)) {
    // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
    // если этого не сделать файл будет читаться в память полностью!
    if (ob_get_level()) {
      ob_end_clean();
    }
    if ($name=='') {
      $f = basename($file);
    } else {
      $f = $name.'.'.pathinfo(basename($file),PATHINFO_EXTENSION);
    }

    header('Content-Type: application/pdf');

    if ($fd = fopen($file, 'rb')) {
      while (!feof($fd)) {
        print fread($fd, 1024);
      }
      fclose($fd);
    }
    exit;
  }
  if (isset($documentidext)) {
    $sql = "select filename,dir,status,sigexid from documents where documentid=$documentidext";
    $res = $condocs->query($sql);
    list($filename,$dir,$status,$sigexid) = mysqli_fetch_row($res);
    $filenames = $datadir.$dir.$filename;
    if (file_exists($filenames)) {
      $f = explode('.', $filename);
      if ($status==0) {
        file_force_downloadPDF($filenames,$f[0]);
      } else {
        file_force_downloadPDF($filenames,$f[0].'-SigexId'.$sigexid);
      }
    } else {
      echo 'no file '.$filename;
    }
  }

<?php
$sqlnom = "select nomenclatureid,concat(code,'-',nameru) from nomenclature where podr = ".$_SESSION['idpodr']." or str='all' or str='".$_SESSION['role']."' order by nomenclatureid";
$res = $condocs->query($sqlnom) or die($sqlnom.'<br />'.$condocs->error.'<br /><br />');
$option = '';
while (list($nid,$name) = mysqli_fetch_row($res)) {
  $option .= '<option value="'.$nid.'">'.$name.'</option>';
  $nom[$nid] = $name;
}
$Tutor = new Tutor();
$Tutor->con = $con;
switch ($type) {
  case '1':
    $sql = "SELECT distinct documents.dir,documents.filename,documents.author,documents.documentid,documents.name,date_format(documents.createdate,'%d.%m.%Y'),documents.sigexid,documentsignlists.nomenclatureid FROM documentsignlists INNER JOIN documents ON documentsignlists.documentid = documents.documentid WHERE documents.createdate>'2022-01-01 00:00:00' AND documents.status = 1 AND (documentsignlists.status is NULL) AND documentsignlists.sigexsignid IS NULL AND documentsignlists.type = 1 $sqlstart $sqlend AND documentsignlists.personid = ".$_SESSION['personid'];
    break;
  case '2':
    $sql = "SELECT distinct documents.dir,documents.filename,documents.author,documents.documentid,documents.name,date_format(documents.createdate,'%d.%m.%Y'),documents.sigexid,documentsignlists.nomenclatureid FROM documentsignlists INNER JOIN documents ON documentsignlists.documentid = documents.documentid WHERE documents.createdate>'2022-01-01 00:00:00' AND documents.status = 1 AND (documentsignlists.status is NULL) AND documentsignlists.sigexsignid IS NULL AND documentsignlists.type in (2,3) $sqlstart $sqlend AND documentsignlists.personid = ".$_SESSION['personid'];
    //echo $sql;
    break;
  case '3':
    $sql = "SELECT distinct documents.dir,documents.filename,documents.author,documents.documentid,documents.name,date_format(documents.createdate,'%d.%m.%Y'),documents.sigexid,documentsignlists.nomenclatureid FROM documentsignlists INNER JOIN documents ON documentsignlists.documentid = documents.documentid WHERE documents.createdate>'2022-01-01 00:00:00' AND documents.status = 1 AND NOT documentsignlists.sigexsignid IS NULL AND documentsignlists.type in (1,2,3) $sqlstart $sqlend AND documentsignlists.personid = ".$_SESSION['personid'];
    break;
  case '4':
    $sql = "SELECT distinct documents.dir,documents.filename,documents.author,documents.documentid,documents.name,date_format(documents.createdate,'%d.%m.%Y'),documents.sigexid,documentsignlists.nomenclatureid FROM documentsignlists INNER JOIN documents ON documentsignlists.documentid = documents.documentid WHERE documents.createdate>'2022-01-01 00:00:00' AND documents.status = 1 AND documentsignlists.status=0 AND documentsignlists.sigexsignid IS NULL AND documentsignlists.type in (1,2,3)  AND documentsignlists.personid = ".$_SESSION['personid'];
    break;
  case '5':
    $sql = "SELECT distinct documents.dir,documents.filename,documents.author,documents.documentid,documents.name,date_format(documents.createdate,'%d.%m.%Y'),documents.sigexid,documentsignlists.nomenclatureid FROM documentsignlists INNER JOIN documents ON documentsignlists.documentid = documents.documentid WHERE documents.createdate>'2022-01-01 00:00:00' AND documents.status = 1 AND documents.author = ".$_SESSION['personid'];
    break;
  case '6':
    $sql = "SELECT distinct documents.dir,documents.filename,documents.author,documents.documentid,documents.name,date_format(documents.createdate,'%d.%m.%Y'),documents.sigexid,documentsignlists.nomenclatureid FROM documentsignlists INNER JOIN documents ON documentsignlists.documentid = documents.documentid WHERE documents.createdate>'2022-01-01 00:00:00' AND documents.status = 0 AND documents.author = ".$_SESSION['personid'];
    break;
  case 'isp':
    $sql = "SELECT distinct documents.dir,documents.filename,documents.author,documents.documentid,documents.name,date_format(documents.createdate,'%d.%m.%Y'),documents.sigexid,documentsignlists.nomenclatureid FROM documentsignlists INNER JOIN documents ON documentsignlists.documentid = documents.documentid WHERE documents.createdate>'2022-01-01 00:00:00' AND documents.status = 0 AND documentsignlists.type = 4 AND documentsignlists.personid = ".$_SESSION['personid'];
    break;

  default:
    // code...
    break;
}
$res = $condocs->query($sql) or die($sql);
$i=0;
  while (list($dir,$filename,$author,$documentid,$name,$createdate,$sigexid,$nomenclatureid,$signid) = mysqli_fetch_row($res)) {
    if ($type==5) {
      $url = '<button type="button" class="btn btn-outline-danger" onclick="trash('.$documentid.')"><i class="bx bx-trash-alt me-0"></i></button>
      <a href="download.php?documentidext='.$documentid.'" target="_blank" class="btn btn-block btn-outline-primary btn-xs"><i class="lni lni-download"></i></a>';
    } else if ($type==6) {
      $url = '<button type="button" class="btn btn-outline-success" onclick="untrash('.$documentid.')"><i class="lni lni-unlock"></i></button>
      <a href="download.php?documentidext='.$documentid.'" target="_blank" class="btn btn-block btn-outline-primary"><i class="lni lni-download"></i></a>';
      $url = '<div class="btn-group" role="group" aria-label="Basic example">
                                                <button type="button" class="btn btn-outline-dark text-success" onclick="untrash('.$documentid.')"><i class="lni lni-unlock"></i>
                                                </button>
                                                <a type="button" class="btn btn-outline-dark  text-primary" href="download.php?documentidext='.$documentid.'" target="_blank"><i class="lni lni-download"></i>
                                                </a>
                                            </div>';
    } else {
      $url = '<a href="download.php?documentidext='.$documentid.'" target="_blank" class="btn btn-block btn-outline-primary btn-xs">Скачать</a>';
    }
    $sql = "select personid,sigexsignid,type from documentsignlists where documentid=$documentid and type<4 order by type";
    $rest = $condocs->query($sql);
    $text = '';
    while (list($personid,$sigexsignid,$type1) = mysqli_fetch_row($rest)) {
      $Tutor->tutorid=$personid;
      if ($type1==1) {
        //$text .= 'Кому: '.$Tutor->concatfio().' ';
        if ($sigexsignid=='') {
          $text .= 'Кому: <span class="text-danger">'.$Tutor->concatfio().' </span>. Согласование:';
        } else {
          $text .= 'Кому: <span class="text-success">'.$Tutor->concatfio().' </span>. Согласование:';
        }
      } else {
        if ($sigexsignid=='') {
          $text .= ' <span class="text-danger">'.$Tutor->concatfio().' </span>.';
        } else {
          $text .= ' <span class="text-success">'.$Tutor->concatfio().' </span>.';
        }
      }
    }
    $data['data'][$i][] = $documentid;
    $data['data'][$i][] = $name.'<br /><small>'.$text.'</small>';
    $Tutor->tutorid=$author;
    $data['data'][$i][] = $Tutor->concatfio();
    $data['data'][$i][] = $createdate;
    $data['data'][$i][] = $url;
    $data['data'][$i][] = '<button data-bs-toggle="modal" data-bs-target="#FullScreenModal" type="button" class="btn btn-block btn-outline-primary btn-xs" onclick="pdfopen('.$documentid.')">Просмотреть</button>';
    $data['data'][$i][] = $sigexid;
    if ($nomenclatureid==0) {
      $data['data'][$i][] = "<select class=\"form-select single-select\" onchange=\"$.get('modules.php?pa=docs-nom-inbox', { documentid: '$documentid', nomenclatureid: $(this).val() } );\" id=\"nomenclature-$documentid\" aria-label=\"Example select with button addon\">".$option.'</select>';
    } else {
      $data['data'][$i][] = $nom[$nomenclatureid];
    }
    $data['data'][$i][] = $dir;
    $data['data'][$i][] = $filename;
    $i++;
  }
  //print_r($data);
//$data = json_encode($data);
//echo $data;

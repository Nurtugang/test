<?php
$sql = "update documentsignlists set nomenclatureid=$nomenclatureid where documentid=$documentid and personid=".$_SESSION['personid'];
$condocs->query($sql) or die($sql);
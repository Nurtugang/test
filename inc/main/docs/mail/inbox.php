<div class="card">
  <div class="card-body">
    <div class="email-list ps ps--active-y">
      <table class="table-light" id="mytable">
        <thead>
          <tr><th>Входящие</th></tr>
        </thead>
        <tbody>
          <?php
          $sql = "SELECT emails.mailid as id,date_format(emails.maildate,'%d.%m.%Y %h:%i') as `date`,emails.mailtheme as theme,
          concat(tutors.lastname,' ',left(tutors.firstname,1),'.',left(tutors.patronymic,1),'.') as `from`,
          emailsto.mailtoid,emailsto.maildateread
          FROM nitroapps.emailsto INNER JOIN nitroapps.emails ON emailsto.mailid = emails.mailid INNER JOIN nitro.tutors ON tutors.TutorID = emails.mailfrom
          WHERE emails.mailstatus = 1 AND emailsto.status=1 AND emailsto.mailto = ".$personid;
          $res = $con->query($sql);
          $i = 0;
          while (list($mailid,$maildate,$mailtheme,$mailfrom,$mailtoid,$maildateread) = mysqli_fetch_row($res)) {
            if ($maildateread=='') {
              $mailfrom = "<b>$mailfrom</b>";
              $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/>
                      </svg>';
            } else {
              $mailfrom = "$mailfrom";
              $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-open" viewBox="0 0 16 16">
                        <path d="M8.47 1.318a1 1 0 0 0-.94 0l-6 3.2A1 1 0 0 0 1 5.4v.817l5.75 3.45L8 8.917l1.25.75L15 6.217V5.4a1 1 0 0 0-.53-.882zM15 7.383l-4.778 2.867L15 13.117zm-.035 6.88L8 10.082l-6.965 4.18A1 1 0 0 0 2 15h12a1 1 0 0 0 .965-.738ZM1 13.116l4.778-2.867L1 7.383v5.734ZM7.059.435a2 2 0 0 1 1.882 0l6 3.2A2 2 0 0 1 16 5.4V14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V5.4a2 2 0 0 1 1.059-1.765z"/>
                      </svg>';
            }
          ?>
          <tr>
            <td>
              <a href="modules.php?pa=docs-read-mail&mailid=<?=$mailid?>">
                <div class="d-md-flex align-items-center email-message px-3 py-1">
                  <div class="d-flex align-items-center email-actions">
                    <?=$svg?>&nbsp;&nbsp;&nbsp;&nbsp;
                    <p class="mb-0 ml-5"><?=$mailfrom?>
                    </p>
                  </div>
                  <div class="">
                    <p class="mb-0"><?=$mailtheme?></p>
                  </div>
                  <div class="ms-auto">
                    <p class="mb-0 email-time"><?=$maildate?> <?=$maildateread?></p>
                  </div>
                </div>
              </a>
            </td>
          </tr>
          <?php
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

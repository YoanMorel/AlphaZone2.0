<?php $this->title = 'Messagerie'; ?>

<?php
    setlocale(LC_ALL, 'fr_FR.UTF8');

    foreach($inquiries as $inquire):

    $inquireTime = strftime('%A %e %B %Y à %k:%M', strtotime($inquire['INQ_POST_DATE']));


?>

<div id="<?= $inquire['INQ_ID'] ?>" class="inquireContainer <?= $inquire['INQ_OPENED'] ? 'opened' : ''; ?>">
  <img src="<?= $inquire['INQ_OPENED'] ? 'public/img/opened.svg' : 'public/img/received.svg'; ?>" alt="icon" />
  <p><span><b><?= $inquire['CON_LAST_NAME']; ?></b></span>de <?= $inquire['CON_ORGANISME']; ?></p>
  <p>Sujet : <b><?= $inquire['INQ_SUBJECT']; ?></b></p>
  <p class="inquireMsg"><?= substr($inquire['INQ_INQUIRE'], 0, 60); ?>...</p>
  <span class="time"><?= $inquireTime ?></span>
</div>

<?php

    endforeach;

?>
<div class="messengerOverlay">
    <div class="messengerContainer">
      <div class="contactMail"></div>
      <div class="contactName"></div>
      <div class="contactOrganisme"></div>
      <div class="inquireSubject"></div>
      <div class="inquire"></div>
    </div>
</div>
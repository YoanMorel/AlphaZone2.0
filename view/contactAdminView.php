<?php $this->title = 'Contacts'; ?>

<?php
    setlocale(LC_ALL, 'fr_FR.UTF8');

    foreach($inquiries as $inquire):

    $inquireTime = strftime('%A %e %B %Y à %k:%M', strtotime($inquire['INQ_POST_DATE']));


?>

<div class="inquireContainer <?= $inquire['INQ_OPENED'] ? 'opened' : ''; ?>">
  <img src="<?= $inquire['INQ_OPENED'] ? 'public/img/opened.svg' : 'public/img/received.svg'; ?>" alt="icon" />
  <p><span><b><?= $inquire['CON_LAST_NAME']; ?></b></span>de <?= $inquire['CON_ORGANISME']; ?></p>
  <p>Sujet : <b><?= $inquire['INQ_SUBJECT']; ?></b></p>
  <p><?= substr($inquire['INQ_INQUIRE'], 0, 60); ?>...</p>
  <span class="time"><?= $inquireTime ?></span>
</div>

<?php

    endforeach;

?>
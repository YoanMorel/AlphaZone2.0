<?php $this->title = 'Messagerie'; ?>

<?php
    setlocale(LC_ALL, 'fr_FR.UTF8');

    // $inquiries = [];

    if($inquiries):
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
    else:
      ?>
      <div style="display: flex; height: 100vh;">
        <div style="margin: auto; text-align: center;">
          <i class="fas fa-fw fa-7x fa-comment-slash m-4" style="color: #F9B4ED;"></i>
          <div>Vous n'avez <b>pas ou plus de messages à découvrir</b>.</div>
          <div>C'est bien aussi le <b>silence</b> non ?</div>
        </div>
      </div>
      <?php
    endif;

?>
<div class="messengerOverlay slideMessengerAnim">
    <span class="closeMessengerOverlay">&times;</span>
    <div class="messengerContainer">
      <div class="contactMail"></div>
      <div class="inquireSubject"></div>
      <div class="contactName"></div>
      <div class="inquirePostDate"></div>
      <div class="inquireCtrl"></div>
      <div class="inquire"></div>
    </div>
</div>
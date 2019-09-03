<?php $this->title = 'Galeries' ?>

<div class="contactTitle">
  <div style="margin: auto; text-align: center;">
    <div class="boxTitle"><?= isset($sectionTitle) ? $sectionTitle : 'Galerie' ?></div>
    <div>Hinc ille commotus ut iniusta perferens et indigna praefecti custodiam protectoribus mandaverat fidis.</div>
  </div>
</div>

<div class="row no-gutters mt-2 mb-3">
  <?php if(isset($gallery)): ?>
  <div class="gallery">
    <?php
      foreach($gallery as $section => $links):
        $randIndex = rand(0, count($links) - 1);
    ?>

      <div class="galleryItem">
          <div class="containImage">
            <a href="pieces/<?= strtolower($section) ?>.html">
              <img class="galleryImage" src="<?= $links[$randIndex] ?>" alt="" />
            </a>
          </div>
          <div style="display: block; text-align: center; height: 10%">
            <a href="pieces/<?= strtolower($section) ?>.html">
              <?= $section ?>
            </a>
          </div>
      </div>

    <?php endforeach; ?>
  </div>
  <?php
    endif;
    if(isset($sectionGallery)):
  ?>

    <div class="wrapper">
      <div class="containerGrid">
      <?php foreach($sectionGallery as $link): ?>
        <div class="<?= $link[1] ?>">
          <img class="imgTrigger" src="<?= $link[0] ?>" alt="">
        </div>
      <?php endforeach; ?>
      </div>
    </div>
  
  <?php endif; ?>
</div>

<div id="myModal" class="modalImg">
  <span class="close">&times;</span>
  <img class="modalContent" id="img01">
  <div id="caption"></div>
</div
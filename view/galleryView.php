<?php $this->title = 'Galeries' ?>

<div class="sectionTitle">
  <div style="width: 100vh; margin: auto; text-align: center;">
    <div class="boxTitle">
      <?php if(isset($nav) && $nav['prev'] != FALSE): ?>
      <a href="pieces/<?= strtolower($nav['prev']) ?>.html" class="leftArrow">
        <i class="fas fa-fw fa-caret-left"></i>
      </a>
      <?php endif; ?>
      <?= isset($nav) ? $nav['current'] : 'Galerie' ?>
      <?php if(isset($nav) && $nav['next'] != FALSE): ?>
      <a href="pieces/<?= strtolower($nav['next']) ?>.html" class="rightArrow">
        <i class="fas fa-fw fa-caret-right"></i>
      </a>
      <?php endif; ?>
    </div>
    <div style="width: 90%; margin: auto">Hinc ille commotus ut iniusta perferens et indigna praefecti custodiam protectoribus mandaverat fidis.</div>
    <?php if(!isset($gallery)): ?>
      <a href="gallery.html" class="galleryReturn">Retour à la Galerie</a>
    <?php endif; ?>
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
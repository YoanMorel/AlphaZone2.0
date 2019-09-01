<?php $this->title = 'Galeries' ?>

<div class="contactTitle">
  <div style="margin: auto; text-align: center;">
    <div class="boxTitle">Galerie</div>
    <div>Hinc ille commotus ut iniusta perferens et indigna praefecti custodiam protectoribus mandaverat fidis.</div>
  </div>
</div>

<div class="row no-gutters mt-2 mb-3">
  <?php
    if(isset($gallery)):
  ?>
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
          <div style="display: block; text-align: center;">
            <a href="pieces/<?= strtolower($section) ?>.html">
              <?= $section ?>
            </a>
          </div>
      </div>

    <?php
    endforeach;
    ?>
  </div>
  <?php
    endif;
    if(isset($sectionGallery)):
  ?>

    <div class="wrapper">
      <div class="containerGrid">
      <?php
        foreach($sectionGallery as $link):
      ?>
        <div class="<?= $link[1] ?>">
          <img src="<?= $link[0] ?>" alt="">
        </div>
      <?php
        endforeach;
      ?>
      </div>
    </div>
  
  <?php
    endif;
  ?>
</div>

<!-- <div style="display: flex; height: 50vh;">
    <div style="margin: auto; text-align: center;">
      <i class="far fa-fw fa-7x fa-images m-4" style="color: #391463;"></i>
      <div>Elle arrive <b>bientôt</b> !</div>
      <div>La galerie de l'<b>Atelier de Jean-François</b> prend forme.</div>
    </div>
</div> -->
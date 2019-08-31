<?php $this->title = 'Galeries' ?>

<div class="contactTitle">
  <div style="margin: auto; text-align: center;">
    <div class="boxTitle">Galerie</div>
    <div>Hinc ille commotus ut iniusta perferens et indigna praefecti custodiam protectoribus mandaverat fidis.</div>
  </div>
</div>

<div class="row no-gutters mt-2 mb-3">
<div class="gallery">
  <?php
    foreach($gallery as $section => $links):
  ?>

      <div class="galleryItem">
          <div class="containImage">
            <img class="galleryImage" src="<?= $links[0] ?>" alt="" />
          </div>
          <div style="display: block; text-align: center;">
            <?= $section ?>
          </div>
      </div>

  <?php
    endforeach;
  ?>
</div>
</div>

<!-- <div style="display: flex; width: 100vw; flex-direction: row; flex: wrap; justify-content: space-between;">

</div> -->

<!-- <div style="display: flex; height: 50vh;">
    <div style="margin: auto; text-align: center;">
      <i class="far fa-fw fa-7x fa-images m-4" style="color: #391463;"></i>
      <div>Elle arrive <b>bientôt</b> !</div>
      <div>La galerie de l'<b>Atelier de Jean-François</b> prend forme.</div>
    </div>
</div> -->
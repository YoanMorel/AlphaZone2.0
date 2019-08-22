<?php $this->title = 'Galerie' ?>

<div class="row no-gutters galleryRow">

<?php

    foreach($imgLinks as $imgLink):

?>
    <div class="col-12 col-md-4 p-1">
        <div class="galleryCol">
            <div class="dropDownImg">
                <div class="btnDropDownImg">
                    <i class="fas fa-fw fa-bars"></i>
                </div>
                <div class="dropDownList">
                    <a href="#">Afficher</a>
                    <a href="#">Editer</a>
                    <a href="#">Supprimer</a>
                </div>
            </div>
            <!-- <div class="overlay">
                <div class="list">
                    <a href="#">Afficher</a>
                    <a href="#">Editer</a>
                    <a href="#">Supprimer</a>
                </div>
            </div> -->
            <img data-name="" class="" src="gallery/<?= $imgLink ?>" alt="image" />
        </div>
    </div>

<?php

    endforeach;

?>

</div>
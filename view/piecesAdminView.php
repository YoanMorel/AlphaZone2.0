<?php $this->title = 'Galerie';
    // $pieces = []; ?>

<div class="row no-gutters galleryRow" <?= $pieces ? '' : 'style="height: 100vh;"'?>>

<?php
    if($pieces):
        foreach($pieces as $piece):

?>
    <div class="col-12 col-md-4 p-1">
        <div class="galleryCol">
            <div class="overlay">
                <div class="list">
                    <a class="show" href="javascript:void(0)">Afficher</a>
                    <a class="editor" href="javascript:void(0)">Editer</a>
                    <a class="erase" href="javascript:void(0)">Supprimer</a>
                </div>
            </div>
            <img 
                data-title="<?= ($piece['PIE_TITLE']) ? $piece['PIE_TITLE'] : 'Sans titre'; ?>" data-creation="<?= ($piece['PIE_CREATION_DATE']) ? $piece['PIE_CREATION_DATE'] : 'Non datée'; ?>" 
                data-update="<?= ($piece['PIE_UPLOAD_DATE']) ? $piece['PIE_UPLOAD_DATE'] : 'Non datée'; ?>" 
                data-story="<?= ($piece['PIE_STORY']) ? $piece['PIE_STORY'] : 'Non commenté'; ?>"  
                src="gallery/<?= $piece['SEC_SECTION'].'/'.$piece['SUB_SUBSECTION'].'/'.$piece['PIE_IMG_LINK'] ?>" 
                alt="image"
                id="<?= $piece['PIE_ID'] ?>"
                class="<?= (empty($piece['PIE_STORY'])) ? ' needsEdit' : ''; ?>"
            />
        </div>
    </div>

<?php

        endforeach;
    else:
        ?>
        
        <div class="noGallery" style="margin: auto; text-align: center;">
            <i class="fas fa-fw fa-7x fa-exclamation m-4" style="color: #FF784F;"></i>
            <div>Aucune œuvre dans votre toute <b>nouvelle galerie en ligne</b>.</div>
            <div>Pour envoyer vos œuvres, <b><a href="index.php?action=admin&module=upload">cliquez ici</a></b></div>
        </div>
        
        <?php
    endif;

?>

<div class="editorOverlay slideEditorAnim">
    <span class="closeEditor">&times;</span>
    <div class="container">
        <div class="row no-gutters mx-auto">
            <div class="col-6 editorColImg">
                <div class="containerImg">
                    <img class="img-fluid" src="" alt="image" />
                    <div class="textBlock">
                        <div class="section"></div>
                        <div class="subSection"></div>
                    </div>
                </div>
            </div>
            <div class="col-6 editorColData">
                <div class="fieldContainer">
                    <label for="title">Titre</label>
                    <input id="title" type="text" />
                    <label for="creation">Date de création</label>
                    <input id="creation" type="text" />
                    <label for="update">Date de l'envoi</label>
                    <input id="update" type="text" disabled />
                    <label for="story">Commentaire</label>
                    <textarea id="story" name="">
                    </textarea>
                </div>
                <div class="btnContainer">
                    <button type="button" class="btn btnEditor btn-danger" disabled>Modifier</button> 
                </div>
            </div>
        </div>
    </div>
</div>

<div id="openModal" class="modalWindow">
    <div class="erase">
        <div class=modalClose><a href="javascript:void(0);" id="closeModal">Fermer</a></div>
        <h1 class="erase">Attention !</h1>
        <div>
            Cette action <b>supprimera toutes les informations de cette œuvre</b> et ne seront <b>pas récupérables</b>. Pour valider, cliquez sur <i>Valider la suppression</i> puis sur le boutton <i>Suppression</i>.
        </div>
        <div class="container">
            <div class="row no-gutters">
            <div class="col-5 justify-content-end d-flex align-items-center">
                <label class="p-2" for="cbErase">Valider la suppression</label>
            </div>
            <div class="col-1">
                <input id="cbErase" type="checkbox" />
                <span></span>
            </div>
            <div class="col-6 text-center">
                <button type="button" class="btn btn-danger btnErase" disabled>Suppression</button>
            </div>
            </div>
        </div>
    </div>
</div>

<div id="myModal" class="modalImg">
  <span class="close">&times;</span>
  <img class="modalContent" id="img01">
  <div id="caption"></div>
</div>

</div>
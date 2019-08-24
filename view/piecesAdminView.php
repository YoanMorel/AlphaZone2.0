<?php $this->title = 'Galerie' ?>

<div class="row no-gutters galleryRow">

<?php

    foreach($pieces as $piece):

?>
    <div class="col-12 col-md-4 p-1">
        <div class="galleryCol <?= (empty($piece['PIE_STORY'])) ? ' needsEdit' : ''; ?>">
            <div class="overlay">
                <div class="list">
                    <a class="show" href="#">Afficher</a>
                    <a class="editor" href="#">Editer</a>
                    <a class="erase" href="#">Supprimer</a>
                </div>
            </div>
            <img 
                data-title="<?= ($piece['PIE_TITLE']) ? $piece['PIE_TITLE'] : 'Sans titre'; ?>" data-creation="<?= ($piece['PIE_CREATION_DATE']) ? $piece['PIE_CREATION_DATE'] : 'Non datée'; ?>" 
                data-update="<?= ($piece['PIE_UPLOAD_DATE']) ? $piece['PIE_UPLOAD_DATE'] : 'Non datée'; ?>" 
                data-story="<?= ($piece['PIE_STORY']) ? $piece['PIE_STORY'] : 'Non commenté'; ?>"  
                src="gallery/<?= $piece['SEC_SECTION'].'/'.$piece['SUB_SUBSECTION'].'/'.$piece['PIE_IMG_LINK'] ?>" 
                alt="image"
            />
        </div>
    </div>

<?php

    endforeach;

?>

<div class="editorOverlay slideEditorAnim">
    <span class="closeEditorOverlay">&times;</span>
    <div class="container">
        <div class="row no-gutters mx-auto">
            <div class="col-6 editorColImg">
                <img class="img-fluid" src="" alt="image" />
            </div>
            <div class="col-6 editorColData">
                <div class="fieldContainer">
                    <input id="title" type="text" />
                    <input id="creation" type="text" />
                    <input id="update" type="text" disabled />
                    <textarea id="story" name="">
                    </textarea>
                    <button class="btnEditor">Modifier</button>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
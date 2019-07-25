<?php $this->title = 'uploader'; ?>


  <div class="row no-gutters">
    <div class="col-12">
        <div class="alertPopup">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            <span class="alertContent"></span>
        </div> 
    </div>
  </div>
  <div class="row no-gutters pt-3">
    <div class="col-10 mx-auto" id="fileList">
      <!-- ZONE DRAG&DROP -->
    </div>
    <button class="btn btn-danger mx-auto" type="button" id="upload" disabled>Upload</button>
  </div>
  <div id="onHoldTextarea" class="row pt-5 no-gutters showDataFields">
    <div class="col-md-4 col-12 text-center">
      <input id="imgTitle" type="text" name="" placeholder="Titre de l'image" />
      <input id="imgSection" type="text" name="" placeholder="Section de l'image" />
      <input id="imgSubSection" type="text" name="" placeholder="Sous-section de l'image" />
      <input id="imgCreationDate" type="text" name="" placeholder="Date de création de l'oeuvre" />
    </div>
    <div class="col-md-8 col-12">
      <textarea class="onHoldTextImg" name="" placeholder="Entrer du texte"></textarea>
    </div>
  </div>
<?php $this->title = 'uploader'; ?>

  <div class="row no-gutters">
    <div class="col-12">
        <div class="alertPopup">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            <span class="alertContent"></span>
        </div> 
    </div>
  </div>
  <div class="row no-gutters">
    <div class="col-10 mx-auto" id="fileList"></div>
    <button class="btn btn-danger mx-auto" type="button" id="upload" disabled>Upload</button>
  </div>
  <div id="onHoldTextarea" class="row pt-5 no-gutters showDataFields">
    <div class="col-4 text-center">
      <input id="imgTitle" type="text" name="" value="" placeholder="Titre de l'image" />
      <input id="imgSection" type="text" name="" value="" placeholder="Section de l'image" />
      <input id="imgSubSection" type="text" name="" value="" placeholder="Sous-section de l'image" />
    </div>
    <div class="col-8">
      <textarea class="onHoldTextImg" name="" placeholder="Entrer du texte"></textarea>
    </div>
  </div>
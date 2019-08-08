$(function() {

  // Snackbar Handler
  function snackBar(text) {
    $('#snackBar').addClass('show').text(text);
    setTimeout(() => {
      $('#snackBar').removeClass('show').empty();
    }, 3000);
  }

  // Evènement pour le Header
  $(window).scroll(function() {
    if ($(this).scrollTop() > 50){  
      $('div.header').addClass("sticky");
    }
    else{
      $('div.header').removeClass("sticky");
    }
  });

  // Handler pour l'évènement overlayGallery
  $('a.overlayDrop').click(function() {
    $('div.overlayGallery').css("height", "100%");
    $('body').css("overflow", "hidden");
  });
  $('div.closeOverlay').click(function() {
    $('div.overlayGallery').css("height", "0");
    $('body').css("overflow-y", "scroll");
  });

  // Pour les ancres
  $('[href*="#"]').on('click', function(event) {
    event.preventDefault();
    var anchor = $(this).attr('href');
    $('html, body').animate({
      scrollTop: $(anchor).offset().top}, 500, 'linear');
  });

  $('a.mainToGallery').hover(function() {
    $('div.parallaxExtraMainView > img').css("filter", "blur(4px)");
  }, function() {
    $('div.parallaxExtraMainView > img').css("filter", "none");
  });

  // Pour le Scroll to the Highy Top
  $(window).scroll(function() {
    if ($(this).scrollTop() >= 250) {
      $('a#returnToTop').fadeIn(200);
    } else {
      $('a#returnToTop').fadeOut(200);
    }
  });
  $('a#returnToTop').click(function() {
    $('body, html').animate({
      scrollTop : 0
    }, 500);
  });

  // Evenement pour le sideText
  $('.closeLeftSideText, .closeRightSideText').click(function() {
    
    if ($(this).parent().css("width") === "35px") {
      $(this).siblings('.sideTextBox').show();
      if($(this).parent().hasClass('rightSideText')) {
        $(this).parent().css({
          "width": "50%",
          "right": "2rem",
          "border-radius": "10px 10px 10px 10px"
        });
      } else {
        $(this).parent().css({
          "width": "50%",
          "left": "2rem",
          "border-radius": "10px 10px 10px 10px"
        });
      }
      $(this).css("background", "rgba(0, 0, 0, 0.7)");
      if ($(this).children().hasClass('fa-chevron-right')) {
        $(this).children().removeClass().addClass('fas fa-chevron-left');
      } else {
        $(this).children().removeClass().addClass('fas fa-chevron-right');
      }
    } else {
      if($(this).parent().hasClass('rightSideText')) {
        $(this).parent().css({
          "width": "35px",
          "right": "0",
          "border-radius": "10px 0 0 10px"
        });
      } else {
        $(this).parent().css({
          "width": "35px",
          "left": "0",
          "border-radius": "0 10px 10px 0"
        });
      }
      $(this).css("background", "rgba(0, 0, 0, 0.05)");
      $(this).siblings('.sideTextBox').hide();
      if ($(this).children().hasClass('fa-chevron-right')) {
        $(this).children().removeClass().addClass('fas fa-chevron-left');
      } else {
        $(this).children().removeClass().addClass('fas fa-chevron-right');
      }
    }
  });

  // Liste de selecteurs couramment utilisés
  var dropBox = $('#fileList');
  var dataFields = $('div#onHoldTextarea');
  var imgTitle = $('input#imgTitle');
  var imgSection = $('input#imgSection');
  var imgSubSection = $('input#imgSubSection');
  var textArea = $('textarea.onHoldTextImg');
  var uploadBTN = $('button#upload');
  var alertPopup = $('div.alertPopup');

  // Tableau stockage des images, tableau stockage des données concernant les images, tableaux pour l'autocompletion
  var fileStorage = [];
  var objsTab = [];
  var autoCompleteSections = [];
  var autoCompleteSubSections = [];

  //Prototype de l'objet contenant les données concernant les images
  class ObjToUp {
    constructor(id, title, section, subSection, text) {
      this.id = id;
      this.title = title;
      this.section = section;
      this.subSection = subSection;
      this.text = text;
    }
  }

  // Fonction qui retourne l'index de l'objet trouvé dans un tableau
  function fetchTheObj(tab, target) {
    return tab.find(x => x.id === target)
  }

  // Requête AJAX pour l'autocomplétion. Elle récupère d'abord les sections puis, une fois la section sélectionnée et si elle existe, récupère ses sous-sections. La requête attend son due au format JSON. Est récupéré un objet particulier : un tableau 4 dims encodé en JSON
  function autoCompleteSources(section) {
    $.ajax({
      type: 'POST',
      url: 'controller/autoComplete.php',
      dataType: 'json',
      data: {
        'imgSection': section
      },
      success: function(data) {
        if (autoCompleteSubSections.length) {
          autoCompleteSubSections = [];
        }
        for (var x in data['data']) {
          for (var y in data['data'][x]) {
            if (section) {
              autoCompleteSubSections.push(data['data'][x][y]);
            } else {
              autoCompleteSections.push(data['data'][x][y]);
            }
          }
        }
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        console.log('Status :' + textStatus + ' Error:' + errorThrown);
      }
    });
  }

  // Execute la fonction pour l'autocomplétion dès le chargement
  if (!autoCompleteSections.length) {
    autoCompleteSources('');
  }

  alertPopup.addClass('info').show();
  $('div.alertPopup span.alertContent').append('<p><b>Information :</b></p>Pour transférer des fichiers images : sélectionnez vos images sur votre bureau et glissez les dans la zone pointillée du navigateur (ci-dessous)');

  // Lecture du fichier JSON pour remplir le tableaux de données de fichiers si il y a une sauvegarde. Dans le cas où elle existe, on affiche une alerte
  $.getJSON('controller/autoSaveBuffer.json', function(data) {
    $.each(data, function(key, val) {
      if (val.text.length) {
        objsTab.push(val);
      }
      if (objsTab.length) {
        alertPopup.removeClass('info').addClass('warning').show();
        $('div.alertPopup span.alertContent').html('<p><b>Avertissement !</b></p>Une sauvegarde automatique a été detectée. Si votre session a été interrompue momentanément, veuillez glisser une nouvelles fois les fichiers utilisés précédemment.');
      }
    });
  });

  function dragoverDragenterEvent(event) {
    event.preventDefault();
    event.stopPropagation();
    $(this).css('border', '3px dashed red');
    alertPopup.removeClass('info').slideUp();
  }

  function dragleaveEvent(event) {
    event.preventDefault();
    event.stopPropagation();
    $(this).css('border', '3px dashed #BBB');
  }

  //Fonction qui se déclenche au glisser-déposer de fichier images. Elle va lire le dataTransfer, boucler sur chaque fichiers et les afficher avec FileReader
  function dropEvent(event) {
    var dataTransfer = event.originalEvent.dataTransfer;
    if (dataTransfer && dataTransfer.files.length) {
      event.preventDefault();
      event.stopPropagation();
      alertPopup.removeClass('success').slideUp();
      $('div.alertPopup span.alertContent').empty();
      $(this).css('border', '3px dashed green');
      $.each(dataTransfer.files, function(i, file) {
        fileStorage.push(file);
        var reader = new FileReader();
        reader.onload = function(event) {
          if (file.type.match('image.*')) {
            var tempName = file.name.split('.')[0];
            dropBox.append(
              '<div class="btnOverImg" data-imgid="' + tempName + '"><img id="' + tempName + '" class="imgDrop onHold" src="' + event.target.result + '" /><button name="' + tempName + '" class="btn"><i class="far fa-trash-alt"></i></button></div>');
          }
        };
        reader.readAsDataURL(file);
      });
    }
  }

  // Appels des fonctions pour gérer les évènements du DROP
  dropBox.on('dragover dragenter', dragoverDragenterEvent);
  dropBox.on('dragleave', dragleaveEvent);
  dropBox.on('drop', dropEvent);

  // Fonction qui va, à l'aide d'AJAX, envoyer les fichiers vers le serveur. AJAX va executer un script PHP renseigné par son URL. Le fichier PHP va retourner une réponse dans le type de donnée déclaré.
  // Fonction qui va transférer les fichiers de manières asynchrone
  function UploadFiles(imgToUp, index) {
    var reader = new FileReader;
    var img = {};
    var objInTab = objsTab[index];

    reader.onload = function(event) {
      img.file = event.target.result.split(',')[1]; // Découpe les données B64 et assigne la seconde valeur du tableau
      if (objInTab) {
        img.title = objInTab.title;
        img.section = objInTab.section;
        img.subSection = objInTab.subSection;
        img.text = objInTab.text;
      }
      var postUrl = $.param(img); // Transforme les objets en parametres transmissibles par méthode POST. Dans ce cas, img.file=IMGBASE64
      console.log(img.file);
      $.ajax({
        type: 'POST',
        url: 'controller/uploadHandlerCtrl.php',
        data: postUrl,
        success: function(response) {
          console.log(response);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          console.log('Status :' + textStatus + ' Error:' + errorThrown);
        }
      });
    };
    reader.readAsDataURL(imgToUp);
  }

  // Le click déclenche l'upload en instançiant l'obj UploadFiles
  $('button#upload').on('click', function() {
    $.each(fileStorage, function(i, file) {
      new UploadFiles(file, i);
    });

    // A sécuriser en cas d'echec de transfert !!!!
    alertPopup.addClass('success').show();
    $('div.alertPopup span.alertContent').text('Le chargement des fichiers et de leurs données a été exécuté avec succès !');
    uploadBTN.removeClass('btn-success').addClass('btn-danger').prop('disabled', true);
    $('input, textarea').empty();
    dataFields.hide();
    dropBox.css('border', '3px dashed #BBB');
    dropBox.empty();
    objsTab = [];
    fileStorage = [];
    autoSaveData('66');
  });

  // Fonction appelée par le clic sur une image dropé dans la dropBox. Elle va déclencher l'apparition de la zone de texte et plus de paramètres (à venir) et afficher le texte qui correspond à l'image si déjà tapé/enregistré
  $(document).on('click', 'img.onHold', function() {
    var imgId = $(this).attr('id');
    var objFound;

    if (imgId != textArea.attr('name') && dataFields.is(':visible')) {
      textArea.val('');
      dataFields.css("display", "none");
    }

    textArea.attr('name', imgId);
    imgTitle.val(imgId);
    objFound = fetchTheObj(objsTab, textArea.attr('name'));

    if (objFound) {
      alertPopup.removeClass('warning').slideUp();
      $('div.alertPopup span.alertContent').empty();
      imgTitle.val(objsTab[objsTab.indexOf(objFound)].title);
      imgSection.val(objsTab[objsTab.indexOf(objFound)].section);
      imgSubSection.val(objsTab[objsTab.indexOf(objFound)].subSection);
      textArea.val(objsTab[objsTab.indexOf(objFound)].text);
    }

    if (dataFields.is(':visible')) {
      dataFields.css("display", "none");
    } else {
      dataFields.css("display", "flex");
    }
  });

  // Fonction qui au clic sur le bouton de suppression d'image va d'abord remplacer toutes les informations de l'image par un objet vide afin de garder la structure du tableau intacte. Si il n'y a plus d'images détéctées dans la DropBox, la fonction reset tous les tableaux alors chargés d'objets vides.
  $(document).on('click', '.btnOverImg button.btn', function() {
    var btnId = $(this).attr('name');
    var divParentId = $(this).parent().data('imgid');
    var objFound = fetchTheObj(objsTab, btnId);

    if (btnId == divParentId) {
      var idToReplace = parseInt(btnId);
      fileStorage.splice(idToReplace, 1, {})
      if (objsTab && objFound) {
        objsTab[objsTab.indexOf(objFound)] = {};
      }
      $(this).parent().remove();
      dataFields.hide();
      $('textarea, input').val('');
      if (!$('#fileList img').length) {
        fileStorage = [];
        objsTab = [];
        autoSaveData('66');
        dropBox.css('border', '3px dashed #BBB');
        uploadBTN.removeClass('btn-success').addClass('btn-danger').prop('disabled', true);
      }
    }
  });

  // Event qui enregistre en temps réel le text tapé dans chaque "textarea". Il peut aussi sauvegarder automatiquement le texte dans un buffer JSON toutes les 5 secondes si aucune touche n'a été pressée dans l'intervale de temps.
  var autoSaveTimer;

  $('div#onHoldTextarea textarea, div#onHoldTextarea input').on('keyup', function() {
    var idTextarea = textArea.attr('name');
    var textInArea = textArea.val();
    var imgTitleContent = imgTitle.val();
    var imgSectionContent = imgSection.val();
    var imgSubSectionContent = imgSubSection.val();
    var objFound = fetchTheObj(objsTab, idTextarea);
    var counter = 0;

    if (!objsTab.length || !objFound) {
      objsTab.push(new ObjToUp(idTextarea, imgTitleContent, imgSectionContent, imgSubSectionContent, textInArea));
    } else {
      var objTabIndex = objsTab.indexOf(objFound);
      objsTab[objTabIndex].title = imgTitleContent;
      objsTab[objTabIndex].section = imgSectionContent;
      objsTab[objTabIndex].subSection = imgSubSectionContent;
      objsTab[objTabIndex].text = textInArea;
    }

    // Vérifie si tous les champs ont été renseignés pour activer le bouton d'upload
    $.each(objsTab, function(i, obj) {
      if (obj.section && obj.subSection && obj.title) {
        counter++;
      }
    });
    if (objsTab && counter == fileStorage.length) {
      uploadBTN.removeClass('btn-danger').addClass('btn-success').prop('disabled', false);
    } else {
      uploadBTN.removeClass('btn-success').addClass('btn-danger').prop('disabled', true);
    }

    if (autoSaveTimer) {
      clearTimeout(autoSaveTimer);
    }
    if (textInArea || imgTitleContent || imgSectionContent || imgSubSectionContent) {
      autoSaveTimer = setTimeout(autoSaveData, 5000);
    }

    // Script qui auto-grow le textarea
    textArea.css({
      'height': 'auto',
      'margin-bottom': '20px'
    });
    textArea.height(textArea[0].scrollHeight + 'px'); //this[0] pour pouvoir utiliser nativement scrollHeight
    // scroll automatique quand le champ de text descend en dessous de la taille de la fenetre
    var textLength = textArea.height() + textArea.offset().top;
    if (textLength >= $(window).height()) {
      window.scroll(0, (textLength + 35));
    }
  });

  // Fonction qui va être appelé par le timer au bout de x secondes. Elle va envoyer les données concernant les images sous forme de données JSON et à l'aide d'AJAX, les enregistrer dans un fichier JSON qui sera lu en cas de perte de session.
  function autoSaveData(order = true) {
    if (order != '66') {
      $.ajax({
        type: 'POST',
        dataType: 'json',
        url: 'controller/autoSave.php',
        data: {
          data: JSON.stringify(objsTab)
        },
        complete: function(response) {
          snackBar(response.responseJSON.msg);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          console.log('Status :' + textStatus + ' Error:' + errorThrown);
        }
      });
    } else {
      $.ajax({
        type: 'POST',
        url: 'controller/autoSave.php',
        data: 'order=' + order,
        complete: function(response) {
          snackBar(response.responseText);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          console.log('Status :' + textStatus + ' Error:' + errorThrown);
        }
      });
    }
  }

  $('input#imgSection').autocomplete({
    source: autoCompleteSections
  });

  $('input#imgSubSection').autocomplete({
    source: autoCompleteSubSections
  });

  $('input#imgSubSection').focus(function() {
    var imgSectionContent = imgSection.val();
    if (imgSectionContent) {
      autoCompleteSources(imgSectionContent);
    }
  });

  $('form#contactInquiries input').on('blur', function() {
    $name = $(this).attr('name');
    $.ajax({
      type: 'POST',
      url: 'controller/contactCtrl.php',
      data: $name + '=' + $(this).val(),
      complete: function(response) {
        console.log(response.responseText);
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        console.log('Status :' + textStatus + ' Error:' + errorThrown);
      }
    });
  });

});

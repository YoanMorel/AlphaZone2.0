$(function() {

  // [SNACKBAR HANDLER]
  function snackBar(text) {
    $('#snackBar').addClass('show').text(text);
    setTimeout(() => {
      $('#snackBar').removeClass('show').empty();
    }, 3000);
  }

  // [SLIDE EFFECT]
  $(".slideCardAnim").each(function(){
    $(this).addClass("slideCard");
  });

  // [CLOCK]
  startTime();

  function startTime() {
    var today = new Date();
    var h     = today.getHours();
    var m     = today.getMinutes();
    var s     = today.getSeconds();

    m = checkTime(m);
    s = checkTime(s);
    $('div.clockTime').text(h + ":" + m + ":" + s);

    var t = setTimeout(startTime, 500);
  }

  function checkTime(i) {
    if (i < 10) {i = "0" + i};
    return i;
  }

  function isEqual(object1, object2) {
    var object1Keys = Object.getOwnPropertyNames(object1);
    var object2Keys = Object.getOwnPropertyNames(object2);

    if (object1Keys.length != object2Keys.length) {
      return false;
    }

    for (var i = 0; i < object1Keys.length; i++) {
      var keyName = object1Keys[i];

      if (object1[keyName] !== object2[keyName]) {
          return false;
      }
    }
    
    return true;
  }

/*****************************************
 >  [SCRIPT DU MODULE D'UPLOAD D'IMAGES]
 * **************************************/

  // Liste de selecteurs couramment utilisés
  var dropBox       = $('#fileList');
  var dataFields    = $('div#onHoldTextarea');
  var imgTitle      = $('input#imgTitle');
  var imgSection    = $('input#imgSection');
  var imgSubSection = $('input#imgSubSection');
  var textArea      = $('textarea.onHoldTextImg');
  var uploadBTN     = $('button#upload');
  var alertPopup    = $('div.alertPopup');

  // Tableau stockage des images, tableau stockage des données concernant les images, tableaux pour l'autocompletion
  var fileStorage             = [];
  var objsTab                 = [];
  var autoCompleteSections    = [];
  var autoCompleteSubSections = [];

  //Prototype de l'objet contenant les données concernant les images
  class ObjToUp {
    constructor(id, title, section, subSection, text) {
      this.id         = id;
      this.title      = title;
      this.section    = section;
      this.subSection = subSection;
      this.text       = text;
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
      url: 'controller/AjaxRouter.php',
      dataType: 'json',
      data: 'ajax=autoComplete&imgSection=' + section,
      success: function(data) {
        if (autoCompleteSubSections.length) {
          autoCompleteSubSections = [];
        }
        for (var x in data['data']) {
          for (var y in data['data'][x]) {
            if (section) {
              autoCompleteSubSections.push(data['data'][x][y]);
              // console.log('coucou');
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
      if (val.text.length || val.section.length || val.subSection.length) {
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
        img.ajax = 'upload';
      }
      var postUrl = $.param(img); // Transforme les objets en parametres transmissibles par méthode POST. Dans ce cas, img.file=IMGBASE64
      $.ajax({
        type: 'POST',
        url: 'controller/AjaxRouter.php',
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
    if (autoSaveTimer) {
      clearTimeout(autoSaveTimer);
    }
    $.each(fileStorage, function(i, file) {
      new UploadFiles(file, i);
    });

    // A sécuriser en cas d'echec de transfert !!!!
    alertPopup.removeClass('info, warning').addClass('success').show();
    $('div.alertPopup span.alertContent').html('<p><b>Succès : </b></p>Le chargement des fichiers et de leurs données a été exécuté avec succès ! Pour modifier ou supprimer le ou les fichiers, <a href="index.php?action=admin&module=update">Cliquez ici</a>.');
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
      $('input').val('');
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
    var btnId       = $(this).attr('name');
    var divParentId = $(this).parent().data('imgid');
    var objFound    = fetchTheObj(objsTab, btnId);

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
        if (autoSaveTimer) {
          clearTimeout(autoSaveTimer);
        }
        autoSaveData('66');
        dropBox.css('border', '3px dashed #BBB');
        uploadBTN.removeClass('btn-success').addClass('btn-danger').prop('disabled', true);
      }
    }
  });

  // Event qui enregistre en temps réel le text tapé dans chaque "textarea". Il peut aussi sauvegarder automatiquement le texte dans un buffer JSON toutes les 5 secondes si aucune touche n'a été pressée dans l'intervale de temps.
  var autoSaveTimer;

  $('div#onHoldTextarea textarea, div#onHoldTextarea input').on('keyup', function() {
    var idTextarea            = textArea.attr('name');
    var textInArea            = textArea.val();
    var imgTitleContent       = imgTitle.val();
    var imgSectionContent     = imgSection.val();
    var imgSubSectionContent  = imgSubSection.val();
    var objFound              = fetchTheObj(objsTab, idTextarea);
    var counter               = 0;

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
        url: 'controller/AjaxRouter.php',
        data: {
          ajax: 'autoSave',
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
        url: 'controller/AjaxRouter.php',
        data: 'ajax=autoSave&order=' + order + '&data=' + null,
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

/*****************************************
 > FIN SCRIPT DU MODULE D'UPLOAD D'IMAGES
 * **************************************/

/*****************************************
 > DEBUT SCRIPT DE MESSAGERIE
 * **************************************/

  $('div.inquireContainer').click(function() {
    $('div.messengerContainer').children('div').text('');
    var inqId = $(this).attr('id');
    $('div.messengerOverlay').css('top', $(window).scrollTop()).addClass('slideMessenger');

    var mediaQueries = window.matchMedia('(max-width: 500px)');

    function checkMediaQueries(mediaQuerie) {
      if(mediaQuerie.matches) {
        $(window).scrollTop(0);
        $('body').css('overflow', 'auto');
      }
      else {
        $('body').css('overflow', 'hidden');
      }
    }

    checkMediaQueries(mediaQueries);
    mediaQueries.addListener(checkMediaQueries);
    
    $.ajax({
      type:     'POST',
      dataType: 'json',
      url:      'controller/AjaxRouter.php',
      data:     'ajax=messenger&inqId=' + inqId,
      complete: function(response) {
        var mail        = response.responseJSON[0]['CON_MAIL'];
        var lastName    = response.responseJSON[0]['CON_LAST_NAME'];
        var organisme   = response.responseJSON[0]['CON_ORGANISME'];
        var subject     = response.responseJSON[0]['INQ_SUBJECT'];
        var inquire     = response.responseJSON[0]['INQ_INQUIRE'].replace(/\n/g, '<br />');
        var postDate    = response.responseJSON[0]['INQ_POST_DATE'].split(' ');
        var frenchDate  = new Date(postDate[0] + 'T' + postDate[1]);
        var options     = {
          weekday:  'long',
          year:     'numeric',
          month:    'long',
          day:      'numeric',
          hour:     'numeric',
          minute:   'numeric',
          seconde:  '2-digit'
        };

        if(!$('#' + inqId).hasClass('opened')) {
          $('#' + inqId).addClass('opened').children('img').attr('src', 'public/img/opened.svg');
        }

        $('span.closeMessengerOverlay').html('&times;');
        $('div.contactMail').html('<i class="fas fa-fw fa-at"></i>' + mail);
        $('div.contactName').html('<i class="fas fa-fw fa-user"></i> De la part de ' + lastName + ' (' + organisme + ')');
        $('div.inquirePostDate').html('<i class="far fa-fw fa-clock"></i> le ' + frenchDate.toLocaleDateString('fr-FR', options))
        $('div.inquireSubject').html('Objet : <span class="subject">' + subject + '</span>');
        $('div.inquireCtrl').html('<button name="' + inqId + '" id="reply" class="btnInquire"><i class="fas fa-fw fa-reply"></i>Répondre</button><button name="' + inqId + '" id="unread" class="btnInquire"><i class="fas fa-fw fa-envelope"></i>Marquer comme non lu</button>');
        $('div.inquire').html(inquire);
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        console.log('Status :' + textStatus + ' Error:' + errorThrown);
      }
    });
  });

  $('span.closeMessengerOverlay').click(function() {
    $(this).parent().removeClass('slideMessenger');
    $('body').css('overflow', 'visible');
  });

  $(document).on('click', '#reply', function() {
    var inqId = $(this).attr('name');

    $.ajax({
      type:   'POST',
      url:    'controller/AjaxRouter.php',
      data:   'ajax=messenger&inqId=' + inqId + '&action=reply',
      complete: function(response) {
        var mailto_link = 'mailto:' + $('.contactMail').text() + '?subject=(RE:) ' + $('.subject').text();
    
        win = window.open(mailto_link, 'emailWindow');
        if (win && win.open && !win.closed) win.close();
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        console.log('Status :' + textStatus + ' Error:' + errorThrown);
      }
    });

  });

  $(document).on('click', '#unread', function() {
    var inqId = $(this).attr('name');

    $.ajax({
      type:     'POST',
      dataType: 'json',
      url:      'controller/AjaxRouter.php',
      data:     'ajax=messenger&inqId=' + inqId + '&action=unread',
      complete: function(response) {
        if($('#' + inqId).hasClass('opened')) {
          $('#' + inqId).removeClass('opened').children('img').attr('src', 'public/img/received.svg');
        }       
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        console.log('Status :' + textStatus + ' Error:' + errorThrown);
      }
    });

  });

/****************************
 > FIN SCRIPT DE MESSAGERIE
 * *************************/

/**************************
 > DEBUT SCRIPT DE GALERIE
 * ***********************/

  var imgData   = {};
  var fieldData = {};
  var imgNode;
  var imgId;
  var imgSrc;
  var splitedSrc;

  $('a.editor').click(function(event) {
    event.preventDefault();
    imgNode = $(this).parents().siblings('img');
    $('div.editorOverlay').css('top', $(window).scrollTop());
    $('div.sidebar').css('background', '#333');
    $('div.editorOverlay').addClass('slideEditor');
    $('body').css('overflow','hidden');
    imgSrc = imgNode.attr('src');
    imgId = imgNode.attr('id');
    splitedSrc = imgSrc.split('/');
    $.each($(this).parents().siblings('img').data(), function(key, value) {
      imgData[key] = value;
    });
    $('div.textBlock div.section').text('Section : ' + splitedSrc[1]);
    $('div.textBlock div.subSection').text('Sous-Section : ' + splitedSrc[2]);
    $('div.editorOverlay img').attr('src', imgSrc);
    $('input#title').val(imgData['title']);
    $('input#creation').val(imgData['creation']);
    $('input#update').val(imgData['update']);
    $('textarea#story').val(imgData['story']);
  });

  $('a.erase').click(function(event) {
    event.preventDefault();
    imgNode = $(this).parents().children('img');
    $('div.modalWindow').addClass('showModal');
    imgId = imgNode.attr('id');
    imgSrc = imgNode.attr('src');
  });

  $('input[type="checkbox"]').click(function(){
    if($(this).prop("checked") == true){
      $('button.btnErase').prop('disabled', false);
    }
    else {
      $('button.btnErase').prop('disabled', true);   
    }
  });

  $('button.btnErase').click(function() {
    $('div.modalWindow').removeClass('showModal');
    $.ajax({
      type: 'POST',
      url: 'controller/AjaxRouter.php',
      data: 'ajax=delete&pieceId=' + imgId + '&path=' + imgSrc,
      complete: function(response) {
        snackBar(response.responseText);
        imgNode.parent().parent().remove();
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        console.log('Status :' + textStatus + ' Error:' + errorThrown);
      }
    });
  });

  // Evenement Modal
  $('a#closeModal').click(function() {
    $('div#openModal').removeClass('showModal');
  });

  $('.fieldContainer').children().on('keyup', function() {
    $.each($('.fieldContainer').children('input, textarea'), function() {
      fieldData[$(this).attr('id')] = $(this).val();
    });
    if(!isEqual(imgData, fieldData)) {
      $('button.btnEditor').prop('disabled', false).removeClass('btn-danger').addClass('enabled');
    } else {
      $('button.btnEditor').prop('disabled', true).removeClass('enabled').addClass('btn-danger');
    }
  });

  $('button.btnEditor').on('click', function() {
    var btnEditor = $(this);
    var updateVars = {
      title: fieldData['title'],
      creationDate: fieldData['creation'],
      update: fieldData['update'],
      story: fieldData['story'],
      pieceId: imgId,
      link: imgSrc.split('/')[3],
      ajax: 'update'
    }

    var params = $.param(updateVars);
    $.ajax({
      type: 'POST',
      url: 'controller/AjaxRouter.php',
      data: params,
      complete: function(response) {

        snackBar(response.responseText);
        if(imgNode.parents('div.galleryCol').hasClass('needsEdit')) {
          imgNode.parents('div.galleryCol').removeClass('needsEdit');
        }
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        console.log('Status :' + textStatus + ' Error:' + errorThrown);
      }
    });

    $(this).prop('desabled', true).removeClass('enabled').addClass('btn-danger');
  });

  $('span.closeEditorOverlay').click(function() {
    $(this).parent().removeClass('slideEditor');
    $('button.btnEditor').prop('desabled', true).removeClass('enabled').addClass('btn-danger');
    $('body').css('overflow', 'visible');
    $('div.sidebar').css('background', '#191716');
  });

/****************************
 > FIN SCRIPT DE GALERIE
 * *************************/

});
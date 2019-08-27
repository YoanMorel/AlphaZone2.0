$(function() {

  // Evènement pour le Header
  $(window).scroll(function() {
    if ($(this).scrollTop() > 50) {  
      $('div.header').addClass('sticky');
    }
    else {
      $('div.header').removeClass('sticky');
    }
  });
  if($(window).scrollTop() > 50) {
    $('div.header').addClass('sticky');
  }

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

  // Evenement Modal
  $('a#closeModal').click(function() {
    $('div#openModal').removeClass('showModal');
  });

  // Script de controle dynamique de la validité du formulaire de contact
  $('form#contactInquiries input, textarea').on({
    blur: function() {
      var name    = $(this).attr('name');
      $.ajax({
        type:     'POST',
        dataType: 'json',
        url:      'controller/AjaxRouter.php',
        data:     'ajax=validation&' + name + '=' + $(this).val(),
        complete: function(response) {
          fieldFound = Object.keys(response.responseJSON);
          $.each(fieldFound, function(key, value) {
            if(value == name) {
              $('input#' + name).addClass('fieldError').after('<div class="errorMsg">'+ response.responseJSON[value] +'</div>');
            } else if ($('input#' + name).val() && !$('input#' + name).hasClass('fieldError')) {
              $('input#' + name).addClass('fieldGood').siblings('form#contactInquiries div.errorMsg').empty();
            }
          });
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          console.log('Status :' + textStatus + ' Error:' + errorThrown);
        }
      });
    },
    focus: function() {
        $(this).removeClass('fieldError fieldGood').siblings('form#contactInquiries div.errorMsg').empty();
    }
  });


  $('div.ripple').click(function() {
    $('div.containertest p').addClass('animatetest');
  });

});

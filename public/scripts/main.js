$(function() {

  var regex = /[.]/g;
  console.log(window.location.pathname.search(regex));

  // Evènement pour le Header
  $(window).scroll(function() {
    var regex = /[.]/g;
    if ($(this).scrollTop() > 50) {
      if(window.location.pathname.search(regex) != -1) {
        $('div.header').addClass('stickyWhite');
      } else {
        $('div.header').addClass('sticky');
      }
    }
    else {
      $('div.header').removeClass('sticky stickyWhite');
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

  // Evenement Modal
  $('a#closeModal').click(function() {
    $('div#openModal').removeClass('showModal');
  });

  // Script de controle dynamique de la validité du formulaire de contact
  $('form#contactInquiries input, form#contactInquiries textarea').on({
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
              $('input#' + name + ', textarea#' + name).addClass('fieldError').after('<div class="errorMsg">'+ response.responseJSON[value] +'</div>');
            } else if ($('input#' + name + ', textarea#' + name).val() && !$('input#' + name + ', textarea#' + name).hasClass('fieldError')) {
              $('input#' + name + ', textarea#' + name).addClass('fieldGood').siblings('form#contactInquiries div.errorMsg').empty();
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


  $('div.ondine').click(function() {
    $(this).siblings().children('p').addClass('animateSideText');
  });

  $('.galleryCol a.show').click(function() {
    $('body').css('overflow', 'hidden');
    var imgSrc    = $(this).parents('.overlay').siblings('img').attr('src');
    var imgStory  = $(this).parents('.overlay').siblings('img').data('story');

    $('.modalImg').css('display', 'block').children('img').attr('src', imgSrc).siblings('#caption').text(imgStory);
  });

  $('.close').click(function() {
    $('body').css('overflow', 'visible');
    $('.modalImg').css('display', 'none');
  });

});

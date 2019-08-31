<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
    <base href="http://private.com/testZone/alphaZone/">
    <meta charset="utf-8" />

    <!-- Bootstrap setting -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
     <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <link rel="stylesheet" href="public/css/style.css" />
    <link rel="stylesheet" href="public/css/admin.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous" />
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.7.8/angular.min.js"></script>
    <title><?= $title; ?></title>
</head>

<body onload="startTime()">
  <a href="javascript:" id="returnToTop"><i class="fas fa-chevron-up"></i></a>
  <div class="container-fluid p-0">
      <div class="row no-gutters">
          <?php if(isset($_SESSION['user'])): ?>
          <div class="col-12 col-lg-12 col-xl-2">
            <div class="sidebar">
                <a class="main" href="admin/main.html">
                  <i class="fas fa-fw fa-home"></i> Accueil
                </a>
                <a class="upload" href="admin/upload.html">
                  <i class="fas fa-fw fa-upload"></i> Envoyer
                </a>
                <a class="update" href="admin/update.html">
                  <i class="fas fa-fw fa-image"></i> Galerie
                </a>
                <a class="contact" href="admin/contact.html">
                  <i class="fas fa-fw fa-envelope"></i> Messagerie
                </a>
                <a class="events" href="admin/events.html">
                  <i class="far fa-fw fa-calendar-alt"></i> Évènements
                </a>
                <a class="settings" href="admin/settings.html">
                  <i class="fas fa-fw fa-cogs"></i> Paramètres
                </a>
                <a class="red shutdown" href="admin/exit.html">
                  <i class="fas fa-fw fa-power-off"></i> Déconnexion
                </a>
                <a class="seeSite" href="home">
                  <i class="far fa-fw fa-window-maximize"></i> Voir le site
                </a>
            </div>
          </div>
          <?php endif; ?>
          <div class="col-12 col-lg-12 <?= isset($_SESSION['user']) ? 'col-xl-10' : 'col-xl-12'; ?>">
            <div id="snackBar"></div>
            <?= $content; ?>
          </div>
      </div>
  </div>

  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.4.0.js" integrity="sha256-DYZMCC8HTC+QDr5QNaIcfR7VSPtcISykd+6eSmBW5qo=" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js" integrity="sha256-0YPKAwZP7Mp3ALMRVB2i8GXeEndvCq3eSl/WsAl1Ryk=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <!-- JSmaiSON -->
  <script type="text/javascript" src="public/scripts/main.js"></script>
  <script type="text/javascript" src="public/scripts/admin.js"></script>
</body>

</html>

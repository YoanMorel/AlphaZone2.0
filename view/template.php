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
    <link href="https://fonts.googleapis.com/css?family=Charm|Italianno|Marck+Script|Bilbo+Swash+Caps|Tangerine&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="public/css/style.css" />
    <link rel="stylesheet" href="public/css/main.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous" />
    <title><?= $title; ?></title>
</head>

<body>
    <a href="javascript:" id="returnToTop"><i class="fas fa-chevron-up"></i></a>
    <div class="header <?= (isset($_GET['action']) && $_GET['action'] != 'main') ? '' : 'main'; ?>">
        <div class="headerTitle">L'atelier de Jean-François</div>
        <div class="headerIcon"><i class="fas fa-fw fa-bars"></i></div>
        <div class="headerLinks <?= (isset($_GET['action']) && $_GET['action'] != 'main') ? '' : 'main'; ?>">
            <a href="home">Accueil</a>
            <a href="gallery.html">Œuvres</a>
            <a href="philosophy.html">Philosophie</a>
            <a href="biography.html">Biographie</a>
            <a href="contact.html">Contact</a>
            <a class="overlayDrop" href="javascript:void(0)"><i class="fas fa-th-large"></i></a>
        </div>
    </div>
    <div class="overlayGallery">
        <div class="closeOverlay">Fermer</div>
        <div class="container mx-auto my-auto w-50">
            <div class="row">
            <?php
                foreach($overlay as $link): 
            ?>
                <div class="col-6">
                    <img src="<?= $link ?>" />
                </div>
            <?php
                endforeach;
            ?>
            </div>
        </div>
    </div>
    <div class="container-fluid p-0">
        <?= $content; ?>
        <?php
            if(isset($_SESSION['user'])):
        ?>
        <a class="float" href="admin/main.html">
            <i class="fas fa-fw fa-user-cog"></i>
        </a>
        <?php endif; ?>
    </div>
    <div class="footer">
        <div class="textBoxFooter"><a href="home/admin.html">Admin</a></div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js" integrity="sha256-0YPKAwZP7Mp3ALMRVB2i8GXeEndvCq3eSl/WsAl1Ryk=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <!-- JSmaiSON -->
    <script type="text/javascript" src="public/scripts/main.js"></script>
</body>

</html>

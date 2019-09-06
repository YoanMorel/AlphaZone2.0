<div class="col-12 col-lg-12 col-xl-2">
    <div class="titleNav">
        <span class="<?= isset($_GET['module']) ? $_GET['module'] : '' ?>"><?= $this->title ?></span>
        <i class="fas fa-fw fa-bars icon"></i>
    </div>
    <div class="sidebar">
        <div class="sideTitle">
            <span class="<?= isset($_GET['module']) ? $_GET['module'] : '' ?>"><?= $this->title ?></span>
        </div>
        <a class="main <?= $_GET['module'] == 'main' ? 'active' : '' ?>" href="index.php?action=admin&module=main">
            <i class="fas fa-fw fa-home"></i> Accueil
        </a>
        <a class="upload <?= $_GET['module'] == 'upload' ? 'active' : '' ?>" href="index.php?action=admin&module=upload">
            <i class="fas fa-fw fa-upload"></i> Envoyer
        </a>
        <a class="update <?= $_GET['module'] == 'update' ? 'active' : '' ?>" href="index.php?action=admin&module=update">
            <i class="fas fa-fw fa-image"></i> Galerie
        </a>
        <button class="messenger dropDownBtn <?= $_GET['module'] == 'messenger' ? 'active' : '' ?>">
            <i class="fas fa-fw fa-envelope"></i> Messagerie <i class="fas fa-fw fa-caret-down"></i>
        </button>
        <div class="dropDownContainer">
            <a class="messenger" href="index.php?action=admin&module=messenger&service=reception">
                <i class="fas fa-fw fa-inbox"></i> Réception
            </a>
            <a class="messenger" href="index.php?action=admin&module=messenger&service=trash">
                <i class="fas fa-fw fa-trash-alt"></i> Corbeille
            </a>
        </div>
        <a class="events <?= $_GET['module'] == 'events' ? 'active' : '' ?>" href="index.php?action=admin&module=events">
            <i class="far fa-fw fa-calendar-alt"></i> Évènements
        </a>
        <button class="settings dropDownBtn <?= $_GET['module'] == 'settings' ? 'active' : '' ?>">
            <i class="fas fa-fw fa-cogs"></i> Paramètres <i class="fas fa-fw fa-caret-down"></i>
        </button>
        <div class="dropDownContainer">
            <a class="settings" href="index.php?action=admin&module=settings#main">
                <i class="fas fa-fw fa-sliders-h"></i> Généraux
            </a>
            <a class="settings" href="index.php?action=admin&module=settings#user">
                <i class="fas fa-fw fa-user-cog"></i> Utilisateurs
            </a>
            <a class="settings" href="index.php?action=admin&module=settings#files">
                <i class="far fa-fw fa-folder-open"></i> Fichiers
            </a>
            <a class="settings" href="index.php?action=admin&module=settings#messenger">
                <i class="fas fa-fw fa-mail-bulk"></i> Messagerie
            </a>
        </div>
        <a class="red shutdown" href="index.php?action=admin&module=exit">
            <i class="fas fa-fw fa-power-off"></i> Déconnexion
        </a>
        <a class="seeSite" href="home">
            <i class="far fa-fw fa-window-maximize"></i> Voir le site
        </a>
    </div>
</div>
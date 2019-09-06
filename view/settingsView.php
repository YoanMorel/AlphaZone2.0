<?php $this->title = 'Paramètres' ?>

<div id="main" class="wrapperSettings">
    <div class="containSettings">
        <div class="settingTitle">Paramètres Généraux</div>
        <div class="settingSubTitle">Préférences</div>
        <div class="settingBody" style="min-height: 10%">Ici, possibilité de controler l'affichage. Modifier les couleurs et pitêtre pk pas la possibilité de plus maybe, idk</div>
    </div>
    <div id="user" class="containSettings">
        <div class="settingTitle">Paramètres utilisateurs</div>
        <div class="settingSubTitle">Gestionnaire de mot de passe</div>
        <div class="settingBody">
            <?php 
                if(isset($newPwdSuccess)):
            ?>
            <div class="alertPopup success show">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <span class="alertContent"><?= $newPwdSuccess['msg'] ?></span>
            </div> 
            <?php
                endif;
            ?>
            <form id="userPwd" action="index.php?action=admin&module=settings#user" method="POST">
                <input type="hidden" name="login" value="<?= $_SESSION['user']->use_login ?>" />
                <label for="password">Mot de passe actuel : *</label>
                <input id="password" class="<?= isset($errors['password']) ? 'fieldError' : ''; ?>" type="password" name="password" placeholder="Mot de passe" />
                <?= isset($errors['password']) ? '<div class="errorMsg">'.$errors['password'].'</div>' : ''; ?>
                <label for="newPwd">Nouveau mot de passe : *</label>
                <input id="newPwd" class="<?= isset($errors['newPwd']) ? 'fieldError' : ''; ?>" type="password" name="newPwd" placeholder="Nouveau mot de passe" />
                <?= isset($errors['newPwd']) ? '<div class="errorMsg">'.$errors['newPwd'].'</div>' : ''; ?>
                <label for="repNewPwd">Répétez le nouveau mot de passe : *</label>
                <input id="repNewPwd" class="<?= isset($errors['repNewPwd']) ? 'fieldError' : ''; ?>" type="password" name="repNewPwd" placeholder="Répétez le nouveau mot de passe" />
                <?= isset($errors['repNewPwd']) ? '<div class="errorMsg">'.$errors['repNewPwd'].'</div>' : ''; ?>
            </form>
            <button form="userPwd" class="btn m-3">Changer</button>
        </div>
        <div id="login" class="settingSubTitle">Gestionnaire d'utilisateurs</div>
        <div class="settingBody">
            <?php 
                if(isset($newLoginSuccess)):
            ?>
            <div class="alertPopup success show">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <span class="alertContent"><?= $newLoginSuccess['msg'] ?></span>
            </div>
            <?php
                endif;
            ?>
            <p>
                <table>
                    <tr>
                        <td>Utilisateur actuel</td><td class="p-2"> : </td><td><?= $_SESSION['user']->use_name ?></td>
                    </tr>
                    <tr>
                        <td>Identifiant</td><td class="p-2"> : </td><td><?= $_SESSION['user']->use_login ?></td>
                    </tr>
                    <tr>
                        <td>Statut</td><td class="p-2"> : </td><td><?= $_SESSION['user']->use_admin ? 'Administrateur' : '' ?></td>
                    </tr>
                </table> 
            </p>
            <form id="userLogin" action="index.php?action=admin&module=settings#login" method="POST">
                <input type="hidden" name="login" value="<?= $_SESSION['user']->use_login ?>" />
                <label for="newLogin">Nouvel identifiant : *</label>
                <input id="newLogin" class="<?= isset($errors['newLogin']) ? 'fieldError' : ''; ?>" type="text" name="newLogin" placeholder="Nouvel identifiant" />
                <?= isset($errors['newLogin']) ? '<div class="errorMsg">'.$errors['newLogin'].'</div>' : ''; ?>
                <label for="repNewLogin">Répétez le nouvel identifiant : *</label>
                <input id="repNewLogin" class="<?= isset($errors['repNewLogin']) ? 'fieldError' : ''; ?>" type="text" name="repNewLogin" placeholder="Répétez le nouvel identifiant" />
                <?= isset($errors['repNewLogin']) ? '<div class="errorMsg">'.$errors['repNewLogin'].'</div>' : ''; ?>
            </form>
            <button form="userLogin" class="btn m-3">Changer</button>
        </div>
    </div>
    <div id="files" class="containSettings">
        <div class="settingTitle">Paramètres de fichiers</div>
        <div class="settingSubTitle">Régulariser</div>
        <div class="settingBody" style="min-height: 10%">Ici, possibilité de vérifier la cohérence entre la base de données et l'arborescence des fichiers sur le serveur. Ainsi, si il y a des fichiers sur le serveur qui ne sont pas enregistrés dans la SGBDR, un petit clique et c'est fait... on en parle plus LuLu</div>
    </div>
    <div id="messenger" class="containSettings">
        <div class="settingTitle">Paramètres de messagerie</div>
        <div class="settingSubTitle">Vider la corbeille</div>
        <div class="settingBody" style="height: 10%">Ici, supression des vilains messages reçus via la page de contact du site client</div>
        <div class="settingSubTitle">Etat de la messagerie</div>
        <div class="settingBody" style="min-height: 10%">Ici, tout un tas de renseignements pour épater la galerie ! Mais aussi la possibilité de tout virer vers la corbeille, de marquer le tout comme lu/non lu etc...</div>
    </div>
    <div class="containSettings">
        <div class="settingTitle">Paramètres de la galerie d'administration</div>
        <div class="settingSubTitle">Tri et affichage</div>
        <div class="settingBody" style="min-height: 10%">Ici, possibilité de controler l'affichage. En vrac, par section, par sous-section, par date, par couleurs, par osiris, par taille aussi... pk pa</div>
    </div>
</div>
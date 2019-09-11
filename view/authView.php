<?php $this->title = 'Authentification' ?>

<div class="authContain">
    <div style="margin: auto auto 0 auto">AJF Administration</div>
    <div class="authBox">
            <div class="fields">
                <form id="authForm" action="admin.html" method="POST">
                    <label for="login">Identifiant</label>
                    <input class="<?= isset($errors['login']) ? 'fieldError' : ''; ?>" id="login" type="text" name="login" />
                    <?= isset($errors['login']) ? '<div class="errorMsg">'.$errors['login'].'</div>' : ''; ?>
                    <label for="password">Mot de passe</label>
                    <input class="<?= isset($errors['password']) ? 'fieldError' : ''; ?>" id="password" type="password" name="password" />
                    <?= isset($errors['password']) ? '<div class="errorMsg">'.$errors['password'].'</div>' : ''; ?>
                </form>
                <div style="text-align: right;">
                    <button form="authForm" class="btn btn-info spaceAround" style="font-size: 12px; background: #bd2d87; border: none;">Se connecter</button>
                </div>
                <div style="margin: 1rem 0 0 3rem; font-size: 12px;">
                    <a href="javascript:void(0)">Mot de passe oublié ?</a>
                </div>
            </div>
    </div>
    <div style="margin: 0 auto auto auto;"><a href="home"><i class="fas fa-fw fa-long-arrow-alt-left"></i> Retour</a></div>
</div>
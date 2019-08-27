<?php $this->title = 'Administration'; ?>

<div class="container" style="padding: 0; margin: 0; display: flex; flex-direction: column; width: 100%; height: 100%; overflow: hidden;">
    <div class="topBar slideTopBar">
        <span style="padding-left: 1rem;">Bienvenue sur votre console d'administration Jean-François</span>
        <div class="clockTime"></div>
    </div>
    <div class="row slideCardAnim" style="margin: auto; width: 50%;">
        <div class="col-12 col-md-6">
            <div class="card" style="background: #28536b">
                <p><i class="far fa-envelope"></i></p>
                <h3><?= $inquiries ?></h3>
                <p>Messages non lus</p>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card" style="background: #845a6d">
                <p><i class="far fa-file-image"></i></p>
                <h3><?= $pieces ?></h3>
                <p>Oeuvres enregistrées</p>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card" style="background: #eca72c">
                <p><i class="fas fa-pencil-alt"></i></p>
                <h3><?= $nullStories ?></h3>
                <p>Editions en attente</p>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card" style="background: #ed6a5a">
                <p><i class="fas fa-calendar-alt"></i></p>
                <h3>0</h3>
                <p>Evènements</p>
            </div>
        </div>
    </div>
</div>
<?php $this->title = 'Administration'; ?>

<div class="text-center" style="width: 100%;">Bienvenue dans votre console administrative Jean-François</div>
<div style="display: flex; width: 100%; height: 60vh;">
<div class="container mx-auto my-auto w-75">
    <div class="row slideanim">
        <div class="col-4">
            <div class="card">
                <p><i class="far fa-envelope"></i></p>
                <h3><?= $inquiries ?></h3>
                <p>Messages non lus</p>
            </div>
        </div>

        <div class="col-4">
            <div class="card">
                <p><i class="far fa-file-image"></i></p>
                <h3><?= $pieces ?></h3>
                <p>Oeuvres enregistrées</p>
            </div>
        </div>

        <div class="col-4">
            <div class="card">
                <p><i class="fas fa-users"></i></p>
                <h3><?= $contacts ?></h3>
                <p>Contacts vous ont écrit</p>
            </div>
        </div>
    </div>
</div>
</div>
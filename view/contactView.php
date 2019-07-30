<?php $this->title = 'Contact'; ?>

<div class="contactHeader"></div>
<div class="contactTitle">
    <div class="boxTitle">Contact</div>
</div>
<div class="row no-gutters text-center">
    <div class="col-6 pt-4 pb-4 mx-auto">
        <form id="contactInquiries" action="index.php?target=contact" method="POST">
            <p>
                <label for="name">Nom</label>
                <input type="text" class="contactField fieldError" placeholder="Votre nom ici" id="name" />
            </p>
            <p>
                <label for="mail">Adresse mail</label>
                <input type="mail" class="contactField" placeholder="Votre adresse mail ici" id="mail" />
            </p>
            <p>
                <label for="object">Objet</label>
                <input type="text" class="contactField" placeholder="L'objet de votre message ici" id="object" />
            </p>
            <p>
                <label for="msg">Votre Message : </label>
                <textarea class="contactField" id="msg"></textarea>
            </p>
            <p>
                <button class="btnContact" type="button" form="contactInquiries">Envoyer</button>
            </p>
        </form>
    </div>
</div>
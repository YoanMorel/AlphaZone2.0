<?php $this->title = 'Contact'; ?>

<div class="contactHeader"></div>
<div class="contactTitle">
    <div class="boxTitle">Contact</div>
</div>
<div class="row no-gutters text-center">
    <div class="col-6 pt-4 pb-4 mx-auto">
        <form id="contactInquiries" action="index.php?action=contact" method="POST">
            <p>
                <label for="lname">Nom</label>
                <input type="text" class="contactField fieldGood" placeholder="Votre nom ici" id="lname" name="lname" />
            </p>
            <p>
                <label for="organisme">Organisme</label>
                <input type="text" class="contactField fieldError" placeholder="Votre prénom ici" id="organisme" name="organisme" />
            </p>
            <p>
                <label for="mail">Adresse mail</label>
                <input type="mail" class="contactField" placeholder="Votre adresse mail ici" id="mail" name="mail" />
            </p>
            <p>
                <label for="subject">Objet</label>
                <input type="text" class="contactField" placeholder="L'objet de votre message ici" id="object" name="subject" />
            </p>
            <p>
                <label for="inquire">Votre Message : </label>
                <textarea class="contactField" id="inquire" name="inquire"></textarea>
            </p>
            <p>
                <button class="btnContact" type="submit" form="contactInquiries">Envoyer</button>
            </p>
        </form>
    </div>
</div>
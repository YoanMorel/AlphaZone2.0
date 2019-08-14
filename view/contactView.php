<?php $this->title = 'Contact'; ?>

<div class="contactHeader"></div>
<div class="contactTitle">
    <div class="boxTitle">Contact</div>
</div>
<div class="row no-gutters text-center">
    <div class="col-6 pt-4 pb-4 mx-auto">
        <form id="contactInquiries" action="index.php?action=contact" method="POST">
            <div class="pb-3">
                <label for="lname">Nom</label>
                <input type="text" class="contactField <?= isset($errors['lname']) ? 'fieldError' : ''; ?>" placeholder="Votre nom ici" id="lname" name="lname" value="<?= isset($varsValue['lname']) ? $varsValue['lname'] : ''; ?>" />
                <?= isset($errors['lname']) ? '<div class="errorMsg">'.$errors['lname'].'</div>' : ''; ?>
            </div>
            <div class="pb-3">
                <label for="organisme">Organisme</label>
                <input type="text" class="contactField <?= isset($errors['organisme']) ? 'fieldError' : ''; ?>" placeholder="Votre organisme ici" id="organisme" name="organisme" value="<?= isset($varsValue['lname']) ? $varsValue['organisme'] : ''; ?>" />
                <?= isset($errors['organisme']) ? '<div class="errorMsg">'.$errors['organisme'].'</div>' : ''; ?>
            </div>
            <div class="pb-3">
                <label for="mail">Adresse mail</label>
                <input type="mail" class="contactField <?= isset($errors['mail']) ? 'fieldError' : ''; ?>" placeholder="Votre adresse mail ici" id="mail" name="mail" value="<?= isset($varsValue['lname']) ? $varsValue['mail'] : ''; ?>" />
                <?= isset($errors['mail']) ? '<div class="errorMsg">'.$errors['mail'].'</div>' : ''; ?>
            </div>
            <div class="pb-3">
                <label for="subject">Objet</label>
                <input type="text" class="contactField <?= isset($errors['subject']) ? 'fieldError' : ''; ?>" placeholder="L'objet de votre message ici" id="subject" name="subject" value="<?= isset($varsValue['subject']) ? $varsValue['subject'] : ''; ?>" />
                <?= isset($errors['subject']) ? '<div class="errorMsg">'.$errors['subject'].'</div>' : ''; ?>
            </div>
            <div class="pb-3">
                <label for="inquire">Votre Message : </label>
                <textarea class="contactField" id="inquire" name="inquire" value="<?= isset($varsValue['inquire']) ? $varsValue['inquire'] : ''; ?>"></textarea>
            </div>
            <div>
                <button class="btnContact" type="submit" form="contactInquiries">Envoyer</button>
            </div>
        </form>
    </div>
</div>
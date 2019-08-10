<?php

class FormValidator {

    private static $ERROR_EMPTY_ELEMENT = [
        'lname' => 'Vous devez renseigner un nom',
        'mail' => 'Vous devez renseigner une adresse mail'
    ];

    private static $ERROR_INVALID_ELEMENT = [
        'lname' => 'Ce n\'est pas un nom valide',
        'mail' => 'Cette adresse mail est invalide'
    ];

    private $errors = [];
    private $filterRules = [];

    public function __get($value) {
        if($value != 'errors'):
            throw new BadMethodCallException(__CLASS__ . '::'.$value.' : inaccessible ou inexistant.');
        endif;
        return $this->errors;
    }

    public function __construct() {
        $this->filterRules = [
            'lname' => [
                'filter' => FILTER_CALLBACK,
                'options' => [$this, 'filterLname']
            ],
            'mail' => [
                'filter' => FILTER_CALLBACK,
                'options' => [$this, 'filterMailAdress']
            ]
        ];
    }

    public function validationFilter() {
        $filter = filter_input_array(INPUT_POST, $this->filterRules);
        foreach(array_keys($filter, NULL, true) as $key):
            if(empty($this->errors[$key]) && !empty(self::$ERROR_EMPTY_ELEMENT[$key])):
                $this->errors[$key] = self::$ERROR_EMPTY_ELEMENT[$key];
            endif;
        endforeach;
    }

    public function getErrors() {
        return !empty($this->errors);
    }

    private function filterMailAdress($input) {
        $filter = NULL; // simule FILTER_NULL_ON_FAILURE
        if(!empty($input)):
            $mail = filter_var($input, FILTER_SANITIZE_EMAIL);
            $mail = filter_var($input, FILTER_VALIDATE_EMAIL);
            if($mail === false):
                $this->errors['mail'] = self::$ERROR_INVALID_ELEMENT['mail'];
            else:
                $filter = $mail;
            endif;
        endif;

        return $filter;
    }

    private function filterLname($input) {
        $filter = NULL;
        if(!empty($input)):
            $lname = filter_var($input, FILTER_SANITIZE_STRING);
            if(preg_match("/^[a-zA-ZéèÉÈôîêûÛÊÔÎùÙïöëüËÏÖÜç']{2,17}[- ']?[a-zA-ZéèÉÈôîêûÛÊÔÎùÙïöëüËÏÖÜç]{0,17}$/", $lname)):
                return $filter = $lname;
            else:
                $this->errors['lname'] = self::$ERROR_INVALID_ELEMENT['lname'];
            endif;
        endif;

        return $filter;
    }
}

// vvv HTML DE TEST POUR LA CLASS DE VALIDATION vvv
// INPUT_POST n'est pas initialisée si la REQUEST_METHOD n'est pas égale à POST
// Donc aucun test ne peut se faire en initialisant la variable POST directement dans le code
?>
<!-- <form id="contactInquiries" action="FormValidator.php" method="POST">
            <p>
                <label for="lname">Nom</label>
                <input type="text" class="contactField fieldGood" placeholder="Votre nom ici" id="lname" name="lname" />
            </p>
            <p>
                <label for="mail">Adresse mail</label>
                <input type="mail" class="contactField" placeholder="Votre adresse mail ici" id="mail" name="mail" />
            </p>
            <p>
                <button class="btnContact" type="submit" form="contactInquiries">Envoyer</button>
            </p>
</form> -->
<?php
// if(!empty($_POST)):
// $validator = new FormValidator();
// $validator->validationFilter();
// if($validator->getErrors()):
//     var_dump($validator->errors);
// else:
//     echo 'Clear';
// endif;
// endif;
?>
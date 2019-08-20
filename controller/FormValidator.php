<?php

class FormValidator {

    private static $ERROR_EMPTY_ELEMENT = [
        'lname'     => 'Vous devez renseigner un nom',
        'mail'      => 'Vous devez renseigner une adresse mail',
        'subject'   => 'Vous devez renseigner un objet',
        'inquire'   => 'Vous devez saisir un commentaire',
        'login'     => 'Vous devez renseigner un identifiant',
        'password'  => 'Vous devez saisir un mot de passe',
        'repeatPwd' => 'Vous devez saisir une deuxième fois votre mot de passe'
    ];

    private static $ERROR_INVALID_ELEMENT = [
        'lname'     => 'Ce n\'est pas un nom valide',
        'mail'      => 'Cette adresse mail est invalide',
        'subject'   => 'Ce n\'est pas un nom d\'objet valide',
        'inquire'   => 'Ce commentaire n\'est pas valide',
        'login'     => 'Cet identifiant/mot de passe n\'est pas valide ou n\'éxiste pas',
        'password'  => 'Cet identifiant/mot de passe n\'est pas valide ou n\'existe pas',
        'repeatPwd' => 'Les mots de passe saisis ne coïncide pas'
    ];

    private static $ERROR_ALREADY_EXISTS_ELEMENT = [
        'login'     => 'Vous utilisez déjà cet identifiant',
        'password'  => 'Vous devez saisir un nouveau mot de passe'
    ];

    private $errors         = [];
    private $filterRules    = [];

    public function __get($value) {
        if($value != 'errors'):
            throw new BadMethodCallException(__CLASS__ . '::'.$value.' : inaccessible ou inexistant.');
        endif;
        return $this->errors;
    }

    public function __construct() {
        $this->filterRules = [
            'lname' => [
                'filter'    => FILTER_CALLBACK,
                'options'   => [$this, 'lnameFilter']
            ],
            'mail'  => [
                'filter'    => FILTER_CALLBACK,
                'options'   => [$this, 'mailAdressFilter']
            ],
            'subject' => [
                'filter'    => FILTER_CALLBACK,
                'options'   => [$this, 'subjectFilter']
            ],
            'inquire' => [
                'filter'    => FILTER_CALLBACK,
                'options'   => [$this, 'inquireFilter']
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

    public function hasErrors() {
        return !empty($this->errors);
    }

    private function mailAdressFilter($input) {
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

    private function lnameFilter($input) {
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

    private function subjectFilter($input) {
        $filter = NULL;
        if(!empty($input)):
            $subject = filter_var($input, FILTER_SANITIZE_STRING);
            if($subject === false):
                $this->errors['subject'] = self::$ERROR_INVALID_ELEMENT['subject'];
            else:
                $filter = $subject;
            endif;
        endif;

        return $filter;
    }

    private function inquireFilter($input) {
        $filter = NULL;
        if(!empty($input)):
            $inquire = filter_var($input, FILTER_SANITIZE_STRING);
            if($inquire === false):
                $this->errors['inquire'] = self::$ERROR_INVALID_ELEMENT['inquire'];
            else:
                $filter = $inquire;
            endif;
        endif;

        return $filter;
    }
}

// vvv HTML DE TEST POUR LA CLASS DE VALIDATION vvv
// INPUT_POST n'est pas initialisée si la REQUEST_METHOD n'est pas égale à POST
// Donc aucun test ne peut se faire en initialisant la variable POST directement dans le code
?>

<?php

class FormValidator extends User {

    private static $ERROR_EMPTY_ELEMENT = [
        'lname'         => 'Saisissez un nom',
        'mail'          => 'Saisissez une adresse mail',
        'subject'       => 'Saisissez l\'objet du commentaire',
        'inquire'       => 'Saisissez un commentaire',
        'login'         => 'Saisissez un identifiant',
        'newLogin'      => 'Saisissez un nouvel identifiant',
        'repNewLogin'   => 'Saisissez une deuxième fois votre nouvel identifiant',
        'password'      => 'Saisissez un mot de passe',
        'newPwd'        => 'Saisissez un nouveau mot de passe',
        'repNewPwd'     => 'Saisissez une deuxième fois votre nouveau mot de passe'
    ];

    private static $ERROR_INVALID_ELEMENT = [
        'lname'         => 'Ce n\'est pas un nom valide',
        'mail'          => 'Cette adresse mail est invalide',
        'subject'       => 'Ce n\'est pas un nom d\'objet valide',
        'inquire'       => 'Ce commentaire n\'est pas valide',
        'login'         => 'Cet identifiant/mot de passe n\'est pas valide ou n\'éxiste pas',
        'password'      => 'Cet identifiant/mot de passe n\'est pas valide',
        'newLogin'      => 'Cet identifiant n\'est pas valide',
        'repNewLogin'   => 'Les identifiants saisis ne coïncident pas',
        'newPwd'        => 'Ce mot de passe n\'est pas valide',
        'repNewPwd'     => 'Les mots de passe saisis ne coïncident pas'
    ];

    private static $ERROR_ALREADY_EXISTS_ELEMENT = [
        'newLogin'  => 'Vous utilisez déjà cet identifiant',
        'newPwd'    => 'Vous devez saisir un nouveau mot de passe'
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
            'lname'     => [
                'filter'    => FILTER_CALLBACK,
                'options'   => [$this, 'lnameFilter']
            ],
            'mail'      => [
                'filter'    => FILTER_CALLBACK,
                'options'   => [$this, 'mailAdressFilter']
            ],
            'subject'   => [
                'filter'    => FILTER_CALLBACK,
                'options'   => [$this, 'subjectFilter']
            ],
            'inquire'   => [
                'filter'    => FILTER_CALLBACK,
                'options'   => [$this, 'inquireFilter']
            ],
            'login'     => [
                'filter'    => FILTER_CALLBACK,
                'options'   => [$this, 'loginFilter']
            ],
            'newLogin'  => [
                'filter'    => FILTER_CALLBACK,
                'options'   => [$this, 'newLoginFilter']
            ],
            'password'  => [
                'filter'    => FILTER_CALLBACK,
                'options'   => [$this, 'passwordFilter']
            ],
            'newPwd'    => [
                'filter'    => FILTER_CALLBACK,
                'options'   => [$this, 'newPwdFilter']
            ]
        ];
    }

    public function validationFilter() {
        $filter = filter_input_array(INPUT_POST, $this->filterRules);
        foreach(array_keys($filter, NULL, true) as $key):
            if(empty($this->errors[$key]) && !empty(self::$ERROR_EMPTY_ELEMENT[$key]) && isset($_POST[$key])):
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

    private function loginFilter($input) {
        $filter = NULL;
        if(!empty($input)):
            $login = filter_var($input, FILTER_SANITIZE_STRING);
            if($login === false):
                $this->errors['login'] = self::$ERROR_INVALID_ELEMENT['login'];
            else:
                if($this->getUser($login)->fetchAll()):
                    $filter = $login;
                else:
                    $this->errors['login'] = self::$ERROR_INVALID_ELEMENT['login'];
                endif;
            endif;
        endif;

        return $filter;
    }

    private function newLoginFilter($input) {
        $filter = NULL;
        if(!empty($input)):
            $newLogin = filter_var($input, FILTER_SANITIZE_STRING);
            if($newLogin === false):
                $this->errors['newLogin'] = self::$ERROR_INVALID_ELEMENT['newLogin'];
            else:
                if(filter_input(INPUT_POST, 'repNewLogin', FILTER_SANITIZE_STRING) == $newLogin):
                    if($this->getUser($newLogin)->fetchAll()):
                        $this->errors['newLogin'] = self::$ERROR_ALREADY_EXISTS_ELEMENT['newLogin'];
                    else:
                        $filter = $newLogin;
                    endif;
                else:
                    $this->errors['repNewLogin'] = self::$ERROR_INVALID_ELEMENT['repNewLogin'];
                endif;
            endif;
        endif;

        return $filter;
    }

    private function passwordFilter($input) {
        $filter = NULL;
        if(!empty($input)):
            $pwd = filter_var($input, FILTER_SANITIZE_STRING);
            if($pwd === false):
                $this->errors['password'] = self::$ERROR_INVALID_ELEMENT['password'];
            else:
                $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
                if($registeredPwd = $this->getPassword($login)->fetchAll()):
                    if(password_verify($pwd, $registeredPwd[0]['USE_PWD'])):
                        $filter = $pwd;
                    else:
                        $this->errors['password'] = self::$ERROR_INVALID_ELEMENT['password'];
                    endif;
                endif;
            endif;
        endif;
        return $filter;
    }

    private function newPwdFilter($input) {
        $filter = NULL;
        if(!empty($input)):
            $newPwd         = filter_var($input, FILTER_SANITIZE_STRING);
            $password       = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
                if($newPwd === false):
                    $this->errors['newPwd'] = self::$ERROR_INVALID_ELEMENT['newPwd'];
                else:
                    if(filter_input(INPUT_POST, 'repNewPwd', FILTER_SANITIZE_STRING) == $newPwd):
                        if($newPwd == $password):
                            $this->errors['newPwd'] = self::$ERROR_ALREADY_EXISTS_ELEMENT['newPwd'];
                        else:
                            $filter = $newPwd;
                        endif;
                    else:
                        $this->errors['repNewPwd'] = self::$ERROR_INVALID_ELEMENT['repNewPwd'];
                    endif;
                endif;
        endif;

        return $filter;
    }
}

//                      ---- [HTML DE TEST POUR LA CLASS DE VALIDATION] ----
// INPUT_POST n'est pas initialisée si la REQUEST_METHOD n'est pas égale à POST
// Donc aucun test ne peut se faire en initialisant la variable POST directement dans le code
// excepté si l'on crée un formulaire sur la page
// vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
?>

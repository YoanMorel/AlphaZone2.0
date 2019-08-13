<?php

    require_once 'model/DbConnection.php';
    require_once 'model/Inquires.php';
    require_once 'controller/FormValidator.php';
    require_once 'view/View.php';

class ContactCtrl {

    private $inquires;

    public function __construct() {
        $this->inquires = new Inquires();
    }

    public function contactView($content = [false]) {
        $view = new View('contact');
        $view->generate($content);
    }

    public function inquires($lname, $organisme, $mail, $subject, $inquire) {
        $validation = new FormValidator();
        $validation->validationFilter();
        if($validation->getErrors()):
            $this->contactView(['errors' => [$validation->errors, $lname, $organisme, $mail, $subject, $inquire]]);
        else:
            if($this->inquires->addInquire($lname, $organisme, $mail, $subject, $inquire)):
                unset($_POST);
                $this->contactView(['infos' => [$lname, $organisme, $mail, $subject, $inquire]]);
            else:
                $view = new View('error');
                $view->generate(['msgError' => 'Un problème avec la Base de données a été rencontré'], false);
            endif;
        endif;
    }
}

?>
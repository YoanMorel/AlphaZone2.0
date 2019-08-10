<?php

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'):
    require_once '../model/DbConnection.php';
    require_once '../model/Inquires.php';
else:
    require_once 'model/DbConnection.php';
    require_once 'model/Inquires.php';
    require_once 'view/View.php';
endif;


class ContactCtrl {

    private $inquires;

    public function __construct() {
        $this->inquires = new Inquires();
    }

    public function contactView($content = [false]) {
        $view = new View('contact');
        $view->generate($content, false);
    }

    public function inquires($lname, $organisme, $mail, $subject, $inquire) {
        if($this->inquires->addInquire($lname, $organisme, $mail, $subject, $inquire)):
            unset($_POST);
            $this->contactView(['infos' => [$lname, $organisme, $mail]]);
        else:
            $view = new View('error');
            $view->generate(['msgError' => 'Un problème avec la Base de données a été rencontré'], false);
        endif;
    }
}

?>
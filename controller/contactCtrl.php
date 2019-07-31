<?php

require_once 'model/DbConnection.php';
require_once 'model/Inquires.php';
require_once 'view/View.php';

class ContactCtrl {

    private $inquires;

    public function __construct() {
        $this->inquires = new Inquires();
    }

    public function contactView($content = array(false)) {
        $view = new View('contact');
        $view->generate($content, false);
    }

    public function inquires($lname, $fname, $mail, $subject, $inquire) {
        if($this->inquires->addInquire($lname, $fname, $mail, $subject, $inquire)):
            unset($_POST);
            $this->contactView(array('infos' => array($lname, $fname, $mail)));
        else:
            $view = new View('error');
            $view->generate(array('msgError' => 'Un problème avec la Base de données a été rencontré'), false);
        endif;
    }
}

?>
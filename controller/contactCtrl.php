<?php
require_once 'view/View.php';

class ContactCtrl {
    public function contactView() {
        $view = new View('contact');
        $view->generate(array(false), false);
    }
}

?>
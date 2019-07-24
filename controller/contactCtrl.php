<?php
require_once 'view/View.php';

class ContactCtrl {
    public function contactView() {
        $view = new View('uc');
        $view->generate(array(false), false);
    }
}

?>
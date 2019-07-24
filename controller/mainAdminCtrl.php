<?php

require_once 'view/View.php';

class MainAdminCtrl {
    public function ucView() {
        $view = new View('uc');
        $view->generate(array(false), true);
    }
}

?>
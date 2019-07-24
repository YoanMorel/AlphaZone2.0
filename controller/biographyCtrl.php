<?php
require_once 'view/View.php';

class BiographyCtrl {
    public function biographyView() {
        $view = new View('uc');
        $view->generate(array(false), false);
    }
}

?>
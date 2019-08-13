<?php
require_once 'view/View.php';

class PhilosophyCtrl {
    public function philosophyView() {
        $view = new View('uc');
        $view->generate([false]);
    }
}

?>
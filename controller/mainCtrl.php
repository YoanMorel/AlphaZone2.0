<?php

require_once 'view/View.php';

class MainCtrl {

    public function mainView() {
        $view = new View('main');
        $view->generate([null]);
    }
}
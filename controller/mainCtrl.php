<?php

require_once 'view/View.php';

class MainCtrl {


    public function __construct() {

    }

    public function mainView() {
        $view = new View('main');
        $view->generate(array(false), false);
    }
}
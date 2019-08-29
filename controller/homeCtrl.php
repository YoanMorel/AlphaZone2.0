<?php

require_once 'view/View.php';

class HomeCtrl {

    public function homeView() {
        $view = new View('home');
        $view->generate([null]);
    }
}
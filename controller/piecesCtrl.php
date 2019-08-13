<?php
require_once 'view/View.php';

class PiecesCtrl {
    public function piecesView() {
        $view = new View('uc');
        $view->generate([false]);
    }
}

?>
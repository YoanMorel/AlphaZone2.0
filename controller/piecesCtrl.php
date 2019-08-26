<?php

require_once 'model/DbConnection.php';
require_once 'model/Gallery.php';
require_once 'view/View.php';

class PiecesCtrl {

    private $gallery;

    public function __construct() {
        $this->gallery = new Gallery();
    }

    public function piecesView() {
        $view = new View('pieces');
        $view->generate([null]);
    }

    public function piecesAdminView() {
        $view       = new View('piecesAdmin');
        $pieces     = $this->gallery->getAllPieces()->fetchAll();
        $sections   = $this->gallery->getSections()->fetchAll();

        $view->generate(['pieces' => $pieces, 'sections' => $sections], true);
    }
}

?>
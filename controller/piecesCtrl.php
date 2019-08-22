<?php

require_once 'model/DbConnection.php';
require_once 'model/Gallery.php';
require_once 'model/UploadHandler.php';
require_once 'controller/scanDirCtrl.php';
require_once 'view/View.php';

class PiecesCtrl {
    public function piecesView() {
        $view = new View('pieces');
        $view->generate([null]);
    }

    public function piecesAdminView() {
        $view = new View('piecesAdmin');
        $gallery = new Gallery();
        $scanDir = new ScanDir('gallery');
        $pieces = $gallery->getAllPieces()->fetchAll();
        $imgLinks = $scanDir->getDataScan();
        $view->generate(['pieces' => $pieces, 'imgLinks' => $imgLinks], true);
    }
}

?>
<?php

require_once 'model/DbConnection.php';
require_once 'model/Gallery.php';
require_once 'model/DataHandler.php';
require_once 'controller/scanDirCtrl.php';
require_once 'view/View.php';

class PiecesCtrl {

    private $gallery;

    public function __construct() {
        $this->gallery = new Gallery();
    }

    public function galleryView() {
        $view = new View('gallery');
        $gallery = [];
        $piecesLink = $this->gallery->getPiecesLink()->fetchAll();
        foreach($piecesLink as $row):
            $links = explode(',', $row['linkPieces']);
            foreach($links as $link):
                $gallery[$row['SEC_SECTION']][] = $link;
            endforeach;
        endforeach;
        $view->generate(['gallery' => $gallery]);
    }

    public function piecesAdminView() {
        $view       = new View('piecesAdmin');
        $pieces     = $this->gallery->getAllPieces()->fetchAll();
        $sections   = $this->gallery->getSections()->fetchAll();

        $view->generate(['pieces' => $pieces, 'sections' => $sections], true);
    }

    public function piecesView($section) {
        $view = new View('gallery');
        $pieces = $this->gallery->getPiecesLink()->fetchAll();
        $links = [];
        foreach($pieces as $row):
            if(strtolower($row['SEC_SECTION']) == $section):
                $sectionTitle = $row['SEC_SECTION'];
                $piecesLink = explode(',', $row['linkPieces']);
                foreach($piecesLink as $link):
                    if(getimagesize($link)[0] > getimagesize($link)[1])
                        $direction = 'horizontal';
                    if(getimagesize($link)[0] < getimagesize($link)[1])
                        $direction = 'vertical';
                    if(getimagesize($link)[0] == getimagesize($link)[1])
                        $direction = 'big';
                    $links[] = [$link, $direction];
                endforeach;
            endif;
        endforeach;

        $view->generate(['sectionGallery' => $links, 'sectionTitle' => $sectionTitle]);
    }
}

?>
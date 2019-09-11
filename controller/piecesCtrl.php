<?php

require_once 'model/DbConnection.php';
require_once 'model/Gallery.php';
require_once 'model/DataHandler.php';
require_once 'controller/scanDirCtrl.php';
require_once 'view/View.php';

/**
 * PiecesCtrl class
 * 
 * Pieces and gallery controller.
 * 
 * @version 3.0
 * @author  Yoan Morel
 */
class PiecesCtrl {

    /**
     * Attributes to store Gallery instance
     */
    private $gallery;

    /**
     * Magic construct method to instanciate Gallery model
     */
    public function __construct() {
        $this->gallery = new Gallery();
    }

    /**
     * Method that uses the Gallery model to get pieces information from the database
     * then, generate the view
     */
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

    /**
     * Method that uses the Gallery model to get pieces information from the database
     * then, generate the admin view
     */
    public function piecesAdminView() {
        $view       = new View('piecesAdmin');
        $pieces     = $this->gallery->getAllPieces()->fetchAll();
        $sections   = $this->gallery->getSections()->fetchAll();

        $view->generate(['pieces' => $pieces, 'sections' => $sections], true);
    }

    /**
     * Method that uses the Gallery model to get pieces information from the database
     * then, create a links array that contain all links to display.
     * It also create the navigation to move in the galleries
     * 
     * @param string $section The requested section
     */
    public function piecesView($section) {
        $view = new View('gallery');
        $pieces = $this->gallery->getPiecesLink()->fetchAll();
        $sections = [];
        $links = [];

        // Create gallery from the requested section
        foreach($pieces as $row):
            $sections[] = $row['SEC_SECTION'];
            if(strtolower($row['SEC_SECTION']) == $section):
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

        // Create the navigation from the requested section
        if(isset($links)):
            while(strtolower(current($sections)) != $section) next($sections);
            $nav['prev']    = array_search(ucfirst($section), $sections) != 0 ? prev($sections) : FALSE;
            $nav['current'] = $nav['prev'] != FALSE ? next($sections) : current($sections);
            $nav['next']    = next($sections);
        endif;

        $view->generate(['sectionGallery' => $links, 'nav' => $nav]);
    }
}

?>
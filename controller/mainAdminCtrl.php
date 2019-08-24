<?php

require_once 'model/DbConnection.php';
require_once 'model/Inquiries.php';
require_once 'model/Gallery.php';
require_once 'view/View.php';

class MainAdminCtrl {

    private $inquiries;
    private $gallery;

    public function __construct() {
        $this->inquiries    = new Inquiries();
        $this->gallery      = new Gallery();
    }

    public function mainAdminView() {
        $view           = new View('mainAdmin');
        $pieces         = $this->gallery->getAllPieces()->rowCount();
        $inquiries      = $this->inquiries->getSealedInquiries()->rowCount();
        $contacts       = $this->inquiries->getContacts()->rowCount();
        $nullStories    = $this->gallery->getNullStories()->rowCount();

        $view->generate([
            'pieces'        => $pieces,
            'inquiries'     => $inquiries,
            'contacts'      => $contacts,
            'nullStories'   => $nullStories]
            , true
        );
    }

    public function ucView() {
        $view = new View('uc');
        $view->generate([null], true);
    }
}

?>
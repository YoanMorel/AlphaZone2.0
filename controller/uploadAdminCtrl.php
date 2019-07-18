<?php

require_once 'view/View.php';

class UploadAdminCtrl {

    public function uploadView() {
        $view = new View('upload');
        $view->generate(false, true);
    }

}

?>
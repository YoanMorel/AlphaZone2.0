<?php

require_once 'model/DbConnection.php';
require_once 'model/DataHandler.php';
require_once 'model/Gallery.php';
require_once 'model/Inquiries.php';
require_once 'model/User.php';
require_once 'controller/scanDirCtrl.php';
require_once 'controller/FormValidator.php';
require_once 'view/View.php';

class AdminSettings {

    private $scan;

    public function __construct() {
        $this->scan = new ScanDir('gallery/');
    }
    public function settingsView() {
        $logs = null;
        $view = new View('settingsAdmin');
        $this->scan->getScans();
        if($this->scan->hasLog())
            $logs = $this->scan->log;

        $view->generate(['logs', $logs], true);
    }
}

?>
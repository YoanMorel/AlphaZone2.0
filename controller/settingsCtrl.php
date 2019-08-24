<?php

require_once 'view/View.php';

class AdminSettings {
    public function settingsView() {
        $view = new View('adminSettings');
        $view->generate([null], true);
    }
}

?>
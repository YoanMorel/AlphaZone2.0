<?php

require_once 'view/View.php';

class Events {
    public function eventsView() {
        $view = new View('eventsAdmin');
        $view->generate([null], true);
    }
}

?>
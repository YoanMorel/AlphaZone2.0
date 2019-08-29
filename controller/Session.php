<?php

class Session {

    public function __construct() {
        session_start();
    }

    public function destroy() {
        session_destroy();
    }

    public function setSession($index, $value) {
        $_SESSION[$index] = $value;
    }

    public function existsSession($index) {
        return (isset($_SESSION[$index]) && !empty($_SESSION[$index]));
    }

    public function getSessionAttribut($index) {
        if($this->existsSession($index)):
            return $_SESSION[$index];
        else:
            throw new Exception('L\'attribut n\'existe pas dans la Session');
        endif;
    }
}
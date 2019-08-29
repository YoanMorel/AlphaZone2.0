<?php
session_start();

require_once 'model/DbConnection.php';
require_once 'model/Inquiries.php';
require_once 'model/Gallery.php';
require_once 'model/User.php';
require_once 'controller/FormValidator.php';
require_once 'controller/Session.php';
require_once 'view/View.php';

class AdminCtrl{

    private $inquiries;
    private $gallery;

    public function __construct() {
        $this->inquiries    = new Inquiries();
        $this->gallery      = new Gallery();
    }

    public function adminView() {
        $view           = new View('admin');
        $pieces         = $this->gallery->getAllPieces()->rowCount();
        $inquiries      = $this->inquiries->getSealedInquiries()->rowCount();
        $nullStories    = $this->gallery->getNullStories()->rowCount();

        $view->generate([
            'pieces'        => $pieces,
            'inquiries'     => $inquiries,
            'nullStories'   => $nullStories]
            , true
        );
    }

    public function authView($content = [null]) {
        $view = new View('auth');
        $view->generate($content, true);
    }

    public function authUser($login, $password) {
        $validation = new FormValidator();
        $validation->validationFilter();
        if($validation->hasErrors() && (isset($validation->errors['login']) || isset($validation->errors['password']))):
            $this->authView(['errors' => $validation->errors]);
        else:
            $user             = new User();
            $userInfos        = json_encode(array_change_key_case($user->getUser($login)->fetchAll()[0]));
            $_SESSION['user'] = json_decode($userInfos);

            header('location: main.html');
        endif;
    }

    public function exitAdmin() {
        session_destroy();
        header('location: main.html');
    }
}

?>
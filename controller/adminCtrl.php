<?php
session_start();

require_once 'model/DbConnection.php';
require_once 'model/Inquiries.php';
require_once 'model/Gallery.php';
require_once 'model/User.php';
require_once 'controller/FormValidator.php';
require_once 'controller/Session.php';
require_once 'view/View.php';

/**
 * AdminCtrl class
 * 
 * Class controller to handle auth admin, admin view and admin deconnection
 * 
 * @version 1.2
 * @author 	Yoan Morel
 */
class AdminCtrl{

    /**
     * Storing class instances
     */
    private $inquiries;
    private $gallery;

    /**
     * Magic construct method. Instanciate Inquiries and Gallery classes and stores them
     */
    public function __construct() {
        $this->inquiries    = new Inquiries();
        $this->gallery      = new Gallery();
    }

    /**
     * Method for generate administration view
     */
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

    /**
     * Method for generate authentication view
     */
    public function authView($content = [null]) {
        $view = new View('auth');
        $view->generate($content, true);
    }

    /**
     * Method for admin authentication and redirect to administration main page
     * 
     * @param string $login admin login
     */
    public function authUser($login, $password) {
        $validation = new FormValidator();
        $validation->validationFilter();
        if($validation->hasErrors() && (isset($validation->errors['login']) || isset($validation->errors['password']))):
            $this->authView(['errors' => $validation->errors]);
        else:
            $user             = new User();
            $userInfos        = json_encode(array_change_key_case($user->getUser($login)->fetchAll()[0]));
            $_SESSION['user'] = json_decode($userInfos);

            header('location: index.php?action=admin&module=main');
        endif;
    }

    /**
     * Method for admin deconnection and redirect to auth view
     */
    public function exitAdmin() {
        session_destroy();
        header('location: admin.html');
    }
}

?>
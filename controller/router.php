<?php

require_once 'controller/uploadAdminCtrl.php';
require_once 'controller/mainAdminCtrl.php';
require_once 'controller/mainCtrl.php';
require_once 'view/View.php';

class Router {

    private $mainCtrl;
    private $mainAdminCtrl;
    private $uploadAdminCtrl;

    public function __construct() {
        $this->mainCtrl         = new MainCtrl();
        $this->mainAdminCtrl    = new MainAdminCtrl(); 
        $this->uploadAdminCtrl  = new UploadAdminCtrl();
    }

    public function getRoute() {
        try {
            if (isset($_GET['target']) && $_GET['target'] == 'main'):
                $this->mainCtrl->mainView();
            elseif (isset($_GET['target']) && $_GET['target'] == 'admin'):
                // if (!isset($_SESSION['sUser'])):
                //     $login = $this->getParams($_POST, 'login');
                //     $pwd = $this->getParams($_POST, 'pwd');
                //     $this->mainAdminCtrl->authAdmin($login, $pwd);
                // endif;
                if (isset($_GET['module'])):
                    if ($_GET['module'] == 'main'):
                        $this->mainAdminCtrl->ucView();
                    endif;
                    if ($_GET['module'] == 'upload'):
                        $this->uploadAdminCtrl->uploadView();
                    endif;
                    if ($_GET['module'] == 'update'):
                        $this->mainAdminCtrl->ucView();
                    endif;
                    if ($_GET['module'] == 'contact'):
                        $this->mainAdminCtrl->ucView();
                    endif;
                endif;
            else:
                throw new Exception('Cible GET non valide');
            endif;
        } catch (Exception $error) {
            $this->errorAlert($error->getMessage());
        }
    }

    private function errorAlert($msgError) {
        $view = new View('error');
        $view->generate(array('msgError' => $msgError));
    }

    private function getParams($tab, $name) {
        if (isset($tab[$name])):
            return $tab[$name];
        else:
            throw new Exception('Paramètre '.$name.' introuvable');
        endif;
    }
}
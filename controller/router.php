<?php

require_once 'controller/uploadAdminCtrl.php';
require_once 'controller/mainAdminCtrl.php';
require_once 'controller/mainCtrl.php';
require_once 'controller/piecesCtrl.php';
require_once 'controller/philosophyCtrl.php';
require_once 'controller/biographyCtrl.php';
require_once 'controller/contactCtrl.php';
require_once 'controller/settingsCtrl.php';
require_once 'controller/eventsAdminCtrl.php';
require_once 'view/View.php';

class Router {

    private $mainCtrl;
    private $piecesCtrl;
    private $philosophyCtrl;
    private $biographyCtrl;
    private $contactCtrl;
    private $mainAdminCtrl;
    private $uploadAdminCtrl;
    private $adminSettings;
    private $eventsAdminCtrl;

    public function __construct() {
        
        $this->mainCtrl         = new MainCtrl();
        $this->piecesCtrl       = new PiecesCtrl();
        $this->philosophyCtrl   = new PhilosophyCtrl();
        $this->biographyCtrl    = new BiographyCtrl();
        $this->contactCtrl      = new ContactCtrl();
        $this->mainAdminCtrl    = new MainAdminCtrl(); 
        $this->uploadAdminCtrl  = new UploadAdminCtrl();
        $this->adminSettings    = new AdminSettings();
        $this->eventsAdminCtrl  = new Events();

    }

    public function getRoute() {
        try {
            if(isset($_GET['action'])):
                if($_GET['action'] == 'main'):
                    $this->mainCtrl->mainView();
                elseif($_GET['action'] == 'pieces'):
                    $this->piecesCtrl->piecesView();
                elseif($_GET['action'] == 'philosophy'):
                    $this->philosophyCtrl->philosophyView();
                elseif($_GET['action'] == 'biography'):
                    $this->biographyCtrl->biographyView();
                elseif($_GET['action'] == 'contact'):
                    if(empty($_POST)):
                        $this->contactCtrl->contactView();
                    else:
                        array_map('htmlspecialchars', $_POST);

                        $lname      = $this->getParams($_POST, 'lname');
                        $organisme  = $this->getParams($_POST, 'organisme');
                        $mail       = $this->getParams($_POST, 'mail');
                        $subject    = $this->getParams($_POST, 'subject');
                        $inquire    = $this->getParams($_POST, 'inquire');

                        $this->contactCtrl->inquiries($lname, $organisme, $mail, $subject, $inquire);
                    endif;
                elseif($_GET['action'] == 'admin'):
                    // if (!isset($_SESSION['sUser'])):
                    //     $login = $this->getParams($_POST, 'login');
                    //     $pwd = $this->getParams($_POST, 'pwd');
                    //     $this->mainAdminCtrl->authAdmin($login, $pwd);
                    // endif;
                    if(isset($_GET['module'])):
                        if($_GET['module'] == 'main'):
                            $this->mainAdminCtrl->mainAdminView();
                        endif;
                        if($_GET['module'] == 'upload'):
                            $this->uploadAdminCtrl->uploadView();
                        endif;
                        if($_GET['module'] == 'update'):
                            $this->piecesCtrl->piecesAdminView();
                        endif;
                        if($_GET['module'] == 'contact'):
                            $this->contactCtrl->messengerView();
                        endif;
                        if($_GET['module'] == 'events'):
                            $this->eventsAdminCtrl->eventsView();
                        endif;
                        if($_GET['module'] == 'settings'):
                            $this->adminSettings->settingsView();
                        endif;
                    else:
                        $this->errorAlert('Index GET invalide !', true);
                    endif;
                else:
                    $this->errorAlert('Index GET invalide !', false);  
                endif;
            else:
                $this->mainCtrl->mainView();
            endif;
        } catch (Exception $error) {
            $this->errorAlert($error->getMessage());
        }
    }

    private function errorAlert($msgError, $admin = false) {
        $view = new View('error');
        $view->generate(['msgError' => $msgError, 'admin' => $admin], $admin);
    }

    private function getParams($tab, $name) {
        if (isset($tab[$name])):
            return $tab[$name];
        else:
            throw new Exception('Paramètre '.$name.' introuvable');
        endif;
    }
}
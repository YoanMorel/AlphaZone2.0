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
    private $user;

    public function __construct() {
        $this->scan = new ScanDir('gallery/');
        $this->user = new User();
    }

    public function settingsView($content = [NULL]) {
        $logs = null;
        $view = new View('settings');
        $this->scan->getScans();
        if($this->scan->hasLog())
            $logs = $this->scan->log;
        $content['logs'] = $logs;
            
        $view->generate($content, true);
    }

    public function updateUser() {
        $validation = new FormValidator();
        $validation->validationFilter();
        if($validation->hasErrors()):
            $errors = $validation->errors;
            $this->settingsView(['errors' => $errors]);
        else:
            if(isset($_POST['newPwd'])):
                $options = ['cost' => 4];
                $hashPassword = password_hash($_POST['newPwd'], PASSWORD_BCRYPT, $options);
                $password = $this->user->getPassword($_SESSION['user']->use_login)->fetchAll()[0]['USE_PWD'];
                if($this->user->updatePwd($hashPassword, $password)):
                    $_SESSION['user']->use_pwd = $hashPassword;
                    $msg = ['msg' => 'Le mot de passe a été changé avec succès'];
                    $this->settingsView(['newPwdSuccess' => $msg]);
                endif;
            else:
                if($this->user->updateLogin($_POST['newLogin'], $_SESSION['user']->use_login)):
                    $_SESSION['user']->use_login = $_POST['newLogin'];
                    $msg = ['msg' => 'L\'identifiant a bien été changé'];
                    $this->settingsView(['newLoginSuccess' => $msg]);
                endif;
            endif;
        endif;
            
    }
}

?>
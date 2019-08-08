<?php

// if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'):
//     require_once '../model/DbConnection.php';
//     require_once '../model/Inquires.php';

//     array_map('htmlspecialchars', $_POST);
//     $contact = new ContactCtrl();
//     if(isset($_POST['mail']) && !empty($_POST['mail'])):
//         if($contact->isEmailValid($_POST['mail'])):
//             $response = json_encode(['message' => 'EMAIL is Valid']);
//         else:
//             $response = json_encode(['message' => 'EMAIL is Invalid']);
//         endif;
//     else:
//         $response = json_encode(['message' => 'This is AJAX responding. What can I do for you ?']); 
//     endif;
//     echo $response;
//     exit;

    require_once 'model/DbConnection.php';
    require_once 'model/Inquires.php';
    require_once 'view/View.php';


class ContactCtrl {

    private $inquires;

    public function __construct() {
        $this->inquires = new Inquires();
    }

    public function contactView($content = [false]) {
        $view = new View('contact');
        $view->generate($content, false);
    }

    public function inquires($lname, $fname, $mail, $subject, $inquire) {
        if($this->inquires->addInquire($lname, $fname, $mail, $subject, $inquire)):
            unset($_POST);
            $this->contactView(['infos' => [$lname, $fname, $mail]]);
        else:
            $view = new View('error');
            $view->generate(['msgError' => 'Un problème avec la Base de données a été rencontré'], false);
        endif;
    }

    public function isEmailValid ($email) {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if(filter_var($email, FILTER_VALIDATE_EMAIL))
            return $email;
        return false;
    }
}

?>
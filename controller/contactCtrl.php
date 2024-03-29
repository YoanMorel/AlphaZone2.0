<?php

    require_once 'model/DbConnection.php';
    require_once 'model/Inquiries.php';
    require_once 'controller/FormValidator.php';
    require_once 'view/View.php';

class ContactCtrl {

    private $inquiries;

    public function __construct() {
        $this->inquiries = new Inquiries();
    }

    public function contactView($content = [null]) {
        $view = new View('contact');
        $view->generate($content);
    }

    public function messengerView() {
        $view               = new View('messenger');
        $contacts           = $this->inquiries->getContacts()->fetchAll();
        $inquiries          = $this->inquiries->getInquiries()->fetchAll();
        $trashedInquiries   = $this->inquiries->getTrashedInquiries()->fetchAll();
        
        if(isset($_GET['service']) && $_GET['service'] == 'reception'):
            $view->generate(['contacts' => $contacts, 'inquiries' => $inquiries], true);
        elseif(isset($_GET['service']) && $_GET['service'] == 'trash'):
            $view->generate(['contacts' => $contacts, 'inquiries' => $trashedInquiries], true);
        else:
            $view->generate(['contacts' => $contacts, 'inquiries' => $inquiries], true);
        endif;
    }

    public function inquiries($lname, $organisme, $mail, $subject, $inquire) {
        $vars = [
            'errors'        => [],
            'varsValue'     => [
                'lname'     => $lname,
                'organisme' => $organisme,
                'mail'      => $mail,
                'subject'   => $subject,
                'inquire'   => $inquire
            ]
        ];

        $validation = new FormValidator();
        $validation->validationFilter();
        if($validation->hasErrors()):
            $vars['errors'] = $validation->errors;
            $this->contactView($vars);
        else:
            if($this->inquiries->addInquire($vars['varsValue'])):
                unset($_POST);
                $this->contactView($vars['varsValue']);
            else:
                $view = new View('error');
                $view->generate(['msgError' => 'Un problème avec la Base de données a été rencontré'], false);
            endif;
        endif;
    }
}

?>
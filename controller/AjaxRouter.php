<?php

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'):

    // [Controleur de la validation formulaire de contact]
    if(isset($_POST['ajax']) && !empty($_POST) && $_POST['ajax'] == 'validation'):
        require_once 'FormValidator.php';

        array_map('htmlspecialchars', $_POST);
        $validator = new FormValidator();
        $validator->validationFilter();
        if($validator->hasErrors()):
            $response = $validator->errors;
        else:
            $response = NULL;
        endif;
        echo json_encode($response);
        exit;
    endif;

    // [Controleur de l'autocomplétion]
    if(isset($_POST['ajax']) && !empty($_POST) && $_POST['ajax'] == 'autoComplete'):
        require_once 'autoComplete.php';
    
        array_map('htmlspecialchars', $_POST);
        extract($_POST);
        $sections = new AutoComplete($imgSection);
        echo json_encode(['data' => $sections->sections()]);
        exit;
    endif;

    // [Controleur de messagerie]
    if(isset($_POST['ajax']) && !empty($_POST) && $_POST['ajax'] == 'messenger'):
        require_once '../model/DbConnection.php';
        require_once '../model/Inquiries.php';

        array_map('htmlspecialchars', $_POST);
        $inquiries = new Inquiries();
        $inquiries->setOpenedInquire((int) $_POST['inqId'])->fetchAll();
        $stmt = $inquiries->getInquire((int) $_POST['inqId'])->fetchAll();
        echo json_encode($stmt);
    endif;

else:
    echo 'Vous n\'êtes pas AJAX ! Vous ne passerez PAS !';
endif;

?>
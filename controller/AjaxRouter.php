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

    // [Controleur de la sauvegarde automatique]
    if(isset($_POST['ajax']) && !empty($_POST) && $_POST['ajax'] == 'autoSave'):
        require_once 'autoSave.php';

        array_map('htmlspecialchars', $_POST);
        extract($_POST);
        $autoSave = new autoSave(__DIR__.'/autoSaveBuffer.json', $data);
        if(isset($order) && $order == '66'):
            $response = $autoSave->unlinkData();
        else:
            $response = json_encode(['msg' => $autoSave->linkData()]);
        endif;

        echo $response;
        exit;
    endif;

    // [Controleur de messagerie]
    if(isset($_POST['ajax']) && !empty($_POST) && $_POST['ajax'] == 'messenger'):
        require_once '../model/DbConnection.php';
        require_once '../model/Inquiries.php';

        array_map('htmlspecialchars', $_POST);
        $inquiries = new Inquiries();

        if(isset($_POST['action']) && $_POST['action'] == 'reply'):
            $inquiries->setRepliedInquire((int) $_POST['inqId']);
            echo json_encode(['done']);
            exit;
        endif;

        if(isset($_POST['action']) && $_POST['action'] == 'unread'):
            $inquiries->setOpenedInquire((int) $_POST['inqId']);
            echo json_encode(['done']);
            exit;
        endif;

        if(count($inquiries->getSealedInquire((int) $_POST['inqId'])->fetchAll()) > 0):
            $inquiries->setOpenedInquire((int) $_POST['inqId']);
        endif;

        $stmt = $inquiries->getInquire((int) $_POST['inqId'])->fetchAll();
        echo json_encode($stmt);
        exit;
    endif;

else:
    echo 'Vous n\'êtes pas AJAX ! Vous ne passerez PAS !';
endif;

?>
<?php

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'):

    array_map('htmlspecialchars', $_POST);

    // [Controleur de la validation formulaire de contact]
    if(isset($_POST['ajax']) && !empty($_POST) && $_POST['ajax'] == 'validation'):
        require_once '../model/DbConnection.php';
        require_once '../model/User.php';
        require_once 'FormValidator.php';

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

    // [Controleur de l'upload]
    if(isset($_POST['ajax']) && !empty($_POST) && $_POST['ajax'] == 'upload'):
        require_once '../model/DbConnection.php';
        require_once '../model/DataHandler.php';
        require_once 'uploadHandlerCtrl.php'; 

        extract($_POST);
        if(empty($text))
            $text = NULL;
        $uploadHandler = new UploadHandler($section, $subSection, $title, $text, '../gallery/', $file);
        if($uploadHandler->uploader()):
            $response = $uploadHandler->getName().' loaded in gallery/'.$uploadHandler->getSection().'/'.$uploadHandler->getSubSection().'/ at '.date('H:i:s', time()).' ...';
        else:
            $response = 'upload failed ...';
        endif;

        echo $response;
        exit;
    endif;

    // [Controleur de l'update]
    if(isset($_POST['ajax']) && !empty($_POST) && $_POST['ajax'] == 'update'):
        require_once '../model/DbConnection.php';
        require_once '../model/DataHandler.php';

        extract($_POST);

        $dataHandler = new DataHandler();
        $dataHandler->setData($title, $story, $link, $creationDate);
        if($dataHandler->updateDataFrom($pieceId)):
            $response = 'Mise à jour réussie...';
        else:
            $response = 'Echec de la mise à jour...';
        endif;

        echo $response;
        exit;
    endif;

    // [Controleur de suppression]
    if(isset($_POST['ajax']) && !empty($_POST) && $_POST['ajax'] == 'delete'):
        require_once '../model/DbConnection.php';
        require_once '../model/DataHandler.php';

        extract($_POST);

        $dataHandler = new DataHandler();
        if(is_file('../'.$path))
            unlink('../'.$path);

        if($dataHandler->deleteDataFrom((int) $pieceId)):
            $response = 'Suppression réussie...';
        else:
            $response = 'Echec de la suppression...';
        endif;

        echo $response;
        exit;
    endif;

    // [Controleur de l'autocomplétion]
    if(isset($_POST['ajax']) && !empty($_POST) && $_POST['ajax'] == 'autoComplete'):
        require_once '../model/DbConnection.php';
        require_once '../model/Gallery.php';
        require_once 'autoComplete.php';
    
        extract($_POST);
        $sections = new AutoComplete($imgSection);
        echo json_encode(['data' => $sections->sections()]);
        exit;
    endif;

    // [Controleur de la sauvegarde automatique]
    if(isset($_POST['ajax']) && !empty($_POST) && $_POST['ajax'] == 'autoSave'):
        require_once 'autoSave.php';

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
    echo 'Vous n\'êtes pas AJAX ! Vous ne passerez PAS !<br />';
endif;

?>
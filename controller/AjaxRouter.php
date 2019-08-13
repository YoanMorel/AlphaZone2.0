<?php

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'):

    // Controller de la validation formulaire de contact
    if(isset($_POST['ajax']) && !empty($_POST) && $_POST['ajax'] == 'validation'):
        require_once 'FormValidator.php';

        $validator = new FormValidator();
        $validator->validationFilter();
        if($validator->getErrors()):
            $response = $validator->errors;
        else:
            $response = NULL;
        endif;
        echo json_encode($response);
        exit;
    endif;

else:
    echo 'Vous n\'êtes pas AJAX ! Vous ne passerez PAS !';
endif;

?>
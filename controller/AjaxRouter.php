<?php

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'):

    if(isset($_POST['validation']) && !empty($_POST)):
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
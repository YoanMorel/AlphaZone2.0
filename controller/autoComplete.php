<?php
require '../../config/config.php';
require '../model/DbConnection.php';
require '../model/Sections.php';

function sections ($dbConf) {

  extract($_POST);

  $dbRequest = new Sections($dbConf['PARAM_db_name'], $dbConf['PARAM_user'], $dbConf['PARAM_pwd'], $dbConf['PARAM_options']);

  if (empty($imgSection)):
    $response = $dbRequest->getSections();
    $data = $response->fetchAll(PDO::FETCH_NUM);
  else:
    $response = $dbRequest->getSubSections($imgSection);
    $data = $response->fetchAll(PDO::FETCH_NUM);
  endif;

  return $data;
}

if(isset($_POST)):
  echo json_encode(array('data' => sections($dbConfig)));
endif;

 ?>

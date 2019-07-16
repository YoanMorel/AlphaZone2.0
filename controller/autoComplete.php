<?php
require '../model/DbConnection.php';
require '../model/Sections.php';

function sections () {

  extract($_POST);

  $dbRequest = new Sections();

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
  echo json_encode(array('data' => sections()));
endif;

 ?>

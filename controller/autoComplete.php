<?php

require_once '../model/DbConnection.php';
require_once '../model/Gallery.php';

class AutoComplete {

  private $dbRequest;
  private $section;

  public function __construct($imgSection) {
    $this->dbRequest = new Gallery();
    $this->section = $imgSection;
  }

  public function sections() {
    $data;

    if(empty($this->section)):
      $response = $this->dbRequest->getSections();
      $data = $response->fetchAll(PDO::FETCH_NUM);
    else:
      $response = $this->dbRequest->getSubSections($this->section);
      $data = $response->fetchAll(PDO::FETCH_NUM);
    endif;

    return $data;
  }
}

if(isset($_POST)):
  extract($_POST);
  $sections = new AutoComplete($imgSection);
  echo json_encode(array('data' => $sections->sections()));
endif;

 ?>

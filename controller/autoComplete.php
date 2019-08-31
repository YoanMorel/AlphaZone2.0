<?php

class AutoComplete {

  private $gallery;
  private $section;

  public function __construct($imgSection) {
    $this->gallery = new Gallery();
    $this->section = $imgSection;
  }

  public function sections() {
    $data = [];

    if(empty($this->section)):
      $data = $this->gallery->getSections()->fetchAll(PDO::FETCH_NUM);
    else:
      $data = $this->gallery->getSubSectionsFrom($this->section)->fetchAll(PDO::FETCH_NUM);
    endif;

    return $data;
  }
}

 ?>

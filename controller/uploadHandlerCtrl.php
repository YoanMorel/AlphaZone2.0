<?php

class UploadHandler {

  private $name;
  private $encodedData;
  private $decodeData;
  private $section;
  private $subSection;
  private $sectionLink;
  private $subSectionLink;
  private $dataHandler;

  public function __construct($section, $subSection, $title, $text, $gallery, $file) {
    $this->dataHandler = new DataHandler();
    $this->dataHandler->setSection($section);
    $this->dataHandler->setSubSection($subSection);
    $this->name = md5(rand().time().'unPeuDePaprikaPourDonnerDuGoutAMonHash').'.jpg';
    $this->dataHandler->setData($title, $text, $this->name);
    $this->encodedData = str_replace(' ', '+', $file);
    $this->decodedData = base64_decode($this->encodedData);
    $this->sectionLink = $gallery.$section;
    $this->subSectionLink = $this->sectionLink.'/'.$subSection;
    $this->section = $section;
    $this->subSection = $subSection;
  }

  public function uploader() {
    if(is_dir($this->sectionLink)):
      if(is_dir($this->subSectionLink)):
        file_put_contents($this->subSectionLink.'/'.$this->name, $this->decodeData);
        $stmt = $this->dataHandler->insertDataInDB();
      else:
        mkdir($this->subSectionLink);
        file_put_contents($this->subSectionLink.'/'.$this->name, $this->decodedData);
        $stmt = $this->dataHandler->insertDataInDB();
      endif;
    else:
      mkdir($this->sectionLink);
      mkdir($this->subSectionLink);
      file_put_contents($this->subSectionLink.'/'.$this->name, $this->decodedData);
      $this->dataHandler->insertSectionInDB();
      $this->dataHandler->insertSubSectionInDB();
      $stmt = $this->dataHandler->insertDataInDB();
    endif;

    return $stmt;
  }

  public function getName() {
    return $this->name;
  }

  public function getSection() {
    return $this->section;
  }

  public function getSubSection() {
    return $this->subSection;
  }

}

?>

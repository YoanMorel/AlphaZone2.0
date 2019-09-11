<?php
/**
 * UploadHandler Class
 * 
 * Class that handles sending files to the server and data in the database
 * 
 * @version 2.0
 * @author  Yoan Morel
 */
class UploadHandler {

  /**
   * Attributes storing data and instanciate DataHandler
   */
  private $name;
  private $encodedData;
  private $decodeData;
  private $section;
  private $subSection;
  private $sectionLink;
  private $subSectionLink;
  private $dataHandler;

  /**
   * Magic construct method.
   * 
   * Stores the image's data and the instance of DataHandler Class. Process and format Data for transfert
   * 
   * @param string $section The section's name
   * @param string $subSection The subsection's name
   * @param string $title The image's title
   * @param string $text The image's story
   * @param string $gallery Path of the main folder that contain the Gallery
   * @param string $file The file to store on the server
   */
  public function __construct($section, $subSection, $title, $text = null, $gallery, $file) {
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

  /**
   * uploader method is the class that write the file on the server
   * and stores the data in the database
   * 
   * @return PDO Object $stmt PDO statement
   */
  public function uploader() {
    if(is_dir($this->sectionLink)):
      if(is_dir($this->subSectionLink)):
        file_put_contents($this->subSectionLink.'/'.$this->name, $this->decodedData);
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

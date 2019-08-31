<?php

class autoSave {

  private $filePath;
  private $jsonData;

  public function __construct($jsonFilePath, $jsonData = NULL) {
    $this->filePath = $jsonFilePath;
    $this->jsonData = $jsonData;
  }

  public function linkData() {
    try {
      $openFile = fopen($this->filePath, 'w');
      fwrite($openFile, $this->jsonData);
      fclose($openFile);
    } catch (Exception $error) {
      return $error->getMessage();
    }

    return 'Sauvegarde '.date('H:i:s', time()).'...';
  }

  public function unlinkData() {
    if(is_file($this->filePath)):
      unlink($this->filePath);
      return 'Sauvegarde supprimée...';
    else:
      return 'Pas de sauvegarde...';
    endif;
  }
}
?>
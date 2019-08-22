<?php

class autoSave {

  private $filePath;
  private $jsonData;

  public function __construct($jsonFilePath, $jsonData = NULL) {
    $this->filePath = $jsonFilePath;
    $this->jsonPath = $jsonData;
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

// function saveData () {
//   $data;

//   $jsonFile = __DIR__.'/autoSaveBuffer.json';
//   if (isset($_POST['order']) && $_POST['order'] == '66'):
//     if(is_file($jsonFile)):
//       unlink($jsonFile);
//       $data = 'Sauvegarde supprimée...';
//     else:
//       $data = 'Pas de Sauvegarde...';
//     endif;
//   else:
//     $openFile = fopen($jsonFile, 'w') or die(1);
//     $dataReceived = $_POST['data'];
//     fwrite($openFile, $dataReceived);
//     fclose($openFile);

//     $data = json_encode(['msg' => 'Sauvegarde '.date('H:i:s', time()).'...']);
//   endif;
//   return $data;
// }

// if (isset($_POST)):
//   $response = saveData();
//   echo $response;
// endif;
 ?>

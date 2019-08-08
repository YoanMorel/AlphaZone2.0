<?php

function saveData () {
  extract($_POST);
  $data;

  $jsonFile = __DIR__.'/autoSaveBuffer.json';
  if (isset($order) && $order == '66'):
    if(is_file($jsonFile)):
      unlink($jsonFile);
      $data = 'Sauvegarde supprimée...';
    else:
      $data = 'Pas de Sauvegarde...';
    endif;
  else:
    $openFile = fopen($jsonFile, 'w') or die(1);
    $dataReceived = $_POST['data'];
    fwrite($openFile, $dataReceived);
    fclose($openFile);

    $data = json_encode(['msg' => 'Sauvegarde '.date('H:i:s', time()).'...']);
  endif;
  return $data;
}

if (isset($_POST)):
  $response = saveData();
  echo $response;
endif;
 ?>

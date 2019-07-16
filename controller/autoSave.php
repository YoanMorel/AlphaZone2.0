<?php

function saveData () {
  extract($_POST);

  $jsonFile = __DIR__.'/autoSaveBuffer.json';
  if (isset($order) && $order == '66'):
    if(is_file($jsonFile)):
      unlink($jsonFile);
      $jsonData = json_encode('Order 66 completed...');
    else:
      $jsonData = json_encode('Order 66 already completed...');
    endif;
  else:
    $openFile = fopen($jsonFile, 'w') or die(1);
    $jsonData = $_POST['data'];
    fwrite($openFile, $jsonData);
    fclose($openFile);

    $jsonData = json_encode('Autosave '.date('H:i:s', time()).'...');
  endif;
  return $jsonData;
}

if (isset($_POST)):
  $response = saveData();
  echo $response;
endif;
 ?>

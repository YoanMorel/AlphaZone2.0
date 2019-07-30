<?php

  require '../model/DbConnection.php';
  require '../model/Sections.php';
  require '../model/UploadHandler.php';

  function uploadHandler () {

    extract($_POST);

    $name           = md5(rand().time().'unPeuDePaprikaPourDonnerDuGoutAMonHash').'.jpg';
    $encodedData    = str_replace(' ', '+', $_POST['file']);
    $decodedData    = base64_decode($encodedData);
    $sectionLink    = '../public/img/'.$section;
    $subSectionLink = '../public/img/'.$section.'/'.$subSection;
    $dbRequest      = new UploadHandler();

    $dbRequest->setSection($section);
    $dbRequest->setSubSection($subSection);
    $dbRequest->setData($title, $text, $name);

    if (is_dir($sectionLink)):
      if (is_dir($subSectionLink)):
        file_put_contents($subSectionLink.'/'.$name, $decodedData);
        $stmt = $dbRequest->insertDataInDB();
      else:
        mkdir($subSectionLink);
        file_put_contents($subSectionLink.'/'.$name, $decodedData);
        $dbRequest->insertSubSectionInDB();
        $stmt = $dbRequest->insertDataInDB();
      endif;
    else:
      mkdir($sectionLink);
      mkdir($subSectionLink);
      file_put_contents($subSectionLink.'/'.$name, $decodedData);
      $dbRequest->insertSectionInDB();
      $dbRequest->insertSubSectionInDB();
      $stmt = $dbRequest->insertDataInDB();
    endif;

    if ($stmt):
      return $name.' loaded in public/img/'.$section.'/'.$subSection.'/ at '.date('H:i:s', time()).' ...';
    else:
      return 'upload failed...';
    endif;

}

  if (isset($_POST)):
    $response = uploadHandler();
    echo $response;
  endif;

  ?>

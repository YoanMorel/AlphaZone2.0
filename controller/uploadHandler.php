<?php

  require '../../config/config.php';
  require '../model/DbConnection.php';
  require '../model/Sections.php';
  require '../model/UploadHandler.php';

  function uploadHandler ($dbConf) {

    extract($_POST);

    $dbRequest      = new UploadHandler($dbConf['PARAM_db_name'], $dbConf['PARAM_user'], $dbConf['PARAM_pwd'], $dbConf['PARAM_options']);
    $name           = md5(rand().time().'unPeuDePaprikaPourDonnerDuGoutAMonHash').'.jpg';
    $encodedData    = str_replace(' ', '+', $file);
    $decodedData    = base64_decode($encodedData);
    $sectionLink    = '../public/img/'.$section;
    $subSectionLink = '../public/img/'.$section.'/'.$subSection;

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
    $response = uploadHandler($dbConfig);
    echo $response;
  endif;

  ?>

<?php

require_once '../model/DbConnection.php';
require_once '../model/UploadHandler.php';
require_once '../model/Gallery.php';
     
class ScanDir {

    private $dirToScan;
    private $gallery;
    private $uploadHandler;
    private $imagesPathList = [];
    private $dataScan       = [];
    private $dirScan        = [];
    private $log            = [];

    public function __construct($pathToScan) {
        $this->gallery          = new Gallery();
        $this->uploadHandler    = new UploadHandler();
        $this->dirToScan        = $pathToScan;
    }

    public function regularize() {
        $this->getDirScan();
        $this->getDataScan();
        if($this->dataScan && $this->dirScan):
            if(count($this->dataScan) < count($this->dirScan)):
                $this->log['scan'] = 'Irrégularités détéctées lors du Scan. Des informations sont manquantes dans la base de données. Lancement du régularisateur';
                $this->loadPendingData($this->dataScan, $this->dirScan);
            else:
                $this->log['scan'] = 'Aucune irrégularité détéctée';
            endif;
        endif;
    }

    private function loadPendingData($array1, $array2) {
        $arrayDiff  = array_diff($array2, $array1);

        foreach($arrayDiff as $path):
            $pathTab = explode('/', $path);
            $name    = md5(rand().time().'unPeuDePaprikaPourDonnerDuGoutAMonHash').'.jpg';
            $ifSectionExists = false;

            if(rename('../gallery/'.$path, '../gallery/'.$pathTab[0].'/'.$pathTab[1].'/'.$name)):
                $pathTab[2] = $name;
            else:
                $this->log['rename'] = 'Le fichier n\'a pas pu être renommé';
            endif;

            $sections = $this->gallery->getSections()->fetchAll();
            foreach($sections as $section):
                $ifSubSectionExists = false;
                if($pathTab[0] == $section['SEC_SECTION']):
                    $ifSectionExists = true;
                    $this->uploadHandler->setSection($section['SEC_SECTION']);
                    $subSections = $this->gallery->getSubSections($section['SEC_SECTION'])->fetchAll();
                    foreach($subSections as $subSection):
                        if($pathTab[1] == $subSection['SUB_SUBSECTION']):
                            $ifSubSectionExists = true;
                            $this->uploadHandler->setSubSection($subSection['SUB_SUBSECTION']);
                            $this->uploadHandler->setData(null, null, $pathTab[2]);
                            if($this->uploadHandler->insertDataInDB()):
                                $this->log['LVL0'] = '>Release< Done Level 0. Files loaded';
                            else:
                                $this->log['LVL0'] = '>Release< Something\'s wrong appened on Level 0';
                            endif;
                        endif;
                    endforeach;
                    if(!$ifSubSectionExists):
                        $this->uploadHandler->setSubSection($pathTab[1]);
                        $this->uploadHandler->setData(null, null, $pathTab[2]);
                        $this->uploadHandler->insertSubSectionInDB();                    
                        if($this->uploadHandler->insertDataInDB()):
                            $this->log['LVL1'] = '>Release< Done Level 1. Subsection made and Files loaded';
                        else:
                            $this->log['LVL1'] = '>Release< Something\'s wrong appened on Level 1';
                        endif;
                    endif;
                endif;
            endforeach;
            if(!$ifSectionExists):
                $this->uploadHandler->setSection($pathTab[0]);
                $this->uploadHandler->setSubSection($pathTab[1]);
                $this->uploadHandler->setData(null, null, $pathTab[2]);
                $this->uploadHandler->insertSectionInDB();
                $this->uploadHandler->insertSubSectionInDB();                                       if($this->uploadHandler->insertDataInDB()):
                    $this->log['LVL2'] = '>Release< Done Level 2. Section, Subection made and Files loaded';
                else:
                    $this->log['LVL2'] = '>Release< Something\'s wrong appened on Level 2';
                endif;
            endif;
        endforeach;
    }

    private function getDirScan() {
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->dirToScan)) as $fileName):
            if ($fileName->isFile()):
                $trimmed = str_replace($this->dirToScan, '', $fileName->getPathname());
                $this->imagesPathList[] = $trimmed;
            endif;
        endforeach;

        foreach($this->imagesPathList as $path):
            $this->dirScan[] = $path;
        endforeach;
    }

    private function getDataScan() {
        $sectionsList = $this->gallery->getSections()->fetchAll();
        foreach($sectionsList as $section):
            $subSectionsList = $this->gallery->getSubSections($section['SEC_SECTION'])->fetchAll();
            $i = 0;
            foreach($subSectionsList as $subSection):
                $pieces = $this->gallery->getPieces($section['SEC_SECTION'], $subSection['SUB_SUBSECTION'])->fetchAll();
                foreach($pieces as $piece):
                    $this->dataScan[] = $section['SEC_SECTION'].'/'.$subSection['SUB_SUBSECTION'].'/'.$piece['PIE_IMG_LINK'];
                endforeach;
            endforeach;
        endforeach;
    }

    public function getLog() {
        return $this->log;
    }
}

$scan = new ScanDir('../gallery/');

$scan->regularize();
echo json_encode(['log' => $scan->getLog()]);




?>
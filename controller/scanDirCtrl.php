<?php
     
class ScanDir {

    private $dirToScan;
    private $gallery;
    private $dataHandler;
    private $imagesPathList = [];
    private $dataScan       = [];
    private $dirScan        = [];
    private $log            = [];

    public function __get($value) {
        if($value != 'log'):
            throw new BadMethodCallException(__CLASS__ . '::'.$value.' : inaccessible ou inexistant.');
        endif;
        return $this->log;
    }

    public function __construct($pathToScan) {
        $this->gallery          = new Gallery();
        $this->dataHandler      = new DataHandler();
        $this->dirToScan        = $pathToScan;
    }

    public function regularize() {
        $this->getDirScan();
        $this->getDataScan();
        if($this->dataScan && $this->dirScan):
            if(count($this->dataScan) < count($this->dirScan)):
                $this->log['scan'] = 'Irrégularités détéctées lors du Scan. Des informations sont manquantes dans la base de données. Lancement du régularisateur';
                $this->loadPendingData($this->dataScan, $this->dirScan);
                $this->log['scan'] .= "\n\rOpération terminé à ".date('G:i', mktime());
            else:
                $this->log['scan'] = 'Aucune irrégularité détéctée';
                $this->log['scan'] .= "\n\rOpération terminée à ".date('G:i', mktime());
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
                $this->log[$pathTab[2]]['rename'] = 'Fichier renommé';
            else:
                $this->log[$pathTab[2]]['rename'] = 'Le fichier n\'a pas pu être renommé';
            endif;

            $sections = $this->gallery->getSections()->fetchAll();
            foreach($sections as $section):
                $ifSubSectionExists = false;
                if($pathTab[0] == $section['SEC_SECTION']):
                    $ifSectionExists = true;
                    $this->dataHandler->setSection($section['SEC_SECTION']);
                    $subSections = $this->gallery->getSubSectionsFrom($section['SEC_SECTION'])->fetchAll();
                    foreach($subSections as $subSection):
                        if($pathTab[1] == $subSection['SUB_SUBSECTION']):
                            $ifSubSectionExists = true;
                            $this->dataHandler->setSubSection($subSection['SUB_SUBSECTION']);
                            $this->dataHandler->setData(null, null, $pathTab[2]);
                            if($this->dataHandler->insertDataInDB()):
                                $this->loglog[$pathTab[2]]['LVL0'] = '>Release< Done Level 0. Files loaded';
                            else:
                                $this->loglog[$pathTab[2]]['LVL0'] = '>Release< Something\'s wrong appened on Level 0';
                            endif;
                        endif;
                    endforeach;
                    if(!$ifSubSectionExists):
                        $this->dataHandler->setSubSection($pathTab[1]);
                        $this->dataHandler->setData(null, null, $pathTab[2]);
                        $this->dataHandler->insertSubSectionInDB();                    
                        if($this->dataHandler->insertDataInDB()):
                            $this->log[$pathTab[2]]['LVL1'] = '>Release< Done Level 1. Subsection made and Files loaded';
                        else:
                            $this->log[$pathTab[2]]['LVL1'] = '>Release< Something\'s wrong appened on Level 1';
                        endif;
                    endif;
                endif;
            endforeach;
            if(!$ifSectionExists):
                $this->dataHandler->setSection($pathTab[0]);
                $this->dataHandler->setSubSection($pathTab[1]);
                $this->dataHandler->setData(null, null, $pathTab[2]);
                $this->dataHandler->insertSectionInDB();
                $this->dataHandler->insertSubSectionInDB();                                       if($this->dataHandler->insertDataInDB()):
                    $this->log[$pathTab[2]]['LVL2'] = '>Release< Done Level 2. Section, Subection made and Files loaded';
                else:
                    $this->log[$pathTab[2]]['LVL2'] = '>Release< Something\'s wrong appened on Level 2';
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

    public function getDataScan() {
        $sectionsList = $this->gallery->getSections()->fetchAll();
        foreach($sectionsList as $section):
            $subSectionsList = $this->gallery->getSubSectionsFrom($section['SEC_SECTION'])->fetchAll();
            $i = 0;
            foreach($subSectionsList as $subSection):
                $pieces = $this->gallery->getPiecesFrom($section['SEC_SECTION'], $subSection['SUB_SUBSECTION'])->fetchAll();
                foreach($pieces as $piece):
                    $this->dataScan[] = $section['SEC_SECTION'].'/'.$subSection['SUB_SUBSECTION'].'/'.$piece['PIE_IMG_LINK'];
                endforeach;
            endforeach;
        endforeach;

        return $this->dataScan;
    }
}

?>
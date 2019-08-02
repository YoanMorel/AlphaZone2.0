<?php

require_once '../model/DbConnection.php';
require_once '../model/UploadHandler.php';
require_once '../model/Gallery.php';
     
class ScanDir {

    private $dirToScan;
    private $gallery;
    private $uploadHandler;
    private $imagesPathList = array();
    private $dataScan       = array();
    private $dirScan        = array();
    private $log            = array();

    public function __construct($pathToScan) {
        $this->gallery          = new Gallery();
        $this->uploadHandler    = new UploadHandler();
        $this->dirToScan        = $pathToScan;
    }

    public function regularize() {
        $this->getDirScan();
        $this->getDataScan();
        if($this->dataScan && $this->dirScan):
            if(count($this->dataScan, COUNT_RECURSIVE) < count($this->dirScan, COUNT_RECURSIVE)):
                $this->loadPendingData();
            elseif (count($this->dataScan, COUNT_RECURSIVE) > count($this->dirScan, COUNT_RECURSIVE)):
                'Some shit doing some pretty shit';
            else:
                echo 'Pas d\'irrégularités détéctées';
            endif;
        endif;
    }

    private function loadPendingData() {
        foreach($this->dirScan as $section => $subSections):
            $this->uploadHandler->setSection($section);
            if($this->uploadHandler->insertSectionInDB())
                $this->log[$section] = '>loaded<';
            foreach($subSections as $subSection => $files):
                $this->uploadHandler->setSubSection($subSection);
                if($this->uploadHandler->insertSubSectionInDB())
                    $this->log[$subSection] = '>loaded<';
                foreach($files as $file):
                    $this->uploadHandler->setData(null, null, $file);
                    if($this->uploadHandler->insertDataInDB())
                        $this->log[$file] = '>loaded<';
                endforeach;
            endforeach;
        endforeach;
    }

    private function getDirScan() {
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->dirToScan)) as $fileName):
            if ($fileName->isFile()):
                $trimmed = str_replace($this->dirToScan, '', $fileName->getPathname());
                $this->imagesPathList[] = $trimmed;
            endif;
        endforeach;

        $folder = [];

        foreach($this->imagesPathList as $path):
            $pathTab = explode('/', $path);
            $this->dirScan[$pathTab[0]][$pathTab[1]][] = $pathTab[2];
        endforeach;
    }

    private function getDataScan() {
        $sectionsList = $this->gallery->getSections()->fetchAll();
        foreach($sectionsList as $section):
            $subSectionsList = $this->gallery->getSubSections($section['SEC_SECTION'])->fetchAll();
            foreach($subSectionsList as $subSection):
                $pieces = $this->gallery->getPieces($section['SEC_SECTION'], $subSection['SUB_SUBSECTION'])->fetchAll();
                foreach($pieces as $piece):
                    $this->dataScan[$section['SEC_SECTION']][$subSection['SUB_SUBSECTION']][] = $piece['PIE_IMG_LINK'];
                endforeach;
            endforeach;
        endforeach;
    }
}

$scan = new ScanDir('../gallery/');

$scan->regularize();




?>
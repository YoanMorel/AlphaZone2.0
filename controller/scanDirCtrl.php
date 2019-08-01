<?php

require_once '../model/DbConnection.php';
require_once '../model/UploadHandler.php';
require_once '../model/Gallery.php';
     
class ScanDir {

    private $imagesPathList = array();
    private $dirToScan;
    private $gallery;
    private $uploadHandler;

    public function __construct($pathToScan) {
        $this->gallery = new Gallery();
        $this->uploadHandler = new UploadHandler();
        $this->dirToScan = $pathToScan;
    }

    public function doScan() {
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->dirToScan)) as $fileName):
            if ($fileName->isFile()):
                $trimmed = str_replace($this->dirToScan, '', $fileName->getPathname());
                $this->imagesPathList[] = $trimmed;
            endif;
        endforeach;

        $folder = [];

        foreach($this->imagesPathList as $path):
            $pathTab = explode('/', $path);
            $folder[$pathTab[0]][$pathTab[1]][] = $pathTab[2];
        endforeach;
        
        return $folder;
    }

    public function regularize() {
        $sectionsList = $this->gallery->getSections()->fetchAll(PDO::FETCH_NUM);
        // $subSectionsList = $this->sections->getSubSections()->fetchAll(PDO::FETCH_NUM);
        $tree = $this->doScan();
        var_dump($tree);
    }
}

$scan = new ScanDir('../gallery/');

$scan->regularize();

mkdir('../gallery/1993', 0777);

?>
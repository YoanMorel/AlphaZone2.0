<?php

require_once 'model/DbConnection.php';

class OverlayPictures extends DbConnection {
    public function getPicturesLink() {
        $returnData = $this->queryCall('SELECT p.imgLink, sub.subSection, s.section FROM pieces p LEFT JOIN subSections sub ON p.id_subSections = sub.id LEFT JOIN sections s ON s.id = sub.id_sections ORDER BY RAND() LIMIT 4');

        return $returnData;
    }
}
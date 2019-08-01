<?php

require_once 'model/DbConnection.php';

class OverlayPictures extends DbConnection {
    public function getPicturesLink($limit) {
        $returnData = $this->queryCall('SELECT p.PIE_IMG_LINK, sub.SUB_SUBSECTION, s.SEC_SECTION FROM T_PIECES p LEFT JOIN T_SUBSECTIONS sub ON p.SUB_ID = sub.SUB_ID LEFT JOIN T_SECTIONS s ON s.SEC_ID = sub.SEC_ID ORDER BY RAND() LIMIT :limit',
        array( 
                array('limit', $limit, PDO::PARAM_STR)
        ));

        return $returnData;
    }
}
<?php

require_once 'model/DbConnection.php';

/**
 * DbConnection's child OverlayPictures class
 * 
 * Provides links for the client's side overlay
 * 
 * @version 2.0
 * @author  Yoan Morel
 */
class OverlayPictures extends DbConnection {

    /**
     * SQL method service to get random links
     * 
     * @param int $limit limit for the random function in the SQL query
     * @return object $result PDO statement
     */
    public function getPicturesLink($limit) {
        $result = $this->queryCall(
            'SELECT p.PIE_IMG_LINK, sub.SUB_SUBSECTION, s.SEC_SECTION 
            FROM T_PIECES p 
            LEFT JOIN T_SUBSECTIONS sub ON p.SUB_ID = sub.SUB_ID 
            LEFT JOIN T_SECTIONS s ON s.SEC_ID = sub.SEC_ID 
            ORDER BY RAND() LIMIT :limit',
            [
                ['limit', $limit, PDO::PARAM_INT]
            ]
        );

        return $result;
    }
}
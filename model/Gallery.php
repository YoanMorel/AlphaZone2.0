<?php

/**
 * DbConnection's child Gallery class
 * 
 * Centralizes gallery services to access the pieces informations
 * 
 * @version 2.0
 * @author  Yoan Morel
 */
class Gallery extends DbConnection {

    /**
     * SQL service method to get Sections gallery
     * 
     * @return object $result PDO statement
     */
    public function getSections() {
        $result = $this->queryCall('SELECT SEC_SECTION FROM T_SECTIONS');

        return $result;
    }

    /**
     * SQL service method to get subsections from a section
     * 
     * @param string $sectionData section
     * @return object $result PDO statement
     */
    public function getSubSectionsFrom($sectionData) {
        $result = $this->queryCall(
            'SELECT sub.SUB_SUBSECTION FROM T_SUBSECTIONS sub INNER JOIN T_SECTIONS s ON s.SEC_ID = sub.SEC_ID AND s.SEC_SECTION = :sectionData',
            [
                ['sectionData', $sectionData, PDO::PARAM_STR]
            ]
        );

        return $result;
    }

    /**
     * SQL service method to get Subsections Gallery
     * 
     * @return object $result PDO statement
     */
    public function getSubSections() {
        $result = $this->queryCall('SELECT * FROM T_SUBSECTIONS');

        return $result;
    }

    /**
     * SQL service method to get all the pieces related to a particular section
     * 
     * @param string $sectionData section
     * @param string $subSectionData subsection
     * @return object $result PDO statement
     */
    public function getPiecesFrom($sectionData, $subSectionData) {
        $result = $this->queryCall(
            'SELECT p.* FROM T_PIECES p 
            LEFT JOIN T_SUBSECTIONS sub ON sub.SUB_ID = p.SUB_ID 
            LEFT JOIN T_SECTIONS s ON s.SEC_ID = sub.SEC_ID
            WHERE :sectionData = s.SEC_SECTION AND :subSectionData = sub.SUB_SUBSECTION',
            [
                ['sectionData', $sectionData, PDO::PARAM_STR],
                ['subSectionData', $subSectionData, PDO::PARAM_STR]
            ]
        );

        return $result;
    }

    /**
     * SQL service method to get some informations about pieces in the gallery
     * 
     * @return object $result PDO statement
     */
    public function getPiecesLink() {
        $result = $this->queryCall(
            "SELECT GROUP_CONCAT(CONCAT('gallery/', sec.SEC_SECTION, '/', sub.SUB_SUBSECTION, '/', pie.PIE_IMG_LINK) SEPARATOR ',') AS linkPieces, sec.SEC_SECTION, sub.SUB_SUBSECTION 
            FROM T_PIECES pie 
            LEFT JOIN T_SUBSECTIONS sub ON pie.SUB_ID = sub.SUB_ID 
            LEFT JOIN T_SECTIONS sec ON sub.SEC_ID = sec.SEC_ID 
            GROUP BY sec.SEC_SECTION, sub.SUB_SUBSECTION"
        );

        return $result;
    }

    /**
     * SQL service method to get all pieces in the Gallery
     * 
     * @return object $result PDO statement
     */
    public function getAllPieces() {
        $result = $this->queryCall(
            'SELECT pie.*, sec.SEC_SECTION, sub.SUB_SUBSECTION 
            FROM T_PIECES pie 
            LEFT JOIN T_SUBSECTIONS sub ON pie.SUB_ID = sub.SUB_ID 
            LEFT JOIN T_SECTIONS sec ON sub.SEC_ID = sec.SEC_ID');

        return $result;
    }

    /**
     * SQL service method to get all null Stories about all pieces in the gallery
     * 
     * @return object $result PDO statement
     */
    public function getNullStories() {
        $result = $this->queryCall('SELECT * FROM T_PIECES WHERE ISNULL(PIE_STORY)');

        return $result;
    }

    /**
     * SQL service method to get null story from a particular piece in the Gallery
     * 
     * @param int $pieceId piece id
     * @return object $result PDO statement
     */
    public function getNullStoryFrom($pieceId) {
        $result = $this->queryCall(
            'SELECT PIE_STORY FROM T_PIECES WHERE PIE_ID = :pieceId',
            [
                ['pieceId', $pieceId, PDO::PARAM_INT]
            ]
        );

        return $result;
    }

}
?>
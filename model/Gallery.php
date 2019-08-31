<?php

class Gallery extends DbConnection {

    public function getSections() {
        $sectionsData = $this->queryCall('SELECT SEC_SECTION FROM T_SECTIONS');

        return $sectionsData;
    }

    public function getSubSectionsFrom($sectionData) {
        $subSectionData = $this->queryCall(
            'SELECT sub.SUB_SUBSECTION FROM T_SUBSECTIONS sub INNER JOIN T_SECTIONS s ON s.SEC_ID = sub.SEC_ID AND s.SEC_SECTION = :sectionData',
            [
                ['sectionData', $sectionData, PDO::PARAM_STR]
            ]
        );

        return $subSectionData;
    }

    public function getSubSections() {
        $subSections = $this->queryCall('SELECT * FROM T_SUBSECTIONS');

        return $subSections;
    }

    public function getPiecesFrom($sectionData, $subSectionData) {
        $piecesData = $this->queryCall(
            'SELECT p.* FROM T_PIECES p 
            LEFT JOIN T_SUBSECTIONS sub ON sub.SUB_ID = p.SUB_ID 
            LEFT JOIN T_SECTIONS s ON s.SEC_ID = sub.SEC_ID
            WHERE :sectionData = s.SEC_SECTION AND :subSectionData = sub.SUB_SUBSECTION',
            [
                ['sectionData', $sectionData, PDO::PARAM_STR],
                ['subSectionData', $subSectionData, PDO::PARAM_STR]
            ]
        );

        return $piecesData;
    }

    public function getPiecesLink() {
        $gallery = $this->queryCall(
            "SELECT GROUP_CONCAT(CONCAT('gallery/', sec.SEC_SECTION, '/', sub.SUB_SUBSECTION, '/', pie.PIE_IMG_LINK) SEPARATOR ',') AS linkPieces, sec.SEC_SECTION, sub.SUB_SUBSECTION 
            FROM T_PIECES pie 
            LEFT JOIN T_SUBSECTIONS sub ON pie.SUB_ID = sub.SUB_ID 
            LEFT JOIN T_SECTIONS sec ON sub.SEC_ID = sec.SEC_ID 
            GROUP BY sec.SEC_SECTION, sub.SUB_SUBSECTION"
        );

        return $gallery;
    }

    public function getAllPieces() {
        $piecesData = $this->queryCall(
            'SELECT pie.*, sec.SEC_SECTION, sub.SUB_SUBSECTION 
            FROM T_PIECES pie 
            LEFT JOIN T_SUBSECTIONS sub ON pie.SUB_ID = sub.SUB_ID 
            LEFT JOIN T_SECTIONS sec ON sub.SEC_ID = sec.SEC_ID');

        return $piecesData;
    }

    public function getNullStories() {
        $piecesData = $this->queryCall('SELECT * FROM T_PIECES WHERE ISNULL(PIE_STORY)');

        return $piecesData;
    }

    public function getNullStoryFrom($pieceId) {
        $pieceData = $this->queryCall(
            'SELECT PIE_STORY FROM T_PIECES WHERE PIE_ID = :pieceId',
            [
                ['pieceId', $pieceId, PDO::PARAM_INT]
            ]
        );

        return $pieceData;
    }

}
?>
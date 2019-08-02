<?php

class Gallery extends DbConnection {

    public function getSections() {
        $sectionsData = $this->queryCall('SELECT SEC_SECTION FROM T_SECTIONS');

        return $sectionsData;
    }

    public function getSubSections($sectionData) {
        $subSectionData = $this->queryCall(
            'SELECT sub.SUB_SUBSECTION FROM T_SUBSECTIONS sub INNER JOIN T_SECTIONS s ON s.SEC_ID = sub.SEC_ID AND s.SEC_SECTION = :sectionData',
            array(
                array('sectionData', $sectionData, PDO::PARAM_STR))
        );

        return $subSectionData;
    }

    public function getPieces($sectionData, $subSectionData) {
        $piecesData = $this->queryCall(
            'SELECT p.* FROM T_PIECES p 
            LEFT JOIN T_SUBSECTIONS sub ON sub.SUB_ID = p.SUB_ID 
            LEFT JOIN T_SECTIONS s ON s.SEC_ID = sub.SEC_ID
            WHERE :sectionData = s.SEC_SECTION AND :subSectionData = sub.SUB_SUBSECTION',
            array(
                array('sectionData', $sectionData, PDO::PARAM_STR),
                array('subSectionData', $subSectionData, PDO::PARAM_STR)
            )
        );

        return $piecesData;
    }

}
?>
<?php

class Sections extends DbConnection {

    public function getSections () {
        $sectionsData = $this->queryCall('SELECT section FROM sections');

        return $sectionsData;
    }

    public function getSubSections ($dataSection) {
        $subSectionData = $this->queryCall(
            'SELECT sub.subSection FROM subSections sub INNER JOIN sections s ON s.id = sub.id_sections AND s.section = :dataSection',
            array(
                array('dataSection', $dataSection, PDO::PARAM_STR))
        );

        return $subSectionData;
    }

    public function getAllAboutSubSection ($dataSection) {
        $nbrSubSections = $this->queryCall(
            'SELECT * FROM subSections sub LEFT JOIN sections s ON s.id = sub.id_sections WHERE :dataSection = s.section',
            array(
                array('dataSection', $dataSection, PDO::PARAM_STR))
        );

        return $nbrSubSection;
    }

}
?>
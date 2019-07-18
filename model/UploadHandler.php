<?php

class UploadHandler extends DbConnection {

    private $section;
    private $subSection;
    private $dataTitle;
    private $dataStory;
    private $dataLink;

    public function setSection ($dataSection) {
        $this->section = $dataSection;
    }

    public function setSubSection ($dataSubSection) {
        $this->subSection = $dataSubSection;
    }

    public function setData ($title, $story, $link) {
        $this->dataTitle = $title;
        $this->dataStory = $story;
        $this->dataLink = $link;
    }

    public function insertSectionInDB () {
        $success = $this->queryCall(
            'INSERT INTO sections (section, creationDate) VALUES (:newSection, NOW())',
            array(
                array('newSection', $this->section, PDO::PARAM_STR))
        );

        return $success;
    }

    public function insertSubSectionInDB () {
        $success = $this->queryCall(
            'INSERT INTO subSections (subSection, creationDate, id_sections) VALUES (:newSubSection, NOW(), (SELECT id FROM sections WHERE section = :relativeSection))',
            array(
                array('newSubSection', $this->subSection, PDO::PARAM_STR),
                array('relativeSection', $this->section, PDO::PARAM_STR)
            )
        );

        return $success;
    }

    public function insertDataInDB () {
        $success = $this->queryCall(
            'INSERT INTO pieces (imgTitle, imgLink, imgStory, imgUploadDate, id_subSections) VALUES (:dataTitle, :dataLink, :dataStory, NOW(), (SELECT sub.id FROM subSections sub LEFT JOIN sections s ON s.id = sub.id_sections AND sub.subSection = :subSectionName WHERE s.section = :sectionName))',
            array(
                array('dataTitle', $this->dataTitle, PDO::PARAM_STR),
                array('dataLink', $this->dataLink, PDO::PARAM_STR),
                array('dataStory', $this->dataStory, PDO::PARAM_STR),
                array('subSectionName', $this->subSection, PDO::PARAM_STR),
                array('sectionName', $this->section, PDO::PARAM_STR)
            )
        );

        return $success;
    }
}
?>
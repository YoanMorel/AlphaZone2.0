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
            'INSERT INTO T_SECTIONS (SEC_SECTION, SEC_CREATION_DATE) VALUES (:newSection, NOW())',
            array(
                array('newSection', $this->section, PDO::PARAM_STR))
        );

        return $success;
    }

    public function insertSubSectionInDB () {
        $success = $this->queryCall(
            'INSERT INTO T_SUBSECTIONS (SUB_SUBSECTION, SUB_CREATION_DATE, SEC_ID) VALUES (:newSubSection, NOW(), (SELECT SEC_ID FROM T_SECTION WHERE SEC_SECTION = :relativeSection))',
            array(
                array('newSubSection', $this->subSection, PDO::PARAM_STR),
                array('relativeSection', $this->section, PDO::PARAM_STR)
            )
        );

        return $success;
    }

    public function insertDataInDB () {
        $success = $this->queryCall(
            'INSERT INTO T_PIECES (PIE_TITLE, PIE_IMG_LINK, PIE_STORY, PIE_UPLOAD_DATE, SUB_ID) VALUES (:dataTitle, :dataLink, :dataStory, NOW(), (SELECT sub.SUB_ID FROM T_SUBSECTIONS sub LEFT JOIN T_SECTIONS s ON s.SEC_ID = sub.SEC_ID AND sub.SUB_SUBSECTION = :subSectionName WHERE s.SEC_SECTION = :sectionName))',
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
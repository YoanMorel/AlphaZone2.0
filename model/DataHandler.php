<?php

class DataHandler extends DbConnection {

    private $section;
    private $subSection;
    private $dataTitle;
    private $dataStory;
    private $dataLink;
    private $dataCreationDate;

    public function setSection($dataSection) {
        $this->section = $dataSection;
    }

    public function setSubSection($dataSubSection) {
        $this->subSection = $dataSubSection;
    }

    public function setData($title, $story, $link, $creationDate = null) {
        $this->dataTitle        = $title;
        $this->dataStory        = $story;
        $this->dataLink         = $link;
        $this->dataCreationdate = $creationDate;
    }

    public function insertSectionInDB() {
        $result = $this->queryCall(
            'INSERT INTO T_SECTIONS (SEC_SECTION, SEC_CREATION_DATE) VALUES (:newSection, NOW())',
            [
                ['newSection', $this->section, PDO::PARAM_STR]
            ]
        );

        return $result;
    }

    public function insertSubSectionInDB() {
        $result = $this->queryCall(
            'INSERT INTO T_SUBSECTIONS (SUB_SUBSECTION, SUB_CREATION_DATE, SEC_ID) VALUES (:newSubSection, NOW(), (
                SELECT SEC_ID 
                FROM T_SECTIONS 
                WHERE SEC_SECTION = :relativeSection))',
            [
                ['newSubSection', $this->subSection, PDO::PARAM_STR],
                ['relativeSection', $this->section, PDO::PARAM_STR]
            ]
        );

        return $result;
    }

    public function insertDataInDB() {
        $result = $this->queryCall(
            'INSERT INTO T_PIECES (PIE_TITLE, PIE_IMG_LINK, PIE_STORY, PIE_UPLOAD_DATE, SUB_ID) 
            VALUES (:dataTitle, :dataLink, :dataStory, NOW(), (
                SELECT sub.SUB_ID FROM T_SUBSECTIONS sub 
                LEFT JOIN T_SECTIONS s ON s.SEC_ID = sub.SEC_ID 
                AND sub.SUB_SUBSECTION = :subSectionName 
                WHERE s.SEC_SECTION = :sectionName))',
            [
                ['dataTitle', $this->dataTitle, PDO::PARAM_STR],
                ['dataLink', $this->dataLink, PDO::PARAM_STR],
                ['dataStory', $this->dataStory, PDO::PARAM_STR],
                ['subSectionName', $this->subSection, PDO::PARAM_STR],
                ['sectionName', $this->section, PDO::PARAM_STR]
            ]
        );

        return $result;
    }

    public function updateDataFrom($pieceId) {
        $result = $this->queryCall(
            'UPDATE T_PIECES 
            SET PIE_TITLE = :dataTitle, PIE_STORY = :dataStory, PIE_CREATION_DATE = :dataCreationDate WHERE PIE_ID = :pieceId',
            [
                ['dataTitle', $this->dataTitle, PDO::PARAM_STR],
                ['dataStory', $this->dataStory, PDO::PARAM_STR],
                ['dataCreationDate', $this->dataCreationDate, PDO::PARAM_STR],
                ['pieceId', $pieceId, PDO::PARAM_INT]
            ]
        );

        return $result;
    }

    public function deleteDataFrom($pieceId) {
        $result = $this->queryCall(
            'DELETE FROM T_PIECES WHERE PIE_ID = :pieceId',
            [
                ['pieceId', $pieceId, PDO::PARAM_INT]
            ]
        );

        return $result;
    }
}
?>
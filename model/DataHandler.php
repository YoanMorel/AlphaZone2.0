<?php

/**
 * DbConnection's child DataHandler class
 * 
 * Make database informations about all the pieces and transfers all object on the server
 * 
 * @version 2.0
 * @author  Yoan Morel
 */
class DataHandler extends DbConnection {

    /**
	 * Variables storing pieces information before transfer
	 */
    private $section;
    private $subSection;
    private $dataTitle;
    private $dataStory;
    private $dataLink;
    private $dataCreationDate;

    /**
     * Set section attribute
     * 
     * @param string $sectionData section
     */
    public function setSection($sectionData) {
        $this->section = $sectionData;
    }

    /**
     * Set subsection attribute
     * 
     * @param string $subSectionData subsection
     */
    public function setSubSection($subSectionData) {
        $this->subSection = $subSectionData;
    }

    /**
     * Set data attributes
     * 
     * @param string $title piece's title
     * @param string $story piece's story
     * @param string $link  piece's link
     * @param string $creationDate piece's creation date
     */
    public function setData($title, $story = null, $link, $creationDate = null) {
        $this->dataTitle        = $title;
        $this->dataStory        = $story;
        $this->dataLink         = $link;
        $this->dataCreationdate = $creationDate;
    }

    /**
     * SQL service method to insert the new section in the SGBDR
     * 
     * @return object $result PDO statement
     */
    public function insertSectionInDB() {
        $result = $this->queryCall(
            'INSERT INTO T_SECTIONS (SEC_SECTION, SEC_CREATION_DATE) VALUES (:newSection, NOW())',
            [
                ['newSection', $this->section, PDO::PARAM_STR]
            ]
        );

        return $result;
    }

    /**
     * SQL service method to insert the new subsection in the SGBDR
     * 
     * @return object $result PDO statement
     */
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

    /**
     * SQL service method to insert new data informations about the new piece in the SGBDR
     * 
     * @return object $result PDO statement
     */
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

    /**
     * SQL service method to update data from a particular piece
     * 
     * @param int $pieceId piece id
     * @return object $result PDO statement
     */
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

    /**
     * SQL service method to delete data from a particular piece in the SGBDR
     * 
     * @param int $pieceId piece id
     * @return object $result PDO statement
     */
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
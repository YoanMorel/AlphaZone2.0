<?php

/**
 * DbConnection's child Inquiries class
 * 
 * Centralizes inquire services to access the inquiries informations in the SGBDR
 * 
 * @version 2.0
 * @author  Yoan Morel
 */
class Inquiries extends DbConnection {

    /**
     * SQL service method to store inquiries and contacts data in the SGBDR
     * 
     * @param array $vars Inquiries and Contacts Informations
     * @return object $result PDO statement
     */
    public function addInquire($vars) {
        try {
            extract($vars);
            $exists = false;
            $contactID = null;

            $contacts = $this->getContacts()->fetchAll();
            foreach($contacts as $contact):
                if($contact['CON_MAIL'] == $mail):
                    $exists = true;
                    $contactID = $contact['CON_ID'];
                endif;
            endforeach;

            $this->startTransaction();

            if(!$exists):
                $result = $this->queryCall(
                    'INSERT INTO T_CONTACTS (CON_LAST_NAME, CON_ORGANISME, CON_MAIL) VALUES (:lname, :organisme, :mail)',
                    [
                        ['lname', $lname, PDO::PARAM_STR],
                        ['organisme', $organisme, PDO::PARAM_STR],
                        ['mail', $mail, PDO::PARAM_STR]
                    ]
                );

                $contactID = $this->getLastInsertId();
            endif;

            $result = $this->queryCall(
                'INSERT INTO T_INQUIRIES (INQ_SUBJECT, INQ_INQUIRE, INQ_POST_DATE, INQ_OPENED, INQ_REPLIED, CON_ID) VALUES (:subject, :inquire, NOW(), false, false, :contactID)',
                [
                    ['subject', $subject, PDO::PARAM_STR],
                    ['inquire', $inquire, PDO::PARAM_STR],
                    ['contactID', $contactID, PDO::PARAM_INT]
                ]
            );
            
            if($this->commitTransaction())
                return $result;
        } catch (PDOException $error) {
            $this->preventTransaction();
            $msg = 'ERREUR PDO within ' . $error->getFile() . ' L.' . $error->getLine() . ' : ' . $error->getMessage();
			die($msg);
        }
    }

    /**
     * SQL service method to get contact informations from the SGBDR
     * 
     * @return object $result PDO statement
     */   
    public function getContacts() {
        $result = $this->queryCall('SELECT * FROM T_CONTACTS');

        return $result;
    }

    /**
     * SQL service method to get all inquieries or inquiries related to a particular contact
     * 
     * @param int $contactID contact id
     * @return object $result PDO statement
     */
    public function getInquiries($contactID = NULL) {
        if(!$contactID):
            $result = $this->queryCall(
                'SELECT inq.*, con.* FROM T_INQUIRIES inq LEFT JOIN T_CONTACTS con ON con.CON_ID = inq.CON_ID ORDER BY inq.INQ_POST_DATE DESC'
            );
        else:
            $result = $this->queryCall(
                'SELECT inq.*, con.* FROM T_INQUIRIES inq INNER JOIN T_CONTACTS con ON :contactID = inq.CON_ID ORDER BY inq.INQ_POST_DATE DESC',
                [
                    ['contactID', $contactID, PDO::PARAM_STR]
                ]
            );
        endif;

        return $result;
    }

    /**
     * SQL service method to get informations about a particular inquire
     * 
     * @param int $inquireID inquire id
     * @return object $result PDO statement
     */
    public function getInquire($inquireID) {
        $result = $this->queryCall(
            'SELECT inq.*, con.* FROM T_INQUIRIES inq INNER JOIN T_CONTACTS con ON con.CON_ID = inq.CON_ID AND inq.INQ_ID = :inquireID',
            [
                ['inquireID', $inquireID, PDO::PARAM_INT]
            ]
        );

        return $result;
    }

    /**
     * SQL service method to get all sealed inquiries
     * 
     * @return object $result PDO statement
     */
    public function getSealedInquiries() {
        $result = $this->queryCall('SELECT INQ_OPENED FROM T_INQUIRIES WHERE INQ_OPENED = 0');

        return $result;
    }

    /**
     * SQL service method to get one particular sealed inquire
     * 
     * @param int $inquireID inquire id
     * @return object $result PDO statement
     */
    public function getSealedInquire($inquireID) {
        $result = $this->queryCall(
            'SELECT INQ_OPENED FROM T_INQUIRIES WHERE INQ_OPENED = 0 AND INQ_ID = :inquireID',
            [
                ['inquireID', $inquireID, PDO::PARAM_INT]
            ]
        );

        return $result;
    }

    /**
     * SQL service method to get all opened inquiries
     * 
     * @return object $result PDO statement
     */
    public function getRepliedInquiries() {
        $result = $this->queryCall('SELECT INQ_REPLIED FROM T_INQUIRIES');

        return $result;
    }

    /**
     * SQL service method to set a value open on a particular inquire
     * 
     * @param int $inquireID inquire id
     * @return object $result PDO statement
     */
    public function setOpenedInquire($inquireID) {
            // TOGGLED BOOL
        $result = $this->queryCall(
            'UPDATE T_INQUIRIES SET INQ_OPENED = NOT INQ_OPENED WHERE INQ_ID = :inquireID',
            [
                ['inquireID', $inquireID, PDO::PARAM_INT]
            ]
        );

        return $result;
    }

    /**
     * SQL service method to set all inquiries on opened
     * 
     * @return object $result PDO statement
     */
    public function setOpenedAllInquieries() {
        $result = $this->queryCall('UPDATE T_INQUIRIES SET INQ_OPENED = 1');

        return $result;
    }

    /**
     * SQL service method to set all inquiries on sealed
     * 
     * @return object $result PDO statement
     */
    public function setSealedAllInquieries() {
        $result = $this->queryCall('UPDATE T_INQUIRIES SET INQ_OPENED = 0');

        return $result;
    }

    /**
     * SQL service method to set a value replied on a particular inquire
     * 
     * @param int $inquireID inquire id
     * @return object $result PDO statement
     */
    public function setRepliedInquire($inquireID) {
            // TOGGLED BOOL
        $result = $this->queryCall(
            'UPDATE T_INQUIRIES SET INQ_REPLIED = NOT INQ_OPENED WHERE INQ_ID = :inquireID',
            [
                ['inquireID', $inquireID, PDO::PARAM_INT]
            ]
        );

        return $result;
    }

    /**
     * SQL service method to get all inquiries in trash table
     * 
     * @return object $result PDO statement
     */
    public function getTrashedInquiries() {
        $result = $this->queryCall(
            'SELECT inq_t.*, con.* FROM T_INQUIRIES_TRASH inq_t LEFT JOIN T_CONTACTS con ON con.CON_ID = inq_t.CON_ID ORDER BY inq_t.INQ_POST_DATE DESC'
        );

        return $result;
    }

    /**
     * SQL service method to get a particular inquire in the trash table
     * 
     * @param int $inquireID inquire id
     * @return object $result PDO statement
     */
    public function getTrashedInquire($inquireID) {
        $result = $this->queryCall(
            'SELECT inq_t.*, con.* FROM T_INQUIRIES_TRASH inq_t INNER JOIN T_CONTACTS con ON con.CON_ID = inq_t.CON_ID AND inq_t.INQ_ID = :inquireID',
            [
                ['inquireID', $inquireID, PDO::PARAM_INT]
            ]
        );

        return $result;
    }

    /**
     * SQL service method to move a particular inquire from inquiries table to trash table
     * 
     * @param int $inquireID inquire id
     * @return object $result PDO statement
     */
    public function moveInquireToTrash($inquireID) {
        try{
        $this->startTransaction();
        $result = $this->queryCall(
            'INSERT INTO T_INQUIRIES_TRASH SELECT inq.* FROM T_INQUIRIES inq WHERE inq.INQ_ID = :inquireID',
            [
                ['inquireID', $inquireID, PDO::PARAM_INT]
            ]
        );

        if($result):
            $result = $this->queryCall(
                'DELETE FROM T_INQUIRIES WHERE INQ_ID = :inquireID',
                [
                    ['inquireID', $this->getLastInsertId(), PDO::PARAM_INT]
                ]
            );
        endif;

        if($this->commitTransaction())
            return $result;
        } catch (PDOException $error) {
            $this->preventTransaction();
            $msg = 'ERREUR PDO within ' . $error->getFile() . ' L.' . $error->getLine() . ' : ' . $error->getMessage();
			die($msg);
        }
    }

    /**
     * SQL service method to move a particular inquire from trash table to inquiries table
     * 
     * @param int $trashID inquire id in trash table
     * @return object $result PDO statement
     */
    public function moveTrashToInquire($trashID) {
        try{
            $this->startTransaction();
            $result = $this->queryCall(
                'INSERT INTO T_INQUIRIES SELECT inq_t.* FROM T_INQUIRIES_TRASH inq_t WHERE inq_t.INQ_ID = :trashID',
                [
                    ['trashID', $trashID, PDO::PARAM_INT]
                ]
            );
    
            if($result):
                $result = $this->queryCall(
                    'DELETE FROM T_INQUIRIES_TRASH WHERE INQ_ID = :trashID',
                    [
                        ['trashID', $this->getLastInsertId(), PDO::PARAM_INT]
                    ]
                );
            endif;
    
            if($this->commitTransaction())
                return $result;
        } catch (PDOException $error) {
            $this->preventTransaction();
            $msg = 'ERREUR PDO within ' . $error->getFile() . ' L.' . $error->getLine() . ' : ' . $error->getMessage();
            die($msg);
        }
    }
}
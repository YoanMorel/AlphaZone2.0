<?php

class Inquiries extends DbConnection {

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

    public function getContacts() {
        $result = $this->queryCall('SELECT * FROM T_CONTACTS');

        return $result;
    }

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

    public function getInquire($inquireID) {
        $result = $this->queryCall(
            'SELECT inq.*, con.* FROM T_INQUIRIES inq INNER JOIN T_CONTACTS con ON con.CON_ID = inq.CON_ID AND inq.INQ_ID = :inquireID',
            [
                ['inquireID', $inquireID, PDO::PARAM_INT]
            ]
        );

        return $result;
    }

    public function getSealedInquiries() {
        $result = $this->queryCall('SELECT INQ_OPENED FROM T_INQUIRIES WHERE INQ_OPENED = 0');

        return $result;
    }

    public function getSealedInquire($inquireID) {
        $result = $this->queryCall(
            'SELECT INQ_OPENED FROM T_INQUIRIES WHERE INQ_OPENED = 0 AND INQ_ID = :inquireID',
            [
                ['inquireID', $inquireID, PDO::PARAM_INT]
            ]
        );

        return $result;
    }

    public function getRepliedInquiries() {
        $result = $this->queryCall('SELECT INQ_REPLIED FROM T_INQUIRIES');

        return $result;
    }

    // TOGGLED BOOL
    public function setOpenedInquire($inquireID) {
        $result = $this->queryCall(
            'UPDATE T_INQUIRIES SET INQ_OPENED = NOT INQ_OPENED WHERE INQ_ID = :inquireID',
            [
                ['inquireID', $inquireID, PDO::PARAM_INT]
            ]
        );

        return $result;
    }

    public function setOpenedAllInquieries() {
        $result = $this->queryCall('UPDATE T_INQUIRIES SET INQ_OPENED = 1');

        return $result;
    }

    public function setSealedAllInquieries() {
        $result = $this->queryCall('UPDATE T_INQUIRIES SET INQ_OPENED = 0');

        return $result;
    }

    // TOGGLED BOOL
    public function setRepliedInquire($inquireID) {
        $result = $this->queryCall(
            'UPDATE T_INQUIRIES SET INQ_REPLIED = NOT INQ_OPENED WHERE INQ_ID = :inquireID',
            [
                ['inquireID', $inquireID, PDO::PARAM_INT]
            ]
        );

        return $result;
    }
}
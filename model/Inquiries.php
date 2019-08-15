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
                $success = $this->queryCall(
                    'INSERT INTO T_CONTACTS (CON_LAST_NAME, CON_ORGANISME, CON_MAIL) VALUES (:lname, :organisme, :mail)',
                    [
                        ['lname', $lname, PDO::PARAM_STR],
                        ['organisme', $organisme, PDO::PARAM_STR],
                        ['mail', $mail, PDO::PARAM_STR]
                    ]
                );

                $contactID = $this->getLastInsertId();
            endif;

            $success = $this->queryCall(
                'INSERT INTO T_INQUIRIES (INQ_SUBJECT, INQ_INQUIRE, INQ_POST_DATE, INQ_OPENED, CON_ID) VALUES (:subject, :inquire, NOW(), false, :contactID)',
                [
                    ['subject', $subject, PDO::PARAM_STR],
                    ['inquire', $inquire, PDO::PARAM_STR],
                    ['contactID', $contactID, PDO::PARAM_STR]
                ]
            );
            
            if($this->commitTransaction())
                return $success;
        } catch (PDOException $error) {
            $this->preventTransaction();
            $msg = 'ERREUR PDO within ' . $error->getFile() . ' L.' . $error->getLine() . ' : ' . $error->getMessage();
			die($msg);
        }
    }

    public function getContacts() {
        $success = $this->queryCall('SELECT * FROM T_CONTACTS');

        return $success;
    }

    public function getInquiries($contactID = NULL) {
        if(!$contactID):
            $success = $this->queryCall(
                'SELECT inq.*, con.* FROM T_INQUIRIES inq LEFT JOIN T_CONTACTS con ON con.CON_ID = inq.CON_ID'
            );
        else:
            $success = $this->queryCall(
                'SELECT inq.*, con.* FROM T_INQUIRIES inq LEFT JOIN T_CONTACTS con ON :contactID = inq.CON_ID',
                [
                    ['contactID', $contactID, PDO::PARAM_STR]
                ]
            );
        endif;

        return $success;
    }
}
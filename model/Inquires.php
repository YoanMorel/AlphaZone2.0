<?php

class Inquires extends DbConnection {

    public function addInquire($vars) {
        try {
            extract($vars);
            $this->startTransaction();

            $success = $this->queryCall(
                'INSERT INTO T_CONTACTS (CON_LAST_NAME, CON_ORGANISME, CON_MAIL) VALUES (:lname, :organisme, :mail)',
                [
                    ['lname', $lname, PDO::PARAM_STR],
                    ['organisme', $organisme, PDO::PARAM_STR],
                    ['mail', $mail, PDO::PARAM_STR]
                ]
            );

            $success = $this->queryCall(
                'INSERT INTO T_INQUIRES (INQ_SUBJECT, INQ_INQUIRE, INQ_POST_DATE, INQ_OPENED, CON_ID) VALUES (:subject, :inquire, NOW(), false, LAST_INSERT_ID())',
                [
                    ['subject', $subject, PDO::PARAM_STR],
                    ['inquire', $inquire, PDO::PARAM_STR]
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

    // public function getInquires($contactMail = NULL) {
    //     $success = $this->queryCall('');
    // }
}
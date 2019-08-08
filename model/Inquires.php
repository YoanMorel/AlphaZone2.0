<?php

class Inquires extends DbConnection {

    public function addInquire($authorLastName, $authorFirstName, $authorMail, $inquireSubject, $inquire) {
        try {
            $this->startTransaction();

            $success = $this->queryCall(
                'INSERT INTO T_CONTACTS (CON_LAST_NAME, CON_FIRST_NAME, CON_MAIL) VALUES (:lname, :fname, :mail)',
                [
                    ['lname', $authorLastName, PDO::PARAM_STR],
                    ['fname', $authorFirstName, PDO::PARAM_STR],
                    ['mail', $authorMail, PDO::PARAM_STR]
                ]
            );

            $success = $this->queryCall(
                'INSERT INTO T_INQUIRES (INQ_SUBJECT, INQ_INQUIRE, INQ_POST_DATE, INQ_OPENED, CON_ID) VALUES (:inquireSubject, :inquire, NOW(), false, LAST_INSERT_ID())',
                [
                    ['inquireSubject', $inquireSubject, PDO::PARAM_STR],
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
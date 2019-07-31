<?php

class Inquires extends DbConnection {

    public function addInquire($authorLastName, $authorFirstName, $authorMail, $inquireSubject, $inquire) {
        try {
            $this->startTransaction();

            $success = $this->queryCall(
                'INSERT INTO contacts (lname, fname, mail) VALUES (:lname, :fname, :mail)',
                array(
                    array('lname', $authorLastName, PDO::PARAM_STR),
                    array('fname', $authorFirstName, PDO::PARAM_STR),
                    array('mail', $authorMail, PDO::PARAM_STR)
                ));

            $success = $this->queryCall(
                'INSERT INTO inquires (inquireSubject, inquire, inquireDate, id_contacts) VALUES (:inquireSubject, :inquire, NOW(), LAST_INSERT_ID())',
                array(
                    array('inquireSubject', $inquireSubject, PDO::PARAM_STR),
                    array('inquire', $inquire, PDO::PARAM_STR)
                ));
            
            if($this->commitTransaction())
                return $success;
        } catch (PDOException $error) {
            $this->preventTransaction();
            $msg = 'ERREUR PDO within ' . $error->getFile() . ' L.' . $error->getLine() . ' : ' . $error->getMessage();
			die($msg);
        }
    }

    public function getContacts() {
        $success = $this->queryCall('SELECT * FROM contacts');

        return $success;
    }

    public function getInquires($contactMail = NULL) {
        $success = $this->queryCall('');
    }
}
<?php

class User extends DbConnection {
    public function getUser($login) {
        $result = $this->queryCall(
            'SELECT * FROM T_USERS WHERE USE_LOGIN = :login',
            [
                ['login', $login, PDO::PARAM_STR]
            ]
        );

        return $result;
    }

    protected function getPassword($login) {
        $result = $this->queryCall(
            'SELECT USE_PWD FROM T_USERS WHERE USE_LOGIN = :login',
            [
                ['login', $login, PDO::PARAM_STR]
            ]
        );

        return $result;
    }
}
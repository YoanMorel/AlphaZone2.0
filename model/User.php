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

    public function getPassword($login) {
        $result = $this->queryCall(
            'SELECT USE_PWD FROM T_USERS WHERE USE_LOGIN = :login',
            [
                ['login', $login, PDO::PARAM_STR]
            ]
        );

        return $result;
    }

    public function updatePwd($newPwd, $password) {
        $result = $this->queryCall(
            'UPDATE T_USERS SET USE_PWD = :newPwd WHERE USE_PWD = :password',
            [
                ['newPwd', $newPwd, PDO::PARAM_STR],
                ['password', $password, PDO::PARAM_STR]
            ]
        );

        return $result;
    }

    public function updateLogin($newLogin, $login) {
        $result = $this->queryCall(
            'UPDATE T_USERS SET USE_LOGIN = :newLogin WHERE USE_LOGIN = :login',
            [
                ['newLogin', $newLogin, PDO::PARAM_STR],
                ['login', $login, PDO::PARAM_STR]
            ]
        );

        return $result;
    }

    public function updateName($name) {

    }
}
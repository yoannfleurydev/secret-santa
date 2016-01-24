<?php

namespace SecretSanta\DAO;


use SecretSanta\POPO\User;

class UserDAO extends DAO {
    private $COST = 11;

    public function find($id) {
        $sql = "SELECT * FROM santa_user WHERE user_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));
        if ($row) return $this->buildDomainObject($row); else
            throw new \Exception("UserDAO : No santa_user matching id " . $id);
    }

    public function findAll() {
        $sql = "SELECT * FROM santa_user";
        $rows = $this->getDb()->fetchAll($sql);
        $users = array();
        foreach ($rows as $row) {
            $user_id = $row['user_id'];
            $users[$user_id] = $this->buildDomainObject($row);
        }
        return $users;
    }

    public function findByUserLogin($user_login) {
        $sql = "SELECT * FROM santa_user WHERE user_login=?";
        $row = $this->getDb()->fetchAssoc($sql, array($user_login));
        if ($row) return $this->buildDomainObject($row); else
            throw new \Exception('User ' . $user_login . ' not found.');
    }

    public function setUser($user_login, $user_password, $user_firstname, $user_lastname,
                            $user_email, $user_access = "USER") {
        $login = $user_login;
        $email = $user_email;
        $firstname = $user_firstname;
        $lastname = $user_lastname;

        $pass = password_hash($user_password, PASSWORD_BCRYPT, array('cost' => $this->COST));
        $userData = array(
            'user_login' => $login,
            'user_password' => $pass,
            'user_firstname' => $firstname,
            'user_lastname' => $lastname,
            'user_email' => $email,
            'user_access' => $user_access
        );
        $this->getDb()->insert("santa_user", $userData);
    }

    public function deleteUser($user_id) {
        $this->getDb()->delete('santa_user', array('user_id' => $user_id));
    }

    public function updatePassword($user_password, $user_id) {
        $pass = password_hash($user_password, PASSWORD_BCRYPT, array('cost' => $this->COST));
        $this->getDb()->update("santa_user", array('user_password' => $pass), array('user_id' => $user_id));
    }

    public function userLoginExist($user_login) {
        $login = htmlspecialchars($user_login);
        $sql = "SELECT * FROM santa_user WHERE user_login=?";
        $row = $this->getDb()->fetchAssoc($sql, array($login));

        return !($row == null);
    }

    public function verifyLogin($user_login, $password) {
        $sql = "SELECT * FROM santa_user WHERE user_login=?";
        $row = $this->getDb()->fetchAssoc($sql, array(htmlspecialchars($user_login)));
        return password_verify($password, $row['user_password']);
    }

    protected function buildDomainObject($row) {
        $user = new User();

        $user->setUserId($row['user_id']);
        $user->setUserLogin($row['user_login']);
        $user->setUserPassword($row['user_password']);
        $user->setUserFirstname($row['user_firstname']);
        $user->setUserLastname($row['user_lastname']);
        $user->setUserEmail($row['user_email']);
        $user->setUserAccess($row['user_access']);

        return $user;
    }
}
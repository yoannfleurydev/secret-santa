<?php

namespace SecretSanta\DAO;


use SecretSanta\POPO\User;

class UserDAO extends DAO {
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

    public function setUser($username, $password) {
        $login = htmlspecialchars($username);
        $options = array('cost' => 11);
        $pass = password_hash($password, PASSWORD_BCRYPT, $options);
        $userData = array('user_login' => $login, 'user_password' => $pass, // TODO en dur dans le code, mais peut être serait-il intéressant de faire une requête sur la base de
            // données pour avoir par défaut l'id qui correspond à un USER.
            'user_access_id' => 2);
        $this->getDb()->insert("mq_user", $userData);
    }
    public function updatePassword($user_password, $user_id) {
        $pass = password_hash($user_password, PASSWORD_BCRYPT, array('cost' => 11));
        $this->getDb()->update("mq_user", array('user_password' => $pass), array('user_id' => $user_id));
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
<?php

namespace SecretSanta\DAO;


class InstanceDAO extends DAO {

    public function find($id) {
        $sql = "SELECT * FROM santa_instance WHERE instance_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));
        if ($row) return $this->buildDomainObject($row); else
            throw new \Exception("UserDAO : No santa_instance matching id " . $id);
    }

    public function findAll() {
        $sql = "SELECT * FROM santa_instance";
        $rows = $this->getDb()->fetchAll($sql);
        $instances = array();
        foreach ($rows as $row) {
            $instance_id = $row['instance_id'];
            $instances[$instance_id] = $this->buildDomainObject($row);
        }
        return $instances;
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
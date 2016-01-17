<?php

namespace SecretSanta\DAO;


use SecretSanta\POPO\Instance;

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

    public function setInstance($instance_name, $instance_hash, $instance_author_id) {
        $instance_name = htmlspecialchars($instance_name);
        $instance_hash = htmlspecialchars($instance_hash);
        $instance_year = date('Y');

        $instanceData = array(
            'instance_year' => $instance_year,
            'instance_name' => $instance_name,
            'instance_hash' => $instance_hash,
            'instance_author_id' => $instance_author_id
        );
        $this->getDb()->insert("santa_instance", $instanceData);
    }

    protected function buildDomainObject($row) {
        $instance = new Instance();

        $instance->setInstanceId($row['instance_id']);
        $instance->setInstanceYear($row['instance_year']);
        $instance->setInstanceName($row['instance_name']);
        $instance->setInstanceHash($row['instance_hash']);
        $instance->setInstanceAuthorId($row['instance_author_id']);


        return $instance;
    }
}
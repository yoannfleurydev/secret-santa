<?php

namespace SecretSanta\DAO;


use SecretSanta\POPO\Instance;

class InstanceDAO extends DAO {
    public function find($instance_id) {
        $sql = "SELECT * FROM santa_instance WHERE instance_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($instance_id));
        if ($row) return $this->buildDomainObject($row); else
            throw new \Exception("Instance : No santa_instance matching id " . $instance_id);
    }

    public function findInstanceHash($instance_hash) {
        $sql = "SELECT * FROM santa_instance WHERE instance_hash=?";
        $row = $this->getDb()->fetchAssoc($sql, array($instance_hash));
        if ($row) return $this->buildDomainObject($row); else
            throw new \Exception("Instance : No santa_instance matching instance_hash " . $instance_hash);
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

    public function setInstance($instance_year, $instance_name, $instance_hash, $instance_author_id) {
        $instanceData = array(
            'instance_year' => $instance_year,
            'instance_name' => $instance_name,
            'instance_hash' => $instance_hash,
            'instance_author_id' => $instance_author_id
        );
        $this->getDb()->insert("santa_instance", $instanceData);
    }

    public function instanceNameExist($instance_name) {
        $name = $instance_name;
        $sql = "SELECT * FROM santa_instance WHERE instance_name=?";
        $row = $this->getDb()->fetchAssoc($sql, array($name));

        return !($row == null);
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
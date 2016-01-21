<?php

namespace SecretSanta\DAO;


use SecretSanta\POPO\Result;

class ResultDAO extends DAO {
    public function find($result_id) {
        $sql = "SELECT * FROM santa_result WHERE result_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($result_id));
        if ($row) return $this->buildDomainObject($row); else
            throw new \Exception("Instance : No santa_result matching id " . $result_id);
    }

    public function findAll() {
        $sql = "SELECT * FROM santa_result";
        $rows = $this->getDb()->fetchAll($sql);
        $results = array();
        foreach ($rows as $row) {
            $result_id = $row['result_id'];
            $results[$result_id] = $this->buildDomainObject($row);
        }
        return $results;
    }

    public function setResult($result_instance_id, $result_sender_user_id, $result_recipient_user_id) {
        $resultData = array(
            'result_instance_id' => $result_instance_id,
            'result_sender_user_id' => $result_sender_user_id,
            'result_recipient_user_id' => $result_recipient_user_id
        );
        $this->getDb()->insert("santa_result", $resultData);
    }

    public function resultInstanceIdExist($instance_id) {
        $sql = "SELECT * FROM santa_result WHERE result_instance_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($instance_id));

        return !($row == null);
    }

    protected function buildDomainObject($row) {
        $result = new Result();

        $result->setResultId($row['result_id']);
        $result->setResultInstanceId($row['result_instance_id']);
        $result->setResultSenderUserId($row['result_sender_user_id']);
        $result->setResultRecipientUserId($row['result_recipient_user_id']);

        return $result;
    }
}
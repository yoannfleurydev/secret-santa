<?php

namespace SecretSanta\DAO;


use SecretSanta\POPO\Participation;

class ParticipationDAO extends DAO {
    public function find($id) {
        $sql = "SELECT * FROM santa_participation WHERE participation_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));
        if ($row) return $this->buildDomainObject($row); else
            throw new \Exception("Participation : No santa_participation matching id " . $id);
    }

    public function countParticipationsInstanceId($participation_instance_id) {
        $sql = "SELECT COUNT(*) FROM santa_participation WHERE participation_instance_id=?";
        $count = $this->getDb()->fetchAssoc($sql, array($participation_instance_id));

        return $count['COUNT(*)'];
    }

    public function countRealParticipationsInstanceId($participation_instance_id, $participation_result = 1) {
        $sql = "SELECT COUNT(*) FROM santa_participation WHERE participation_instance_id=? AND participation_result=?";
        $count = $this->getDb()->fetchAssoc($sql, array($participation_instance_id, $participation_result));

        return $count['COUNT(*)'];
    }

    public function findParticipationsUserId($participation_user_id) {
        $allParticipations = $this->findAll();

        $participations = array();
        foreach($allParticipations as $participation) {
            if ($participation->getParticipationUserId() === $participation_user_id) {
                $participations[$participation->getParticipationId()] = $participation;
            }
        }

        return $participations;
    }

    public function countParticipationUserId($participation_user_id) {
        $sql = "SELECT COUNT(*) FROM santa_participation WHERE participation_user_id=?";
        $count = $this->getDb()->fetchAssoc($sql, array($participation_user_id));

        return $count['COUNT(*)'];
    }

    public function findAll() {
        $sql = "SELECT * FROM santa_participation";
        $rows = $this->getDb()->fetchAll($sql);
        $participations = array();
        foreach ($rows as $row) {
            $participation_id = $row['participation_id'];
            $participations[$participation_id] = $this->buildDomainObject($row);
        }
        return $participations;
    }

    public function setParticipation($participation_instance_id, $participation_user_id, $participation_result = 0) {
        $participation_data = array(
            'participation_instance_id' => $participation_instance_id,
            'participation_user_id' => $participation_user_id,
            'participation_result' => $participation_result
        );
        $this->getDb()->insert("santa_participation", $participation_data);
    }

    public function updateParticipationResult($participation_id, $participation_result) {
        $this->getDb()->update("santa_participation",
            array('participation_result' => $participation_result),
            array('participation_id' => $participation_id)
        );
    }

    public function participationExist($participation_instance_id, $participation_user_id) {
        $sql = "SELECT * FROM santa_participation WHERE participation_instance_id=? AND participation_user_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($participation_instance_id, $participation_user_id));

        return !($row == null);
    }

    protected function buildDomainObject($row) {
        $participation = new Participation();

        $participation->setParticipationId($row['participation_id']);
        $participation->setParticipationInstanceId($row['participation_instance_id']);
        $participation->setParticipationUserId($row['participation_user_id']);
        $participation->setParticipationResult($row['participation_result']);

        return $participation;
    }
}
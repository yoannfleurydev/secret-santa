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

    protected function buildDomainObject($row) {
        $participation = new Participation();

        $participation->setParticipationId($row['participation_id']);
        $participation->setParticipationInstanceId($row['participation_instance_id']);
        $participation->setParticipationUserId($row['participation_user_id']);
        $participation->setParticipationResult($row['participation_result']);

        return $participation;
    }
}
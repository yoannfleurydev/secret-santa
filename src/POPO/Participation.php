<?php

namespace SecretSanta\POPO;


class Participation {
    private $participation_id;
    private $participation_instance_id;
    private $participation_user_id;
    private $participation_result;

    /**
     * @return mixed
     */
    public function getParticipationId() {
        return $this->participation_id;
    }

    /**
     * @param mixed $participation_id
     */
    public function setParticipationId($participation_id) {
        $this->participation_id = $participation_id;
    }

    /**
     * @return mixed
     */
    public function getParticipationInstanceId() {
        return $this->participation_instance_id;
    }

    /**
     * @param mixed $participation_instance_id
     */
    public function setParticipationInstanceId($participation_instance_id) {
        $this->participation_instance_id = $participation_instance_id;
    }

    /**
     * @return mixed
     */
    public function getParticipationUserId() {
        return $this->participation_user_id;
    }

    /**
     * @param mixed $participation_user_id
     */
    public function setParticipationUserId($participation_user_id) {
        $this->participation_user_id = $participation_user_id;
    }

    /**
     * @return mixed
     */
    public function getParticipationResult() {
        return $this->participation_result;
    }

    /**
     * @param mixed $participation_result
     */
    public function setParticipationResult($participation_result) {
        $this->participation_result = $participation_result;
    }
}
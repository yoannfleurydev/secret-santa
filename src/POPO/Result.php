<?php

namespace SecretSanta\POPO;


class Result {
    private $result_id;
    private $result_instance_id;
    private $result_sender_user_id;
    private $result_recipient_user_id;

    /**
     * @return mixed
     */
    public function getResultId() {
        return $this->result_id;
    }

    /**
     * @param mixed $result_id
     */
    public function setResultId($result_id) {
        $this->result_id = $result_id;
    }

    /**
     * @return mixed
     */
    public function getResultInstanceId() {
        return $this->result_instance_id;
    }

    /**
     * @param mixed $result_instance_id
     */
    public function setResultInstanceId($result_instance_id) {
        $this->result_instance_id = $result_instance_id;
    }

    /**
     * @return mixed
     */
    public function getResultSenderUserId() {
        return $this->result_sender_user_id;
    }

    /**
     * @param mixed $result_sender_user_id
     */
    public function setResultSenderUserId($result_sender_user_id) {
        $this->result_sender_user_id = $result_sender_user_id;
    }

    /**
     * @return mixed
     */
    public function getResultRecipientUserId() {
        return $this->result_recipient_user_id;
    }

    /**
     * @param mixed $result_recipient_user_id
     */
    public function setResultRecipientUserId($result_recipient_user_id) {
        $this->result_recipient_user_id = $result_recipient_user_id;
    }
}
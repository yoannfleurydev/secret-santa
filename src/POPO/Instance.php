<?php

namespace SecretSanta\POPO;


class Instance {
    private $instance_id;
    private $instance_year;
    private $instance_name;
    private $instance_hash;
    private $instance_author_id;

    /**
     * @return mixed
     */
    public function getInstanceAuthorId() {
        return $this->instance_author_id;
    }

    /**
     * @param mixed $instance_author_id
     */
    public function setInstanceAuthorId($instance_author_id) {
        $this->instance_author_id = $instance_author_id;
    }

    /**
     * @return mixed
     */
    public function getInstanceId() {
        return $this->instance_id;
    }

    /**
     * @param mixed $instance_id
     */
    public function setInstanceId($instance_id) {
        $this->instance_id = $instance_id;
    }

    /**
     * @return mixed
     */
    public function getInstanceYear() {
        return $this->instance_year;
    }

    /**
     * @param mixed $instance_year
     */
    public function setInstanceYear($instance_year) {
        $this->instance_year = $instance_year;
    }

    /**
     * @return mixed
     */
    public function getInstanceName() {
        return $this->instance_name;
    }

    /**
     * @param mixed $instance_name
     */
    public function setInstanceName($instance_name) {
        $this->instance_name = $instance_name;
    }

    /**
     * @return mixed
     */
    public function getInstanceHash() {
        return $this->instance_hash;
    }

    /**
     * @param mixed $instance_hash
     */
    public function setInstanceHash($instance_hash) {
        $this->instance_hash = $instance_hash;
    }
}
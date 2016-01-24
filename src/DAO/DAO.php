<?php

namespace SecretSanta\DAO;

use Doctrine\DBAL\Connection;

abstract class DAO {
    private $db;

    public function __construct(Connection $db) {
        $this->db = $db;
    }

    protected function getDb() {
        return $this->db;
    }

    /**
     * @param $row The row of a table, containing the data.
     * @return mixed A POPO with the data from database.
     */
    protected abstract function buildDomainObject($row);
}
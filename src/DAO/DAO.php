<?php
/**
 * Created by PhpStorm.
 * User: Yoann
 * Date: 15/01/2016
 * Time: 21:44
 */

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

    protected abstract function buildDomainObject($row);
}
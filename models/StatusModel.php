<?php
    require_once("db/Connect.php");

    class StatusModel{
        private const string BASE_QUERY = "select * from status";
        private $db;
        private $statuses;

        public function __construct() {
            $this->db = Connect::getInstance()->getPDO();
            $this->statuses = [];
        }

        public function read() {
            $query = $this->db->query(self::BASE_QUERY);
            while($rows= $query->fetch(PDO::FETCH_ASSOC)) {
                $this->statuses[] = $rows;
            }
            return $this->statusesd;
        }
    }
?>
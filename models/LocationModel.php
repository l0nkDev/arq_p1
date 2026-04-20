<?php

require_once("db/Connect.php");

class LocationModel{
    private const BASE_QUERY = 'SELECT 
    l.id AS "Location_id",
    COALESCE(p_self.name, p_mod.name, p_room.name) AS "Precinct",
    COALESCE(m_self.number, m_room.number) AS "Module",
    r.number AS "Room"
FROM locations l
LEFT JOIN precincts p_self ON l.id = p_self.id
LEFT JOIN modules m_self ON l.id = m_self.id
LEFT JOIN precincts p_mod ON m_self.precinct_id = p_mod.id
LEFT JOIN rooms r ON l.id = r.id
LEFT JOIN modules m_room ON r.module_id = m_room.id
LEFT JOIN precincts p_room ON m_room.precinct_id = p_room.id
ORDER BY "Precinct", "Module", "Room" ASC;';
    private $db;
    private $locations;

    public function __construct() {
        $this->db = Connect::connection();
        $this->locations = [];
    }

    public function read() {
        $query = $this->db->query(self::BASE_QUERY);
        while($rows= $query->fetch(PDO::FETCH_ASSOC)) {
            $this->locations[] = $rows;
        }
        return $this->locations;
    }

    public function readId($id) {
        $sql = self::BASE_QUERY . " AND Location_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createPrecinct($data) {
        $this->db->beginTransaction();
        try {
            $locins = $this->db->query("insert into locations (type) values ('P') returning id;");
            $serial = $locins->fetch(PDO::FETCH_ASSOC)["id"];
        $sql = "insert into precincts (id, name) values(:id, :name)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id" => $serial, ":name" => $data["name"]]);
        $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function createModule($data) {
        $this->db->beginTransaction();
        try {
            $locins = $this->db->query("insert into locations (type) values ('M') returning id;");
            $serial = $locins->fetch(PDO::FETCH_ASSOC)['id'];
        $sql = "insert into modules (id, number, precinct_id) values(?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$serial, $data["number"], $data["precinct_id"]]);
        $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function createRoom($data) {
        $this->db->beginTransaction();
        try {
            $locins = $this->db->query("insert into locations (type) values ('R') returning id;");
            $serial = $locins->fetch(PDO::FETCH_ASSOC)['id'];
        $sql = "insert into rooms (id, number, module_id) values(?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$serial, $data["number"], $data["module_id"]]);
        $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
?>
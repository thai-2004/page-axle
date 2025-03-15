<?php
class Category {
    private $conn;
    private $table_name = "categories";

    public $id;
    public $name;
    public $icon;
    public $course_count;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                SET name=:name, icon=:icon, course_count=:course_count";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->icon = htmlspecialchars(strip_tags($this->icon));
        $this->course_count = htmlspecialchars(strip_tags($this->course_count));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":icon", $this->icon);
        $stmt->bindParam(":course_count", $this->course_count);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?> 
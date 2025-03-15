<?php
class News {
    private $conn;
    private $table_name = "news";

    public $id;
    public $title;
    public $description;
    public $image;
    public $author;
    public $category_id;
    public $views;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getLatest($limit = 6) {
        $query = "SELECT n.*, c.name as category_name 
                FROM " . $this->table_name . " n
                LEFT JOIN categories c ON n.category_id = c.id
                ORDER BY n.created_at DESC LIMIT " . $limit;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getAll() {
        $query = "SELECT n.*, c.name as category_name 
                FROM " . $this->table_name . " n
                LEFT JOIN categories c ON n.category_id = c.id
                ORDER BY n.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getById($id) {
        $query = "SELECT n.*, c.name as category_name 
                FROM " . $this->table_name . " n
                LEFT JOIN categories c ON n.category_id = c.id
                WHERE n.id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                SET title=:title, description=:description, 
                    image=:image, author=:author, 
                    category_id=:category_id, views=:views";

        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->image = htmlspecialchars(strip_tags($this->image));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->views = htmlspecialchars(strip_tags($this->views));

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":author", $this->author);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":views", $this->views);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        // If there's a new image
        if (!empty($this->image)) {
            $query = "UPDATE " . $this->table_name . "
                    SET title=:title, description=:description, 
                        image=:image, author=:author, 
                        category_id=:category_id, views=:views
                    WHERE id=:id";
        } else {
            // If no new image, don't update the image field
            $query = "UPDATE " . $this->table_name . "
                    SET title=:title, description=:description, 
                        author=:author, category_id=:category_id, 
                        views=:views
                    WHERE id=:id";
        }

        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->views = htmlspecialchars(strip_tags($this->views));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":author", $this->author);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":views", $this->views);
        $stmt->bindParam(":id", $this->id);

        if (!empty($this->image)) {
            $this->image = htmlspecialchars(strip_tags($this->image));
            $stmt->bindParam(":image", $this->image);
        }

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?> 
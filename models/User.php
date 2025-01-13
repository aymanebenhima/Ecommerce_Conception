<?php
namespace Models;

abstract class User {
    protected $conn;
    protected $table = "users";
    
    protected $id;
    protected $name;
    protected $email;
    protected $password;
    protected $role;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($name, $email, $password, $role): bool {
        $query = "INSERT INTO " . $this->table . " SET name=:name, email=:email, password=:password, role=:role";
        $stmt = $this->conn->prepare($query);

        $params = [
            ":name" => htmlspecialchars(strip_tags($name)),
            ":email" => htmlspecialchars(strip_tags($email)),
            ":password" => password_hash($password, PASSWORD_DEFAULT),
            ":role" => $role
        ];

        if ($stmt->execute($params)) {
            return true;
        }
        return false;
    }

    public function login($email, $password): bool {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([":email" => $email]);

        if ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            if (password_verify($password, $row['password'])) {
                $this->id = $row['id'];
                $this->name = $row['name'];
                $this->email = $row['email'];
                $this->role = $row['role'];
                return true;
            }
        }
        return false;
    }
}

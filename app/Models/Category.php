<?php

require_once 'config/Database.php';

class Category {

    private $db;

    public function __construct() 
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {   
        try {
           $query = "SELECT * FROM categories";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); 
        } catch (PDOException $e) {
            throw new Exception("Error :".$e->getMessage());
        }
        
    }

}
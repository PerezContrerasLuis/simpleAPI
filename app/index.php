<?php
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/Models/Category.php';
require_once __DIR__ . '/Controllers/CategoryController.php';

$db = Database::getInstance();

$connection = $db->getConnection();

if ($connection instanceof PDO) {
    

    $cat = new CategoryController();
    $cat->getAll();

    
}
?>

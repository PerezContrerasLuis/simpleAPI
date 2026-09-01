<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <h5> Welcome to My Local LAMP </h5>

    <?php

require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/Models/Category.php';

$db = Database::getInstance();

$connection = $db->getConnection();

if ($connection instanceof PDO) {
    echo "✅ Conexión exitosa.<br>";
    echo "Clase: " . get_class($connection);
    echo "--------";

    $cat = new Category();
    $result = $cat->getAll();

    print_r($result);
    
}
?>
</body>
</html>
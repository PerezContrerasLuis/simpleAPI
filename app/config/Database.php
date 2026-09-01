<?php
class Database {
    private static $instance = null;
    private $conection;

    public function __construct(){
        $env = parse_ini_file(__DIR__.'/../.env');

        $host = $env['DB_HOST'];
        $port = $env['DB_PORT'];
        $dbname = $env['DB_DATABASE'];
        $password = $env['DB_PASSWORD'];
        $username = $env['DB_USERNAME'];

        try {
            $dsn = "mysql:host={$host};port=$port;dbname={$dbname};charset=utf8mb4";
            $this->conection = new PDO(
                $dsn,$username,$password,[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['Error'=>'Error en conexion de Base de datos','code'=>'500',$e->getMessage()]);
            exit;
        }

    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
        
    }

    public function getConnection() {
        return $this->conection;
    }
}
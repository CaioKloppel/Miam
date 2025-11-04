<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

class GetCon{
    private static ?GetCon $getCon = null;
    private $con;

    private function __construct(){
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $port = $_ENV['DB_PORT'] ?? '3306';
        $dbname = $_ENV['DB_SCHEMA'] ?? '';
        $user = $_ENV['DB_USER'] ?? '';
        $password = $_ENV['DB_PASSWORD'] ?? '';
        $this->con = mysqli_connect($host, $user, $password, $dbname, $port);

        if (!$this->con) {
            die(json_encode([
                'success' => false, 
                'message' => 'Erro de conexão: ' . mysqli_connect_error()
            ]));
        }
        
        mysqli_set_charset($this->con, "utf8mb4");
    }

    public static function getInstance(){
        if(self::$getCon == null){
            self::$getCon = new GetCon();
        } return self::$getCon;
    }

    public function returnCon(){return $this->con;}
    public function closeCon(){
        if ($this->con){
            mysqli_close($this->con);
        }
    }

}
?>
<?php
class Connection {

    private const HOST = "127.0.0.1";
    private const DB = "mini_twitter";
    private const USER = "root";
    private const PASSWORD = "root";

    public const DUPLICATE_ROW = 1062;
    public const TABLE_NOT_EXIST = 1146;
    public const COLUMNS_DOESNT_MATCH = 1136;
    public const INCORRECT_DATETIME_VALUE = 1292;

    private $conn;

    public function __construct() {
        $this->conn = new PDO('mysql:host='.SELF::HOST.';dbname='.SELF::DB, SELF::USER, SELF::PASSWORD);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function fetchAll($sql) {
        $statement = $this->conn->prepare($sql);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function execute($sql, $params) {
        try {
            $statement = $this->conn->prepare($sql);

            if($params != null) {
                $totalParams = count($params);

                for($i=0; $i<$totalParams; $i++) {
                    $statement->bindValue(':'.$params[$i]['name'], $params[$i]['value']);
                }
            }
            $statement->execute();
            return true;
        } catch(PDOException $e) {
            echo json_encode($e)."<br />";
            return $e->errorInfo[1];
        }


    }
}
?>

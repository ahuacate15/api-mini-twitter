<?php
class Connection {

    private const HOST = "127.0.0.1";
    private const DB = "mini_twitter";
    private const USER = "root";
    private const PASSWORD = "root";

    /* constantes de estado para una consulta */
    public const DUPLICATE_ROW = 1062;
    public const TABLE_NOT_EXIST = 1146;
    public const COLUMNS_DOESNT_MATCH = 1136;
    public const INCORRECT_DATETIME_VALUE = 1292;
    public const FOREIGN_KEY_FAIL = 1452;
    public const DATA_TO_LONG = 1406;
    public const ERROR_PARAMS = 0;
    public const OK = -1;

    protected $conn;
    private $statement;

    public function __construct() {
        $this->conn = new PDO('mysql:host='.SELF::HOST.';dbname='.SELF::DB, SELF::USER, SELF::PASSWORD);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    protected function setQuery($sql) {
        $this->statement = $this->conn->prepare($sql);
        return $this;
    }

    protected function setInteger($param, $value) {
        $this->statement->bindValue(':'.$param, $value, PDO::PARAM_INT);
        return $this;
    }

    protected function setString($param, $value) {
        $this->statement->bindValue(':'.$param, $value, PDO::PARAM_STR);
        return $this;
    }

    protected function fetch() {
        $this->statement->execute();
        return $this->statement->fetch(PDO::FETCH_ASSOC);
    }

    protected function fetchAll() {
        $this->statement->execute();
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    * regresa alguna de las constantes de estado definidas
    * al inicio de la clase
    */
    public function execute() {
        try {
            $this->statement->execute();
            return self::OK;
        } catch(PDOException $e) {
            return $e->errorInfo[1];
        }


    }
}
?>

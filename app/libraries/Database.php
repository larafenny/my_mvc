<?php
    // PDO database class: connects to DB, prepare statements, bind values and fetch records

class Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbName = DB_NAME;
    private $dbHandler;
    private $statement;
    private $error;

    public function __construct() {
        // set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbName;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        // PDO instance
        try {
            $this->dbHandler = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    // prepare statement with query
    public function query($sql) {
        $this->statement = $this->dbHandler->prepare($sql);
    }

    // method to bind values
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->statement->bindValue($param, $value, $type);
    }

    // execute the prepare statement
    public function execute() {
        return $this->statement->execute();
    }

    // get results set as array of objects
    public function resultSet() {
        $this->execute();
        return $this->statement->fetchAll(PDO::FETCH_OBJ);
    }

    // get single record as object
    public function single() {
        $this->execute();
        return $this->statement->fetch(PDO::FETCH_OBJ);
    }

    // get row count
    public function rowCount() {
        return $this->statement->rowCount();
    }
}
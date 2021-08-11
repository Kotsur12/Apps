<?php

class User
{
    private $id;
    private $identificator;
    private $isValid;

    public function __construct($id, $identificator, $isValid)
    {
        $this->id = $id;
        $this->identificator = $identificator;
        $this->isValid = $isValid;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIdentificator()
    {
        return $this->identificator;
    }

    public function getIsValid()
    {
        return $this->isValid;
    }
}

class MyPDO
{
    private $_pdo;

    public function __construct()
    {
        $host = 'localhost';
        $db = 'sport';
        $user = 'devuser';
        $pass = 'G-^Ai9oA?@shFMTj';
        $charset = 'utf8';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        try {
            $this->_pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function getIsUserValid($identificator)
    {
        $sql = 'SELECT * FROM `user` WHERE `identificator` = ?';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute([$identificator]);
        return $stmt->fetch();
    }

    public function registerNewUser($identificator)
    {
        $sql = 'INSERT INTO `user` (`identificator`, `is_valid`) VALUES (?, ?)';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->execute([$identificator, 1]);
    }

    public function getPrediction(){
        $sql = 'SELECT `prediction_visibility` from `prediction`';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetch();
    }
}

try {
    $host = 'localhost';
    $db = 'sport';
    $user = 'devuser';
    $pass = 'G-^Ai9oA?@shFMTj';
    $charset = 'utf8';

    $pdo = new MyPDO();

} catch (PDOException $e) {
    die("Could not connect to the database $db :" . $e->getMessage());
}

try {
    $isVisible = $pdo->getPrediction();
    echo htmlspecialchars($isVisible['prediction_visibility']);
//    if (isset($_REQUEST['identificator'])) {
//        $isUserValid = $pdo->getIsUserValid($_REQUEST['identificator']);
//        if ($isUserValid != null) {
//            echo htmlspecialchars($isUserValid['is_valid']);
//        } else {
//            $pdo->registerNewUser($_REQUEST['identificator']);
//            //Новый пользователь всегда valid
//            echo 1;
//       }
//    }else{
//        echo "PARAMETERS NOT SET";
//    }
} catch (Exception $e) {
    echo "ERR";
}

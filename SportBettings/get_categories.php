<?php
//error_reporting(E_ALL);


class Category
{

    private $name;
    private $priority;

    public function __construct($id, $name, $priority)
    {
        $this->id = $id;
        $this->name = $name;
        $this->priority = $priority;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPriority()
    {
        return $this->priority;
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

    public function getRowsJson()
    {
        $sql = 'SELECT * FROM `category` WHERE `is_show` >= 1 ORDER BY `priority`';
        $q = $this->_pdo->query($sql);

        $arr = array();
        while ($row = $q->fetch()):

            $category = new Category($row['id'], $row['name'], $row['priority']);
            $obj = array('id' => $category->getId(), 'name' => $category->getName(), 'priority' => $category->getPriority());
            array_push($arr, $obj);
        endwhile;

        return json_encode($arr);
    }
}

try {
    $host = 'localhost';
    $db = 'sport';
    $user = 'devuser';
    $pass = 'G-^Ai9oA?@shFMTj';
    $charset = 'utf8';

    $pdo = new MyPDO();

    $categoriesJson = $pdo->getRowsJson();

    echo $categoriesJson;

} catch (PDOException $e) {
    die("Could not connect to the database $db :" . $e->getMessage());
}








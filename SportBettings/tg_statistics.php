<?php

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

    public function createNewRecord($userId)
    {
        $sql = 'INSERT INTO `tg_link_follows` (`user_id`, `link_click`) VALUES (?, ?)';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->execute([$userId, 1]);
    }

    public function updateRecord($userId)
    {
        $sql = 'UPDATE `tg_link_follows` SET `link_click` = `link_click` + 1 WHERE `id` = ?';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->execute([$userId]);
    }

    public function findRecord($userId): bool
    {
        $sql = 'SELECT * FROM `tg_link_follows` WHERE `id` = ?';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->execute([$userId]);
        $row = $stmt->fetch();
        return (htmlspecialchars($row['user_id']) != null);
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
    if (isset($_REQUEST['user_id'])) {
        $isFollowerExist = $pdo->findRecord($_REQUEST['user_id']);
        if ($isFollowerExist) {
            $pdo->updateRecord($_REQUEST['user_id']);
        } else {
            $pdo->createNewRecord($_REQUEST['user_id']);
        }
    } else {
        echo "PARAMETERS NOT SET";
    }
} catch (Exception $e) {
    echo "ERR";
}

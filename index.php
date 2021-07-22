<?php

use function Sodium\add;

class Category
{

    private $name;
    private $priority;

    public function __construct($name, $priority)
    {
        $this->name = $name;
        $this->priority = $priority;
    }

    public function printCategory()
    {
        echo $this->name . ' ' . $this->priority;
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

        $dsn = "mysql:host=$host;dbname=$db";

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

    public function getRows()
    {
        $sql = 'SELECT * FROM `category` ORDER BY `priority` DESC ';
        $q = $this->_pdo->query($sql);
        $q->setFetchMode(PDO::FETCH_ASSOC);
        return $q;
    }

    public function upPriority($id)
    {
        $sql = 'UPDATE `category` SET `priority` = `priority` + 1 WHERE `id` = ?';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->execute([$id]);

        //протестить этот запрос
//        мы получаем id всех у кого одинаковое priority, нужно оттуда убрать те, у которых id = 1
//        UPDATE `category` SET `priority` = `priority` + 1 WHERE `id` NOT IN (SELECT `id` FROM `category` WHERE `priority` IN (SELECT `priority` FROM `category` WHERE `id` = 1))

        //протестить это!!!!
//        UPDATE `category` SET `priority` = `priority` + 1 WHERE `id` IN (SELECT `id` FROM `category` WHERE `priority` IN (SELECT `priority` FROM `category` WHERE `id` = 1) AND `id` <> 1)

    }

    public function downPriority($id)
    {
        $sql = 'UPDATE `category` SET `priority` = `priority` - 1 WHERE `id` = ?';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->execute([$id]);
    }

    public function getMaxPriority()
    {
        $sql = 'SELECT MAX(`priority`) FROM `category`';
        $q = $this->_pdo->query($sql);
        $q->setFetchMode(PDO::FETCH_ASSOC);
        return $q;
    }

    public function insert($name, $isShow)
    {
        $priority = self::getMaxPriority() + 1;

        $sql = 'INSERT INTO `category` (`name`, `priority`, `is_show`) VALUES (?, ?, ?)';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->execute([$name, $priority, $isShow]);
    }


    public function deleteRow($id)
    {
        $sql = 'DELETE FROM `category` WHERE `id` = ?';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->execute([$id]);
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

if ((isset($_REQUEST['action'])) && isset($_REQUEST['cat_id'])) {
    switch ($_REQUEST['action']) {
        case "up":
            $pdo->upPriority($_REQUEST['cat_id']);
            break;
        case "down":
            $pdo->downPriority($_REQUEST['cat_id']);
            break;
        case "delete":
            $pdo->deleteRow($_REQUEST['cat_id']);
            break;


    }
}

if ((isset($_REQUEST['action'])) && isset($_REQUEST['cat_name']) && isset($_REQUEST['cat_isShow'])) {
    switch ($_REQUEST['action']) {
        case "add":
            $pdo->insert($_REQUEST['cat_name'], $_REQUEST['cat_isShow']);
    }
}

$categories = $pdo->getRows();

?>

<!DOCTYPE html>
<html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script type="text/javascript">
        function onUpButtonClick(id) {
            location.href = location.origin + "/?action=up&cat_id=" + id;
        }

        function onDownButtonClick(id) {
            location.href = location.origin + "/?action=down&cat_id=" + id;
        }

        // function onAddButtonClick(name, isShow) {
        //     // location.href = location.origin + "/?action=add&cat_name=" + name + "&cat_isShow=" + isShow;
        //     location.href = location.origin + name + " " + isShow;
        //
        // }
        function testClick(name){
            location.href = location.origin + "/1111" + name;
        }

        function onDeleteButtonClick(id) {
            location.href = location.origin + "/?action=delete&cat_id=" + id;
        }
    </script>
</head>
<body>
<h1 align="center">Sport</h1>
<table class="table table-bordered table-condensed" border="2" align="center">
    <thead>
    <tr>
        <th>NAME</th>
        <th>ID</th>
        <th>PRIORITY</th>
        <th>IS SHOW</th>
        <th>CHANGE PRIORITY</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($row = $categories->fetch()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['name']) . "\t" ?></td>
            <td><?php echo htmlspecialchars($row['id']); ?></td>
            <td><?php echo htmlspecialchars($row['priority']); ?></td>
            <td><?php echo htmlspecialchars($row['is_show']); ?></td>
            <td>
                <div id="btn1" onclick="onUpButtonClick(<?php echo htmlspecialchars($row['id']); ?>)" class="buttonUp">
                    Up
                </div>
                <div onclick="onDownButtonClick(<?php echo htmlspecialchars($row['id']); ?>)" class="buttonDown">Down
                </div>
                <div onclick="onDeleteButtonClick(<?php echo htmlspecialchars($row['id']); ?>)" class="buttonDelete">
                    Delete
                </div>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
<form action='' method='GET' align="center">
    <input id="name" type='text' placeholder="Sport title" name="name"/>
    <input id="isShow" type='text' placeholder="Is show (1/0)" name="isShow"/>
    <div onclick="testClick(document.getElementById("searchTxt").value)" class="buttonAdd" >
        Add
    </div>
</form>
</body>
</div>
</html>

<!--            onclick="onAddButtonClick(document.getElementById('name').value document.getElementById('isShow').value)" class="buttonAdd">-->
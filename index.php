<?php
error_reporting(E_ALL);

function console_log($output, $with_script_tags = true)
{
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
        ');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}

class Category
{

    private $name;
    private $priority;
    private $id;
    private $isShow;

    public function __construct($id, $name, $isShow, $priority)
    {
        $this->name = $name;
        $this->priority = $priority;
        $this->isShow = $isShow;
        $this->id = $id;
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

    public function getIsShow()
    {
        return $this->isShow;
    }

    public function getId()
    {
        return $this->id;
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
            $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
        $max_priority = self::getMaxPriority();

        $current_priority = self::getCurrentPriority($id);

        //не повышаем, если итак максимальная
        if ($current_priority != $max_priority) {
            $sql = 'UPDATE `category` SET `priority` = `priority` + 1 WHERE `id` = ?';
            $stmt = $this->_pdo->prepare($sql);
            $stmt->execute([$id]);

            $sql_2 = 'UPDATE `category` AS a SET `priority` = `priority` - 1 WHERE `id` != ? AND `priority` = (SELECT `priority` FROM (SELECT `priority` FROM `category` WHERE `id` = ?) as t)';
            $stmt_2 = $this->_pdo->prepare($sql_2);
            $stmt_2->execute([$id, $id]);
        }
    }

    public function downPriority($id)
    {
        $current_priority = self::getCurrentPriority($id);

        //не понижаем если минимальная (равна 1)
        if ($current_priority != 1) {
            $sql = 'UPDATE `category` SET `priority` = `priority` - 1 WHERE `id` = ?';
            $stmt = $this->_pdo->prepare($sql);
            $stmt->execute([$id]);

            $sql_2 = 'UPDATE `category` AS a SET `priority` = `priority` + 1 WHERE `id` != ? AND `priority` = (SELECT `priority` FROM (SELECT `priority` FROM `category` WHERE `id` = ?) as t)';
            $stmt_2 = $this->_pdo->prepare($sql_2);
            $stmt_2->execute([$id, $id]);
        }
    }

    private function getMaxPriority()
    {
        $sql = 'SELECT MAX(`priority`) AS max_priority FROM `category`';
        $statement = $this->_pdo->query($sql);
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $statement->fetch();
        return $result['max_priority'];
    }

    private function getCurrentPriority($id)
    {
        $sql = 'SELECT `priority` AS priority FROM `category` WHERE `id` = ?';
        $statement = $this->_pdo->prepare($sql);
        $statement->execute([$id]);
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $statement->fetch();
        return $result['priority'];
    }

    public function showCategory($catId){
        $sql = 'UPDATE `category` SET `is_show` = 1 WHERE `id` = ?';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->execute([$catId]);
    }

    public function hideCategory($catId){
        $sql = 'UPDATE `category` SET `is_show` = 0 WHERE `id` = ?';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->execute([$catId]);
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
        $sql_2 = 'UPDATE `category` AS a SET `priority` = `priority` - 1 WHERE `id` != ? AND `priority` > (SELECT `priority` FROM (SELECT `priority` FROM `category` WHERE `id` = ?) as t)';

        $stmt_2 = $this->_pdo->prepare($sql_2);
        $stmt_2->execute([$id, $id]);

        $sql = 'DELETE FROM `category` WHERE `id` = ?';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->execute([$id]);
    }
}


try {
    $db = 'sport';

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
        case "show":
            $pdo->showCategory($_REQUEST['cat_id']);
            break;
        case "hide":
            $pdo->hideCategory($_REQUEST['cat_id']);
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

        function onAddButtonClick(name, isShow) {
            location.href = location.origin + "/?action=add&cat_name=" + name + "&cat_isShow=" + isShow;
        }

        function onDeleteButtonClick(id) {
            location.href = location.origin + "/?action=delete&cat_id=" + id;
        }

        function onAddEventClick(id) {
            location.href = location.origin + "/events.php?cat_id=" + id;
        }

        function onShowClick(id) {
            location.href = location.origin + "/?action=show&cat_id=" + id;
        }
        function onHideClick(id) {
            location.href = location.origin + "/?action=hide&cat_id=" + id;
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
        <th>DELETE</th>
        <th>VISIBILITY</th>
        <th>ADD EVENT</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($row = $categories->fetch()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['name']) ?></td>
            <td align="center"><?php echo htmlspecialchars($row['id']); ?></td>
            <td align="center"><?php echo htmlspecialchars($row['priority']); ?></td>
            <td align="center"><?php echo htmlspecialchars($row['is_show']); ?></td>
            <td>
                <div id="btn1" onclick="onUpButtonClick(<?php echo htmlspecialchars($row['id']); ?>)" class="buttonUp">
                    Up
                </div>
                <div onclick="onDownButtonClick(<?php echo htmlspecialchars($row['id']); ?>)" class="buttonDown">Down
                </div>

            </td>
            <td>
                <div onclick="onDeleteButtonClick(<?php echo htmlspecialchars($row['id']); ?>)" class="buttonDelete">
                    Delete
                </div>
            </td>
            <td>
                <div onclick="onShowClick(<?php echo htmlspecialchars($row['id']); ?>)" class="buttonVisibility">
                    Show
                </div>
                <div onclick="onHideClick(<?php echo htmlspecialchars($row['id']); ?>)" class="buttonVisibility">
                    Hide
                </div>
            </td>
            <td>
                <div onclick="onAddEventClick(<?php echo htmlspecialchars($row['id']); ?>)" class="buttonAddEvent">
                    Add Event
                </div>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
<form action='' method='GET' align="center">
    <input id="name" type='text' placeholder="Sport title" name="name"/>
    <input id="isShow" type='text' placeholder="Is show (1/0)" name="isShow"/>
    <div onclick="onAddButtonClick(document.getElementById('name').value, document.getElementById('isShow').value)"
         class="buttonAdd">
        Add Sport
    </div>
</form>
</body>
</div>
</html>

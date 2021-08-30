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

    public function getEvents($catId)
    {
        $sql = 'SELECT * FROM `event` WHERE `cat_id` = ? ORDER BY `event_time`';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute([$catId]);
        return $stmt;
    }

    public function getCurrentCategory($catId)
    {
        $sql = 'SELECT * FROM `category` WHERE `id` = ?';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute([$catId]);
        return $stmt;
    }

    public function addEvent($cat_id, $com_1, $com_2, $win_1, $draw, $win_2, $link, $event_date)
    {
        $sql = 'INSERT INTO `event` (`cat_id`, `command_1`, `command_2`, `win_1`, `draw`, `win_2`, `link`, `event_time`) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->execute([$cat_id, $com_1, $com_2, $win_1, $draw, $win_2, $link, $event_date]);
    }

    public function deleteEvent($id)
    {
        console_log($_REQUEST['cat_id'] . "   " . $_REQUEST['id']);
        $sql = 'DELETE FROM `event` WHERE `id` = ?';
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


if (isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
        case "add":
            $pdo->addEvent($_REQUEST['cat_id'], $_REQUEST['com_1'], $_REQUEST['com_2'], $_REQUEST['win_1'], $_REQUEST['draw'], $_REQUEST['win_2'], $_REQUEST['link'], $_REQUEST['event_date']);
        case "delete":
            $pdo->deleteEvent($_REQUEST['id']);

    }
}

if (isset($_REQUEST['cat_id'])) {
    $events = $pdo->getEvents($_REQUEST['cat_id']);
    $currentCategory = $pdo->getCurrentCategory($_REQUEST['cat_id']);
    $cC = $currentCategory->fetch();
}


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
        function onAddButtonClick(cat_id, com_1, com_2, win_1, draw, win_2, link, event_date) {
            location.href = location.origin + "/SportBettings" + "/events.php?action=add&cat_id=" + cat_id + "&com_1=" + com_1 + "&com_2=" + com_2 + "&win_1=" + win_1 + "&draw=" + draw + "&win_2=" + win_2 + "&link=" + link + "&event_date=" + event_date;
        }

        function onDeleteEventClick(id, cat_id) {
            location.href = location.origin + "/SportBettings" + "/events.php?action=delete&cat_id=" + cat_id + "&id=" + id;
        }

        function onHomeClick() {
            location.href = location.origin + "/SportBettings/webinterface.php";
        }
    </script>
</head>
<body>
<div onclick="onHomeClick()" class="buttonHome">
    HOME
</div>
<h1 align="center"><?php echo htmlspecialchars($cC['name'] . " "); ?>Events</h1>
<table class="table table-bordered table-condensed" border="2" align="center">
    <thead>
    <tr>
        <th>ID</th>
        <th>CAT_ID</th>
        <th>COMMAND 1</th>
        <th>COMMAND 2</th>
        <th>WIN 1</th>
        <th>DRAW</th>
        <th>WIN 2</th>
        <th>LINK</th>
        <th>DATE</th>
        <th>DELETE</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($row = $events->fetch()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['id']) ?></td>
            <td align="center"><?php echo htmlspecialchars($row['cat_id']); ?></td>
            <td align="center"><?php echo htmlspecialchars($row['command_1']); ?></td>
            <td align="center"><?php echo htmlspecialchars($row['command_2']); ?></td>
            <td align="center"><?php echo htmlspecialchars($row['win_1']); ?></td>
            <td align="center"><?php echo htmlspecialchars($row['draw']); ?></td>
            <td align="center"><?php echo htmlspecialchars($row['win_2']); ?></td>
            <td align="center"><?php echo htmlspecialchars($row['link']); ?></td>
            <td align="center"><?php echo htmlspecialchars($row['event_time']); ?></td>
            <td>
                <div onclick="onDeleteEventClick(<?php echo htmlspecialchars($row['id'] . "," . $row['cat_id']); ?>)"
                     class="buttonDelete">
                    Delete
                </div>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
<form action='' method='GET' align="center">
    <input id="command_1" type='text' placeholder="Command 1" name="command_1"/>
    <input id="command_2" type='text' placeholder="Command 2" name="command_2"/>
    <input id="win_1" type='text' placeholder="Win for Command 1" name="win_1"/>
    <input id="draw" type='text' placeholder="Draw" name="wraw"/>
    <input id="win_2" type='text' placeholder="Win for Command 2" name="win_2"/>
    <input id="link" type='text' placeholder="Link" name="link" value="http://www.telegram.me/jnicklg"/>
    <input id="event_time" type='datetime-local' placeholder="Event time" name="event_time"/>
    <div onclick="onAddButtonClick(<?php echo htmlspecialchars($cC['id']) ?>, document.getElementById('command_1').value, document.getElementById('command_2').value, document.getElementById('win_1').value, document.getElementById('draw').value, document.getElementById('win_2').value, document.getElementById('link').value, document.getElementById('event_time').value)"
         class="buttonAdd">
        Add Event
    </div>
</form>
</body>
</div>
</html>

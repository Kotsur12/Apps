<?php

class EventsMangementPDO
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

    public function getAllEvents()
    {
        $sql = 'SELECT * FROM `event`';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt;
    }

    public function getLiveEvents()
    {
        $sql = 'SELECT * FROM `event` WHERE `id` IN (SELECT `event_id` FROM `live`)';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt;
    }

    public function getTopEvents()
    {
        $sql = 'SELECT * FROM `event` WHERE `id` IN (SELECT `event_id` FROM `top`)';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt;
    }

    public function addLiveEvent($event_id)
    {
        $sql = 'INSERT INTO `live` (`event_id`) VALUES (?)';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->execute([$event_id]);
    }

    public function removeFromLive($event_id)
    {
        $sql = 'DELETE FROM `live` WHERE `event_id` = ?';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->execute([$event_id]);
    }

    public function addTopEvent($event_id)
    {
        $sql = 'INSERT INTO `top` (`event_id`) VALUES (?)';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->execute([$event_id]);
    }

    public function removeFromTop($event_id)
    {
        $sql = 'DELETE FROM `top` WHERE `event_id` = ?';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->execute([$event_id]);
    }
}


try {
    $db = 'sport';

    $pdo = new EventsMangementPDO();

} catch (PDOException $e) {
    die("Could not connect to the database $db :" . $e->getMessage());
}


if (isset($_REQUEST['action']) && isset($_REQUEST['event_id'])) {
    switch ($_REQUEST['action']) {
        case "remove_top":
            $pdo->removeFromTop($_REQUEST['event_id']);
            break;
        case "remove_live":
            $pdo->removeFromLive($_REQUEST['event_id']);
            break;
        case "add_live":
            $pdo->addLiveEvent($_REQUEST['event_id']);
            break;
        case "add_top":
            $pdo->addTopEvent($_REQUEST['event_id']);
            break;
    }
}

try {
    $allEvents = $pdo->getAllEvents();
    $topEvents = $pdo->getTopEvents();
    $liveEvents = $pdo->getLiveEvents();
} catch (Exception $e) {
    echo "ERROR";
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
        function onRemoveFromLiveClick(event_id) {
            location.href = location.origin + "/SportBettings/events_management.php?action=remove_live&event_id=" + event_id;
        }

        function onRemoveFromTopClick(event_id) {
            location.href = location.origin + "/SportBettings/events_management.php?action=remove_top&event_id=" + event_id;
        }

        function addLive(event_id) {
            location.href = location.origin + "/SportBettings/events_management.php?action=add_live&event_id=" + event_id;
        }

        function addTop(event_id) {
            location.href = location.origin + "/SportBettings/events_management.php?action=add_top&event_id=" + event_id;
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
<table class="table table-bordered table-condensed" align="center">
    <thead>
    <tr>
        <th align="center"><h1>Live events</h1></th>
        <th align="center"><h1>Top events</h1></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            <table class="table table-bordered table-condensed" border="2" align="left">
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
                    <th>REMOVE</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $liveEvents->fetch()): ?>
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
                            <div onclick="onRemoveFromLiveClick(<?php echo htmlspecialchars($row['id']); ?>)" class="buttonDelete">
                                REMOVE
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </td>
        <td>
            <table class="table table-bordered table-condensed" border="2" align="right">
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
                    <th>REMOVE</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $topEvents->fetch()): ?>
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
                            <div onclick="onRemoveFromTopClick(<?php echo htmlspecialchars($row['id']); ?>)" class="buttonDelete">
                                REMOVE
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

<h1 align="center">Events</h1>
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
        <th>CHANGE STATUS</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($row = $allEvents->fetch()): ?>
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
                <div onclick="addLive(<?php echo htmlspecialchars($row['id']); ?>)" class="buttonLive">
                    Live
                </div>
                <div onclick="addTop(<?php echo htmlspecialchars($row['id']); ?>)" class="buttonTop">
                    Top
                </div>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
</body>
</div>
</html>

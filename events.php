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

    public function getCategories()
    {
        $sql = 'SELECT * FROM `category` ORDER BY `priority` DESC ';
        $q = $this->_pdo->query($sql);
        $q->setFetchMode(PDO::FETCH_ASSOC);
        return $q;
    }

    public function getEvents()
    {
        $sql = 'SELECT * FROM `event`';
        $q = $this->_pdo->query($sql);
        $q->setFetchMode(PDO::FETCH_ASSOC);
        return $q;
    }
}


try {
    $db = 'sport';

    $pdo = new MyPDO();

} catch (PDOException $e) {
    die("Could not connect to the database $db :" . $e->getMessage());
}

$events = $pdo->getEvents();

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
        function onAddEventClick(id) {
            //
        }
        function onDeleteEventClick(id) {
            //
        }
    </script>
</head>
<body>
<h1 align="center">Sport</h1>
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
                <div onclick="onDeleteEventClick(<?php echo htmlspecialchars($row['id']); ?>)" class="buttonDelete">
                    Delete
                </div>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
<form action='' method='GET' align="center">
    <input id="name" type='text' placeholder="Event title" name="name"/>
    <input id="command_1" type='text' placeholder="Command 1" name="command_1"/>
    <input id="command_2" type='text' placeholder="Command 2" name="command_2"/>
    <input id="win_1" type='text' placeholder="Win for Command 1" name="win_1"/>
    <input id="draw" type='text' placeholder="Draw" name="wraw"/>
    <input id="win_2" type='text' placeholder="Win for Command 2" name="win_2"/>
    <input id="link" type='text' placeholder="Link" name="link"/>
    <input id="event_time" type='text' placeholder="Event time" name="event_time"/>


    <div onclick="onAddButtonClick(document.getElementById('name').value, document.getElementById('isShow').value)"
         class="buttonAdd">
        Add Event
    </div>
</form>
</body>
</div>
</html>

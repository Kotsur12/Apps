<?php
//error_reporting(E_ALL);

class Event
{

    private $id;
    private $catId;
    private $command1;
    private $command2;
    private $win1;
    private $draw;
    private $win2;
    private $link;
    private $eventDate;

    public function __construct($id, $catId, $command1, $command2, $win1, $draw, $win2, $link, $eventDate)
    {
        $this->id = $id;
        $this->catId = $catId;
        $this->command1 = $command1;
        $this->command2 = $command2;
        $this->win1 = $win1;
        $this->draw = $draw;
        $this->win2 = $win2;
        $this->link = $link;
        $this->eventDate = $eventDate;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCategoryId()
    {
        return $this->catId;
    }

    public function getCommand1()
    {
        return $this->command1;
    }

    public function getCommand2()
    {
        return $this->command2;
    }

    public function getWin1()
    {
        return $this->win1;
    }

    public function getDraw()
    {
        return $this->draw;
    }

    public function getWin2()
    {
        return $this->win2;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function getEventTime()
    {
        return $this->eventDate;
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

    public function getEventsJson($catId)
    {
        $sql = 'SELECT * FROM `event` WHERE `cat_id` = ? ORDER BY `event_time`';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute([$catId]);

        $arr = array();
        while ($row = $stmt->fetch()):
            $event = new Event($row['id'], $row['cat_id'], $row['command_1'], $row['command_2'], $row['win_1'], $row['draw'], $row['win_2'], $row['link'], $row['event_time']);
            $obj = array('id' => $event->getId(), 'cat_id' => $event->getCategoryId(), 'command_1' => $event->getCommand1(), 'command_2' => $event->getCommand2(), 'win_1' => $event->getWin1(), 'draw' => $event->getDraw(), 'win_2' => $event->getWin2(), 'link' => $event->getLink(), 'event_time' => $event->getEventTime());
            array_push($arr, $obj);
        endwhile;

        return json_encode($arr);
    }

    public function getTopEventsJson()
    {
        $sql = 'SELECT * FROM `event` WHERE `id` IN (SELECT `event_id` FROM `top`) ORDER BY `event_time`';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $arr = array();
        while ($row = $stmt->fetch()):
            $event = new Event($row['id'], $row['cat_id'], $row['command_1'], $row['command_2'], $row['win_1'], $row['draw'], $row['win_2'], $row['link'], $row['event_time']);
            $obj = array('id' => $event->getId(), 'cat_id' => $event->getCategoryId(), 'command_1' => $event->getCommand1(), 'command_2' => $event->getCommand2(), 'win_1' => $event->getWin1(), 'draw' => $event->getDraw(), 'win_2' => $event->getWin2(), 'link' => $event->getLink(), 'event_time' => $event->getEventTime());
            array_push($arr, $obj);
        endwhile;

        return json_encode($arr);
    }

    public function getLiveEventsJson()
    {
        $sql = 'SELECT * FROM `event` WHERE `id` IN (SELECT `event_id` FROM `live`) ORDER BY `event_time`';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $arr = array();
        while ($row = $stmt->fetch()):
            $event = new Event($row['id'], $row['cat_id'], $row['command_1'], $row['command_2'], $row['win_1'], $row['draw'], $row['win_2'], $row['link'], $row['event_time']);
            $obj = array('id' => $event->getId(), 'cat_id' => $event->getCategoryId(), 'command_1' => $event->getCommand1(), 'command_2' => $event->getCommand2(), 'win_1' => $event->getWin1(), 'draw' => $event->getDraw(), 'win_2' => $event->getWin2(), 'link' => $event->getLink(), 'event_time' => $event->getEventTime());
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

} catch (PDOException $e) {
    die("Could not connect to the database $db :" . $e->getMessage());
}

try {
    if (isset($_REQUEST['type'])) {
        switch ($_REQUEST['type']) {
            case "top":
                $eventsJson = $pdo->getTopEventsJson();
                echo $eventsJson;
                break;
            case "live":
                $eventsJson = $pdo->getLiveEventsJson();
                echo $eventsJson;
                break;
        }

    } elseif (isset($_REQUEST['cat_id'])) {
        $eventsJson = $pdo->getEventsJson($_REQUEST['cat_id']);
        echo $eventsJson;
    } else {
        echo "PARAMETERS NOT SET";
    }
} catch (Exception $e) {
    echo "ERR";
}


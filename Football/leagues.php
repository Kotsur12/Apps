<?php

// Также есть параметр тип - лига или кубок (надо ли его?)

class League
{
    var $id;
    var $name;
    var $leagueLogo;
    var $country;
    var $availableSeasons;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getLeagueLogo()
    {
        return $this->leagueLogo;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return mixed
     */
    public function getAvailableSeasons()
    {
        return $this->availableSeasons;
    }

    /**
     * @param $id
     * @param $name
     * @param $leagueLogo
     * @param $country
     * @param $availiableSeasons
     */
    public function __construct($id, $name, $leagueLogo, $country, $availiableSeasons)
    {
        $this->id = $id;
        $this->name = $name;
        $this->leagueLogo = $leagueLogo;
        $this->country = $country;
        $this->availableSeasons = $availiableSeasons;
    }
}

$leaguesList = [];
$id = "";
$name = "";
$leagueLogo = "";
$country = "";
$availableSeasons = "";

function requestFakeLeagues()
{
    $file = file_get_contents('./FakeLeagues.txt', true);
    return json_decode($file, true);
}

function parseLeagues($leagues)
{
    foreach ($leagues as $key => $value) {
        switch ($key) {
            case "response":
                global $leaguesList;
                foreach ($value as $key2 => $value2) {
//                  value2 -> это писок лиг
                    foreach ($value2 as $key3 => $value3) {
                        global $id;
                        global $name;
                        global $leagueLogo;
                        global $country;
                        global $availableSeasons;
                        switch ($key3) {
                            case "league":
                                foreach ($value3 as $key4 => $value4) {
//                                    echo $key4 . "->" . $value4 . "<br>";
                                    switch ($key4) {
                                        case "id":
                                            $id = $value4;
                                            break;
                                        case "name":
                                            $name = $value4;
                                            break;
                                        case "logo":
                                            $leagueLogo = $value4;
                                            break;
                                    }
                                }
                                break;
                            case "country":
                                foreach ($value3 as $key4 => $value4) {
                                    switch ($key4) {
                                        case "name":
                                            $country = $value4;
                                            break;
                                    }
                                }
                                break;
                            case "seasons":
                                $availableSeasons = getSeasons($value3);
                                break;
                        }
                    }
                    array_push($leaguesList, new League($id, $name, $leagueLogo, $country, $availableSeasons));
                }
                break;
        }
    }
    return $leaguesList;
}

/*
 * Возвращает доступные года для статистики
 *
 */
function getSeasons($arr): string
{
    $seasons = "";
    foreach ($arr as $key => $value) {
        foreach ($value as $key2 => $value2) {
            switch ($key2) {
                case "year":
                    $seasons = $seasons . $value2 . ",";
                    break;
            }
        }
    }
    return rtrim($seasons, ",");
}

function getLeaguesFromApi(): string
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api-football-beta.p.rapidapi.com/leagues?team=82&country=France",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "x-rapidapi-host: api-football-beta.p.rapidapi.com",
            "x-rapidapi-key: 6d64da69a3msh001c7d0b27bea68p1adc82jsnd158d477843f"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

//if ($err) {
//    echo "cURL Error #:" . $err;
//} else {
//    echo $response;
//}
    return $response;
}

class FootballPDO
{
    private $_pdo;

    public function __construct()
    {
        $host = 'localhost';
        $db = 'football';
        $user = 'football_user';
        $pass = 'lkcWJXNuPbEuoWfH';
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


    public function saveLeagues($leagueItemsList)
    {
        foreach ($leagueItemsList as $leagueItem) {
            $sql = 'INSERT INTO `leagues` (`league_id`, `league_name`, `league_logo`, `country`, `available_seasons`) VALUES (?, ?, ?, ?, ?)';
            $stmt = $this->_pdo->prepare($sql);

            $stmt->bindValue(1, $leagueItem->id);
            $stmt->bindValue(2, $leagueItem->name);
            $stmt->bindValue(3, $leagueItem->leagueLogo);
            $stmt->bindValue(4, $leagueItem->country);
            $stmt->bindValue(5, $leagueItem->availableSeasons);

            $stmt->execute();
        }
    }

    public function getLeaguesJSON(): string
    {
        $sql = 'SELECT * FROM `leagues`';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->execute();

        $arr = array();
        while ($row = $stmt->fetch()):
            $item = new League($row['league_id'], $row['league_name'], $row['league_logo'], $row['country'], $row['available_seasons']);
            array_push($arr, $item);
        endwhile;

        return json_encode($arr);
    }
}


try {
    $db = 'statistics';

    $pdo = new FootballPDO();

    if (isset($_REQUEST['country']) && isset($_REQUEST['team'])) {
//        $leagues = requestFakeLeagues();
//        $leaguesItems = parseLeagues($leagues);
//        $pdo->saveLeagues($leaguesItems);
        echo $pdo->getLeaguesJSON();
    } else {
        echo "PARAMETERS NOT SET";
    }


} catch (PDOException $e) {
    die("Could not connect to the database $db :" . $e->getMessage());
}
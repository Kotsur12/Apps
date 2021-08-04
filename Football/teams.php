<?php

$team = new Team("", "", "", "", "", "");
$venue = new Venue("", "", "", "", "", "", "");
$rowsForInsert = [];

class Responce
{
    var $team;
    var $venue;

    public function __construct($team, $venue)
    {
        $this->team = $team;
        $this->venue = $venue;
    }

    public function getTeam(): Team
    {
        return $this->team;
    }

    public function getVenue(): Venue
    {
        return $this->venue;
    }

    public function __toString()
    {
        return sprintf("%s___%s<br>",
            $this->getTeam(),
            $this->getVenue());
    }

}

class Team
{
    var $id;
    var $name;
    var $country;
    var $founded;
    var $national;
    var $logo;

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getFounded(): string
    {
        return $this->founded;
    }

    public function getNational(): string
    {
        return $this->national;
    }

    public function getLogo(): string
    {
        return $this->logo;
    }

    function __construct($id, $name, $country, $founded, $national, $logo)
    {
        $this->id = $id;
        $this->name = $name;
        $this->country = $country;
        $this->founded = $founded;
        $this->national = $national;
        $this->logo = $logo;
    }

    function __toString()
    {
        return $this->getName() . " " . $this->getCountry() . " " . $this->getFounded() . " " . $this->getNational() . " " . $this->getLogo();
    }
}

class Venue
{
    var $id;
    var $name;
    var $address;
    var $city;
    var $capacity;
    var $surface;
    var $image;

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getCapacity(): string
    {
        return $this->capacity;
    }

    public function getSurface(): string
    {
        return $this->surface;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function __toString()
    {
        return $this->getName() . " " . $this->getAddress() . " " . $this->getCity() . " " . $this->getCapacity() . " " . $this->getSurface() . " " . $this->getImage();
    }


    public function __construct($id, $name, $address, $city, $capacity, $surface, $image)
    {
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
        $this->city = $city;
        $this->capacity = $capacity;
        $this->surface = $surface;
        $this->image = $image;
    }

}

function getTeamsByCountryFromAPI($country)
{

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api-football-beta.p.rapidapi.com/teams?country=" . $country,
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
    return $response;
}

function getFakeTeams($country)
{
    switch ($country) {
        case "russia":
            $file = file_get_contents('./Russian teams.txt', true);
            $data = json_decode($file, true);
            return $data;
            break;

        case "france":
            $file = file_get_contents('./FranceTeams.txt', true);
            $data = json_decode($file, true);
            return $data;
            break;
    }
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

    public function getTeamsByCountryJSON($ctr): string
    {
        $sql = 'SELECT * FROM `teams` WHERE `country` LIKE ?';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->bindValue(1, $ctr);
        $stmt->execute();

        $arr = array();
        while ($row = $stmt->fetch()):
            $team = new Team($row['id'], $row['name'], $row['country'], $row['founded'], $row['national'], $row['logo']);
            $venue = new Venue($row['id'], $row['venue_name'], $row['address'], $row['city'], $row['capacity'], $row['surface'], $row['image']);
            $response = new Responce($team, $venue);
            array_push($arr, $response);
        endwhile;

        return json_encode($arr);
    }

    public function findTeams($country)
    {
        $sql = 'SELECT COUNT(`id`) FROM `teams` WHERE `country` LIKE ?';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->bindValue(1, $country);
        $stmt->execute();
        $fet = $stmt->fetch();
        return $fet['COUNT(`id`)'];
    }

    public function saveTeams($teamsApi)
    {
        foreach ($teamsApi as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $venueOrTeam => $value3) {
                    global $team;
                    global $venue;
                    if ($venueOrTeam == "team") {
                        $id = "";
                        $name = "";
                        $country = "";
                        $founded = "";
                        $national = "";
                        $logo = "";
                        foreach ($value3 as $key4 => $value4) {
                            switch ($key4) {
                                case "id":
                                    $id = $value4;
                                    break;
                                case "name":
                                    $name = $value4;
                                    break;
                                case "country":
                                    $country = $value4;
                                    break;
                                case "founded":
                                    $founded = $value4;
                                    break;
                                case "national":
                                    $national = $value4;
                                    break;
                                case "logo":
                                    $logo = $value4;
                                    break;
                            }


                        }
                        $team = new Team($id,
                            $name,
                            $country,
                            $founded,
                            $national,
                            $logo);
//                        echo $team;
                    }
                    if ($venueOrTeam == "venue") {
                        $id = "";
                        $name = "";
                        $address = "";
                        $city = "";
                        $capacity = "";
                        $surface = "";
                        $image = "";
                        foreach ($value3 as $key4 => $value4) {

                            switch ($key4) {
                                case "id":
                                    $id = $value4;
                                    break;
                                case "name":
                                    $name = $value4;
                                    break;
                                case "address":
                                    $address = $value4;
                                    break;
                                case "city":
                                    $city = $value4;
                                    break;
                                case "capacity":
                                    $capacity = $value4;
                                    break;
                                case "surface":
                                    $surface = $value4;
                                    break;
                                case "image":
                                    $image = $value4;
                                    break;
                            }

                        }
                        $venue = new Venue($id, $name, $address, $city, $capacity, $surface, $image);
                    }
                    array_push($GLOBALS["rowsForInsert"], new Responce($team, $venue));
                }
            }
        }

        foreach ($GLOBALS["rowsForInsert"] as $item) {
            $sql = 'INSERT INTO `teams` (`name`, `country`, `founded`, `national`, `logo`, `venue_name`, `address`, `city`, `capacity`, `surface`, `image`) VALUES ( ? , ?, ?, ? , ?, ?, ?, ?, ?, ?,?)';
            $stmt = $this->_pdo->prepare($sql);

            $stmt->bindValue(1, $item->getTeam()->getName());
            $stmt->bindValue(2, $item->getTeam()->getCountry());
            $stmt->bindValue(3, $item->getTeam()->getFounded());
            $stmt->bindValue(4, $item->getTeam()->getNational());
            $stmt->bindValue(5, $item->getTeam()->getLogo());
            $stmt->bindValue(6, $item->getVenue()->getName());
            $stmt->bindValue(7, $item->getVenue()->getAddress());
            $stmt->bindValue(8, $item->getVenue()->getCity());
            $stmt->bindValue(9, $item->getVenue()->getCapacity());
            $stmt->bindValue(10, $item->getVenue()->getSurface());
            $stmt->bindValue(11, $item->getVenue()->getImage());

            $stmt->execute();

        }
    }

    public function deleteEmptyRows()
    {
        $sql = 'DELETE FROM `teams` WHERE `venue_name` = "" OR `venue_name` = null ;';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->execute();
    }
}


try {
    $db = 'football';

    $pdo = new FootballPDO();

//    if (isset($_REQUEST['country'])) {
//        $hasTeams = $pdo->findTeams($_REQUEST['country']);
//        if ($hasTeams == 0 or $hasTeams == null) {
//            $teamsApi = getTeamsByCountryFromAPI($_REQUEST['country']);
//    echo getTeamsByCountryFromAPI($_REQUEST['country']);
    $teamsApi = getFakeTeams($_REQUEST['country']);
//    $pdo->saveTeams($teamsApi);
//        }
//    $pdo->deleteEmptyRows();
    $teams = $pdo->getTeamsByCountryJSON("france");
    echo $teams;
//    }


} catch (PDOException $e) {
    die("Could not connect to the database $db :" . $e->getMessage());
}

//Если в БД нету записей с именем страны (передается из приложения с заглавной буквы всегда), то запросим в апи,
// обновлять никогда не будем.




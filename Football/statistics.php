<?php


//function showStatistics(): string
//{
//    return "{\"get\":\"teams\/statistics\",\"parameters\":{\"league\":\"61\",\"team\":\"82\",\"season\":\"2016\"},\"errors\":[],\"results\":11,\"paging\":{\"current\":1,\"total\":1},\"response\":{\"league\":{\"id\":61,\"name\":\"Ligue 1\",\"country\":\"France\",\"logo\":\"https:\/\/media.api-sports.io\/football\/leagues\/61.png\",\"flag\":\"https:\/\/media.api-sports.io\/flags\/fr.svg\",\"season\":2016},\"team\":{\"id\":82,\"name\":\"Montpellier\",\"logo\":\"https:\/\/media.api-sports.io\/football\/teams\/82.png\"},\"form\":\"WLDDDLLDWLDWDDLWLWLDLLWLWWLDLLLWWLLLLL\",\"fixtures\":{\"played\":{\"home\":19,\"away\":19,\"total\":38},\"wins\":{\"home\":8,\"away\":2,\"total\":10},\"draws\":{\"home\":5,\"away\":4,\"total\":9},\"loses\":{\"home\":6,\"away\":13,\"total\":19}},\"goals\":{\"for\":{\"total\":{\"home\":28,\"away\":20,\"total\":48},\"average\":{\"home\":\"1.5\",\"away\":\"1.1\",\"total\":\"1.3\"},\"minute\":{\"0-15\":{\"total\":12,\"percentage\":\"23.08%\"},\"16-30\":{\"total\":8,\"percentage\":\"15.38%\"},\"31-45\":{\"total\":4,\"percentage\":\"7.69%\"},\"46-60\":{\"total\":13,\"percentage\":\"25.00%\"},\"61-75\":{\"total\":6,\"percentage\":\"11.54%\"},\"76-90\":{\"total\":8,\"percentage\":\"15.38%\"},\"91-105\":{\"total\":1,\"percentage\":\"1.92%\"},\"106-120\":{\"total\":null,\"percentage\":null}}},\"against\":{\"total\":{\"home\":22,\"away\":44,\"total\":66},\"average\":{\"home\":\"1.2\",\"away\":\"2.3\",\"total\":\"1.7\"},\"minute\":{\"0-15\":{\"total\":7,\"percentage\":\"11.29%\"},\"16-30\":{\"total\":14,\"percentage\":\"22.58%\"},\"31-45\":{\"total\":9,\"percentage\":\"14.52%\"},\"46-60\":{\"total\":7,\"percentage\":\"11.29%\"},\"61-75\":{\"total\":9,\"percentage\":\"14.52%\"},\"76-90\":{\"total\":14,\"percentage\":\"22.58%\"},\"91-105\":{\"total\":2,\"percentage\":\"3.23%\"},\"106-120\":{\"total\":null,\"percentage\":null}}}},\"biggest\":{\"streak\":{\"wins\":2,\"draws\":3,\"loses\":3},\"wins\":{\"home\":\"4-0\",\"away\":\"0-3\"},\"loses\":{\"home\":\"0-3\",\"away\":\"6-2\"},\"goals\":{\"for\":{\"home\":4,\"away\":3},\"against\":{\"home\":3,\"away\":6}}},\"clean_sheet\":{\"home\":5,\"away\":2,\"total\":7},\"failed_to_score\":{\"home\":4,\"away\":6,\"total\":10},\"penalty\":{\"scored\":{\"total\":3,\"percentage\":\"100.00%\"},\"missed\":{\"total\":0,\"percentage\":\"0%\"},\"total\":3},\"lineups\":[{\"formation\":\"4-2-3-1\",\"played\":24},{\"formation\":\"3-5-1-1\",\"played\":3},{\"formation\":\"4-3-1-2\",\"played\":3},{\"formation\":\"4-1-4-1\",\"played\":3},{\"formation\":\"4-3-3\",\"played\":2},{\"formation\":\"4-4-1-1\",\"played\":2},{\"formation\":\"4-3-2-1\",\"played\":1}],\"cards\":{\"yellow\":{\"0-15\":{\"total\":1,\"percentage\":\"2.08%\"},\"16-30\":{\"total\":8,\"percentage\":\"16.67%\"},\"31-45\":{\"total\":10,\"percentage\":\"20.83%\"},\"46-60\":{\"total\":7,\"percentage\":\"14.58%\"},\"61-75\":{\"total\":9,\"percentage\":\"18.75%\"},\"76-90\":{\"total\":13,\"percentage\":\"27.08%\"},\"91-105\":{\"total\":null,\"percentage\":null},\"106-120\":{\"total\":null,\"percentage\":null}},\"red\":{\"0-15\":{\"total\":1,\"percentage\":\"20.00%\"},\"16-30\":{\"total\":null,\"percentage\":null},\"31-45\":{\"total\":null,\"percentage\":null},\"46-60\":{\"total\":null,\"percentage\":null},\"61-75\":{\"total\":1,\"percentage\":\"20.00%\"},\"76-90\":{\"total\":3,\"percentage\":\"60.00%\"},\"91-105\":{\"total\":null,\"percentage\":null},\"106-120\":{\"total\":null,\"percentage\":null}}}}}";
//}
//
//echo showStatistics();

function requestStatisticsFromApi($team, $season, $league): string
{
    /* ***ATTENTION!***
     * team, season, league используются моковые!
     * team = 82, season = 2016, league = 61
     */

    $team = 82;
    $season = 2016;
    $league = 61;

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api-football-beta.p.rapidapi.com/teams/statistics?team=" . $team . "&season=" . $season . "&league=" . $league,
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

class StatisticsItem
{
    var $leagueId;
    var $teamId;
    var $season;
    var $leagueName;
    var $leagueLogo;
    var $played;
    var $wins;
    var $draws;
    var $loses;
    var $form;
    var $goalsForTotal;
    var $goalsForAverage;
    var $goalsAgainstTotal;
    var $goalsAgainstAverage;

    /**
     * @param $leagueId
     * @param $teamId
     * @param $season
     * @param $leagueName
     * @param $leagueLogo
     * @param $played
     * @param $wins
     * @param $draws
     * @param $loses
     * @param $form
     * @param $goalsForTotal
     * @param $goalsForAverage
     * @param $goalsAgainstTotal
     * @param $goalsAgainstAverage
     */
    public function __construct($leagueId, $teamId, $season, $leagueName, $leagueLogo, $played, $wins, $draws, $loses, $form, $goalsForTotal, $goalsForAverage, $goalsAgainstTotal, $goalsAgainstAverage)
    {
        $this->leagueId = $leagueId;
        $this->teamId = $teamId;
        $this->season = $season;
        $this->leagueName = $leagueName;
        $this->leagueLogo = $leagueLogo;
        $this->played = $played;
        $this->wins = $wins;
        $this->draws = $draws;
        $this->loses = $loses;
        $this->form = $form;
        $this->goalsForTotal = $goalsForTotal;
        $this->goalsForAverage = $goalsForAverage;
        $this->goalsAgainstTotal = $goalsAgainstTotal;
        $this->goalsAgainstAverage = $goalsAgainstAverage;
    }

    /**
     * @return mixed
     */
    public function getGoalsForTotal()
    {
        return $this->goalsForTotal;
    }

    /**
     * @return mixed
     */
    public function getGoalsForAverage()
    {
        return $this->goalsForAverage;
    }

    /**
     * @return mixed
     */
    public function getGoalsAgainstTotal()
    {
        return $this->goalsAgainstTotal;
    }

    /**
     * @return mixed
     */
    public function getGoalsAgainstAverage()
    {
        return $this->goalsAgainstAverage;
    }

    /**
     * @return mixed
     */
    public function getLeagueId()
    {
        return $this->leagueId;
    }

    /**
     * @return mixed
     */
    public function getTeamId()
    {
        return $this->teamId;
    }

    /**
     * @return mixed
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * @return mixed
     */
    public function getLeagueName()
    {
        return $this->leagueName;
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
    public function getPlayed()
    {
        return $this->played;
    }

    /**
     * @return mixed
     */
    public function getWins()
    {
        return $this->wins;
    }

    /**
     * @return mixed
     */
    public function getDraws()
    {
        return $this->draws;
    }

    /**
     * @return mixed
     */
    public function getLoses()
    {
        return $this->loses;
    }

    /**
     * @return mixed
     */
    public function getForm()
    {
        return $this->form;
    }
}


$leagueId = "";
$teamId = "";
$season = "";
$leagueName = "";
$leagueLogo = "";
$played = "";
$wins = "";
$draws = "";
$loses = "";
$form = "";
$goalsForTotal = "";
$goalsForAverage = "";
$goalsAgainstTotal = "";
$goalsAgainstAverage = "";


function requestFakeStatistics()
{
    $file = file_get_contents('./FakeStatistics.txt', true);
    $data = json_decode($file, true);
    return $data;
}

function parseStatistics($statistics): StatisticsItem
{
    foreach ($statistics as $key => $value) {
        switch ($key) {
            case "response":
                foreach ($value as $key2 => $value2) {
                    global $form;
                    switch ($key2) {
                        case "league":
                            global $leagueId;
                            global $leagueName;
                            global $leagueLogo;
                            global $season;
                            foreach ($value2 as $key3 => $value3) {
                                switch ($key3) {
                                    case "id":
                                        $leagueId = $value3;
                                        break;
                                    case "name":
                                        $leagueName = $value3;
                                        break;
                                    case "logo":
                                        $leagueLogo = $value3;
                                        break;
                                    case "season":
                                        $season = $value3;
                                        break;
                                }
                            }
//                            echo $leagueId . $leagueName . $leagueLogo . $season ."<br>";
                            break;
                        case "team":
                            global $teamId;
                            foreach ($value2 as $key3 => $value3) {
                                switch ($key3) {
                                    case "id":
                                        $teamId = $value3;
                                        break;
                                }
                            }
//                            echo $teamId . "<br>";
                            break;
                        case "fixtures":
                            global $played;
                            global $wins;
                            global $draws;
                            global $loses;
                            foreach ($value2 as $key3 => $value3) {
                                switch ($key3) {
                                    case "played":
                                        $played = getHomeAwayTotal($value3);
                                        break;
                                    case "wins":
                                        $wins = getHomeAwayTotal($value3);
                                        break;
                                    case "draws":
                                        $draws = getHomeAwayTotal($value3);
                                        break;
                                    case "loses":
                                        $loses = getHomeAwayTotal($value3);
                                        break;
                                }
                            }
                            break;
                        case "goals":
                            foreach ($value2 as $key3 => $value3) {
                                switch ($key3) {
                                    case "for":
                                        global $goalsForTotal;
                                        global $goalsForAverage;
                                        foreach ($value3 as $key4 => $value4) {
                                            switch ($key4) {
                                                case "total":
                                                    $goalsForTotal = getHomeAwayTotal($value4);
                                                    break;
                                                case "average":
                                                    $goalsForAverage = getHomeAwayTotal($value4);
                                                    break;
                                            }
                                        }
                                        break;
                                    case "against":
                                        global $goalsAgainstTotal;
                                        global $goalsAgainstAverage;
                                        foreach ($value3 as $key4 => $value4) {
                                            switch ($key4) {
                                                case "total":
                                                    $goalsAgainstTotal = getHomeAwayTotal($value4);
                                                    break;
                                                case "average":
                                                    $goalsAgainstAverage = getHomeAwayTotal($value4);
                                                    break;
                                            }
                                        }
                                        break;
                                }
                            }
                            break;
                        case "form":
                            $form = $value2;
                            break;
                    }
                }
                break;

        }
    }
    //Парсинг сделан, можно записать в БД.
//    echo $leagueId . "<br>" . $teamId . "<br>" . $season . "<br>" . $leagueName . "<br>" . $leagueLogo . "<br>" . $played . "<br>" . $wins . "<br>" . $draws . "<br>" . $loses . "<br>" . $form;
    return new StatisticsItem($leagueId, $teamId, $season, $leagueName, $leagueLogo, $played, $wins, $draws, $loses, $form, $goalsForTotal, $goalsForAverage, $goalsAgainstTotal, $goalsAgainstAverage);
}

function getHomeAwayTotal($arr): string
{
    $res = "";
    foreach ($arr as $item) {
        $res = $res . $item . ",";
    }
    return rtrim($res, ",");
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

    // Просто достанем статистику, которую "замокали". Без повторных запросов сделать нормально не получится.
    // Поэтому функции поиска статистики в базе не будет. Логики сохранения статистики из АПИ - тоже не будет.
    // => Параметры для получения JSON статистики из базы не нужны.
    public function getStatisticsJSON(): string
    {
        $sql = 'SELECT * FROM `statistics`';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->execute();

//        $arr = array();
//        while ($row = $stmt->fetch()):
//            $item = new StatisticsItem($row['league'], $row['team'], $row['season'], $row['league_name'], $row['league_logo'], $row['played'], $row['wins'], $row['draws'], $row['loses'], $row['form']);
//            array_push($arr, $item);
//        endwhile;

        $row = $stmt->fetch();
        $item = new StatisticsItem($row['league'], $row['team'], $row['season'], $row['league_name'], $row['league_logo'], $row['played'], $row['wins'], $row['draws'], $row['loses'], $row['form'], $row['goals_for_total'], $row['goals_for_average'], $row['goals_against_total'], $row['goals_against_average']);

//        return json_encode($arr);
        return json_encode($item);

    }

    public function saveStatistics($statisticsItem)
    {
        $sql = 'INSERT INTO `statistics` (`league`, `team`, `season`, `league_name`, `league_logo`, `played`, `wins`, `draws`, `loses`, `form`, `goals_for_total`, `goals_for_average`, `goals_against_total`, `goals_against_average`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )';
        $stmt = $this->_pdo->prepare($sql);

        $stmt->bindValue(1, $statisticsItem->getLeagueId());
        $stmt->bindValue(2, $statisticsItem->getTeamId());
        $stmt->bindValue(3, $statisticsItem->getSeason());
        $stmt->bindValue(4, $statisticsItem->getLeagueName());
        $stmt->bindValue(5, $statisticsItem->getLeagueLogo());
        $stmt->bindValue(6, $statisticsItem->getPlayed());
        $stmt->bindValue(7, $statisticsItem->getWins());
        $stmt->bindValue(8, $statisticsItem->getDraws());
        $stmt->bindValue(9, $statisticsItem->getLoses());
        $stmt->bindValue(10, $statisticsItem->getForm());
        $stmt->bindValue(11, $statisticsItem->getGoalsForTotal());
        $stmt->bindValue(12, $statisticsItem->getGoalsForAverage());
        $stmt->bindValue(13, $statisticsItem->getGoalsAgainstTotal());
        $stmt->bindValue(14, $statisticsItem->getGoalsAgainstAverage());


        $stmt->execute();
    }

    public function deleteAllStatistics()
    {
        $sql = 'DELETE FROM `statistics`';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->execute();
    }
}


try {
    $db = 'statistics';

    $pdo = new FootballPDO();

    if (isset($_REQUEST['team']) && isset($_REQUEST['season']) && isset($_REQUEST['league'])) {
//    $statistics = requestStatisticsFromApi($_REQUEST['team'], $_REQUEST['season'], $_REQUEST['league']);
//    echo $statistics;
//        $stat = requestFakeStatistics();
//        $statisticItem = parseStatistics($stat);
//        $pdo->saveStatistics($statisticItem);
        echo $pdo->getStatisticsJSON();

    } else {
        echo "PARAMETERS NOT SET";
    }


} catch (PDOException $e) {
    die("Could not connect to the database $db :" . $e->getMessage());
}


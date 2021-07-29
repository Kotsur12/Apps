<?php

class Country
{

    var $name;
    var $code;
    var $flag;

    function __construct($name, $code, $flag)
    {
        $this->name = $name;
        $this->code = $code;
        $this->flag = $flag;
    }

    function __toString()
    {
        return $this->name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getFlag()
    {
        return $this->flag;
    }
}

class MyPDO
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

    public function updateCountries($countries)
    {

        $sql = 'DROP TABLE `countries`';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->execute();

        $sql = 'CREATE TABLE `football`.`countries` ( `id` INT NOT NULL AUTO_INCREMENT , `name` TEXT NULL, `code` TEXT NULL , `flag` TEXT NULL, PRIMARY KEY (`id`)) ENGINE = InnoDB;';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->execute();

        foreach ($countries as $country) {
            $this->insertCountry($country);
        }
        $this->updateInsertionDate();
    }

    public function updateInsertionDate()
    {
        $sql = 'DELETE FROM `countries_download_date`';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->execute();

        $sql = 'INSERT INTO `countries_download_date` (`countries_download_date`) VALUES (?)';
        $stmt = $this->_pdo->prepare($sql);
        $stmt->execute([date("z")]);
    }

    public function insertCountry($country)
    {
        if ($country->getName() != null && $country->getCode() != null) {
            $sql = 'INSERT INTO `countries` (`name`, `code`, `flag`) VALUES (?, ?, ?)';
            $stmt = $this->_pdo->prepare($sql);
            $stmt->execute([$country->getName(), $country->getCode(), $country->getFlag()]);
        }
    }

    public function shouldWeUpdate()
    {
        //если датаСохранения - датаСегодня > 30дней или NULL -> вернуть ДА иначе НЕТ
        $dateDB = $this->getInsertionDate()['countries_download_date'];
        if (($dateDB - date('z')) > 30 || $dateDB == null) {
            //Временно сделаю 0 вместо 30 чтобы не обновлять
            return 0;
        } else return 0;
    }

    public function getInsertionDate()
    {
        $sql = "SELECT `countries_download_date` FROM `countries_download_date`";
        $stmt = $this->_pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getCountriesJSON()
    {
        $sql = 'SELECT * FROM `countries`';
        $q = $this->_pdo->query($sql);

        $arr = array();
        while ($row = $q->fetch()):
            $country = new Country($row['name'], $row['code'], $row['flag']);
            $obj = array('name' => $country->getName(), 'code' => $country->getCode(), 'flag' => $country->getFlag());
            array_push($arr, $obj);
        endwhile;

        return json_encode($arr);
    }
}

try {
    $db = 'football';

    $pdo = new MyPDO();

} catch (PDOException $e) {
    die("Could not connect to the database $db :" . $e->getMessage());
}

//$file = file_get_contents('./Countries.txt', true);
//$data = json_decode($file, true);
function getCountriesFromApi(){
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api-football-beta.p.rapidapi.com/countries",
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

    if ($err) {
        echo "cURL Error #:" . $err;
    }

    return json_decode($response, true);

}

$countries = [];

if ($pdo->shouldWeUpdate()) {
    $data = getCountriesFromApi();

    foreach ($data as $item) {
        foreach ($item as $subItem) {
            $country = new Country($subItem['name'], $subItem['code'], $subItem['flag']);
            array_push($countries, $country);
        }
    }

    $pdo->updateCountries($countries);
}

$countries = $pdo->getCountriesJSON();
echo $countries;


<?php

//$curl = curl_init();
//
//curl_setopt_array($curl, [
//    CURLOPT_URL => "https://api-football-beta.p.rapidapi.com/leagues?team=82&season=2016&id=61&country=France",
//    CURLOPT_RETURNTRANSFER => true,
//    CURLOPT_FOLLOWLOCATION => true,
//    CURLOPT_ENCODING => "",
//    CURLOPT_MAXREDIRS => 10,
//    CURLOPT_TIMEOUT => 30,
//    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//    CURLOPT_CUSTOMREQUEST => "GET",
//    CURLOPT_HTTPHEADER => [
//        "x-rapidapi-host: api-football-beta.p.rapidapi.com",
//        "x-rapidapi-key: 6d64da69a3msh001c7d0b27bea68p1adc82jsnd158d477843f"
//    ],
//]);
//
//$response = curl_exec($curl);
//$err = curl_error($curl);
//
//curl_close($curl);
//
//if ($err) {
//    echo "cURL Error #:" . $err;
//} else {
//    echo $response;
//}

function showLeague(): string
{
    return "{\"get\":\"leagues\",\"parameters\":{\"country\":\"France\",\"team\":\"82\",\"season\":\"2016\",\"id\":\"61\"},\"errors\":[],\"results\":1,\"paging\":{\"current\":1,\"total\":1},\"response\":[{\"league\":{\"id\":61,\"name\":\"Ligue 1\",\"type\":\"League\",\"logo\":\"https:\/\/media.api-sports.io\/football\/leagues\/61.png\"},\"country\":{\"name\":\"France\",\"code\":\"FR\",\"flag\":\"https:\/\/media.api-sports.io\/flags\/fr.svg\"},\"seasons\":[{\"year\":2016,\"start\":\"2016-08-12\",\"end\":\"2017-05-20\",\"current\":false,\"coverage\":{\"fixtures\":{\"events\":true,\"lineups\":true,\"statistics_fixtures\":true,\"statistics_players\":true},\"standings\":true,\"players\":true,\"top_scorers\":true,\"top_assists\":true,\"top_cards\":true,\"injuries\":false,\"predictions\":true,\"odds\":false}}]}]}";
}

echo showLeague();
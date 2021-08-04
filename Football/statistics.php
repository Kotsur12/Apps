<?php

//$curl = curl_init();
//
//curl_setopt_array($curl, [
//    CURLOPT_URL => "https://api-football-beta.p.rapidapi.com/teams/statistics?team=82&season=2016&league=61",
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

function showStatistics(): string
{
    return "{\"get\":\"teams\/statistics\",\"parameters\":{\"league\":\"61\",\"team\":\"82\",\"season\":\"2016\"},\"errors\":[],\"results\":11,\"paging\":{\"current\":1,\"total\":1},\"response\":{\"league\":{\"id\":61,\"name\":\"Ligue 1\",\"country\":\"France\",\"logo\":\"https:\/\/media.api-sports.io\/football\/leagues\/61.png\",\"flag\":\"https:\/\/media.api-sports.io\/flags\/fr.svg\",\"season\":2016},\"team\":{\"id\":82,\"name\":\"Montpellier\",\"logo\":\"https:\/\/media.api-sports.io\/football\/teams\/82.png\"},\"form\":\"WLDDDLLDWLDWDDLWLWLDLLWLWWLDLLLWWLLLLL\",\"fixtures\":{\"played\":{\"home\":19,\"away\":19,\"total\":38},\"wins\":{\"home\":8,\"away\":2,\"total\":10},\"draws\":{\"home\":5,\"away\":4,\"total\":9},\"loses\":{\"home\":6,\"away\":13,\"total\":19}},\"goals\":{\"for\":{\"total\":{\"home\":28,\"away\":20,\"total\":48},\"average\":{\"home\":\"1.5\",\"away\":\"1.1\",\"total\":\"1.3\"},\"minute\":{\"0-15\":{\"total\":12,\"percentage\":\"23.08%\"},\"16-30\":{\"total\":8,\"percentage\":\"15.38%\"},\"31-45\":{\"total\":4,\"percentage\":\"7.69%\"},\"46-60\":{\"total\":13,\"percentage\":\"25.00%\"},\"61-75\":{\"total\":6,\"percentage\":\"11.54%\"},\"76-90\":{\"total\":8,\"percentage\":\"15.38%\"},\"91-105\":{\"total\":1,\"percentage\":\"1.92%\"},\"106-120\":{\"total\":null,\"percentage\":null}}},\"against\":{\"total\":{\"home\":22,\"away\":44,\"total\":66},\"average\":{\"home\":\"1.2\",\"away\":\"2.3\",\"total\":\"1.7\"},\"minute\":{\"0-15\":{\"total\":7,\"percentage\":\"11.29%\"},\"16-30\":{\"total\":14,\"percentage\":\"22.58%\"},\"31-45\":{\"total\":9,\"percentage\":\"14.52%\"},\"46-60\":{\"total\":7,\"percentage\":\"11.29%\"},\"61-75\":{\"total\":9,\"percentage\":\"14.52%\"},\"76-90\":{\"total\":14,\"percentage\":\"22.58%\"},\"91-105\":{\"total\":2,\"percentage\":\"3.23%\"},\"106-120\":{\"total\":null,\"percentage\":null}}}},\"biggest\":{\"streak\":{\"wins\":2,\"draws\":3,\"loses\":3},\"wins\":{\"home\":\"4-0\",\"away\":\"0-3\"},\"loses\":{\"home\":\"0-3\",\"away\":\"6-2\"},\"goals\":{\"for\":{\"home\":4,\"away\":3},\"against\":{\"home\":3,\"away\":6}}},\"clean_sheet\":{\"home\":5,\"away\":2,\"total\":7},\"failed_to_score\":{\"home\":4,\"away\":6,\"total\":10},\"penalty\":{\"scored\":{\"total\":3,\"percentage\":\"100.00%\"},\"missed\":{\"total\":0,\"percentage\":\"0%\"},\"total\":3},\"lineups\":[{\"formation\":\"4-2-3-1\",\"played\":24},{\"formation\":\"3-5-1-1\",\"played\":3},{\"formation\":\"4-3-1-2\",\"played\":3},{\"formation\":\"4-1-4-1\",\"played\":3},{\"formation\":\"4-3-3\",\"played\":2},{\"formation\":\"4-4-1-1\",\"played\":2},{\"formation\":\"4-3-2-1\",\"played\":1}],\"cards\":{\"yellow\":{\"0-15\":{\"total\":1,\"percentage\":\"2.08%\"},\"16-30\":{\"total\":8,\"percentage\":\"16.67%\"},\"31-45\":{\"total\":10,\"percentage\":\"20.83%\"},\"46-60\":{\"total\":7,\"percentage\":\"14.58%\"},\"61-75\":{\"total\":9,\"percentage\":\"18.75%\"},\"76-90\":{\"total\":13,\"percentage\":\"27.08%\"},\"91-105\":{\"total\":null,\"percentage\":null},\"106-120\":{\"total\":null,\"percentage\":null}},\"red\":{\"0-15\":{\"total\":1,\"percentage\":\"20.00%\"},\"16-30\":{\"total\":null,\"percentage\":null},\"31-45\":{\"total\":null,\"percentage\":null},\"46-60\":{\"total\":null,\"percentage\":null},\"61-75\":{\"total\":1,\"percentage\":\"20.00%\"},\"76-90\":{\"total\":3,\"percentage\":\"60.00%\"},\"91-105\":{\"total\":null,\"percentage\":null},\"106-120\":{\"total\":null,\"percentage\":null}}}}}";
}

echo showStatistics();
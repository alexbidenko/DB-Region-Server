<?php
include_once "funs.php";
include_once "Geohash.php";

/*$postData = file_get_contents('php://input');
$postData = jsonDecodeFromPost($postData);
echo $postData;
foreach($postData as $key => $value) {
    echo $key.": ".$value;
}*/

$geoHash = new Geohash();
$test = $geoHash->encode(45.02248600057393, 39.01851118469551, 16);
echo $test." \n";
$test = $geoHash->encode(45.01566999472532, 39.02516112094798, 16);
echo $test." \n";
$test = $geoHash->encode(45.01817999472532, 39.02581112094798, 16);
echo $test." \n";
$test = $geoHash->decode($test);
foreach($test as $value) {
    echo $value."\n";
}

echo file_get_contents("http://hackathon.tw1.ru/users/requirements.json");
?>
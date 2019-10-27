<?php
include_once "../back/funs.php";
include_once "../back/Geohash.php";
$mysqli = BaseConect();

//$_POST = jsonDecodeFromPost(file_get_contents('php://input'));

$result = null;

if(!isset($_GET['completed'])) {
    $result = $mysqli->query("SELECT * FROM hackathon_diseased_requests WHERE is_finish = 0;");
} else {
    $result = $mysqli->query("SELECT * FROM hackathon_diseased_requests WHERE is_finish = 1;");
}

$ans = array();

if(!isset($_GET['distance'])) {
    $_GET['distance'] = 10000;
}

if(!isset($_GET['count'])) {
    $_GET['count'] = 10000;
}

while($row = $result->fetch_assoc()) {
    $geoHash = new Geohash();
    $coord = $geoHash->decode($row['locationHash']);

    if(isset($_GET['lat'])) {
        $distance = getDistance(+$_GET['lat'], +$_GET['lng'], $coord[0], $coord[1]);
    }

    if($distance <= +$_GET['distance'] || !isset($_GET['lat'])) {
        $row['locationHash'] = substr($row['locationHash'], 0, 5);
        $row['distance'] = $distance;
        if(!isset($_GET['completed'])) {
            $row['is_finish'] = false;
        } else {
            $row['is_finish'] = true;
        }

        $result_d = $mysqli->query("SELECT * FROM hackathon_diseased WHERE id = ".$row['author']." ORDER BY id DESC;");
        $row_d = $result_d->fetch_assoc();

        $row['author'] = $row_d['last_name']." ".$row_d['first_name']." ".$row_d['middle_name'];

        if(count($ans) < $_GET['count']) {
            array_push($ans, $row);
        }
    }
}

echo '{"data":'.json_encode($ans).'}';
?>
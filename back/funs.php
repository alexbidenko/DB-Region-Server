<?php
function BaseConect () {
    $mysqli = new mysqli("127.0.0.1", "cp36696_hack", "hackathon", "cp36696_hack");
    if ($mysqli->connect_errno) {
        echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }
    return $mysqli;
}

function checkPassport($answer) {
    return true;
}

function getDistance($lat1, $lon1, $lat2, $lon2) {
    $pi = 3.14;
    return 6371 * 1000 * acos(
        sin($lat1 * $pi / 180) * sin($lat2 * $pi / 180) +
                cos($lat1 * $pi / 180) * cos($lat2 * $pi / 180) *
                cos($lon1 * $pi / 180 - $lon2 * $pi / 180)
    );
}

function cors() {

    // Allow from any origin
    /*if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }*/

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }
}

function jsonDecodeFromPost($data) {
    if(gettype($data) == "object") {
        $data = json_encode($data);
    }

    //$data = str_replace('\\"', '"', $pair);

    $data = substr($data, 1, strlen($data) - 2);

    $data = explode('","', $data);

    $ans = array();

    foreach($data as $pair) {
        $pair = str_replace('"', '', $pair);

        $pair = explode(':', $pair);

        $ans[$pair[0]] = $pair[1];
    }

    return $ans;
}

function jsonDecodeFromPostWithArray($data) {
    if(gettype($data) == "object") {
        $data = json_encode($data);
    }

    //$data = str_replace('\\"', '"', $pair);

    $array = "[".explode('"requirements":[', explode(']', $data)[0])[1]."]";

    $data = str_replace($array, '"#####"', $data);

    $array = json_decode($array);

    $data = substr($data, 1, strlen($data) - 2);

    $data = explode('","', $data);

    $ans = array();

    foreach($data as $pair) {
        $pair = str_replace('"', '', $pair);

        $pair = explode(':', $pair);

        if($pair[0] == "requirements") {
            $ans[$pair[0]] = $array;
        } else {
            $ans[$pair[0]] = $pair[1];
        }
    }

    return $ans;
}
?>
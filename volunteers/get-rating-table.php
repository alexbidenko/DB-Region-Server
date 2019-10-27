<?php
include_once "../back/funs.php";
$mysqli = BaseConect();

$result = $mysqli->query("SELECT * FROM hackathon_volunteers;");

$ans = array();

while($row = $result->fetch_assoc()) {
    $row['total_score'] = 0;

    $result_achievements = $mysqli->query("SELECT id, score FROM hackathon_volunteers_achievements WHERE volunteer_id = {$row['id']};");

    while($row_achievements = $result_achievements->fetch_assoc()) {
        $row['total_score'] += (int) $row_achievements['score'];
    }

    $result_achievements = $mysqli->query("SELECT id, score FROM hackathon_performed WHERE volunteers_id = {$row['id']};");

    while($row_achievements = $result_achievements->fetch_assoc()) {
        $row['total_score'] = $row['total_score'] + (int) $row_achievements['score'];
    }

    array_push($ans, $row);
}

$ready = true;
do {
    $ready = true;
    for($i = 1; $i < count($ans); $i++) {
        if($ans[$i]['total_score'] < $ans[$i - 1]['total_score']) {
            $cash = $ans[$i]['total_score'];
            $ans[$i]['total_score'] = $ans[$i - 1]['total_score'];
            $ans[$i - 1]['total_score'] = $cash;
            $ready = false;
        }
    }
} while(!$ready);

if(!isset($_GET['num'])) {
    $_GET['num'] = 0;
}

if(!isset($_GET['count'])) {
    $_GET['count'] = count($ans);
}

$finish_ans = array();

if((int)$_GET['num'] + (int)$_GET['count'] > count($ans)) {
    for($i = (int)$_GET['num']; $i < count($ans); $i++) {
        array_push($finish_ans, $ans[$i]);
    }
} else {
    for($i = (int)$_GET['num']; $i < (int)$_GET['num'] + (int)$_GET['count']; $i++) {
        array_push($finish_ans, $ans[$i]);
    }
}

echo '{"data":'.json_encode($finish_ans).'}';
?>
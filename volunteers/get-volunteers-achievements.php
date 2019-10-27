<?php
include_once "../back/funs.php";
$mysqli = BaseConect();

$ans = array();

$result = $mysqli->query("SELECT * FROM hackathon_volunteers_achievements WHERE volunteer_id = {$_GET['id']};");

while($row = $result->fetch_assoc()) {
    array_push($ans, $row);
}

echo json_encode($ans);
?>
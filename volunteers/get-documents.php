<?php
include_once "../back/funs.php";
$mysqli = BaseConect();

cors();

if(isset(getallheaders()['Authorization'])) {

    $token = explode('_', getallheaders()['Authorization'])[1];

    $result = $mysqli->query("SELECT id FROM hackathon_volunteers WHERE token = '".$token."';");
    $row = $result->fetch_assoc();

    $user_id = $row['id'];
    
    $result = $mysqli->query("SELECT * FROM hackathon_volunteers_documents WHERE volunteer_id = ".$user_id.";");

    $ans = array();

    while($row = $result->fetch_assoc()) {
        array_push($ans, $row);
    }

    echo '{"data":'.json_encode($ans).'}';
} else {
    echo '{"error":"no_token"}';
}
?>
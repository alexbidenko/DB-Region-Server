<?php
include_once "../back/funs.php";
$mysqli = BaseConect();

cors();

if(isset(getallheaders()['Authorization'])) {

    $token = explode('_', getallheaders()['Authorization'])[1];

    $result = $mysqli->query("SELECT * FROM hackathon_volunteers WHERE token = '".$token."';");
    $row = $result->fetch_assoc();

    $result = $mysqli->query("SELECT * FROM hackathon_performed WHERE volunteers_id = '".$row['id']."';");

    $ans = array();

    while($row = $result->fetch_assoc()) {
        $result_r = $mysqli->query("SELECT * FROM hackathon_diseased_requests WHERE id = ".$row['requests_id'].";");
        $row_r = $result_r->fetch_assoc();

        $row['full_info'] = $row_r;

        array_push($ans, $row);
    }

    echo '{"data":'.json_encode($ans).'}';
}
else
{
    echo '{"error":"no_token"}';
}
?>
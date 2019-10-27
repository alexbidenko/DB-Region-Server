<?php
//cors();

include_once "../back/funs.php";
$mysqli = BaseConect();

$token = explode('_', getallheaders()['Authorisation'])[1];

cors();

$result = $mysqli->query("SELECT * FROM hackathon_diseased WHERE token = '".$token."';");

if($result->num_rows > 0)
{
    $row = $result->fetch_assoc();

    echo json_encode($row);
}
else
{
    echo '{"error":"no_token"}';
}
?>
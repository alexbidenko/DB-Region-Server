<?php
//cors();

include_once "../back/funs.php";
$mysqli = BaseConect();

$_POST = jsonDecodeFromPost(file_get_contents('php://input'));

cors();

$result = $mysqli->query("SELECT * FROM hackathon_volunteers WHERE login = '".$_POST['login']."' AND password = '".$_POST['password']."';");

if($result->num_rows > 0)
{
    $row = $result->fetch_assoc();

    if($row['token'] != "") {
        echo json_encode(array("token" => $row['token']));
    } else {
        $token = rand(1000, 9999).date("U");

        $mysqli->query("UPDATE hackathon_volunteers SET token = '".$token."' WHERE id = ".$row['id'].";");

        echo json_encode(array("token" => $token));
    }
}
else
{
    echo '{"error":"no_login_or_password"}';
}
?>

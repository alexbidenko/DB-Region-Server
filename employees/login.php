<?php
include_once "../back/funs.php";
$mysqli = BaseConect();

$_POST = jsonDecodeFromPost(file_get_contents('php://input'));

cors();

$result = $mysqli->query("SELECT * FROM hackathon_employees WHERE login = '".$_POST['login']."' AND password = '".$_POST['password']."';");

if($result->num_rows > 0)
{
    $row = $result->fetch_assoc();

    echo json_encode(array("token" => $row['token']));
}
else
{
    echo '{"error":"no_login_or_password"}';
}
?>

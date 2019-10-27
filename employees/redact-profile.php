<?php
include_once "../back/funs.php";
$mysqli = BaseConect();

$_POST = jsonDecodeFromPost(file_get_contents('php://input'));

if(isset(getallheaders()['Authorisation'])) {

    $token = explode('_', getallheaders()['Authorisation'])[1];

    $mysqli->query("UPDATE hackathon_employees SET
        ".$_POST['key']." = '".$_POST['value']."' WHERE token = '".$token."';");

    echo "well";
}
?>
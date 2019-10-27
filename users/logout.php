<?php
include_once "../back/funs.php";
$mysqli = BaseConect();

if(isset(getallheaders()['Authorisation'])) {

    $token = explode('_', getallheaders()['Authorisation'])[1];

    $mysqli->query("UPDATE hackathon_volunteers SET token = '' WHERE token = '".$token."';");
    $mysqli->query("UPDATE hackathon_diseased SET token = '' WHERE token = '".$token."';");
    $mysqli->query("UPDATE hackathon_employees SET token = '' WHERE token = '".$token."';");

    echo "well";
}
?>
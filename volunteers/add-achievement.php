<?php
include_once "../back/funs.php";
$mysqli = BaseConect();

$_POST = jsonDecodeFromPost(file_get_contents('php://input'));

$mysqli->query("INSERT INTO hackathon_volunteers_achivements (
    token,
    description, 
    diseased,
    category,
    score
) VALUES (
    '".$_POST['token']."',
    '".$_POST['description']."', 
    '".$_POST['diseased']."', 
    '".$_POST['category']."', 
    ".$_POST['score']."
)");

echo '{"response":"well"}';
?>
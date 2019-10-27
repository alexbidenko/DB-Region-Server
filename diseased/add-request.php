<?php
include_once "../back/funs.php";
$mysqli = BaseConect();

$token = explode('_', getallheaders()['Authorisation'])[1];

$_POST = jsonDecodeFromPostWithArray(file_get_contents('php://input'));

$result = $mysqli->query("SELECT * FROM hackathon_diseased WHERE token = '".$token."';");

if($result->num_rows > 0)
{
    $row = $result->fetch_assoc();
    
    $mysqli->query("INSERT INTO hackathon_diseased_requests (
        title,
        description,
        full_task,
        locationHash, 
        category,
        author,
        is_finish
    ) VALUES (
        '".$_POST['title']."',
        '".$_POST['description']."',
        '".$_POST['full_task']."',
        '".$_POST['locationHash']."', 
        '".$_POST['category']."', 
        '".$row['id']."',
        0
    )");
    
    echo "well";
}
else
{
    echo '{"error":"no_token"}';
}
?>
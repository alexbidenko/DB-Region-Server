<?php
include_once "../back/funs.php";
$mysqli = BaseConect();

$_POST = jsonDecodeFromPost(file_get_contents('php://input'));
    
$result = $mysqli->query("SELECT * FROM hackathon_diseased WHERE login = '".$_POST['login']."';");

if($result->num_rows > 0)
{
    $row = $result->fetch_assoc();
    if ($row['login'] == $_POST['login']) {
        echo "no_login";
    }
}
else
{
    $token = rand(1000, 9999).date("U");

    /*$myCurl = curl_init();
    curl_setopt_array($myCurl, array(
        CURLOPT_URL => 'https://www.gosuslugi.ru/api',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query(array("passport" => $_POST['confirmation']))
    ));
    $response = curl_exec($myCurl);
    curl_close($myCurl);*/

    if(checkPassport(true/*$response*/)) {
        $mysqli->query("INSERT INTO hackathon_diseased (
            token,
            login, 
            password
        ) VALUES (
            '".$token."',
            '".$_POST['login']."', 
            '".$_POST['password']."'
        )");

        echo json_encode(array("token" => $token));
    } else {
        echo "no_passport";
    }
}
?>
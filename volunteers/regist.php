<?php
//cors();

include_once "../back/funs.php";
$mysqli = BaseConect();

$_POST = jsonDecodeFromPost(file_get_contents('php://input'));

cors();
    
$result = $mysqli->query("SELECT * FROM hackathon_volunteers WHERE login = '".$_POST['login']."';");

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
        CURLOPT_POSTFIELDS => http_build_query(array("passport" => $_POST['password']))
    ));
    $response = curl_exec($myCurl);
    curl_close($myCurl);*/

    if(!isset($_POST['first_name'])) {
        $_POST['first_name'] = "";
    }
    if(!isset($_POST['last_name'])) {
        $_POST['last_name'] = "";
    }
    if(!isset($_POST['middle_name'])) {
        $_POST['middle_name'] = "";
    }
    if(!isset($_POST['email'])) {
        $_POST['email'] = "";
    }
    if(!isset($_POST['phone'])) {
        $_POST['phone'] = "";
    }

    if(checkPassport(true/*$response*/)) {
        $mysqli->query("INSERT INTO hackathon_volunteers (
            first_name,
            last_name,
            middle_name,
            token,
            login, 
            password,
            email,
            phone
        ) VALUES (
            '".$_POST['first_name']."', 
            '".$_POST['last_name']."', 
            '".$_POST['middle_name']."', 
            '".$token."',
            '".$_POST['login']."', 
            '".$_POST['password']."', 
            '".$_POST['email']."', 
            '".$_POST['phone']."'
        )");

        echo json_encode(array("token" => $token));
    } else {
        echo "no_passport";
    }
}
?>

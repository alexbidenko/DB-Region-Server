<?php
include_once "../back/funs.php";
$mysqli = BaseConect();
    
cors();

$_POST = jsonDecodeFromPost(file_get_contents('php://input'));

if(isset(getallheaders()['Authorization'])) {

    $token = explode('_', getallheaders()['Authorization'])[1];

    $query = "";
    foreach($_POST as $key => $value) {
        if($query != "") {
            $query .= ", ";
        }

        $query .= $key." = '".$value."'";

        if($key == "active_task_id" ) {
            $result = $mysqli->query("SELECT author FROM hackathon_diseased_requests WHERE id = {$value};");
            $row = $result->fetch_assoc();
            
            $result = $mysqli->query("SELECT phone FROM hackathon_diseased WHERE id = {$row['author']};");
            $phone = $result->fetch_assoc()['phone'];

            $result = $mysqli->query("SELECT * FROM hackathon_volunteers WHERE token = '".$token."';");
            $row = $result->fetch_assoc();

            file_get_contents("http://smspilot.ru/api.php?send=".urlencode($row['first_name']." ".$row['last_name']. " вызвался вам помочь от сервиса Могу Помочь!")."&to={$phone}&apikey=05BB1RG89G98OK6QF341E369PJ1D5R08C878WD4H2XP10Z2S60I2I4Y283F9815J");
        }
    }

    $mysqli->query("UPDATE hackathon_volunteers SET {$query} WHERE token = '".$token."';");

    echo '{"response":"well"}';
} else {
    echo '{"error":"no_token"}';
}
?>
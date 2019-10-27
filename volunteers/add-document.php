<?php
include_once "../back/funs.php";
$mysqli = BaseConect();

cors();

if(isset(getallheaders()['Authorization'])) {

    $token = explode('_', getallheaders()['Authorization'])[1];

    $_POST = jsonDecodeFromPost(file_get_contents('php://input'));

    $result = $mysqli->query("SELECT id FROM hackathon_volunteers WHERE token = '".$token."';");
    $row = $result->fetch_assoc();

    $user_id = $row['id'];
    
    foreach($_POST as $type => $value) {
        $result = $mysqli->query("SELECT * FROM hackathon_volunteers_documents WHERE volunteer_id = ".$user_id." AND type = '".$type."';");
        $row = $result->fetch_assoc();

        if($result->num_rows == 0)
        {
            $mysqli->query("INSERT INTO hackathon_volunteers_documents (
                volunteer_id,
                type, 
                content
            ) VALUES (
                '".$user_id."',
                '".$type."', 
                '".$value."'
            )");
        } else {
            $mysqli->query("UPDATE hackathon_volunteers_documents SET content = '".$value."' WHERE volunteer_id = ".$user_id." AND type = '".$type."';");
        }
    }

    echo '{"response":"well"}';
} else {
    echo '{"error":"no_token"}';
}
?>
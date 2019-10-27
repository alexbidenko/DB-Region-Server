<?php
include_once "../back/funs.php";
$mysqli = BaseConect();

cors();

$_POST = jsonDecodeFromPost(file_get_contents('php://input'));

if(isset(getallheaders()['Authorization'])) {

    $token = explode('_', getallheaders()['Authorization'])[1];

    $mysqli->query("UPDATE hackathon_volunteers SET active_task_id = '0' WHERE token = '".$token."';");

    $mysqli->query("UPDATE hackathon_diseased_requests SET is_finish = 1 WHERE id = ".$_POST['id'].";");

    $result = $mysqli->query("SELECT id FROM hackathon_volunteers WHERE token = '".$token."';");
    $row = $result->fetch_assoc();
    
    $result_dr = $mysqli->query("SELECT * FROM hackathon_diseased_requests WHERE id = ".$_POST['id'].";");
    $row_dr = $result_dr->fetch_assoc();
    
    $requirements = json_decode(file_get_contents("../users/requirements.json"))->data;
    $score = 0;
    foreach($requirements as $requirement) {
        if($requirement->type == $row_dr['category']) {
            $score = $requirement->score;
        }
    }

    $mysqli->query("INSERT INTO hackathon_performed (
        requests_id,
        volunteers_id,
        score
    ) VALUES (
        ".$_POST['id'].",
        ".$row['id'].",
        ".$score."
    )");

    echo '{"response":"well"}';
} else {
    echo '{"error":"no_token"}';
}

/*INSERT INTO hackathon_diseased_requests (
        title,
        description,
        full_task,
        locationHash, 
        category,
        author,
        is_finish
    ) VALUES (
        'sdfhdfg',
        'sdhfsdh',
        'sdhfhhhdhfh',
        'ub5b053ubdnws2q9', 
        'care', 
        '1',
        0
    )*/
?>
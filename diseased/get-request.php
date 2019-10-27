<?php
include_once "../back/funs.php";

cors();

include_once "../back/Geohash.php";
$mysqli = BaseConect();

if(isset(getallheaders()['Authorization'])) {

    $token = explode('_', getallheaders()['Authorization'])[1];

    $requirements = json_decode(file_get_contents("../users/requirements.json"));

    $result = $mysqli->query("SELECT * FROM hackathon_diseased_requests WHERE id = ".$_GET['id'].";");

    $row = $result->fetch_assoc();

    $row['requirements'] = json_decode($row['requirements']);

    $result_d = $mysqli->query("SELECT * FROM hackathon_diseased WHERE id = ".$row['author'].";");
    $row_d = $result_d->fetch_assoc();

    $row['author'] = $row_d['last_name']." ".$row_d['first_name']." ".$row_d['middle_name'];

    $v_result_id = $mysqli->query("SELECT * FROM hackathon_volunteers WHERE token = '".$token."';");
    $v_row_id = $v_result_id->fetch_assoc();
    $v_id = $v_row_id['id'];

    $result_d = $mysqli->query("SELECT * FROM hackathon_volunteers_documents WHERE volunteer_id = ".$v_id.";");

    $documents = array();

    while($d = $result_d->fetch_assoc()) {
        array_push($documents, $d['type']);
    }

    $nesesuary = array();

    if($row['category'] != "") {
        foreach($requirements as $requirement) {
            if($requirement->type == $row['category']) {
                foreach($requirement->documents as $document) {
                    $find = false;
                    foreach($documents as $u_d) {
                        if($document == $u_d) {
                            $find = true;
                        }
                    }

                    if(!$find) {
                        array_push($nesesuary, $document);
                    }
                }
            }
        }
    }

    $row['required_documents'] = $nesesuary;

    echo '{"data":'.json_encode($row).'}';
} else {
    echo '{"error":"no_token"}';
}
?>
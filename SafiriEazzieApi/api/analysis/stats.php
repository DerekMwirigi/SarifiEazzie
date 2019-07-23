<?php 
    include '../../controllers/request.php';
    $requestHandler = new RequestHandler();
    $reqRes = $requestHandler->flagRequest($_SERVER, json_decode(file_get_contents('php://input'), true));
    if($reqRes["success"]){
        include '../../controllers/analysis.php';
        $analysis = new Analysis();
        $reqRes = $analysis->stats();
    }
    echo json_encode($reqRes);
?>
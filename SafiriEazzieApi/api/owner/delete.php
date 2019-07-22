<?php 
    include '../../controllers/request.php';
    $requestHandler = new RequestHandler();
    $reqRes = $requestHandler->flagRequest($_SERVER, json_decode(file_get_contents('php://input'), true));
    if($reqRes["success"]){
        include '../../controllers/owner.php';
        $owner = new Owner();
        $reqRes = $owner->remove($reqRes["payLoad"], $reqRes["data"]);
    }
    echo json_encode($reqRes);
?>
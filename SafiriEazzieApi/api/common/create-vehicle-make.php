<?php 
    include '../../controllers/request.php';
    $requestHandler = new RequestHandler();
    $reqRes = $requestHandler->flagRequest($_SERVER, json_decode(file_get_contents('php://input'), true));
    if($reqRes["success"]){
        include '../../controllers/common.php';
        $common = new Common();
        $reqRes = $common->create($reqRes["payLoad"], $reqRes["data"], json_decode(file_get_contents("../../models/vehicle-make.json"), true));
    }
    echo json_encode($reqRes);
?>
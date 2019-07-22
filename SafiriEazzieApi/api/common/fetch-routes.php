<?php 
    include '../../controllers/request.php';
    $requestHandler = new RequestHandler();
    $reqRes = $requestHandler->flagRequest($_SERVER);
    if($reqRes["success"]){
        include '../../controllers/common.php';
        $common = new Common();
        $reqRes = $common->fetch(json_decode(file_get_contents("../../models/route.json"), true)["getList"]);
    }
    echo json_encode($reqRes);
?>
<?php 
    include '../../controllers/request.php';
    $requestHandler = new RequestHandler();
    $reqRes = $requestHandler->flagRequest($_SERVER);
    if($reqRes["success"]){
        include '../../controllers/common.php';
        $common = new Common();
        $searchModel = json_decode(file_get_contents("../../models/common.json"), true)["getListVehicleModels"];
        $searchModel["keyModel"] = array(
            "makeId"=>"=".$_GET["makeId"]
        );
        $reqRes = $common->fetch($searchModel);
    }
    echo json_encode($reqRes);
?>
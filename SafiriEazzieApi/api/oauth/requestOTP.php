<?php 
    include '../../controllers/request.php';
    $requestHandler = new RequestHandler();
    $reqRes = $requestHandler->flagRequest($_SERVER);
    if($reqRes["success"]){
        include '../../controllers/oauth.php';
        $oauth = new Oauth();
        $reqRes = $oauth->requestOTP(json_decode(file_get_contents('php://input'), true));
    }
    echo json_encode($reqRes);
?>
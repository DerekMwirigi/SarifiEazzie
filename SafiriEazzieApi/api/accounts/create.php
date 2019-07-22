<?php 
    include '../../controllers/request.php';
    $requestHandler = new RequestHandler();
    $reqRes = $requestHandler->flagRequest($_SERVER);
    if($reqRes["success"]){
        include '../../controllers/account.php';
        $account = new Account();
        $reqRes = $account->create(json_decode(file_get_contents('php://input'), true));
    }
    echo json_encode($reqRes);
?>
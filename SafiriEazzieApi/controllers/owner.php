<?php 
    class Owner extends DatabaseHandler {
        private $debug;
        private $ownerModel;
        public function __construct($debug = NULL){
            $this->debug = $debug;
            parent::__construct($this->debug);
            $this->ownerModel = json_decode(file_get_contents("../../models/owner.json"), true);
        }

        public function create ($ownerModel, $userModel){
            $errors = array();
            $ownerModel["userId"] = $userModel["id"];
            $validRes = $this->utils->validateModel($ownerModel, $this->ownerModel["validModel"]);
            if($validRes["success"]){
                $ownerModel["code"] = $this->utils->generateRandom(1111, 9999, 4);
                $dbRes = $this->insert("owners", $ownerModel);
                if($dbRes[0] == 1){
                    return array(
                        "success"=>true,
                        "errors"=>null,
                        "status_code"=>1,
                        "status_message"=>"Successful.",
                        "message"=>"owner was created.",
                        "data"=>$this->fetch(null, $userModel)["data"]
                    );
                }else{
                    array_push($errors, $dbRes[1]);
                    return array(
                        "success"=>true,
                        "errors"=>$errors,
                        "status_code"=>0,
                        "status_message"=>"Failed.",
                        "message"=>"owner was not created.",
                        "data"=>null
                    );
                }
            }
            return $validRes;
        }

        public function edit ($ownerModel){
            $dbRes = $this->update("owners", $ownerModel, array("id"=>$owner["id"]));
            if($dbRes[0] == 1){
                return array(
                    "success"=>true,
                    "errors"=>null,
                    "status_code"=>1,
                    "status_message"=>'Successful.',
                    "message"=>"owner details udated.",
                    "data"=>$this->view(array("id"=>"='" . $ownerModel["id"] . "'"))["data"]
                );
            }else{
                $errors = array();
                array_push($errors, $dbRes[1]);
                return array(
                    "success"=>true,
                    "errors"=>$errors,
                    "status_code"=>0,
                    "status_message"=>'Failed.',
                    "message"=>"owner details not udated.",
                    "data"=>null
                );
            }
        } 

        public function remove ($ownerModel, $userModel){
            $dbRes = $this->delete("owners", $ownerModel, array("id"=>$ownerModel["id"]));
            if($dbRes[0] == 1){
                return array(
                    "success"=>true,
                    "errors"=>null,
                    "status_code"=>1,
                    "status_message"=>'Successful.',
                    "message"=>"owner details udated.",
                    "data"=>$this->fetch($userModel)["data"]
                );
            }else{
                $errors = array();
                array_push($errors, $dbRes[1]);
                return array(
                    "success"=>true,
                    "errors"=>$errors,
                    "status_code"=>0,
                    "status_message"=>'Failed.',
                    "message"=>"owner details not udated.",
                    "data"=>null
                );
            }
        } 
        
        public function view ($keyModel){
            $this->ownerModel["viewModel"]["keyModel"] = $keyModel;
            $dbRes = $this->search($this->ownerModel["viewModel"]);
            if($dbRes[0] == 1){
                return array(
                    "success"=>true,
                    "errors"=>null,
                    "status_code"=>1,
                    "status_message"=>"Success.",
                    "message"=>"owner found.",
                    "data"=>$dbRes[2][0]
                );
            }else{
                $errors = array();
                array_push($errors, $dbRes[1]);
                return array(
                    "success"=>true,
                    "errors"=>$errors,
                    "status_code"=>1,
                    "status_message"=>"Failed.",
                    "message"=>"billing address not found.",
                    "data"=>null
                );
            }
        }

        public function fetch ($searchModel, $userModel){
            $dbRes = $this->search($this->ownerModel["getList"]);
            if($dbRes[0] == 1){
                return array(
                    "success"=>true,
                    "errors"=>null,
                    "status_code"=>1,
                    "status_message"=>'Successful.',
                    "message"=>"Found " . count($dbRes[2]) . " items",
                    "data"=>$dbRes[2],
                    "pagination"=>$dbRes[3]
                );
            }else{
                $errors = array();
                array_push($errors, $dbRes[1]);
                return array(
                    "success"=>true,
                    "errors"=>$errors,
                    "status_code"=>0,
                    "status_message"=>'Failed.',
                    "message"=>"No items found.",
                    "data"=>null
                );
            }
        }
    }
?>
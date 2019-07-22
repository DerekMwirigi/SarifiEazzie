<?php 
    class Vehicle extends DatabaseHandler {
        private $debug;
        private $vehicleModel;
        public function __construct($debug = NULL){
            $this->debug = $debug;
            parent::__construct($this->debug);
            $this->vehicleModel = json_decode(file_get_contents("../../models/vehicle.json"), true);
        }

        public function create ($vehicleModel, $userModel){
            $errors = array();
            $vehicleModel["userId"] = $userModel["id"];
            $validRes = $this->utils->validateModel($vehicleModel, $this->vehicleModel["validModel"]);
            if($validRes["success"]){
                $vehicleModel["code"] = $this->utils->generateRandom(1111, 9999, 4);
                $dbRes = $this->insert("vehicles", $vehicleModel);
                if($dbRes[0] == 1){
                    return array(
                        "success"=>true,
                        "errors"=>null,
                        "status_code"=>1,
                        "status_message"=>"Successful.",
                        "message"=>"vehicle was created.",
                        "data"=>$this->fetch(null, $userModel)["data"]
                    );
                }else{
                    array_push($errors, $dbRes[1]);
                    return array(
                        "success"=>true,
                        "errors"=>$errors,
                        "status_code"=>0,
                        "status_message"=>"Failed.",
                        "message"=>"vehicle was not created.",
                        "data"=>null
                    );
                }
            }
            return $validRes;
        }

        public function edit ($vehicleModel){
            $dbRes = $this->update("vehicles", $vehicleModel, array("id"=>$vehicle["id"]));
            if($dbRes[0] == 1){
                return array(
                    "success"=>true,
                    "errors"=>null,
                    "status_code"=>1,
                    "status_message"=>'Successful.',
                    "message"=>"vehicle details udated.",
                    "data"=>$this->view(array("id"=>"='" . $vehicleModel["id"] . "'"))["data"]
                );
            }else{
                $errors = array();
                array_push($errors, $dbRes[1]);
                return array(
                    "success"=>true,
                    "errors"=>$errors,
                    "status_code"=>0,
                    "status_message"=>'Failed.',
                    "message"=>"vehicle details not udated.",
                    "data"=>null
                );
            }
        } 

        public function remove ($vehicleModel, $userModel){
            $dbRes = $this->delete("vehicles", $vehicleModel, array("id"=>$vehicleModel["id"]));
            if($dbRes[0] == 1){
                return array(
                    "success"=>true,
                    "errors"=>null,
                    "status_code"=>1,
                    "status_message"=>'Successful.',
                    "message"=>"vehicle details udated.",
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
                    "message"=>"vehicle details not udated.",
                    "data"=>null
                );
            }
        } 
        
        public function view ($keyModel){
            $this->vehicleModel["viewModel"]["keyModel"] = $keyModel;
            $dbRes = $this->search($this->vehicleModel["viewModel"]);
            if($dbRes[0] == 1){
                return array(
                    "success"=>true,
                    "errors"=>null,
                    "status_code"=>1,
                    "status_message"=>"Success.",
                    "message"=>"vehicle found.",
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
            $dbRes = $this->search($this->vehicleModel["getList"]);
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
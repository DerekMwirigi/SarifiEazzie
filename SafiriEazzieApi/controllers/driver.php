<?php 
    class Driver extends DatabaseHandler {
        private $debug;
        private $driverModel;
        public function __construct($debug = NULL){
            $this->debug = $debug;
            parent::__construct($this->debug);
            $this->driverModel = json_decode(file_get_contents("../../models/driver.json"), true);
        }

        public function create ($driverModel, $userModel){
            $errors = array();
            $driverModel["userId"] = $userModel["id"];
            $validRes = $this->utils->validateModel($driverModel, $this->driverModel["validModel"]);
            if($validRes["success"]){
                $driverModel["code"] = $this->utils->generateRandom(1111, 9999, 4);
                $dbRes = $this->insert("drivers", $driverModel);
                if($dbRes[0] == 1){
                    return array(
                        "success"=>true,
                        "errors"=>null,
                        "status_code"=>1,
                        "status_message"=>"Successful.",
                        "message"=>"driver was created.",
                        "data"=>$this->fetch(null, $userModel)["data"]
                    );
                }else{
                    array_push($errors, $dbRes[1]);
                    return array(
                        "success"=>true,
                        "errors"=>$errors,
                        "status_code"=>0,
                        "status_message"=>"Failed.",
                        "message"=>"driver was not created.",
                        "data"=>null
                    );
                }
            }
            return $validRes;
        }

        public function edit ($driverModel){
            $dbRes = $this->update("drivers", $driverModel, array("id"=>$driver["id"]));
            if($dbRes[0] == 1){
                return array(
                    "success"=>true,
                    "errors"=>null,
                    "status_code"=>1,
                    "status_message"=>'Successful.',
                    "message"=>"driver details udated.",
                    "data"=>$this->view(array("id"=>"='" . $driverModel["id"] . "'"))["data"]
                );
            }else{
                $errors = array();
                array_push($errors, $dbRes[1]);
                return array(
                    "success"=>true,
                    "errors"=>$errors,
                    "status_code"=>0,
                    "status_message"=>'Failed.',
                    "message"=>"driver details not udated.",
                    "data"=>null
                );
            }
        } 

        public function remove ($driverModel, $userModel){
            $dbRes = $this->delete("drivers", $driverModel, array("id"=>$driverModel["id"]));
            if($dbRes[0] == 1){
                return array(
                    "success"=>true,
                    "errors"=>null,
                    "status_code"=>1,
                    "status_message"=>'Successful.',
                    "message"=>"driver details udated.",
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
                    "message"=>"driver details not udated.",
                    "data"=>null
                );
            }
        } 
        
        public function view ($keyModel){
            $this->driverModel["viewModel"]["keyModel"] = $keyModel;
            $dbRes = $this->search($this->driverModel["viewModel"]);
            if($dbRes[0] == 1){
                return array(
                    "success"=>true,
                    "errors"=>null,
                    "status_code"=>1,
                    "status_message"=>"Success.",
                    "message"=>"driver found.",
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
            $dbRes = $this->search($this->driverModel["getList"]);
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
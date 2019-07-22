<?php 
    class Common extends DatabaseHandler {
        private $debug;
        public function __construct($debug = NULL){
            $this->debug = $debug;
            parent::__construct($this->debug);
        }

        public function createRoute ($routeModel, $userModel){
            $errors = array();
            $routeModel["createdById"] = $userModel["id"];
            $validRes = $this->utils->validateModel($routeModel, json_decode(file_get_contents("../../models/route.json"), true)["validModel"]);
            if($validRes["success"]){
                $routeModel["code"] = $this->utils->generateRandom(1111, 9999, 4);
                $dbRes = $this->insert("routes", $routeModel);
                if($dbRes[0] == 1){
                    return array(
                        "success"=>true,
                        "errors"=>null,
                        "status_code"=>1,
                        "status_message"=>"Successful.",
                        "message"=>"route was created.",
                        "data"=>$this->fetch(json_decode(file_get_contents("../../models/route.json"), true)["getList"])["data"]
                    );
                }else{
                    array_push($errors, $dbRes[1]);
                    return array(
                        "success"=>true,
                        "errors"=>$errors,
                        "status_code"=>0,
                        "status_message"=>"Failed.",
                        "message"=>"route was not created.",
                        "data"=>null
                    );
                }
            }
            return $validRes;
        }
        public function create ($entityModel, $userModel, $modelDescriber){
            $errors = array();
            $routeModel["createdById"] = $userModel["id"];
            $validRes = $this->utils->validateModel($routeModel, $modelDescriber["validModel"]);
            if($validRes["success"]){
                $routeModel["code"] = $this->utils->generateRandom(1111, 9999, 4);
                $dbRes = $this->insert($this->dbTables[$modelDescriber["viewModel"]["entityType"]], $routeModel);
                if($dbRes[0] == 1){
                    return array(
                        "success"=>true,
                        "errors"=>null,
                        "status_code"=>1,
                        "status_message"=>"Successful.",
                        "message"=>"item was created.",
                        "data"=>$this->fetch($modelDescriber["getList"])["data"]
                    );
                }else{
                    array_push($errors, $dbRes[1]);
                    return array(
                        "success"=>true,
                        "errors"=>$errors,
                        "status_code"=>0,
                        "status_message"=>"Failed.",
                        "message"=>"item was not created.",
                        "data"=>null
                    );
                }
            }
            return $validRes;
        }
        public function fetch ($searchModel){
            $dbRes = $this->search($searchModel);
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
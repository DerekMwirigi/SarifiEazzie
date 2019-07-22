<?php 
    include 'oauth.php';
    
    class Account extends DatabaseHandler {
        private $debug;
        private $oauth;
        private $accountModel;
        private $tbIndex = 1;
        public function __construct($debug = NULL){
            $this->debug = $debug;
            $this->oauth = new Oauth();
            $this->accountModel = json_decode(file_get_contents("../../models/account.json"), true);
            parent::__construct($this->debug);
        }

        public function create ($accountModel){
            $errors = array();
            if($accountModel == null) { $accountModel = $this->accountModel["entityModel"]; }
            $validRes = $this->utils->validateModel($accountModel, $this->accountModel["validModel"]);
            if($validRes["success"]){
                $accountModel["code"] = $this->utils->generateRandom(1111, 9999, 4);
                $accountModel["token"] = $this->utils->createToken();
                $accountModel["password"] = $this->utils->encryptPassword($accountModel["password"]);
                $dbRes=$this->insert($this->dbTables[$this->tbIndex],$accountModel);
                if($dbRes[0] == 1){
                    return array(
                        "success"=>true,
                        "errors"=>null,
                        "status_code"=>1,
                        "status_message"=>'Successful.',
                        "message"=>"account was created.",
                        "data"=>$this->view(array("code"=>" ='" . $accountModel["code"] . "'"))["data"]

                    );
                }else{
                    array_push($errors, $dbRes[1]);
                    return array(
                        "success"=>true,
                        "errors"=>$errors,
                        "status_code"=>0,
                        "status_message"=>'Failed.',
                        "message"=>"account details not saved.",
                        "data"=>null
                    );
                }
            }else{
                $errors = $validRes["errors"];
                return array(
                    "success"=>true,
                    "errors"=>$errors,
                    "status_code"=>0,
                    "status_message"=>'Failed.',
                    "message"=>"account details not saved.",
                    "data"=>null
                );
            }
            return $res;
        }

        public function editPassword ($accountModel, $keyModel) {
            $errors = array();
            $accountModel["password"] = $this->utils->encryptPassword($accountModel["password"]);
            $dbRes = $this->update($this->dbTables[$this->tbIndex], $accountModel, $keyModel);
            if($dbRes[0] == 1){
                return array(
                    "success"=>true,
                    "errors"=>null,
                    "status_code"=>1,
                    "status_message"=>'Successful.',
                    "message"=>"account was updated.",
                    "data"=>$this->viewMobile($keyModel)["data"]
                );
            }else{
                array_push($errors, $dbRes[1]);
                return array(
                    "success"=>true,
                    "errors"=>$errors,
                    "status_code"=>0,
                    "status_message"=>'Failed.',
                    "message"=>"account was not updated.",
                    "data"=>null
                );
            }
        }

        public function edit ($accountModel, $keyModel) {
            $errors = array();
            unset($accountModel["id"]);
            unset($keyModel["roleId"]);
            $dbRes = $this->update($this->dbTables[$this->tbIndex], $accountModel, $keyModel);
            //print_r($keyModel);
            if($dbRes[0] == 1){
                return array(
                    "success"=>true,
                    "errors"=>null,
                    "status_code"=>1,
                    "status_message"=>'Successful.',
                    "message"=>"account was updated.",
                    "data"=>$this->view(array("token"=> "='" . $keyModel["token"] . "'"))["data"]
                );
            }else{
                array_push($errors, $dbRes[1]);
                return array(
                    "success"=>true,
                    "errors"=>$errors,
                    "status_code"=>0,
                    "status_message"=>'Failed.',
                    "message"=>"account was not updated.",
                    "data"=>null
                );
            }
        }

        public function view ($keyModel){
            $this->accountModel["viewModel"]["keyModel"] = $keyModel;
            //print_r($this->accountModel["viewModel"]);
            $dbRes = $this->search($this->accountModel["viewModel"]);
            if($dbRes[0] == 1){
                return array(
                    "success"=>true,
                    "errors"=>null,
                    "status_code"=>1,
                    "status_message"=>'Successful.',
                    "message"=>"account was found.",
                    "data"=>$dbRes[2][0]
                ); 
            }else{
                $errors = array();
                array_push($errors, $dbRes[1]);
                return array(
                    "success"=>true,
                    "errors"=>$errors,
                    "status_code"=>0,
                    "status_message"=>'Failed.',
                    "message"=>"account was not found.",
                    "data"=>null
                );
            }
        }
        
        public function viewMobile ($accountModel){
            $this->accountModel["viewModel"]["keyModel"] = array("mobile"=> "='" . $accountModel["mobile"] . "'");
            $dbRes = $this->search($this->accountModel["viewModel"]);
            if($dbRes[0] == 1){
                return array(
                    "success"=>true,
                    "errors"=>null,
                    "status_code"=>1,
                    "status_message"=>'Successful.',
                    "message"=>"account was found.",
                    "data"=>$dbRes[2][0]
                );
            }else{
                $errors = array();
                array_push($errors, $dbRes[1]);
                return array(
                    "success"=>true,
                    "errors"=>$errors,
                    "status_code"=>0,
                    "status_message"=>'Failed.',
                    "message"=>"account was not found.",
                    "data"=>null
                );
            }
        }
    }
?>
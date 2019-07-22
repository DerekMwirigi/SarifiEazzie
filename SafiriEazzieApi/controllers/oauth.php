<?php 
    include 'sms.php';
    
    class Oauth extends DatabaseHandler {
        private $debug;
        private $sms;
        private $smsconfig;
        private $oauthModel;
        public function __construct($debug = NULL){
            $this->debug = $debug;
            parent::__construct($this->debug);
            $this->sms = new Sms($this->debug);
            $this->smsconfig = json_decode(file_get_contents("../../config/sms.config.json"), true);
            $this->oauthModel = json_decode(file_get_contents("../../models/oauth.json"), true);
        }

        public function verifyPhone ($profileModel){
            $errors = array();
            $dbRes = $this->tryAccounts($profileModel["mobile"], $profileModel["roleId"]);
            if($dbRes[0] == 1){
                $res = array(
                    "success"=>true,
                    "errors"=>null,
                    "status_code"=>1,
                    "status_message"=>'',
                    "message"=>"Welcome " . $dbRes[2]["firstName"] . ", please enter your password.",
                    "data"=>null
                );
            }else{
                array_push($errors, $dbRes[1]);
                $res = array(
                    "success"=>true,
                    "errors"=>$errors,
                    "status_code"=>2,
                    "status_message"=>'',
                    "message"=>"Seems you are new here. Continue to account setup.",
                    "data"=>null
                );
            }
            return $res;
        }

        public function verifyPassword ($profileModel){
            $errors = array();
            $dbRes = $this->trySignIn($profileModel["uId"], $profileModel["uPassword"]);
            if($dbRes[0] == 1){
                $res = array(
                    "success"=>true,
                    "errors"=>null,
                    "status_code"=>1,
                    "status_message"=>'Succesful.',
                    "message"=>"Welcome " . $dbRes[2]["firstName"] . ", please enter your password.",
                    "data"=>$dbRes[2]
                );
            }else{
                array_push($errors, $dbRes[1]);
                $res = array(
                    "success"=>true,
                    "errors"=>$errors,
                    "status_code"=>0,
                    "status_message"=>'Failed.',
                    "message"=>"Wrong phone number or password.",
                    "data"=>null
                );
            }
            return $res;
        }

        public function verifyEmail ($profileModel){
            $errors = array();
            $dbRes = $this->trySignInGoogle($profileModel["email"], $profileModel["password"]);
            if($dbRes[0] == 1){
                $res = array(
                    "success"=>true,
                    "errors"=>null,
                    "status_code"=>1,
                    "status_message"=>'Succesful.',
                    "message"=>"Welcome " . $dbRes[2]["firstName"] . ", please enter your password.",
                    "data"=>$dbRes[2]
                );
            }else{
                array_push($errors, $dbRes[1]);
                $res = array(
                    "success"=>true,
                    "errors"=>$errors,
                    "status_code"=>2,
                    "status_message"=>'Account not found.',
                    "message"=>"Please proceed to create account.",
                    "data"=>null
                );
            }
            return $res;
        }

        public function requestOTP ($profileModel){
            $errors = array();
            $otpRequestModel = array(
                "mobile"=>$profileModel["mobile"],
                "otpCode"=>$this->utils->generateRandom(11111, 99999, 5),
                "createdAt"=>$this->dates->getDateTimeNow()
            );

            $smsText = $this->smsconfig["otp"]["request"]["text"];
            $smsText = str_replace('{companyName}', 'DawaSwift', $smsText);
            $smsText = str_replace('{code}', $otpRequestModel["otpCode"], $smsText);
            $messageModels = array(
                array(
                    "recipientNumber"=>$otpRequestModel["mobile"],
                    "smsText"=>$smsText
                )
            );
            $curlRes = $this->sms->expressSmss2s($messageModels);
            if($curlRes["status_code"] > 0){
                $dbRes = $this->insert("otprequests", $otpRequestModel);
                if($dbRes[0] == 1){
                    return array(
                        "success"=>true,
                        "errors"=>null,
                        "status_code"=>1,
                        "status_message"=>'Successful',
                        "message"=>"Please key in the verification code sent to your number.",
                        "data"=>null
                    );
                }else{
                    array_push($errors, $dbRes[1]);
                    return array(
                        "success"=>true,
                        "errors"=>$errors,
                        "status_code"=>0,
                        "status_message"=>'Failed.',
                        "message"=>"Seems you are new here. Continue to account setup.",
                        "data"=>null
                    );
                }
            }
            return $curlRes;
        }

        public function verifyOTP ($otpVerifyModel){
            $errors = array();
            $validRes = $this->utils->validateModel($otpVerifyModel, $this->oauthModel["verifyOtpValidModel"]);
            if($validRes["success"]){
                $dbRes = $this->fetchRow("otprequests", array("mobile"=>$otpVerifyModel["mobile"], "otpCode"=>$otpVerifyModel["otpCode"], "statusCode"=>1));
                if($dbRes[0] == 1){
                    $updateModel = array("statusCode"=>2, "statusName"=>'Used');
                    $keyModel = array("mobile"=>$otpVerifyModel["mobile"], "otpCode"=>$otpVerifyModel["otpCode"]);
                    $this->update("otprequests", $updateModel, $keyModel);
                    return array(
                        "success"=>true,
                        "errors"=>null,
                        "status_code"=>1,
                        "status_message"=>'Successful.',
                        "message"=>"OTP confirmed.",
                        "data"=>$this->fetchRow("users", array("mobile"=>$otpVerifyModel["mobile"]))[2]
                    );
                }else{
                    return array(
                        "success"=>true,
                        "errors"=>$errors,
                        "status_code"=>0,
                        "status_message"=>'Failed.',
                        "message"=>"We couldn't match this code.",
                        "data"=>null
                    );
                }
            }
            $errors = $validRes["errors"];
            return array(
                "success"=>false,
                "errors"=>$errors,
                "status_code"=>0,
                "status_message"=>'Failed.',
                "message"=>"There was an error in inputs.",
                "data"=>null
            );
        }
        
        private function tryAccounts ($mobile, $roleId){
            $accountTypes = array("users");
            $dbRes = array();
            foreach($accountTypes as $accountType){
                $dbRes = $this->fetchRow($accountType, array("mobile"=>$mobile, "roleId"=>$roleId));
                if($dbRes[0] == 1){
                    break;
                }
            }
            return $dbRes;
        } 
        private function trySignInGoogle ($email, $password){
            $accountTypes = array("users");
            $dbRes = array();
            foreach($accountTypes as $accountType){
                $dbRes = $this->fetchRow($accountType, array("email"=>$email, "password"=>$this->utils->encryptPassword($password)));
                if($dbRes[0] == 1){
                    break;
                }
            }
            return $dbRes;
        } 

        private function trySignIn ($email, $password){
            $accountTypes = array("users");
            $dbRes = array();
            foreach($accountTypes as $accountType){
                $dbRes = $this->fetchRow($accountType, array("email"=>$email, "password"=>$this->utils->encryptPassword($password)));
                if($dbRes[0] == 1){
                    break;
                }
            }
            return $dbRes;
        }         
    }
?>
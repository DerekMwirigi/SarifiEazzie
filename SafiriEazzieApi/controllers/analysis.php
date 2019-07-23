<?php 
    class Analysis extends DatabaseHandler {
        private $debug;
        public function __construct($debug = NULL){
            $this->debug = $debug;
            parent::__construct($this->debug);
        }

        public function stats (){
            $errors = array();
            return array(
                "success"=>true,
                "errors"=>null,
                "status_code"=>1,
                "status_message"=>"Successful.",
                "message"=>"data present.",
                "data"=>array(
                    "entityCounts"=>array(
                        "drivers"=>$this->fetchItem(null, null, 'SELECT COUNT(*) AS iCount FROM drivers')[2],
                        "owners"=>$this->fetchItem(null, null, 'SELECT COUNT(*) AS iCount FROM owners')[2],
                        "vehicles"=>$this->fetchItem(null, null, 'SELECT COUNT(*) AS iCount FROM vehicles')[2],
                        "users"=>$this->fetchItem(null, null, 'SELECT COUNT(*) AS iCount FROM users')[2]
                    )
                )
            );
        }
        
    }
?>
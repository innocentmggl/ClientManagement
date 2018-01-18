<?php

(defined('BLACKBOARD'))  or die('Access Denied. You are attempting to access a restricted file directly.');

 class User{
     
     
     private $db;
     private $table_name = 'user';

     
     public function __construct(Database $db){
         $this->db = $db;
     }
      
      public function login($first_name, $last_name){
          
          $arrData = array();
          $arrData['first_name'] = $first_name;
          $arrData['last_name']  = $last_name;
        
          $arrUser = $this->db->fetch_single_row($this->table_name, $where = $arrData);
          
          if (!empty($arrUser)){              
              $this->initUserSession($arrUser['id']);
              return true;
          }
          else{
              return false;
          }
      }
      
      /**
       * Initiate user session
       * 
       * @param int $user
       */
      
      private function initUserSession($user_id){
         
          $_SESSION['logged_in']           = true;
          $_SESSION['logged_in_user_id']   = $user_id;
          $_SESSION['logged_in_user_time'] = time();
      }
      
      public function logout(){
          session_destroy();
      }
      
      public static function isLoggedIn(){
          return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true;
      }
      
      /**
       * validate session time 
       */
      public static function validateSession(){          
          //if user is logged in
          if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true){
              //check if session is still active
              if(time() - $_SESSION['logged_in_user_time'] > (SESSION_TIME_OUT_IN_MINUTES * 60)){
                  //destroy session
                  session_destroy();
              }
              else{
                  //revalidate session time
                 $_SESSION['logged_in_user_time'] = time();
              }
          }
      }
      
      /**
       * Get loggen in user id
       */
      public static function getLoggedInUserId(){
          return (isset($_SESSION['logged_in_user_id']) && !empty($_SESSION['logged_in_user_id'])) ? $_SESSION['logged_in_user_id'] : null;
      }
         

      /**
       * create a user 
       * 
       * @param string $fullname
       * @param string $username
       * @param string $password
       * 
       * return boolean
       */
      public function create($first_name, $last_name){
          
          $arrData = array();
          $arrData['first_name'] = $first_name;
          $arrData['last_name']  = $last_name;
          
          //insert user details
          return $this->db->insert($this->table_name, $arrData);
      }
 }
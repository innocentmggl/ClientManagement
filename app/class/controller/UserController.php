<?php
class UserController {
    
    private $user;
    
    public function __construct(User $user){
        $this->user = $user;
    }
    
    /**
     * Create a user 
     * 
     * @param String $firstname
     * @param String $lastname
     * 
     * return mixed
     */
    public function createUser($firstname, $lastname){
        
       //valide 
        if(!empty($firstname) && !empty($lastname)){
          return  $this->user->create($firstname, $lastname);
       }    
        return false;     
    }

    public function verifyLoginDetails($first_name, $last_name){
        
        return $this->user->login($first_name, $last_name);
    }
    
    public function redirectAfterLogin(){
        
        Helper::redirect($url = 'index.php?mod=client&action=list&');
    }

}
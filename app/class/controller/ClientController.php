<?php
class ClientController {
    
    private $client;
    
    public function __construct(Client $client){
        $this->client = $client;
    }
    
    public function create($client_name){
       
        return $this->client->create($client_name, User::getLoggedInUserId()); 
    }
    
    public function read($id){
       return $this->client->read($id);
    }
    
    public function update($client_name, $id){
        
        return $this->client->update($client_name, $id);
        
    }
    
    public function delete($id){
        return $this->client->delete($id);
    }
    
    public function all( $start_position = 0 ){        
        $this->client->all($start_position);        
    }

}
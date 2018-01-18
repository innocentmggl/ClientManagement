<?php

(defined('BLACKBOARD'))  or die('Access Denied. You are attempting to access a restricted file directly.');

class Client {
    
    private $table_name = 'client';
    private $records_per_page = 3;
    private $db;
    
    public function __construct(Database $db){
        $this->db = $db;
    }
    
    /**
     * Create a client
     * 
     * @param string $client_name
     * @param int $created_id
     * @return boolean
     */
    public function create($client_name, $created_id){
        
         $arrData                       = array();
         $arrData['name']               = $client_name;
         $arrData['created_user_id']    = $created_id;
         
         return $this->db->insert($this->table_name, $arrData);         
    }
    
    public function read($id){
        return $this->db->fetchById($this->table_name, $id);
    }
    
    public function update($client_name, $id){
        
        return $this->db->update( $this->table_name, $data = array( 'name' => $client_name, 'updated_at' => date('y-m-d h:i:s')), $id);
    }
    
    public function delete($id){
        return $this->db->update( $this->table_name, $data = array('deleted_at' => date('y-m-d h:i:s')), $id);   
    }
    
    public function getLastInsertId(){
        return $this->db->getLastInsertId();
    }
    
    public function getTotalClients(){
       return $this->db->count($this->table_name);        
    }
    
    public function all($start_position = 0){       
       return  $this->db->fetchAll($table_name = $this->table_name, $this->records_per_page, $start_position);
      
    }

}
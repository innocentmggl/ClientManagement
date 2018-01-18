<?php

(defined('BLACKBOARD'))  or die('Access Denied. You are attempting to access a restricted file directly.');

 class Database{
     
     private $pdo;
     
     private $host    = "localhost";
     private $db_user = "root";
     private $db_pass = "r00tDB";
     private $db_name = "blackboard";
     
     
     
     public function __construct()
     {
         try
         {
             $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->db_name}",$this->db_user,$this->db_pass);
             //throw sql errors
             $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
             
         } catch (PDOException $e)
         {
             throw new Exception("We having trouble connecting to database". $e->getMessage());
         }
     }

     
     
     /**
      * Insert data to table
      * @param  string $table table name
      * @param  array $data   associative array 'column_name'=>'val'
      */
     public function insert( $table_name, $data = array() ) {
         
         //@todo fix duplicate value          
         if(empty($data)) {
             throw new InvalidArgumentException('Cannot insert an empty array.');
         }
         
         if(!is_string($table_name)) {
             throw new InvalidArgumentException('Table name must be a string.');
         }
         
         $fields       = '`' . implode('`, `', array_keys($data)) . '`';
         $placeholders = ':' . implode(', :', array_keys($data));         
         
         $sql = "INSERT INTO `{$table_name}` ($fields) VALUES ({$placeholders})";      
         
         // Prepare new statement
         $statement = $this->pdo->prepare($sql);
                           
         return $statement->execute($data);
     }
         
     public function fetchAll($table_name, $records_per_page, $starting_position = 0){
         
         try {
             
             if($starting_position > 1)
             {
                 $starting_position= ($starting_position - 1 ) * $records_per_page;
             }
             $query = "SELECT core.*, usr.first_name, usr.last_name FROM {$table_name} as core INNER JOIN user as usr ON (core.created_user_id = usr.id) limit {$starting_position},{$records_per_page}";
             
             $statement = $this->pdo->prepare($query);
             $statement->execute();
             
             return $statement->fetchAll();
         }
         catch (Exception $e){
             throw new Exception($e->getMessage());
         }
     }
     
     public function fetchById($table_name, $id){
         
         try {
             $query = "SELECT core.*, usr.first_name, usr.last_name FROM {$table_name} as core INNER JOIN user as usr ON (core.created_user_id = usr.id) WHERE core.id = :id limit 1";
             
             $statement = $this->pdo->prepare($query);
             $statement->bindParam(':id', $id);
             $statement->execute();
             
             $arrResutls = $statement->fetchAll();
             
             if(!empty($arrResutls)){
                 
                 return $arrResutls[0];
             }
            return array();
         }
         catch (Exception $e){
             throw new Exception($e->getMessage());
         }
     }
     
     /**
      * Count number of records in table
      * 
      * @param string $table_name
      * @param array $where - asssociate array 'colum_name' => value
      * @return int counter
      */    
     public function count($table_name, $where = array()){
         
         //if where clause is supplied
         if(!empty($where)){
             
             $strWhere = '';
             
             foreach ($where as $column => $value){
                 
                 $strWhere .= empty($strWhere) ? "`{$column}` = '{$value}'" : " AND `{$column}` = '{$value}'";
             }
                         
             $statement    = $this->pdo->prepare("SELECT COUNT(*) FROM {$table_name} WHERE {$strWhere}");      
             
             $statement->execute();
         }
         else{
             
             $statement    = $this->pdo->prepare("SELECT COUNT(*) FROM {$table_name}");
             $statement->execute();
         }

         return  $statement->fetchColumn();
     }
     
     public function fetch_single_row($table_name, $where)
     {
         //if where clause is supplied
         if(!empty($where)){
             
             $strWhere = '';
             
             foreach ($where as $column => $value){
                 
                 $strWhere .= empty($strWhere) ? "`{$column}` = '{$value}'" : " AND `{$column}` = '{$value}'";
             }
             
             $statement = $this->pdo->prepare("SELECT * FROM {$table_name} WHERE {$strWhere} limit 1");
             
             $statement->execute();
             
             $arrResutls = $statement->fetchAll();
             
             if(!empty($arrResutls)){
                 
                 return $arrResutls[0];
             }
         }
         
         return array();
     }

     /**
      * update a certain table
      * 
      * @param string $table_name
      * @param array $data
      * @param int $id
      * @return boolean
      */
     public function update($table_name, $data, $id) {
         
         if(!empty($data)){
             
             $strUpdate = '';
             foreach ($data as $column => $value){
                 
                 $strUpdate .= empty($strUpdate) ? "`{$column}` = '{$value}'" : ", `{$column}` = '{$value}'";
             }
             
             $sql = "UPDATE `{$table_name}` SET $strUpdate WHERE `id`=:id";
             
             $statement = $this->pdo->prepare($sql);
             
             $statement->bindParam(':id', $id);
             
             return $statement->execute();
         }
         
         return false;
         
     }
     
     public function getLastInsertId(){
         return $this->pdo->lastInsertId();
     }
         
     public function __destruct() {
         $this->pdo = null;
     }
 }
 ?>
 
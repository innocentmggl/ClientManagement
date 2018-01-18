<?php

(defined('BLACKBOARD'))  or die('Access Denied. You are attempting to access a restricted file directly.');

//start session
session_start();

//require atoaloder class
require_once 'app/class/Autoloader.php';

spl_autoload_register('Autoloader::loader');

//validate setion
User::validateSession();

//security token
if (empty($_SESSION['token'])) {
    //check if native function exists
    if (function_exists('mcrypt_create_iv')) {
        $_SESSION['token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
    } else {
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
    $_SESSION['token_time'] = time();
}
$token = $_SESSION['token'];



//if there is a module get param
if (isset($_GET['mod']) && !empty($_GET['mod'])){
           
    //format class name
    $entity = trim(preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['mod']));
  
    //check entity
    switch ($entity){
        
        case 'client':
            
            //if user is logged in
            if(User::isLoggedIn()){
                
                $model      = new Client(new Database());
                $controller = new ClientController($model);
                $view       = new ClientView($model, $controller);
                
                $action = (isset($_GET['action']) && !empty($_GET['action'])) ? trim(preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['action'])) : null;
                
                if ( $action ) {
                    
                    switch ( $action ){
                        
                        case 'list':
                            $page = (isset($_GET['page_no']) && !empty($_GET['page_no'])) ? trim(preg_replace('/[^Z0-9_]/', '', $_GET['page_no'])) : 0;
                            $page == 1 ? $page = 0 : null; 
                            
                            $view->listClients($page);
                        break;
                        
                        case 'add':
                            $view->createClient();
                        break;
                        
                        case 'view':
                            $view->readClient();
                         break;
                         
                        case 'edit':
                            $view->updateClient();
                        break;
                        
                        case 'delete':
                            $view->deleteClient();
                        break;
                            
                        default:    
                            
                            $title    = "Operation not found";
                            $message  = "Operation for {$_GET['action']} was not found!";
                            include 'app/class/view/event/error.php';
                    }
                }
                else{
                    $title    = "Operation not found";
                    $message  = "No operation specified!";
                    include 'app/class/view/event/error.php';
                }
            }
            //else open login form
            else{
                Helper::redirect($url = 'index.php?mod=user&action=login&');
            }
            break;
            
        case 'user':
            
            $model      = new User(new Database());
            $controller = new UserController($model);
            $view       = new UserView($model, $controller);

            $action = (isset($_GET['action']) && !empty($_GET['action'])) ? trim(preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['action'])) : null;
            
            if ( $action ) {
                
                switch ( $action ){
                                            
                    case 'add':
                        $view->createUser();
                        break;
                        
                    case 'login':
                        $view->login();
                        break;
                                             
                    default:
                        
                        $title    = "Operation not found";
                        $message  = "Operation for {$_GET['action']} was not found!";
                        include 'app/class/view/event/error.php';
                }
            }
            else{
                $title    = "Operation not found";
                $message  = "No operation specified!";
                include 'app/class/view/event/error.php';
            }
            break;
            
        default:
        //show not found message
            $title   = "Page not found";
            $message = "Page for operation ".$_GET['mod']." was not found!";
            include 'app/class/view/event/error.php';
            break;
    }
}
//redirect to home page
else {
    require 'public/home.php';
}
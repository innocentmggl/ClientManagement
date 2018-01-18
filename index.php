<?php 
define('BLACKBOARD', true);
define('RECORDS_PER_PAGE', 3);
define('SESSION_TIME_OUT_IN_MINUTES', 5);


//if there is a module get param
if (isset($_GET['mod']) && !empty($_GET['mod'])){
    
    if (in_array($_GET['mod'], array('user','client'))){
         
        $pageTitle = ucfirst($_GET['mod']);
        
        if (isset($_GET['action']) && !empty($_GET['action'])){
            
            if (in_array($_GET['action'], array('add','edit','view','list','delete','login'))){
                $pageTitle .= ' '. $_GET['action'];
            }           
        }
    }
}
else{
    //setup page title jumbotron
    $jumbotronTextAlign = 'text-right';   
}

require 'public/header.php';

require 'app/class/bootstrap.php';

require 'public/footer.php';

?>
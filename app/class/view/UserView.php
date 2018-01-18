<?php
 class UserView {
     
     private $user;
     private $userController;
     
     public function __construct(User $user, UserController $controller){
         $this->user            = $user;
         $this->userController  = $controller;
     }
     
     
     public function login(){
         
         //check if its post
         if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-login']))
         {
             
             $arrErrors = array();
             
             if (!empty($_POST['token'])) {
                 
                 if (Helper::hash_equals($_SESSION['token'], $_POST['token'])) {
                     
                     
                     $firstName = strip_tags( trim( $_POST['first_name'] ) );
                     $lastName  = strip_tags( trim( $_POST['last_name'] ) );
                     
                     if(empty($firstName)){
                         $arrErrors[] = 'Please supply first name';
                     }
                     
                     if(empty($lastName)){
                         $arrErrors[] = 'Please supply last name';
                     }
                     
                     if(empty($arrErrors)){
                         
                         if($this->userController->verifyLoginDetails($firstName, $lastName))
                         {
                           //redirect to restricted space
                           $this->userController->redirectAfterLogin();
                         }
                         else
                         {
                           $arrErrors[] = 'Invalid login details';
                         }
                   }
                   // Proceed to process the form data
               } else {
                   // Log this as a warning and keep an eye on these attempts
                   $arrErrors[] = 'Could not validate form data';
               }
           }
           //form token not supplied or empty
           else{
               $arrErrors[] = 'Request not authorised, please retry';
           }
           
           //check and display errors
           if(!empty($arrErrors)){
               ?>
                    <div class="container">
                		<div class="alert alert-warning">
                    		<strong>Error! </strong><br/>
                    		<?php print implode('<br/>', $arrErrors)?>
                		</div>
                	</div>
              <?php
           }
       }
       ?>

        <div class="container">
        
        	<form method='post'>
            <table class='table table-bordered'>
         
                <tr>
                    <td>First Name</td>
                    <td><input type='text' name='first_name' class='form-control' required></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><input type='text' name='last_name' class='form-control' required></td>
                </tr>
        
                <tr>
                    <td colspan="2">
                    <button type="submit" class="btn btn-primary" name="btn-login">
            		<span class="glyphicon glyphicon-plus"></span> Login
        			</button>  
                    <a href="index.php?mod=user&action=add" class="btn btn-large btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; Sign Up</a>
                    </td>
                </tr>
         
            </table>
            <input type="hidden" name="token" value="<?php echo $_SESSION['token']?>">
        </form>     
        </div>
         <?php 
     }
     
     public function createUser(){          
             
       //check if its post
       if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-save']))
       {
           
           $arrErrors = array();
           
           if (!empty($_POST['token'])) {
               
               if (Helper::hash_equals($_SESSION['token'], $_POST['token'])) {
                   
                   
                   $firstName = strip_tags( trim( $_POST['first_name'] ) );
                   $lastName  = strip_tags( trim( $_POST['last_name'] ) );
                   
                   if(empty($firstName)){
                       $arrErrors[] = 'Please supply first name';
                   }
                   
                   if(empty($lastName)){
                       $arrErrors[] = 'Please supply last name';
                   }
                   
                   if(empty($arrErrors)){

                       if($this->userController->createUser($firstName, $lastName))
                       {
                           ?>
                            <div class="container">
                        	  <div class="alert alert-info">
                               <strong>WOW!</strong> User details inserted successfully!
                        	  </div>
                        	</div>
        					<?php
                       }
                       else
                       {
                           $arrErrors[] = 'Could not insert user details';
                       }
                   }
                   // Proceed to process the form data
               } else {
                   // Log this as a warning and keep an eye on these attempts
                   $arrErrors[] = 'Could not validate form data';
               }
           }
           //form token not supplied or empty
           else{
               $arrErrors[] = 'Request not authorised, please retry';
           }
           
           //check and display errors
           if(!empty($arrErrors)){
               ?>
                    <div class="container">
                		<div class="alert alert-warning">
                    		<strong>Error! </strong><br/>
                    		<?php print implode('<br/>', $arrErrors)?>
                		</div>
                	</div>
              <?php
           }
       }
    ?>
    
    
    <div class="clearfix"></div><br />
    
    <div class="container">
    
     	
    	<form method='post'>
        <table class='table table-bordered'>
     
            <tr>
                <td>First Name</td>
                <td><input type='text' name='first_name' class='form-control' required></td>
            </tr>
            <tr>
                <td>Last Name</td>
                <td><input type='text' name='last_name' class='form-control' required></td>
            </tr>
    
            <tr>
                <td colspan="2">
                <button type="submit" class="btn btn-primary" name="btn-save">
        		<span class="glyphicon glyphicon-plus"></span> Create User Record
    			</button>  
                <a href="index.php?mod=user&action=login" class="btn btn-large btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; Login</a>
                </td>
            </tr>
     
        </table>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token']?>">
    </form>     
    </div>
    <?php 
    }
 }
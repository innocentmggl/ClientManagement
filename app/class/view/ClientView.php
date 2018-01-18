<?php
class ClientView {
    
    private $client;
    private $clientController;
   
    public function __construct(Client $client, ClientController $controller){
        $this->client            = $client;
        $this->clientController = $controller;
    }
    
    
    public function createClient(){
       
        $bolSuccess = false;
       //check if its post
       if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-save']))
       {
           $arrErrors = array();
           
           if (!empty($_POST['token'])) {
               
               if (Helper::hash_equals($_SESSION['token'], $_POST['token'])) {

                   $clientName = strip_tags( trim( $_POST['client_name'] ) );
                   
                   if(empty($clientName)){
                       
                       $arrErrors[] = 'Please supply client name !';
                   }
                   elseif (strlen($clientName) > 50){
                       
                       $arrErrors[] = 'Client name too long, maximum is 50 characters!';
                   }                       
                   else{
                       
                       if($this->clientController->create($clientName))
                       {
                           $bolSuccess = true
                           ?>
                            <div class="container">
                        	<div class="alert alert-info">
                            <strong>WOW!</strong> Record was inserted successfully <a href="index.php?mod=client&action=view&id=<?php print $this->client->getLastInsertId()?>">View</a>!
                        	</div>
                        	</div>
        					<?php
        					
        					$this->listClients($start_position = 0);
                       }
                       else
                       {
                           $arrErrors[] = 'Database error could not insert record !';
                       }
                   }
                   // Proceed to process the form data
               } else {
                   // Log this as a warning and keep an eye on these attempts
                   $arrErrors[] = 'Could not validate form data, please try again !';
               }
           }
           //form token not supplied or empty
           else{
               $arrErrors[] = 'Could not validate form data, please try again !';
           }
           //check and display errors
           if(!empty($arrErrors)){
               ?>
                    <div class="container">
                		<div class="alert alert-warning">
                    		<strong>Error! </strong><?php print implode('<br/>', $arrErrors)?>
                		</div>
                	</div>
              <?php
           }
       }
    
    
    if(!$bolSuccess)
     {
     ?>
        <div class="clearfix"></div><br />
        
        <div class="container">
        
         	
        	<form method='post'>
            <table class='table table-bordered'>
         
                <tr>
                    <td>Client Name</td>
                    <td><input type='text' name='client_name' class='form-control' max-lenght="20" required></td>
                </tr>
        
                <tr>
                    <td colspan="2">
                    <button type="submit" class="btn btn-primary" name="btn-save">
            		<span class="glyphicon glyphicon-plus"></span> Create Client Record
        			</button>  
                      <a href="index.php?mod=client&action=list" class="btn btn-large btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; Back to Clients</a>
                    </td>
                </tr>
         
            </table>
            <input type="hidden" name="token" value="<?php echo $_SESSION['token']?>">
        </form>     
        </div>
        <?php 
      }
    }
    
    /**
     * 
     */
    public function readClient(){
       
        if(isset($_GET['id']) && is_numeric(trim($_GET['id']))){
            
            $arrDetails =  $this->clientController->read(trim($_GET['id']));
            
            if(!empty($arrDetails)){
                $disabled = $arrDetails['deleted_at'] ? 'disabled' : '';
                ?>
                <div class="container">
            
                    <table class='table table-bordered'>
                   
                        <tr>
                            <td><strong>Name</strong></td>
                            <td><?php print $arrDetails['name'];?></td>
                        </tr>
                        
                        <tr>
                            <td><strong>Creator</strong></td>
                            <td><?php  print($arrDetails['first_name']. ' '. $arrDetails['last_name']);?></td>
                        </tr>
                        
                        <tr>
                            <td><strong>Created</strong></td>
                            <td><?php print $arrDetails['created_at']?></td>
                        </tr>
                        
                        <tr>
                            <td><strong>Last Updated</strong></td>
                            <td><?php print ($arrDetails['updated_at'] ? $arrDetails['updated_at'] : 'N/A');?></td>
                        </tr>
                        
                        <tr>
                            <td><strong>Status</strong></td>
                            <td><?php print($arrDetails['deleted_at'] ? 'Inactive' : 'Active'); ?></td>
                        </tr>
                        
                        <tr>
                        <td colspan="2">
                        <a href="index.php?mod=client&action=edit&id=<?php print $arrDetails['id']?>" class="btn btn-large btn-warning <?php print $disabled ?>"><i class="glyphicon glyphicon-edit"></i> &nbsp; Edit</a>
                        <a href="index.php?mod=client&action=delete&id=<?php print $arrDetails['id']?>" class="btn btn-large btn-danger <?php print $disabled ?>"><i class="glyphicon glyphicon-remove-circle"></i> &nbsp; Delete</a>
                        </td>
                        </tr>
                        
                    </table>
                </div>
              <?php 
            }
        }
        else{
            ?>
                    <div class="container">
                		<div class="alert alert-warning">
                    		<strong>Error! </strong> Invalid client id !
                		</div>
                	</div>
              <?php
            return;
        }
       
        
    }
    
    public function deleteClient(){
        
        if(isset($_GET['id']) && is_numeric(trim($_GET['id']))){
            
            $arrDetails =  $this->clientController->read(trim($_GET['id']));
            
            if(!empty($arrDetails)){

                $bolSuccess = false;
                //check if its post
                if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-delete']))
                {
                    $arrErrors = array();
                    
                    if (!empty($_POST['token'])) {
                        
                        if (Helper::hash_equals($_SESSION['token'], $_POST['token'])) {

                            if($this->clientController->delete($arrDetails['id']))
                                {
                                    $bolSuccess = true
                                    ?>
                                        <div class="container">
                                    	<div class="alert alert-info">
                                        <strong>Notice!</strong> Record was deleted successfully <a href="index.php?mod=client&action=view&id=<?php print $arrDetails['id']?>">View</a>!
                                    	</div>
                                    	</div>
                					<?php
        					
        					$this->listClients($start_position = 0);
                       }
                       else
                       {
                           $arrErrors[] = 'Database error could not insert record !';
                         }
                       
                       // Proceed to process the form data
                           } else {
                               // Log this as a warning and keep an eye on these attempts
                               $arrErrors[] = 'Could not validate form data, please try again !';
                           }
                       }
                       //form token not supplied or empty
                       else{
                           $arrErrors[] = 'Could not validate form data, please try again !';
                       }
                       //check and display errors
                       if(!empty($arrErrors)){
                           ?>
                                <div class="container">
                            		<div class="alert alert-warning">
                                		<strong>Error! </strong><?php print implode('<br/>', $arrErrors)?>
                            		</div>
                            	</div>
                          <?php
                       }
                   }
                if (!$bolSuccess){
                ?>
                <div class="container">
                   <form method='post'>
                    <table class='table table-bordered'>
                   
                        <tr>
                            <td><strong>Name</strong></td>
                            <td><?php print $arrDetails['name'];?></td>
                        </tr>
                        
                        <tr>
                            <td><strong>Creator</strong></td>
                            <td><?php  print($arrDetails['first_name']. ' '. $arrDetails['last_name']);?></td>
                        </tr>
                        
                        <tr>
                            <td><strong>Created</strong></td>
                            <td><?php print $arrDetails['created_at']?></td>
                        </tr>
                        
                        <tr>
                            <td><strong>Last Updated</strong></td>
                            <td><?php print ($arrDetails['updated_at'] ? $arrDetails['updated_at'] : 'N/A');?></td>
                        </tr>
                        
                        <tr>
                            <td><strong>Status</strong></td>
                            <td><?php print($arrDetails['deleted_at'] ? 'Inactive' : 'Active'); ?></td>
                        </tr>
                        
                        <tr>
                        <td colspan="2">
                        <button type="submit" class="btn btn-large btn-danger" name="btn-delete">
                		 <span class="glyphicon glyphicon-remove-circle"></span> Confirm Delete
            			</button>  
                        <a href="index.php?mod=client&action=list" class="btn btn-large btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; Back to Clients</a>                   
                        </td>
                        </tr>
                        
                    </table>
                    <input type="hidden" name="token" value="<?php echo $_SESSION['token']?>">
                    </form>
                </div>
              <?php 
                }
            }
        }
        else{
            ?>
                    <div class="container">
                		<div class="alert alert-warning">
                    		<strong>Error! </strong> Invalid client id !
                		</div>
                	</div>
              <?php
            return;
        }
       
    }
    
    
    public function updateClient(){
        
        $bolSucess = false;
        
        if(isset($_GET['id']) && is_numeric(trim($_GET['id']))){
            
            $arrDetails =  $this->clientController->read(trim($_GET['id']));
            
            if(!empty($arrDetails)){

            //check if its post
            if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-update']))
            {
                $arrErrors = array();
                                
                if (!empty($_POST['token'])) {
                    
                    if (Helper::hash_equals($_SESSION['token'], $_POST['token'])) {
                        
                        
                        $clientName = strip_tags( trim( $_POST['client_name'] ) );
                        
                        if(empty($clientName)){
                            
                            $arrErrors[] = 'Please supply client name !';
                        }
                        elseif (strlen($clientName) > 50){
                            
                            $arrErrors[] = 'Client name too long, maximum is 50 characters!';
                        }
                        else{
                            
                            if($this->clientController->update($clientName, $arrDetails['id']))
                            {
                                $bolSucess = true;
                                ?>
                                    <div class="container">
                                		<div class="alert alert-success">
                                    		<strong>Success! </strong> Record was updated successfully <a href="index.php?mod=client&action=view&id=<?php print $arrDetails['id'] ?>">View</a>!
                                		</div>
                                	</div>
                                <?php
                                $this->listClients(0);
                           }
                           else
                           {
                               $arrErrors[] = 'Error could not update record !';
                           }
                       }
                       // Proceed to process the form data
                   } else {
                       // Log this as a warning and keep an eye on these attempts
                       $arrErrors[] = 'Could not validate form data, please try again !';
                   }
               }
               //form token not supplied or empty
               else{
                   $arrErrors[] = 'Could not validate form data, please try again !';
               }
               //check and display errors
               if(!empty($arrErrors)){
                   ?>
                        <div class="container">
                    		<div class="alert alert-danger">
                        		<strong>Error! </strong><?php print implode('<br/>', $arrErrors)?>
                    		</div>
                    	</div>
                  <?php
               }
           }
        }
        //errror retrieving record
        else{
            
        }
        if(!$bolSucess){
            ?>
            <div class="clearfix"></div><br />
    
            <div class="container">
                 	
            	<form method='post'>
                <table class='table table-bordered'>
             
                    <tr>
                        <td>Client Name</td>
                        <td><input type='text' name='client_name' class='form-control' value="<?php print $arrDetails['name'] ?>" required></td>
                    </tr>
            
                    <tr>
                        <td colspan="2">
                        <button type="submit" class="btn btn-primary" name="btn-update">
                		<span class="glyphicon glyphicon-plus"></span> Submit
            			</button>  
                          <a href="index.php?mod=client&action=list" class="btn btn-large btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; Back to Clients</a>
                        </td>
                    </tr>
             
                </table>
                <input type="hidden" name="token" value="<?php echo $_SESSION['token']?>">
            </form>     
            </div>                   
            <?php 
        }
      }
      //no id
      else{
            ?>
            <div class="container">
        		<div class="alert alert-danger">
            		<strong>Error! </strong> Invalid client id !
        		</div>
        	</div>
            <?php
            return;
      }
    }
    
    
    
    
    
    public function listClients($start_position = 0){
        ?>
        <div class="clearfix"></div>
        
        <div class="container">
          <a href="index.php?mod=client&action=add&" class="btn btn-large btn-info"><i class="glyphicon glyphicon-plus"></i> &nbsp; Add Client</a>
        </div>
        
        <div class="clearfix"></div><br />
        <div class="container">
        <div class="table-responsive">
        <table class='table table-bordered'>
        <tr>
        <th>#</th>
        <th>Name</th>
        <th>Creator</th>
        <th>Created</th>
        <th>Status</th>
        <th colspan="3" align="center">Actions</th>
        </tr>
        <?php
        
        $clients = $this->client->all($start_position);
        
        if(count($clients) > 0)
        {
            foreach( $clients as $row )
            {
                $disabled = $row['deleted_at'] ? 'disabled' : '';
                ?>
                <tr>
                <td><?php print($row['id']); ?></td>
                <td><?php print($row['name']); ?></td>
                <td><?php print($row['first_name']. ' '. $row['last_name']);?></td>
                <td><?php print($row['created_at']); ?></td>
                <td><?php print($row['deleted_at'] ? 'Inactive' : 'Active'); ?></td>
                
                
                <td align="center">
                  <a href="index.php?mod=client&action=view&id=<?php print($row['id']); ?>" class="btn btn-info btn-sm"><i class="glyphicon glyphicon-eye-open"></i> &nbsp;View</a>
                  <a href="index.php?mod=client&action=edit&id=<?php print($row['id']); ?>" class="btn btn-warning  btn-sm <?php print $disabled;?>"><i class="glyphicon glyphicon-edit"></i> &nbsp; Edit</a>
                  <a href="index.php?mod=client&action=delete&id=<?php print($row['id']); ?>" class="btn btn-danger  btn-sm <?php print $disabled;?>"><i class="glyphicon glyphicon-remove-circle"></i> &nbsp; Delete</a>
              
                </td>
                </tr>
                <?php
			}
		}
		else
		{
			?>
            <tr>
            <td>No clients found...</td>
            </tr>
            <?php
		}
        ?>
    <tr>
        <td colspan="8" align="center">
 			<div class="pagination-wrap">
            <?php    
                $self = 'index.php?mod=client&action=list&';
                
                $totalClients = $this->client->getTotalClients();
          
                if($totalClients > 0)
                {
                   ?><ul class ="pagination"><?php
                   
                   $total_no_of_pages = ceil($totalClients / RECORDS_PER_PAGE);
                   
        			$current_page = 1;
        			
        			if(isset($_GET["page_no"]))
        			{
        				$current_page = $_GET["page_no"];
        			}
        			
        			if($current_page != 1)
        			{
        				$previous = $current_page - 1;
        				echo "<li><a href='".$self."page_no=1'>First</a></li>";
        				echo "<li><a href='".$self."page_no=".$previous."'>Previous</a></li>";
        			}
        			
        			for($i=1; $i<=$total_no_of_pages; $i++)
        			{
        				if($i == $current_page)
        				{
        					echo "<li><a href='".$self."page_no=".$i."' style='color:red;'>".$i."</a></li>";
        				}
        				else
        				{
        					echo "<li><a href='".$self."page_no=".$i."'>".$i."</a></li>";
        				}
        			}
        			
        			if($current_page != $total_no_of_pages)
        			{
        				$next = $current_page+1;
        				echo "<li><a href='".$self."page_no=".$next."'>Next</a></li>";
        				echo "<li><a href='".$self."page_no=".$total_no_of_pages."'>Last</a></li>";
        			}
        			?></ul><?php
    		  }

            ?>
        	</div>
        </td>
    </tr>
</table>
</div>
</div>
<?php
    }
}
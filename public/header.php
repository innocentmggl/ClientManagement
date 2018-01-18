<!DOCTYPE html>
<html lang="en">
<head>
  <title>Blackoard</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="public/css/blackboard.css">
</head>
<body>
<div class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <strong><a class="navbar-brand" href="index.php?home">Blackboard</a></strong>
            <a class="navbar-brand" href="index.php?home">Home</a>
            <a class="navbar-brand" href="index.php?mod=client&action=list">Clients</a>
        </div>
        
    </div>
   
</div>
<div class="container">
     <div class="jumbotron <?php isset($jumbotronTextAlign) ? print $jumbotronTextAlign : print 'text-center'?>">
      	<p><?php isset($pageTitle) ? print $pageTitle : print 'We specialize in user & client management'?></p>
     </div>
</div>
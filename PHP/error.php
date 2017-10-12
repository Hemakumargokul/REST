<!DOCTYPE html>
<!-- Student Name:   -->
<html lang="en">
  <head>
    <title>Letter</title>
    <meta charset="utf-8"/>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <script type="text/javascript" src="jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
      
        
    
  </head>

  <body >
       
    <form class="form-horizontal" id="registerForm" action="sendLetter.php" method="post">

    <div id="legend">
      <legend class="">Error Details</legend>
    </div>
    
    <div class="alert alert-danger hide validationMsg">
    </div>
    </div>
       <div class="form-group row">
      
        <div class="col-xs-5">
            <?php echo "<b><center>".$_GET['error']."</center></b>"; ?>
        </div>
    </div>
     
</form>
    <div id="output">&nbsp;</div>
  </body>
</html>
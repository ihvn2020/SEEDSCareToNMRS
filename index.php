<?php require 'controllers/mainController.php';  ?>
<!DOCTYPE html>
<html lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Seedscare TO NMRS Migration</title>

        <!-- Bootstrap CSS -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.3/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
    
       <nav class="navbar navbar-expand-md navbar-light bg-light">
           <a class="navbar-brand" href="#">SEEDCARE2NMRS</a>
           <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#collapsibleNavId" aria-controls="collapsibleNavId"
               aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
           </button>
           <div class="collapse navbar-collapse" id="collapsibleNavId">
               <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                   <li class="nav-item active">
                       <a class="nav-link" href="#">MIGRATE <span class="sr-only">(current)</span></a>
                   </li>
                   <li class="nav-item">
                       <a class="nav-link" href="#">SUPPORT</a>
                   </li>
                   <li class="nav-item dropdown">
                       <a class="nav-link dropdown-toggle" href="#" id="dropdownId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">HOW TO</a>
                       <div class="dropdown-menu" aria-labelledby="dropdownId">
                           <a class="dropdown-item" href="#">SET UP THE MIGRATOR</a>
                           <a class="dropdown-item" href="#">MIGRATE TO NMRS</a>
                       </div>
                   </li>
               </ul>
               <form class="form-inline my-2 my-lg-0">
                   <input class="form-control mr-sm-2" type="text" placeholder="Search">
                   <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
               </form>
           </div>
       </nav>
      
       <div class="container"> 
       <h5>Setup Migration</h5>
       <hr>
       <div class="row">
           <div class="col-md-6">
           <div class="card">
               <div class="card-body">
               <br>
                   <h5 class="card-title">SETUP NMRS CONNECTION</h5><hr>
                   <p class="card-text">Connection Settings</p>
              
                   <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" id="nmrsConnectForm">
                    
                   <div class="row">
                       <div class="col-md-6">
                            <div class="form-group">
                                <label for="servername">Server Name</label>
                                <input id="servername" class="form-control" type="text" name="servername" value="localhost">
                            </div>
                       </div>
                       <div class="col-md-6">
                            <div class="form-group">
                                <label for="database">Database Name</label>
                                <input id="database" class="form-control" type="text" name="database" value="openmrs">
                            </div>
                       </div>
                       <div class="col-md-6">
                            <div class="form-group">
                                <label for="username">Enter Username</label>
                                <input id="username" class="form-control" type="text" name="username" value="openmrs">
                            </div>
                       </div>
                       <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Enter Password</label>
                                <input id="password" class="form-control" type="text" name="password" value="ck9RdGyz&jXR">
                            </div>
                       </div>
                       <div class="col-md-6">
                            <div class="form-group">
                                <label for="port_no">MySQL Port</label>
                                <input id="port_no" class="form-control" type="number" name="port_no" value="3316">
                            </div>
                       </div>
                       <div class="col-md-6">
                            <div class="form-group">
                            <label for="connect"><small>Click &darr; Here to Connect</small></label>
                                <input name="connect" id="connect" type="submit" class="btn btn-primary" value='Connect to NMRS'>
                            </div>
                       </div>
                   </div>
                   <?php // Ignore please // echo bin2hex(random_bytes(6)); ?>
                   </form>
               </div>
           </div>
           </div>

           <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <?php 
                        // Check whether user clicked the connect button
                        if(isset($_POST['connect'])){?>
                        <h5 class="card-title">Select CSV File to Upload</h5>
                        <p class="card-text">
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                            <?php    
                            // Create a new Instance of the main Class seecareToNMRS                        
                            $checkDB = new seedcareToNMRS();
                            // Call the database checking function
                            $checkDB->checkConnection(); 
                            
                            ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="data_category">Select Data Category</label>
                                            <select name="data_category" id="data_category" class="form-control">
                                                <option value="Demographics">Demographics Data</option>
                                                <option value="Clinical">Clinical Data</option>
                                                <option value="Users">Users Data</option>
                                                <option value="Lab">Laboratory Data</option>
                                                <option value="All">All Data</option>
                                            </select>
                                        </div>                            
                                    </div>
                                </div>

                                <div class="input-row">
                                    <div class="form-group">
                                        <label class="col-md-12 control-label">Upload CSV File</label>
                                        <input type="file" name="file" id="file" accept=".csv">
                                    </div>
                                </div>

                                <div class="input-row">
                                    <div class="form-group">
                                        <label class="col-md-12 control-label"></label>
                                        <input type="submit" class="btn btn-primary float-right" name="MigrateData" value="Migrate to NMRS">
                                    </div>
                                </div>
                            </form>
                        </p>
                        <?php 
                            // Check whether user clicked the connect button    
                            } else if(isset($_POST['MigrateData'])){ 
                                
                                // Create Instance of the mainClass
                                $uploadCSV = new seedcareToNMRS();

                                // Call the uploadCSV method/function
                                $uploadCSV->uploadCSV(); 
                            }else{
                        ?>
                            
                            <div class="alert alert-info">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <strong>Attention!</strong> Setup NMRS Server connection and click on Connect.
                            </div>
                            
                        <?php } ?>
                    </div>
                </div>
           </div>
       </div>
           
       </div>
        

        <!-- jQuery -->
        <script src="assets/js/jquery-3.4.1.min.js"></script>
        <!-- Bootstrap JavaScript -->
        <script src="assets/js/bootstrap.min.js"></script>
    </body>
</html>

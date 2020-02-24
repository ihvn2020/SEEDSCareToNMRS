<php // require 'controllers/seedcareToNMRS.php'; ?>
<!DOCTYPE html>
<html lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Title Page</title>

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
                   <form action="" method="post">

                   <div class="row">
                       <div class="col-md-6">
                            <div class="form-group">
                                <label for="servername">Server Name</label>
                                <input id="servername" class="form-control" type="text" name="servername" value="localhost">
                            </div>
                       </div>
                       <div class="col-md-6">
                            <div class="form-group">
                                <label for="servername">Database Name</label>
                                <input id="servername" class="form-control" type="text" name="database" value="openmrs">
                            </div>
                       </div>
                       <div class="col-md-6">
                            <div class="form-group">
                                <label for="servername">Enter Username</label>
                                <input id="servername" class="form-control" type="text" name="username" value="openmrs">
                            </div>
                       </div>
                       <div class="col-md-6">
                            <div class="form-group">
                                <label for="servername">Enter Password</label>
                                <input id="servername" class="form-control" type="text" name="password">
                            </div>
                       </div>
                       <div class="col-md-6">
                            <div class="form-group">
                                <label for="servername">MySQL Port</label>
                                <input id="servername" class="form-control" type="number" name="portno" value="3316">
                            </div>
                       </div>
                       <div class="col-md-6">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Connect</button>
                            </div>
                       </div>
                   </div>
                   
                   </form>
               </div>
           </div>
           </div>

           <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Title</h5>
                        <p class="card-text">Content</p>
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

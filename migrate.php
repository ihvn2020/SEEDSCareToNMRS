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
                   <li class="nav-item">
                       <a class="nav-link" href="index.php">MIGRATE</a>
                   </li>
                   <li class="nav-item">
                       <a class="nav-link active" href="support.php">SUPPORT <span class="sr-only">(current)</span></a>
                   </li>
                   <li class="nav-item dropdown">
                       <a class="nav-link dropdown-toggle" href="#" id="dropdownId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">HOW TO</a>
                       <div class="dropdown-menu" aria-labelledby="dropdownId">
                           <a class="dropdown-item" href="setup.php">SET UP THE MIGRATOR</a>
                           <a class="dropdown-item" href="migrate.php">MIGRATE TO NMRS</a>
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
           <div class="col-md-12">
           <div class="card">
               <div class="card-body">
               <?php
               $a = array(2,2,4,4,2,2,3,4,5,6,7,8,8,8,9,9,6);
                $k = array();

                foreach($a as $b){
                    if(array_search($b,$k,true)===FALSE){
                        echo $b."<br>";

                        array_push($k,$b);
                    }else{}

                    
                }
                echo var_dump($k);
               ?>
                   <h5 class="card-title">Support</h5><hr>
                   <p class="card-text">For support please call: </p>
              
                   
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

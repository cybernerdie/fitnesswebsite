<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php $SearchQueryParameter = $_GET["id"]; ?>
<?php
if(isset($_POST["Submit"])){
  $Name    = $_POST["CommenterName"];
  $Email   = $_POST["CommenterEmail"];
  $Comment = $_POST["CommenterThoughts"];
  date_default_timezone_set("Asia/Karachi");
  $CurrentTime=time();
  $DateTime=strftime("%B-%d-%Y %H:%M:%S",$CurrentTime);

  if(empty($Name)||empty($Email)||empty($Comment)){
    $_SESSION["ErrorMessage"]= "All fields must be filled out";
    Redirect_to("post.php?id={$SearchQueryParameter}");
  }elseif (strlen($Comment)>500) {
    $_SESSION["ErrorMessage"]= "Comment length should be less than 500 characters";
    Redirect_to("post.php?id={$SearchQueryParameter}");
  }else{
    // Query to insert comment in DB When everything is fine
    global $ConnectingDB;
    $sql  = "INSERT INTO comments(datetime,name,email,comment,approvedby,status,post_id)";
    $sql .= "VALUES(:dateTime,:name,:email,:comment,'Pending','OFF',:postIdFromURL)";
    $stmt = $ConnectingDB->prepare($sql);
    $stmt -> bindValue(':dateTime',$DateTime);
    $stmt -> bindValue(':name',$Name);
    $stmt -> bindValue(':email',$Email);
    $stmt -> bindValue(':comment',$Comment);
    $stmt -> bindValue(':postIdFromURL',$SearchQueryParameter);
    $Execute = $stmt -> execute();
    //var_dump($Execute);
    if($Execute){
      $_SESSION["SuccessMessage"]="Comment Submitted Successfully";
      Redirect_to("post.php?id={$SearchQueryParameter}");
    }else {
      $_SESSION["ErrorMessage"]="Something went wrong. Try Again !";
      Redirect_to("post.php?id={$SearchQueryParameter}");
    }
  }
} //Ending of Submit Button If-Condition
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Strong | Fitness Website</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link href="https://fonts.googleapis.com/css?family=Barlow+Semi+Condensed:100,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">

    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/ionicons.min.css">

    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">

    
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
  	<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
	    <div class="container">
	      <a class="navbar-brand py-2 px-4" href="index.php">strong</a>
	      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
	        <span class="oi oi-menu"></span> Menu
	      </button>
	      <div class="collapse navbar-collapse" id="ftco-nav">
	        <ul class="navbar-nav ml-auto">
	          <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
	          <li class="nav-item"><a href="program.php" class="nav-link">Program</a></li>
	          <li class="nav-item"><a href="coaches.php" class="nav-link">Coaches</a></li>
	          <li class="nav-item"><a href="schedule.php" class="nav-link">Schedule</a></li>
	          <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
	          <li class="nav-item active"><a href="blog.php" class="nav-link">Blog</a></li>
	          <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
	        </ul>
	      </div>
		  </div>
	  </nav>
    <!-- END nav -->

    <section class="hero-wrap js-fullheight" style="background-image: url('images/bg_2.jpg');">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center">
          <div class="col-md-9 ftco-animate text-center">
            <h1 class="mb-3 bread">Blog</h1>
            <p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home</a></span> <span class="mr-2"><a href="blog.php">Blog</a></span></p>
          </div>
        </div>
      </div>
    </section>

		<section class="ftco-section">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 ftco-animate">
               <?php
           echo ErrorMessage();
           echo SuccessMessage();
           ?>
          <?php
          global $ConnectingDB;
          // SQL query when Searh button is active
          if(isset($_GET["SearchButton"])){
            $Search = $_GET["Search"];
            $sql = "SELECT * FROM posts
            WHERE datetime LIKE :search
            OR title LIKE :search
            OR category LIKE :search
            OR post LIKE :search";
            $stmt = $ConnectingDB->prepare($sql);
            $stmt->bindValue(':search','%'.$Search.'%');
            $stmt->execute();
          }
          // The default SQL query
          else{
            $PostIdFromURL = $_GET["id"];
            if (!isset($PostIdFromURL)) {
              $_SESSION["ErrorMessage"]="Bad Request !";
              Redirect_to("index.php?page=1");
            }
            $sql  = "SELECT * FROM posts  WHERE id= '$PostIdFromURL'";
            $stmt =$ConnectingDB->query($sql);
            $Result=$stmt->rowcount();
            if ($Result!=1) {
              $_SESSION["ErrorMessage"]="Bad Request !";
              Redirect_to("index.php?page=1");
            }

          }
          while ($DataRows = $stmt->fetch()) {
            $PostId          = $DataRows["id"];
            $DateTime        = $DataRows["datetime"];
            $PostTitle       = $DataRows["title"];
            $Category        = $DataRows["category"];
            $Admin           = $DataRows["author"];
            $Image           = $DataRows["image"];
            $PostDescription = $DataRows["post"];
          ?>
            <h2 class="mb-3"><?php echo htmlentities($PostTitle); ?></h2>
            
            <p>
                <img src="Uploads/<?php echo htmlentities($Image); ?>" style="max-height:450px;" class="img-fluid" />
            </p>
              <div class="meta">
		                  <div><a href="#"><?php echo htmlentities($DateTime); ?></a> | <span>Author: <?php echo htmlentities($Admin); ?></span> | <span><a href="#" class="meta-chat"><span class="icon-chat"></span> <?php echo ApproveCommentsAccordingtoPost($PostId);?></a></span>        
                  </div>
		                  
		                </div> <hr>
            <p><?php echo nl2br($PostDescription); ?></p>
           
<?php   } ?>

            <div class="pt-5 mt-5">
              <h3 class="mb-5">Comments</h3>
              <ul class="comment-list">
                  <?php
        global $ConnectingDB;
        $sql  = "SELECT * FROM comments
         WHERE post_id='$SearchQueryParameter' ";
        $stmt =$ConnectingDB->query($sql);
        while ($DataRows = $stmt->fetch()) {
          $CommentDate   = $DataRows['datetime'];
          $CommenterName = $DataRows['name'];
          $CommentContent= $DataRows['comment'];
        ?>
                <li class="comment">
                  <div class="vcard bio">
                      <img  src="images/comment.png" alt="Image placeholder">
                  </div>
                  <div class="comment-body">
                    <h3><?php echo $CommenterName; ?></h3>
                    <div class="meta"><?php echo $CommentDate; ?></div>
                    <p><?php echo $CommentContent; ?></p>
                  </div>
                </li>
                  </ul>
  <?php } ?>
                
              
              <!-- END comment-list -->
              
              <div class="comment-form-wrap pt-5">
                <h3 class="mb-5">Leave a comment</h3>
                <form class="bg-light p-4" action="post.php?id=<?php echo $SearchQueryParameter ?>" method="post">
                  <div class="form-group">
                    <label for="name">Name *</label>
                    <input type="text" class="form-control bg-white" name="CommenterName" value="">
                  </div>
                  <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" class="form-control" name="CommenterEmail" value="">
                  </div>
                  <div class="form-group">
                    <label for="message">Message</label>
                    <textarea name="CommenterThoughts" cols="30" rows="10" class="form-control"></textarea>
                  </div>
                  <div class="form-group">
                    <input type="submit" value="Post Comment" name="Submit"  class="btn py-3 px-4 btn-primary">
                  </div>

                </form>
              </div>
            </div>

          </div> <!-- .col-md-8 -->
          <div class="col-lg-4 sidebar ftco-animate">
            <div class="sidebar-box">
              <form action="blog.php" class="search-form">
                <div class="form-group">
                	<div class="icon">
                        <button  class="btn btn-primary" name="SearchButton">Go</button>
	                </div>
                  <input type="text" class="form-control"  name="Search" placeholder="Search here" value="" required>
                </div>
              </form>
            </div>
       
            <div class="sidebar-box ftco-animate">
              <div class="categories">
                <h3 class="heading-2">Categories</h3>
                  <?php
                global $ConnectingDB;
                $sql = "SELECT * FROM category ORDER BY id desc";
                $stmt = $ConnectingDB->query($sql);
                while ($DataRows = $stmt->fetch()) {
                  $CategoryId = $DataRows["id"];
                  $CategoryName=$DataRows["title"];
                 ?> 
                <li><a href="index.php?category=<?php echo $CategoryName; ?>"><?php echo $CategoryName; ?></a></li>
                  <?php } ?>
              </div>
            </div>

            <div class="sidebar-box ftco-animate">
              <h3 class="heading-2">Recent Blog</h3>
                <?php
              global $ConnectingDB;
              $sql= "SELECT * FROM posts ORDER BY id desc LIMIT 0,5";
              $stmt= $ConnectingDB->query($sql);
              while ($DataRows=$stmt->fetch()) {
                $Id     = $DataRows['id'];
                $Title  = $DataRows['title'];
                $DateTime = $DataRows['datetime'];
                $Image = $DataRows['image'];
              ?>
              <div class="block-21 mb-4 d-flex">
                  <img src="Uploads/<?php echo htmlentities($Image); ?>" class="blog-img mr-4">
                <div class="text">
                  <h3 class="heading"><a href="post.php?id=<?php echo htmlentities($Id) ; ?>" target="_blank"><?php echo htmlentities($Title); ?></a>
              
                    
                    </h3>
                  <div class="meta">
                    <div><a href="#"><span class="icon-calendar"></span> <?php echo htmlentities($DateTime); ?></a></div>
                    <div><a href="#"><span class=""></span> </a></div>
                  </div>
                </div>
              </div>
              <?php } ?>
            </div>


           
          </div>

        </div>
      </div>
    </section> <!-- .section -->
   

<footer class="ftco-footer ftco-section img">
    	<div class="overlay"></div>
      <div class="container">
        <div class="row mb-5">
          <div class="col-lg-3 col-md-6 mb-5 mb-md-5">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">About Us</h2>
              <p>Strong Fitness is committed to facilitating the accessibility and usability of content and features on its website, including this blog.</p>
              <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
                <li class="ftco-animate"><a href="https://twitter.com/veecthorpaul"><span class="icon-twitter"></span></a></li>
                <li class="ftco-animate"><a href="https://facebook.com/veecthorpaul"><span class="icon-facebook"></span></a></li>
                <li class="ftco-animate"><a href="https://instagram.com/veecthorpaul"><span class="icon-instagram"></span></a></li>
              </ul>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 mb-5 mb-md-5">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2"></h2>
              <div class="block-21 mb-4 d-flex">
                <a class="blog-img mr-4" style=""></a>
                <div class="text">
                  <h3 class="heading"><a href="#"></a></h3>
                  <div class="meta">
                  
                  </div>
                </div>
              </div>
              
            </div>
          </div>
          <div class="col-lg-2 col-md-6 mb-5 mb-md-5">
             <div class="ftco-footer-widget mb-4 ml-md-4">
              <h2 class="ftco-heading-2">Services</h2>
              <ul class="list-unstyled">
                <li><a href="program.php" class="py-2 d-block">Boost Your Body</a></li>
                <li><a href="program.php" class="py-2 d-block">Achieve Your Goal</a></li>
                <li><a href="program.php" class="py-2 d-block">Analyze Your Goal</a></li>
                <li><a href="program.php" class="py-2 d-block">Improve Your Performance</a></li>
              </ul>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-5 mb-md-5">
            <div class="ftco-footer-widget mb-4">
            	<h2 class="ftco-heading-2">Have a Questions?</h2>
            	<div class="block-23 mb-3">
	              <ul>
	                <li><span class="icon icon-map-marker"></span><span class="text">35, Oyedeji Street, Ajegunle, Apapa Lagos</span></li>
	                <li><a href="#"><span class="icon icon-phone"></span><span class="text">+2347031952383</span></a></li>
	                <li><a href="#"><span class="icon icon-envelope"></span><span class="text">veecthorpaul@gmail.com</span></a></li>
	              </ul>
	            </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text-center">

            <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
  Copyright &copy; <script>document.write(new Date().getFullYear());</script> <a href="https://about.me/pauljoshua" target="_blank">Josh Paul Media</a>
  <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
          </div>
        </div>
      </div>
    </footer>
    
  

  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>


  <script src="js/jquery.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/jquery.waypoints.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.animateNumber.min.js"></script>
  <script src="js/bootstrap-datepicker.js"></script>
  <script src="js/jquery.timepicker.min.js"></script>
  <script src="js/scrollax.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
  <script src="js/google-map.js"></script>
  <script src="js/main.js"></script>
    
  </body>
</html>
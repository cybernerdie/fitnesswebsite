<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php
if(isset($_POST["Submit"])){
  $Fname    = $_POST["FirstName"];
  $Lname   = $_POST["LastName"];
  $Phone   = $_POST["Phone"];
  $Message   = $_POST["Message"];
  $Date = $_POST["Date"];
  $Time = $_POST["Time"];

  if(empty($Fname)||empty($Lname)||empty($Phone)||empty($Message)||empty($Date)||empty($Time)){
    $_SESSION["ErrorMessage"]= "All fields must be filled out";
    Redirect_to("program.php");
  }elseif (strlen($Message)>500) {
    $_SESSION["ErrorMessage"]= "Message length should be less than 500 characters";
    Redirect_to("program.php");
  }else{
    // Query to insert comment in DB When everything is fine
    global $ConnectingDB;
    $sql  = "INSERT INTO appointments(date,time,fname,lname,phone,message)";
    $sql .= "VALUES(:date,:time,:fname,:lname,:phone,:message)";
    $stmt = $ConnectingDB->prepare($sql);
    $stmt -> bindValue(':date',$Date);
    $stmt -> bindValue(':time',$Time);
    $stmt -> bindValue(':fname',$Fname);
    $stmt -> bindValue(':lname',$Lname);
    $stmt -> bindValue(':phone',$Phone);
    $stmt -> bindValue(':message',$Message);
    $Execute = $stmt -> execute();
    //var_dump($Execute);
    if($Execute){
      $_SESSION["SuccessMessage"]="Appointment Booked Successfully";
      Redirect_to("program.php");
    }else {
      $_SESSION["ErrorMessage"]="Something went wrong. Try Again !";
      Redirect_to("program.php");
    }
  }
} //Ending of Submit Button If-Condition
 ?>
<?php
if (isset($_GET['page']) && $_GET['page']!="") {
	$page = $_GET['page'];
	} else {
		$page = 1;
        }
$result_count = "SELECT COUNT(*) as total_records FROM programs";
$stmt = $ConnectingDB->prepare( $result_count );
$stmt->execute();
 
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_records = $row['total_records'];
$TotalPerPage=6;
$Previous = $page - 1;
$Next = $page + 1;
$TotalPages = ceil($total_records / $TotalPerPage)

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
	          <li class="nav-item active"><a href="program.php" class="nav-link">Program</a></li>
	          <li class="nav-item"><a href="coaches.php" class="nav-link">Coaches</a></li>
	          <li class="nav-item"><a href="schedule.php" class="nav-link">Schedule</a></li>
	          <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
	          <li class="nav-item"><a href="blog.php" class="nav-link">Blog</a></li>
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
            <h1 class="mb-3 bread">Workout Classes</h1>
            <p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home</a></span> <span>Program</span></p>
          </div>
        </div>
      </div>
    </section>

    
    <section class="ftco-section">
	  	<div class="container-fluid">
        <div class="row">
          <?php
          global $ConnectingDB;
          // SQL query when Searh button is active
          if(isset($_GET["SearchButton"])){
            $Search = $_GET["Search"];
            $sql = "SELECT * FROM programs
            WHERE datetime LIKE :search
            OR title LIKE :search
            OR category LIKE :search
            OR post LIKE :search";
            $stmt = $ConnectingDB->prepare($sql);
            $stmt->bindValue(':search','%'.$Search.'%');
            $stmt->execute();
          }// Query When Pagination is Active i.e index.php?page=1
          elseif (isset($_GET["page"])) {
            $Page = $_GET["page"];
            $Next = $Page + 1;
            $previous = $Page - 1;
            if($Page==0||$Page<1){
            $ShowPostFrom=1;
            $TotalPerPage=6;
          }else{
            $ShowPostFrom=($Page-1) *$TotalPerPage;
          }
            $sql ="SELECT * FROM programs ORDER BY id desc LIMIT $ShowPostFrom,$TotalPerPage";
            $stmt=$ConnectingDB->query($sql);
              
          }
          // Query When Category is active in URL Tab
          elseif (isset($_GET["category"])) {
            $Category = $_GET["category"];
            $sql = "SELECT * FROM programs WHERE category='$Category' ORDER BY id desc";
            $stmt=$ConnectingDB->query($sql);
          }

          // The default SQL query
          else{
            $sql  = "SELECT * FROM programs ORDER BY id desc LIMIT 0,$TotalPerPage";
              $stmt =$ConnectingDB->query($sql);
          }
          while ($DataRows = $stmt->fetch()) {
            $ProgramId          = $DataRows["id"];
            $Name        = $DataRows["name"];
            $Description       = $DataRows["description"];
            $Benefit       = $DataRows["benefit"];
            $Duration           = $DataRows["duration"];
            $Image           = $DataRows["image"];
            $Trainer = $DataRows["trainer"];
          ?>
        	<div class="col-md-6 col-lg-4 col-sm-6">
        		<div class="package-program ftco-animate">
        			<a href="programs.php?id=<?php echo $ProgramId; ?>">
                         <img src="Uploads/<?php echo htmlentities($Image); ?>" class="block-20 d-flex justify-content-center align-items-center" href="trainer.php" /></a>
        			<div class="text mt-3">
        				<h3><a href="#"><?php echo $Name; ?></a></h3>
        				<p><?php if (strlen($Description)>100) { $Description = substr($Description,0,100)."...";} echo htmlentities($Description); ?></p>
                        <a href="programs.php?id=<?php echo $ProgramId; ?>" style="text-align: right">Learn More</a>
        			</div>
        		</div>
        	</div>  
             <?php   } ?>
        </div>
        <div class="row mt-5">
          <div class="col text-center">
            <div class="block-27">
                                <div style='padding: 10px 20px 0px; border-top: dotted 1px #CCC;'>
<strong>Page <?php echo $page." of ".$TotalPages; ?></strong>
</div>
              <ul>
	<?php // if($page > 1){ echo "<li><a href='?page=1'>First Page</a></li>"; } ?>
    
	<li <?php if($page <= 1){ echo "class='disabled'"; } ?>>
	<a <?php if($page > 1){ echo "href='?page=$Previous'"; } ?>>&lt;</a>
	</li>
       
    <?php 
	if ($TotalPages <= 10){  	 
		for ($counter = 1; $counter <= $TotalPages; $counter++){
			if ($counter == $page) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?page=$counter'>$counter</a></li>";
				}
        }
	}
	elseif($TotalPages > 10){
		
	if($page <= 4) {			
	 for ($counter = 1; $counter < 8; $counter++){		 
			if ($counter == $page) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?page=$counter'>$counter</a></li>";
				}
        }
		echo "<li><a>...</a></li>";
		echo "<li><a href='?page=$second_last'>$second_last</a></li>";
		echo "<li><a href='?page=$TotalPages'>$TotalPages</a></li>";
		}

	 elseif($page > 4 && $page < $TotalPages - 4) {		 
		echo "<li><a href='?page=1'>1</a></li>";
		echo "<li><a href='?page=2'>2</a></li>";
        echo "<li><a>...</a></li>";
        for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {			
           if ($counter == $page) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?page=$counter'>$counter</a></li>";
				}                  
       }
       echo "<li><a>...</a></li>";
	   echo "<li><a href='?page=$second_last'>$second_last</a></li>";
	   echo "<li><a href='?page=$TotalPages'>$TotalPages</a></li>";      
            }
		
		else {
        echo "<li><a href='?page=1'>1</a></li>";
		echo "<li><a href='?page=2'>2</a></li>";
        echo "<li><a>...</a></li>";

        for ($counter = $TotalPages - 6; $counter <= $TotalPages; $counter++) {
          if ($counter == $page) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?page=$counter'>$counter</a></li>";
				}                   
                }
            }
	}
?>
    
	<li <?php if($page >= $TotalPages){ echo "class='disabled'"; } ?>>
	<a <?php if($page < $TotalPages) { echo "href='?page=$Next'"; } ?>>&gt;</a>
	</li>
</ul>
            </div>
          </div>
        </div>
	  	</div>
	  </section>

	  <section class="ftco-appointment">
			<div class="overlay"></div>
    	<div class="container-wrap">
    		<div class="row no-gutters d-md-flex align-items-center">
    			<div class="col-md-6 d-flex align-self-stretch img" style="background-image: url(images/about-3.jpg);">
    			</div>
	    		<div class="col-md-6 appointment ftco-animate">
	    		                <?php
           echo ErrorMessage();
           echo SuccessMessage();
           ?>
	    			<h3 class="mb-3">Book a Appointment</h3>
	    			<form action="program.php" class="appointment-form" method="post">
	    				<div class="d-md-flex">
		    				<div class="form-group">
		    					<input type="text" class="form-control" name="FirstName" placeholder="First Name" required>
		    				</div>
		    				<div class="form-group ml-md-4">
		    					<input type="text" class="form-control" name="LastName" placeholder="Last Name" required>
		    				</div>
	    				</div>
	    				<div class="d-md-flex">
		    				<div class="form-group">
		    					<div class="input-wrap">
		            		<div class="icon"><span class="ion-md-calendar"></span></div>
		            		<input type="text" class="form-control appointment_date" name="Date" placeholder="Date" required>
	            		</div>
		    				</div>
		    				<div class="form-group ml-md-4">
		    					<div class="input-wrap">
		            		<div class="icon"><span class="ion-ios-clock"></span></div>
		            		<input type="text" class="form-control appointment_time" name="Time" placeholder="Time" required>
	            		</div>
		    				</div>
		    				<div class="form-group ml-md-4">
		    					<input type="text" class="form-control" name="Phone" placeholder="Phone" required>
		    				</div>
	    				</div>
	    				<div class="d-md-flex">
	    					<div class="form-group">
		              <textarea name="Message" id="" cols="30" rows="2" class="form-control" placeholder="Message" required></textarea>
		            </div>
		            <div class="form-group ml-md-4">
		              <input type="submit" value="Book" name="Submit" class="btn btn-primary py-3 px-4">
		            </div>
	    				</div>
	    			</form>
	    		</div>    			
    		</div>
    	</div>
    </section>


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
<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php $_SESSION["TrackingURL"]=$_SERVER["PHP_SELF"];
Confirm_Login(); ?>
<?php
// Fetching the existing Admin Data Start
$AdminId = $_SESSION["UserId"];
global $ConnectingDB;
$sql  = "SELECT * FROM admins WHERE id='$AdminId'";
$stmt =$ConnectingDB->query($sql);
while ($DataRows = $stmt->fetch()) {
  $ExistingName     = $DataRows['aname'];
  $ExistingUsername     = $DataRows['username'];
  $ExistingUsername = $DataRows['username'];
  $ExistingHeadline = $DataRows['aheadline'];
  $ExistingBio      = $DataRows['abio'];
  $ExistingImage    = $DataRows['aimage'];
}
// Fetching the existing Admin Data End
if(isset($_POST["Submit"])){
  $AName     = $_POST["Name"];
  $Username     = $_POST["Username"];
  $AHeadline = $_POST["Headline"];
  $ABio      = $_POST["Bio"];
  $Image     = $_FILES["Image"]["name"];
  $Target    = "Uploads/".basename($_FILES["Image"]["name"]);
if (strlen($AHeadline)>30) {
    $_SESSION["ErrorMessage"] = "Headline Should be less than 30 characters";
    Redirect_to("MyProfile.php");
  }elseif (strlen($ABio)>500) {
    $_SESSION["ErrorMessage"] = "Bio should be less than than 500 characters";
    Redirect_to("MyProfile.php");
  }else{
    // Query to Update Admin Data in DB When everything is fine
    global $ConnectingDB;
    if (!empty($_FILES["Image"]["name"])) {
      $sql = "UPDATE admins
              SET aname='$AName', aheadline='$AHeadline', abio='$ABio', aimage='$Image', username='$Username'
              WHERE id='$AdminId'";
    }else {
      $sql = "UPDATE admins
              SET aname='$AName', aheadline='$AHeadline', abio='$ABio', username='$Username'
              WHERE id='$AdminId'";
    }
    $Execute= $ConnectingDB->query($sql);
    move_uploaded_file($_FILES["Image"]["tmp_name"],$Target);
    if($Execute){
      $_SESSION["SuccessMessage"]="Details Updated Successfully";
      Redirect_to("MyProfile.php");
    }else {
      $_SESSION["ErrorMessage"]= "Something went wrong. Try Again !";
      Redirect_to("MyProfile.php");
    }
  }
} //Ending of Submit Button If-Condition
 ?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Strong | Fitness Website</title>

  <!-- Custom fonts for this template -->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="css" rel="stylesheet">
    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">STRONG</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item">
        <a class="nav-link" href="dashboard.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-cog"></i>
          <span>Posts</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="Posts.php">Posts</a>
            <a class="collapse-item" href="AddNewPost.php">Add Post</a>
            <a class="collapse-item" href="Comments.php">Comments</a>
          </div>
        </div>
      </li>

        
      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Nav Item - Utilities Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-fw fa-wrench"></i>
          <span>Coaches</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="trainers.php">Coaches</a>
            <a class="collapse-item" href="addtrainer.php">Add Coaches</a>
          </div>
        </div>
      </li>
        
        

      <!-- Divider -->
      <hr class="sidebar-divider">


      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
          <i class="fas fa-fw fa-folder"></i>
          <span>Programs</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="Prog.php">Programs</a>
            <a class="collapse-item" href="addprograms.php">Add Programs</a>
          </div>
        </div>
      </li>

      <!-- Nav Item - Charts -->
      <li class="nav-item">
        <a class="nav-link" href="Categories.php">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Categories</span></a>
      </li>
    
        <li class="nav-item">
        <a class="nav-link" href="Admins.php">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Admins</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

   
          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">           
            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
             <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <?php
          global $ConnectingDB;
                  $AdminId = $_SESSION["UserId"];
                  $sql  = "SELECT * FROM admins WHERE id='$AdminId'";
                    $stmt =$ConnectingDB->query($sql);
                  while ($DataRows = $stmt->fetch()) {
                  $AdminId = $DataRows["id"];
                  $Name=$DataRows["aname"];
                  ?>
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $Name; ?></span>
                   <?php } ?>
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="MyProfile.php">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="Logout.php">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
            
         <div class="card col-md-7">
        <div class="card-header bg-primary text-light">
            <?php
          global $ConnectingDB;
                  $AdminId = $_SESSION["UserId"];
                  $sql  = "SELECT * FROM admins WHERE id='$AdminId'";
                    $stmt =$ConnectingDB->query($sql);
                  while ($DataRows = $stmt->fetch()) {
                  $AdminId = $DataRows["id"];
                  $Name=$DataRows["aname"];
                  $Username=$DataRows["username"];
                  $Headline=$DataRows["aheadline"];
                  $Bio=$DataRows["abio"];
                  $Image=$DataRows["aimage"];
                  ?>
          <h3> <?php echo $Name; ?></h3>
            <h6><small><?php echo $Bio; ?></small></h6> <br>
          <h3><img src="Uploads/<?php echo $Image ; ?>" width="100%;" height="400px"></h3>
        </div>
          <?php } ?>
      </div>
<br><br>
      <?php
       echo ErrorMessage();
       echo SuccessMessage();
       ?>
      <form class="" action="MyProfile.php" method="post" enctype="multipart/form-data">
        <div class="card bg-primary text-light">
          <div class="card-header bg-primary text-light">
            <h4>Edit Admin Profile</h4>
          </div>
          <div class="card-body">
            <div class="form-group">
                 <label for="title"> <span class="FieldInfo"> Admin Name: </span></label>
               <input class="form-control" type="text" name="Name" id="title" placeholder="Your name" value="<?php echo $ExistingName; ?>">
            </div>
              <div class="form-group">
                 <label for="title"> <span class="FieldInfo"> Admin Username: </span></label>
               <input class="form-control" type="text" name="Username" id="title" placeholder="Your name" value="<?php echo $ExistingUsername; ?>">
            </div>
            <div class="form-group">
                 <label for="title"> <span class="FieldInfo">Admin Headline: </span></label>
               <input class="form-control" type="text" id="title" placeholder="Headline" name="Headline" value="<?php echo $ExistingHeadline; ?>">
            </div>
            <div class="form-group">
                 <label for="bio"> <span class="FieldInfo"> Admin Bio: </span></label>
                <input class="form-control" type="text" name="Bio" id="Post" placeholder="Bio" value="<?php echo $ExistingBio; ?>">
            </div>

            <div class="form-group">
                 <label for="image"> <span class="FieldInfo"> Admin Image: </span></label><br>
                <img  class="mb-1" src="Uploads/<?php echo $ExistingImage;?>" width="100px"; height="70px"; >
              <div class="custom-file">
              <input class="custom-file-input" type="File" name="Image" id="imageSelect" value="">
              <label for="imageSelect" class="custom-file-label">Select Image </label>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6 mb-2">
                <a href="dashboard.php" class="btn btn-danger btn-block"><i class="fas fa-arrow-left"></i> Back To Dashboard</a>
              </div>
              <div class="col-lg-6 mb-2">
                <button type="submit" name="Submit" class="btn btn-success btn-block">
                  <i class="fas fa-check"></i> Publish
                </button>
              </div>
            </div>
          </div>
        </div>
      </form>


        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Josh Paul Media</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="login.php">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
    <script>
    $(document).ready(function(){
            $('#post_data').DataTable();
    });
    </script>
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="vendor/datatables/jquery.dataTables.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>
   <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

</body>

</html>

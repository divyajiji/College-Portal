<?php  
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['aid']==0)) {
  header('location:logout.php');
  } else{

    if(isset($_GET['delid']))
  {
    $rowid=$_GET['delid'];
    $query=mysqli_query($con,"delete from tblcourse where ID='$rowid'");
    echo "<script>alert('Course Deleted successfully');</script>";
    echo "<script>window.location.href='manage-course.php'</script>";
  }

?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>

  <title>CUSAT||Seats</title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Quicksand:300,400,500,700"
  rel="stylesheet">
  <link href="https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome.min.css"
  rel="stylesheet">

     <style>
    .errorWrap {
    padding: 10px;
    margin: 20px 0 0px 0;
    background: #fff;
    border-left: 4px solid #dd3d36;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
.succWrap{
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #5cb85c;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
    </style>

</head>
<body class="vertical-layout vertical-menu-modern 2-columns   menu-expanded fixed-navbar"
data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
<?php include('includes/header.php');?>
<?php include('includes/leftbar.php');?>
  <div class="app-content content">
    <div class="content-wrapper">
      <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
          <h3 class="content-header-title mb-0 d-inline-block">
           Manage Course Seats
          </h3>
          <div class="row breadcrumbs-top d-inline-block">
            <div class="breadcrumb-wrapper col-12">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a>
                </li>
            
                </li>
                <li class="breadcrumb-item active">Course Seats
                </li>
                
              </ol>
            </div>
          </div>
        </div>
   
      </div>
      <div class="content-body">
       
<table class="table mb-0">
 <thead>
                <tr>
                  <th>S.NO</th>
                  <th>Course Name</th>
                  <th>College </th>
                  <th> Total Seats </th>
                  <th> Filled Seats </th>
                  <th> Vacant Seats </th>
                   <th>Action</th>
                </tr>
              </thead>
               <?php
$ret=mysqli_query($con,"select * from seat_data");
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>
              
                <tr>
                  <td><?php echo $cnt;?></td>
              
                  <td><?php  echo $row['course'];?></td>
                  <td><?php  echo $row['college'];?></td>
                  <td><?php  echo $row['total_seats'];?></td>
                  <td><?php  echo $row['filled_seats'];?></td>
                  <td><?php  echo $row['vacant_seats'];?></td>
                
                                 </tr>
                <?php 
$cnt=$cnt+1;
}?>
   


</table>






     

</div>
      </div>
      </div>

    
  
<?php include('includes/footer.php');?>
  
 

</body>
</html>
<?php  } ?>

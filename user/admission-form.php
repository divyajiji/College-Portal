<?php
session_start();
error_reporting(E_ALL);
include('includes/dbconnection.php');

if (strlen($_SESSION['uid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $uid = $_SESSION['uid'];
      
        $fathername = $_POST['fathername'];
        $mothername = $_POST['mothername'];
        $dob = $_POST['dob'];
        $nationality = $_POST['nationality'];
        $state = $_POST['state'];
        $gender = $_POST['gender'];
        $rank = $_POST['catrank'];
        $course1 = $_POST['coursename_first'];
        $course2 = $_POST['coursename_second'];
        $course3 = $_POST['coursename_third'];
        $category = $_POST['category'];
        $coradd = $_POST['coradd'];
        $peradd = $_POST['peradd'];

        
        $secboard = $_POST['10thboard'];
        $secyop = $_POST['10thpyear'];
        $secper = $_POST['10thpercentage'];
        $secstream = $_POST['10thstream'];
        $ssecboard = $_POST['12thboard'];
        $ssecyop = $_POST['12thpyear'];
        $ssecper = $_POST['12thpercentage'];
        $ssecstream = $_POST['12thstream'];
        $dec = $_POST['declaration'];

        
        $sign = $_FILES['signature']['name'];
        $upic = $_FILES['userpic']['name'];
        $identity = $_FILES['identityproof']['name'];
        $tenmarksheet = $_FILES['hscimage']['name'];
        $twlevemaksheet = $_FILES['sscimage']['name'];

        
        $allowed_extensions = array(".jpg", ".jpeg", ".png", ".gif", ".pdf", ".PDF");

        function getFileExtension($filename) {
            return substr($filename, strrpos($filename, '.'));
        }

      
        $fileArray = array($upic, $sign, $tenmarksheet, $twlevemaksheet);
        foreach ($fileArray as $file) {
            if ($file && !in_array(getFileExtension($file), $allowed_extensions)) {
                echo "<script>alert('Invalid file format. Only jpg, jpeg, png, gif, and pdf are allowed.');</script>";
                exit();
            }
        }

        
        $userpic = md5($upic) . getFileExtension($upic);
        $identity= md5($identity) . getFileExtension($identity);
        $sign = md5($sign) . getFileExtension($sign);
        $tm = md5($tenmarksheet) . getFileExtension($tenmarksheet);
        $twm = md5($twlevemaksheet) . getFileExtension($twlevemaksheet);

        
        move_uploaded_file($_FILES["hscimage"]["tmp_name"], "userdocs/" . $tm);
        move_uploaded_file($_FILES["sscimage"]["tmp_name"], "userdocs/" . $twm);
        move_uploaded_file($_FILES["userpic"]["tmp_name"], "userimages/" . $userpic);
        move_uploaded_file($_FILES["signature"]["tmp_name"], "userdocs/" . $sign);
        move_uploaded_file($_FILES["identityproof"]["tmp_name"], "userdocs/" . $identity);

        
        $query = mysqli_query($con, "INSERT INTO tbladmapplications 
        (UserId, CourseApplied_first, CourseApplied_second, CourseApplied_third, FatherName, MotherName, DOB, Nationality, state, cat_rank, Gender, Category, Identity_proof, CorrespondenceAdd, PermanentAdd, 
        SecondaryBoard, SecondaryBoardyop, SecondaryBoardper, SecondaryBoardstream, 
        SSecondaryBoard, SSecondaryBoardyop, SSecondaryBoardper, SSecondaryBoardstream, 
        Signature, UserPic, TenMarksheeet, TwelveMarksheet) 
        VALUES ('$uid', '$course1', '$course2', '$course3', '$fathername', '$mothername', '$dob', '$nationality', '$state', '$rank', '$gender', '$category', '$identity', '$coradd', '$peradd', 
        '$secboard', '$secyop', '$secper', '$secstream', 
        '$ssecboard', '$ssecyop', '$ssecper', '$ssecstream', 
        '$sign', '$userpic', '$tm', '$twm')");

        if ($query) {
            echo '<script>alert("Data has been added successfully.")</script>';
            echo "<script>window.location.href ='admission-form.php'</script>";
        } else {
            echo '<script>alert("Something Went Wrong. Please try again.")</script>';
            echo "<script>window.location.href ='admission-form.php'</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>

  <title>University Admission Portal</title>
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
           Application Form
          </h3>
          <div class="row breadcrumbs-top d-inline-block">
            <div class="breadcrumb-wrapper col-12">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a>
                </li>
            
                </li>
                <li class="breadcrumb-item active">Application
                </li>
              </ol>
            </div>
          </div>
        </div>
   
      </div>
      <div class="content-body">
  
      <?php 
$stuid=$_SESSION['uid'];
$query=mysqli_query($con,"SELECT tbladmapplications.*, tbluser.*, tbladmapplications.ID as appid FROM tbladmapplications 
  JOIN tbluser ON tbluser.ID=tbladmapplications.UserId WHERE UserId=$stuid");
$rw=mysqli_num_rows($query);
if($rw>0) {
while($row=mysqli_fetch_array($query)) {
?>
<p style="font-size:16px; color:red" align="center">Your Admission Form has already been submitted.</p>
<div id="exampl">
<table class="table mb-0" border="1" width="100%">
<tr>
  <th>Applicant Name</th>
  <td><?php echo $row['FirstName'] . " " . $row['LastName']; ?></td>
  <th>Registration Date</th>
  <td><?php echo $row['CourseApplieddate']; ?></td>
</tr>
<tr>
  <th>Course Applied (1st Choice)</th>
  <td><?php echo $row['CourseApplied_first']; ?></td>
  <th>Course Applied (2nd Choice)</th>
  <td><?php echo $row['CourseApplied_second']; ?></td>
</tr>
<tr>
  <th>Course Applied (3rd Choice)</th>
  <td><?php echo $row['CourseApplied_third']; ?></td>
  <th>Student Pic</th>
  <td><img src="userimages/<?php echo $row['UserPic']; ?>" width="200" height="150"></td>
</tr>
<tr>
  <th>Father's Name</th>
  <td><?php echo $row['FatherName']; ?></td>
  <th>Mother's Name</th>
  <td><?php echo $row['MotherName']; ?></td>
</tr>
<tr>
  <th>DOB</th>
  <td><?php echo $row['DOB']; ?></td>
  <th>Nationality</th>
  <td><?php echo $row['Nationality']; ?></td>
</tr>
<tr>
  <th>Gender</th>
  <td><?php echo $row['Gender']; ?></td>
  <th>Category</th>
  <td><?php echo $row['Category']; ?></td>
</tr>
<tr>
  <th>State</th>
  <td><?php echo $row['state']; ?></td>
  <th>Category Rank</th>
  <td><?php echo $row['cat_rank']; ?></td>
</tr>
<tr>
  <th>Correspondence Address</th>
  <td><?php echo $row['CorrespondenceAdd']; ?></td>
  <th>Permanent Address</th>
  <td><?php echo $row['PermanentAdd']; ?></td>
</tr>
<tr>
  <th>Identity Proof</th>
  <td><a href="userdocs/<?php echo $row['Identity_proof']; ?>" target="_blank">View File</a></td>
  <th>Signature</th>
  <td><a href="userdocs/<?php echo $row['Signature']; ?>" target="_blank">View File</a></td>
</tr>
<tr>
  <th>10th Marksheet</th>
  <td><a href="userdocs/<?php echo $row['TenMarksheeet']; ?>" target="_blank">View File</a></td>
  <th>12th Marksheet</th>
  <td><a href="userdocs/<?php echo $row['TwelveMarksheet']; ?>" target="_blank">View File</a></td>
</tr>
</table>

<table class="table mb-0" border="1" width="100%" style="margin-top:1%">
<tr>
  <th>#</th>
  <th>Board / University</th>
  <th>Year</th>
  <th>Percentage</th>
  <th>Stream</th>
</tr>
<tr>
  <th>10th (Secondary)</th>
  <td><?php echo $row['SecondaryBoard']; ?></td>
  <td><?php echo $row['SecondaryBoardyop']; ?></td>
  <td><?php echo $row['SecondaryBoardper']; ?></td>
  <td><?php echo $row['SecondaryBoardstream']; ?></td>
</tr>
<tr>
  <th>12th (Senior Secondary)</th>
  <td><?php echo $row['SSecondaryBoard']; ?></td>
  <td><?php echo $row['SSecondaryBoardyop']; ?></td>
  <td><?php echo $row['SSecondaryBoardper']; ?></td>
  <td><?php echo $row['SSecondaryBoardstream']; ?></td>
</tr>
</table>

<?php if($row['AdminStatus']!=""): ?>
<table class="table mb-0" border="1" width="100%">
<tr>
  <th>Admin Remark</th>
  <td><?php echo $row['AdminRemark']; ?></td>
</tr>
<tr>
  <th>Admin Status</th>
  <td>
    <?php 
    if ($row['AdminStatus'] == "1") {
        echo "Selected";
    } elseif ($row['AdminStatus'] == "2") {
        echo "Rejected";
    } else {
        echo "Admin remark is pending";
    }
    ?>
  </td>
</tr>
<tr>
  <th>Admin Remark Date</th>
  <td><?php echo $row['AdminRemarkDate']; ?></td>
</tr>
</table>
<?php endif; ?>

<table class="table mb-0" border="1" width="100%">
<tr>
  <th colspan="2">
    <font color="red">Declaration:</font>
    I hereby state that the facts mentioned above are true to the best of my knowledge and belief.<br>
    (<?php echo $row['Signature']; ?>)
  </th>
</tr>
</table>
</div>
<div style="float:right;">
  <button class="btn btn-primary" style="cursor: pointer;" onClick="CallPrint(this.value)">Print</button>
</div>

<?php if ($row['AdminStatus'] == "") { ?>
<p style="text-align: center;font-size: 20px;"><a href="edit-appform.php?editid=<?php echo $row['appid']; ?>">Edit Details</a></p>
<?php } }} else {?>
  
<form name="submit" method="post" enctype="multipart/form-data">        
        <section class="formatter" id="formatter">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Application Form</h4>

                  <div class="heading-elements">
                    <ul class="list-inline mb-0">
                  
                      <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                      <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                      
                    </ul>
                  </div>
                </div>
                <div class="card-content">
                  <div class="card-body">
 
                  <div class="row">
<div class="col-xl-4 col-lg-12">
 <fieldset>
  <h5>DOB                   </h5>
   <div class="form-group">
   <input class="form-control white_bg" id="dob" name="dob"  type="date" required>
          <small class="text-muted">DOB Must be in this format (YYYY-MM-DD)</small>
    </div>

</fieldset>                  
</div>
<div class="col-xl-4 col-lg-12">
 <fieldset>
  <h5>Gender                </h5>
   <div class="form-group">

   <select class="form-control white_bg" id="gender" name="gender" required>
    
<option value="Male">Male</option>
<option value="Female">Female</option>
<option value="Transgender">Transgender</option>
   </select>
                          </div>
                        </fieldset>
                      </div>

                    </div>
                    <div class="row">
                    <div class="col-xl-4 col-lg-12">
 <fieldset>
  <h5>Nationality</h5>
   <div class="form-group">
   <input class="form-control white_bg" id="nationality" name="nationality" onchange="toggleStateDropdown()"  type="text" required>
                          </div>
</fieldset>
                        
                      </div>
<div class="col-xl-4 col-lg-12">
 <fieldset>
  <h5>State/UT of Residence </h5>
   <div class="form-group">
   <select class="form-control white_bg" id="state" name="state"  disabled>
    
<option value="Kerala">Kerala</option>
<option value="Tamil Nadu">Tamil Nadu</option>
<option value="Karnataka">Karnataka</option>
<option value="Delhi"> Delhi </option>
<option value="Andhra Pradesh">Andhra Pradesh</option>
<option value="Bihar">Bihar</option>
<option value="Uttar Pradesh">Uttar Pradesh</option>
<option value="Uttarakhand">Uttarakhand</option>
<option value="Chattisgarh">Chattisgarh</option>
<option value="Jharkhand">Jharkhand</option>
<option value="Orissa">Orissa</option>
<option value="Maharashtra">Maharashtra</option>
<option value="Jammu and Kashmir">Jammu and Kashmir</option>
<option value="Lakshadweep">Lakshadweep</option>
<option value="Andaman and Nicobar">Andaman and Nicobar Islands</option>
<option value="Puducherry">Puducherry</option>
<option value="Gujarat">Gujarat</option>
<option value="Rajasthan">Rajasthan</option>

   </select>
                          </div>

                        </fieldset>
                      </div>
                      
                     
<div class="col-xl-6 col-lg-12">
 <fieldset>
  <h5>CAT Rank  </h5>
   <div class="form-group">
   <input class="form-control white_bg" id="catrank" name="catrank"  type="number" required>
    </div>
</fieldset>               
</div>
<div class="row">
<div class="col-xl-6 col-lg-12">
 <fieldset>
  <h5>First Course Preference                  </h5>
   <div class="form-group">
   <select name='coursename_first' id="coursename_first" onchange="disableSelected()" class="form-control white_bg" required="true">
   <option value = "B.Tech Computer Science in Soe">Computer Science in SOE</option>
     <option value = "B.Tech Computer Science in cucek">Computer Science in CUCEK</option>
     <option value = "B.Tech Information Technology in Soe">Information Technology in SOE</option>
     <option value = "B.Tech Information Technology in cucek">Information Technology in CUCEK</option>
     <option value = "B.Tech Electrical and electronics engineering in Soe">EEE in SOE</option>
     <option value = "B.Tech Electrical and electronics engineering in Cucek">EEE in CUCEK</option>
     <option value = "B.Tech Electroniocs and communication in Cucek">ECE in CUCEK</option>
     <option value = "B.Tech Electronics and Communication in Soe">ECE in SOE</option>
     <option value = "B.Tech Civil Engineering in Soe">Civil Engineering in SOE</option>
     <option value = "B.Tech Civil Engineering in Cucek">Civil Engineering in CUCEK</option>
     <option value = "B.Tech Mechanical Engineering in Soe">Mechanical Engineering in SOE</option>
     <option value = "B.Tech Marine Engineering">Marine Engineering</option>
     <option value = "B.Tech Naval Architecture and Ship Building">Naval Architecture and Ship Building</option>
     <option value = "B.Tech Saftey and fire Engineering">Safety and Fire Engineering</option>
   </select>
    </div>
</fieldset>
                   
</div>
<div class="row">
<div class="col-xl-6 col-lg-12">
 <fieldset>
  <h5>Second Course Preference</h5>
   <div class="form-group">
   <select name='coursename_second' id="coursename_second" onchange="disableSelected()" class="form-control white_bg" required="true">
     <option value = "B.Tech Computer Science in Soe">Computer Science in SOE</option>
     <option value = "B.Tech Computer Science in cucek">Computer Science in CUCEK</option>
     <option value = "B.Tech Information Technology in Soe">Information Technology in SOE</option>
     <option value = "B.Tech Information Technology in cucek">Information Technology in CUCEK</option>
     <option value = "B.Tech Electrical and electronics engineering in Soe">EEE in SOE</option>
     <option value = "B.Tech Electrical and electronics engineering in Cucek">EEE in CUCEK</option>
     <option value = "B.Tech Electroniocs and communication in Cucek">ECE in CUCEK</option>
     <option value = "B.Tech Electronics and Communication in Soe">ECE in SOE</option>
     <option value = "B.Tech Civil Engineering in Soe">Civil Engineering in SOE</option>
     <option value = "B.Tech Civil Engineering in Cucek">Civil Engineering in CUCEK</option>
     <option value = "B.Tech Mechanical Engineering in Soe">Mechanical Engineering in SOE</option>
     <option value = "B.Tech Marine Engineering">Marine Engineering</option>
     <option value = "B.Tech Naval Architecture and Ship Building">Naval Architecture and Ship Building</option>
     <option value = "B.Tech Saftey and fire Engineering">Safety and Fire Engineering</option>
   </select>
    </div>
</fieldset>
                   
</div>
<div class="row">
<div class="col-xl-6 col-lg-12">
 <fieldset>
  <h5>Third Course Preference</h5>
   <div class="form-group">
   <select name='coursename_third' id="coursename_third" onchange="disableSelected()" class="form-control white_bg" required="true">
   <option value = "B.Tech Computer Science in Soe">Computer Science in SOE</option>
     <option value = "B.Tech Computer Science in cucek">Computer Science in CUCEK</option>
     <option value = "B.Tech Information Technology in Soe">Information Technology in SOE</option>
     <option value = "B.Tech Information Technology in cucek">Information Technology in CUCEK</option>
     <option value = "B.Tech Electrical and electronics engineering in Soe">EEE in SOE</option>
     <option value = "B.Tech Electrical and electronics engineering in Cucek">EEE in CUCEK</option>
     <option value = "B.Tech Electroniocs and communication in Cucek">ECE in CUCEK</option>
     <option value = "B.Tech Electronics and Communication in Soe">ECE in SOE</option>
     <option value = "B.Tech Civil Engineering in Soe">Civil Engineering in SOE</option>
     <option value = "B.Tech Civil Engineering in Cucek">Civil Engineering in CUCEK</option>
     <option value = "B.Tech Mechanical Engineering in Soe">Mechanical Engineering in SOE</option>
     <option value = "B.Tech Marine Engineering">Marine Engineering</option>
     <option value = "B.Tech Naval Architecture and Ship Building">Naval Architecture and Ship Building</option>
     <option value = "B.Tech Saftey and fire Engineering">Safety and Fire Engineering</option>
   </select>
    </div>
</fieldset>
                   
</div>

<div class="col-xl-6 col-lg-12">
 <fieldset>
  <h5>Candidate Photo                   </h5>
   <div class="form-group">
    <input class="form-control white_bg" id="userpic" name="userpic"  type="file" required>
    </div>
</fieldset>                  
</div>
 </div>       
 <div class="col-xl-6 col-lg-12">
 <fieldset>
  <h5>Aadhar Card/Passport                 </h5>
   <div class="form-group">
    <input class="form-control white_bg" id="identityproof" name="identityproof"  type="file" required>
    </div>
</fieldset>                  
</div>
 </div>        
 <div class="row">
<div class="col-xl-6 col-lg-12">
 <fieldset>
  <h5>Father's Name                   </h5>
   <div class="form-group">
   <input class="form-control white_bg" id="fathername" name="fathername"  type="text" required>
    </div>
</fieldset>               
</div>
<div class="col-xl-6 col-lg-12">
 <fieldset>
  <h5>Mother's Name                 </h5>
   <div class="form-group">
   <input class="form-control white_bg" id="mothername" name="mothername"  type="text" required>
                          </div>
                        </fieldset>
                      </div>
                    </div>


<div class= "row">
  <div class="col-xl-12 col-lg-12">
    <h5>Category : </h5>
   
<select class="form-control white_bg" id="category" name="category"  required>
    <option value="">Select Category</option>
<option value="General">General</option>
<option value="ews">EWS</option>
<option value="msm">MSM</option>
<option value="LCC">LCC</option>
<option value="DHV">DHV</option>
<option value="VSV">VSV</option>
<option value="DAC">DAC</option>
<option value="CHD">CHD</option>
<option value="NRI">NRI</option>
<option value="KST">KST</option>

<option value="OBH">OBH</option>
<option value="KSC">KSC</option>
<option value="SPQ">SPQ</option>
   </select>

  </div>
</div>

<div class="row" style="margin-top:1%">
  <div class="col-xl-12 col-lg-12">
    <fieldset>
  <h5>Correspondence Address                  </h5>
   <div class="form-group">
   <textarea class="form-control white_bg" id="coradd" name="coradd"  type="text" required rows="4"></textarea>
    </div>
</fieldset>
  </div>
</div>
<div class="row">
  <div class="col-xl-12 col-lg-12">
    <fieldset>
  <h5>Permanent Address                  </h5>
   <div class="form-group">
   <textarea class="form-control white_bg" id="peradd" name="peradd"  type="text" required rows="4"></textarea>
    </div>
</fieldset>
  </div>
</div>
<div class="row" style="margin-top: 2%">
  <!-- <div class="col-xl-12 col-lg-12"><h4 class="card-title">Education Qualification</h4> -->
<hr />
  <!-- </div> -->
</div>
<div class="row">
<div class="col-xl-12 col-lg-12">
<table class="table mb-0">
<tr>
  <th>#</th>
   <th>Board / University</th>
    <th>Year</th>
     <th>Percentage</th>
       <th>Stream</th>
</tr>
<tr>
<th>10th(Secondary)</th>
<td>   <input class="form-control white_bg" id="10thboard" name="10thboard" placeholder="Board / University"  type="text" required></td>
<td>   <input class="form-control white_bg" id="10thpyeaer" name="10thpyear" placeholder="Year"  type="month" required></td>
<td>   <input class="form-control white_bg" id="10thpercentage" name="10thpercentage" placeholder="Percentage"  type="text" required></td>
<td>   <input class="form-control white_bg" id="10thstream" name="10thstream" placeholder="Stream"  type="text" required></td>
</tr>
<tr>
<th>12th(Senior Secondary)</th>
<td>   <input class="form-control white_bg" id="12thboard" name="12thboard" placeholder="Board / University"  type="text" required></td>
<td>   <input class="form-control white_bg" id="12thboard" name="12thpyear" placeholder="Year"  type="month" required></td>
<td>   <input class="form-control white_bg" id="12thpercentage" name="12thpercentage" placeholder="Percentage"  type="text" required></td>
<td>   <input class="form-control white_bg" id="12thstream" name="12thstream" placeholder="Stream"  type="text" required></td>
</tr>


</table>
</div>
</div>
</hr>

<div class="col-xl-6 col-lg-12">
 <fieldset>
  <h5>10th Marksheet                  </h5>
   <div class="form-group">
    <input class="form-control white_bg" id="hscimage" name="hscimage"  type="file" required>
    </div>
</fieldset>                 
</div>
</div>

<div class="col-xl-6 col-lg-12">
 <fieldset>
  <h5>12th Mark Sheet                   </h5>
   <div class="form-group">
    <input class="form-control white_bg" id="sscimage" name="sscimage"  type="file" required>
    </div>
</fieldset>                 
</div>
              </div>

<div class="row" style="margin-top: 2%">
  
<div class="col-xl-12 col-lg-12"><h4 class="card-title"><b>Declaration</b></h4> <hr />
</div>
</div>
 <div class="row">
<div class="col-xl-12 col-lg-12">
<h5><b>I hereby state that the facts mentioned above are true to the best of my knowledge and belief.</b></h5>
 </div>
 </div>               
<div class="row"> 
<div class="col-xl-4 col-lg-12">
<fieldset>
 <b> Signature:</b>
 <input class="form-control white_bg" id="signature" name="signature" type="file"> 
 </fieldset>  
</div>
</div>
<div class="row" style="margin-top: 2%">
<div class="col-xl-6 col-lg-12">
<button type="submit" name="submit" class="btn btn-info btn-min-width mr-1 mb-1">Submit</button>
</div>
</div>
<div class="row" style="margin-top: 2%">
<div class="col-xl-6 col-lg-12">
<input type="reset"  class="btn btn-info btn-min-width mr-1 mb-1"></button>
</div>
 </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <?php } ?>
        
      </form>  
      </div>
    </div>
  </div>
<?php include('includes/footer.php');?>
 
       <script>
function CallPrint(strid) {
var prtContent = document.getElementById("exampl");
var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
WinPrint.document.write(prtContent.innerHTML);
WinPrint.document.close();
WinPrint.focus();
WinPrint.print();
}
function disableSelected() {
  const selects = [
    document.getElementById("coursename_first"),
    document.getElementById("coursename_second"),
    document.getElementById("coursename_third")
  ];

  
  const selectedValues = selects.map(s => s.value);

  
  selects.forEach(select => {
    Array.from(select.options).forEach(option => {
      option.disabled = false;
    });
  });

  
  selects.forEach(currentSelect => {
    selectedValues.forEach(value => {
      if (value && currentSelect.value !== value) {
        const optionToDisable = Array.from(currentSelect.options).find(
          option => option.value === value
        );
        if (optionToDisable) optionToDisable.disabled = true;
      }
    });
  });
}
function toggleStateDropdown() {
  const nationality = document.getElementById("nationality").value;
  const stateSelect = document.getElementById("state");

  if (nationality === "Indian") {
    stateSelect.disabled = false;
  } else {
    stateSelect.disabled = true;
    stateSelect.value = ""; 
  }
}
</script>
</body>
</html>
<?php  ?>

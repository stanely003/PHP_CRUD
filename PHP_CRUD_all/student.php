<?php 

include 'connection.php';

//add Student details

if(isset($_POST['registration']))
{


    //Image Upload
    // $ImageName=$_FILES['image']['name'];
    
    // $imagepath="./images/".$ImageName;   
    
    // $tempname = $_FILES['image']['tmp_name'];
    //
    // // $ext = pathinfo($ImageName, PATHINFO_EXTENSION);
    // // lowercase()
    // move_uploaded_file($tempname, $imagepath);
      
    $firstname= $_POST['firstname'];
    $lastname = $_POST['lastname'];
    
//Image Upload
    $imageName = $_FILES['image']['name'];
    $imagepath = "./images/".$imageName;
    $tempname = $_FILES['image']['tmp_name'];
    move_uploaded_file($tempname, $imagepath);

    $gender = $_POST['gender'];
    $Department = implode(' , ', $_POST['department']);  
    $college = $_POST['college']; 
    $description = $_POST['description'];
    
    $sql = "insert into student (First_name, Last_name, Upload_image, Gender, Department, College, Description) 
    values ('$firstname','$lastname','$imageName','$gender','$Department','$college','$description')";

    $result = $con->query($sql);
}

//list all Student details


$sql = "select * from student";
$result= $con->query($sql);
$fetchall = $result->fetch_all(MYSQLI_ASSOC);
$row = $result->num_rows;


//Edit Students

if(isset($_GET['edit']))
{

   $getEditvalue = $fetchall[$_GET['edit']];

   $department = $getEditvalue['Department'];
   $explodeDept = explode(',',$department);

   $ece='';
   $cse='';
   $eee='';
   if(count($explodeDept)>0)
   {
        foreach($explodeDept as $dept)
        {
            if($dept=='ece')
            {
                $ece=$dept ;
            } else if($dept=='cse')
            {
                $cse=$dept;
            } else {
                $eee=$dept;  
            }      
        }

   }

}


//Update data to database

if(isset($_POST['UpdateRegistration']))
{

   
    $customerID = $_POST['customerID'];
    $OldImage = $_POST['oldImageName'];

    $ImageColum='';

    //OldImage Delete  
    if($_FILES['image']['name']!='')
    {
        unlink("./images/$OldImage");
    //Image Upload
    $ImageName=$_FILES['image']['name'];
    $imagepath="./images/".$ImageName;   
    $tempname = $_FILES['image']['tmp_name'];
    // $ext = pathinfo($ImageName, PATHINFO_EXTENSION);
    // lowercase()
    move_uploaded_file($tempname, $imagepath);
    $ImageColum="Upload_image='$ImageName',";

    }      
      
    $firstname= $_POST['firstname'];
    $lastname = $_POST['lastname'];
    //$image= $_POST['image'];
    $gender = $_POST['gender'];
    $Department = implode(',', $_POST['department']);  
    $college = $_POST['college'];
    $description = $_POST['description'];   
    
    $sql = "update student set First_name='$firstname', Last_name='$lastname', ".$ImageColum." Gender='$gender', Department='$Department', College='$college', Description='$description' where customer_id=$customerID";  
   
    $result = $con->query($sql);
    header('Location:form.php');  

}

//Delete Students
if(isset($_GET['delete']))
{
    $deleteID = $_GET['delete'];
    $imagename= $_GET['ImageName'];
    unlink("./images/$imagename");
    $delete = "delete from student where customer_id=$deleteID"; 
    $result = $con->query($delete); 
    header('Location:form.php');  
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>

<?php if(isset($_GET['edit'])) { ?>
    
<form enctype="multipart/form-data" id="Updatregisterform" method="post">

<div class="form-group">
<label>FIRST NAME</label>
    <input type="text" class="form-control" name="firstname" value="<?php echo $getEditvalue['First_name']; ?>" >

</div>
<div class="form-group">
<label>LAST NAME</label>
    <input type="text" class="form-control" name="lastname" value="<?php echo $getEditvalue['Last_name']; ?>" >

</div>
<div class="form-group">
<label>UPLOAD YOUR IMAGE</label>
    <input type="file" class="form-control" name="image" >
    <div>
    <img style="max-width: 10%;" src="./images/<?php echo $getEditvalue['Upload_image']; ?>">
    </div>

</div>
<div class="form-group">
<label>GENDER</label><br><br>
  Male:<input type="radio" <?php if($getEditvalue['Gender']=='Male') { echo 'checked'; } ?>  name="gender" class="form-control" value="Male"> 
  Female:<input type="radio" <?php if($getEditvalue['Gender']=='Female') { echo 'checked'; } ?> name="gender" class="form-control" value="Female">

</div>
<div class="form-group">
<label>DEPARTMENT</label><br><br>
  CSE <input type="checkbox" <?php if($cse=='cse') { echo 'checked'; } ?>  class="form-control" name="department[]" value="cse"> 
  ECE <input type="checkbox" <?php if($ece=='ece') { echo 'checked'; } ?> class="form-control" name="department[]" value="ece"> 
  EEE <input type="checkbox" <?php if($eee=='eee') { echo 'checked'; } ?> class="form-control" name="department[]" value="eee">

</div>
<div class="form-group">
<label>COLLEGE</label><br><br>
<select name="college" >
    <option value="">--Select college--</option>
    <option <?php if($getEditvalue['College']=='SKP') { echo 'selected'; } ?> class="form-control" value="SKP" name="college">SKP ENGINEERING COLLEGE</option>
    <option <?php if($getEditvalue['College']=='ARUNAI') { echo 'selected'; } ?> class="form-control" value="ARUNAI" name="college">ARUNAI ENGINEERING COLLEGE</option>
  </select>

</div>
<div class="form-group">
<label>DESCRIPTION</label>

    <textarea name="description" class="form-control" placeholder="Enter description..."><?php echo $getEditvalue['Description']; ?> </textarea>

</div>
<input type="hidden" name="customerID" value="<?php echo $getEditvalue['customer_id']; ?>">
<input type="hidden" name="oldImageName" value="<?php echo $getEditvalue['Upload_image']; ?>">
<button type="submit" class="btn btn-info" value="UpdateRegistration" name="UpdateRegistration">Update</button><br><br>
</form>

<?php } else { ?>

    <form enctype="multipart/form-data" id="registerform" method="post">

<div class="form-group">
<label>FIRST NAME</label>
    <input type="text" class="form-control" name="firstname" >

</div>
<div class="form-group">
<label>LAST NAME</label>
    <input type="text" class="form-control" name="lastname" >

</div>
<div class="form-group">
<label>UPLOAD YOUR IMAGE</label>

    <input type="file" class="form-control" name="image" >

</div>
<div class="form-group">
<label>GENDER</label><br><br>
  Male:<input type="radio" checked name="gender" class="form-control" value="Male"> 
  Female:<input type="radio" name="gender" class="form-control" value="Female">

</div>
<div class="form-group">
<label>DEPARTMENT</label><br><br>
  CSE <input type="checkbox" class="form-control" name="department[]" value="cse"> 
  ECE <input type="checkbox" class="form-control" name="department[]" value="ece"> 
  EEE <input type="checkbox" class="form-control" name="department[]" value="eee">

</div>
<div class="form-group">
<label>COLLEGE</label><br><br>
<select name="college" >
    <option value="">--Select college--</option>
    <option  class="form-control" value="SKP" name="college">SKP ENGINEERING COLLEGE</option>
    <option class="form-control" value="ARUNAI" name="college">ARUNAI ENGINEERING COLLEGE</option>
  </select>

</div>
<div class="form-group">
<label>DESCRIPTION</label>

    <textarea name="description" class="form-control" placeholder="Enter description..."></textarea>

</div>

<button type="submit" class="btn btn-primary" value="Registration" name = "registration">Add</button><br><br>
</form>
<?php } ?>
<?php if($row>0) { ?>

<table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Sno</th>
      <th scope="col">First Name</th>
      <th scope="col">Last name</th>
      <th scope="col">Image</th>
      <th scope="col">Gender</th>
      <th scope="col">Department</th>
      <th scope="col">College</th>
      <th scope="col">Description</th>
      <th scope="col">Action</th>
    
    </tr>
  </thead>

  <tbody>
  <?php $id=1; foreach ($fetchall as $val){?>
    <tr>
    <td><?php echo $id?></td>
      <td><?php echo $val['First_name']; ?></td>
      <td><?php echo $val['Last_name']; ?></td>
      <td>
        <?php if($val['Upload_image']!='') { ?>
        <img style="max-width: 10%;" src="./images/<?php echo $val['Upload_image']; ?>">
        <?php } else  {?>
        <label>Image Not Found</label>
        <?php } ?>
      </td>
      <td><?php echo $val['Gender']; ?></td>
      <td><?php echo $val['Department']; ?></td>
      <td><?php echo $val['College']; ?></td>
      <td><?php echo $val['Description']; ?></td>
      <td> 
          <a href="?edit=<?php echo $id-1 ?>">Edit</a>
          <a href="?delete=<?php echo $val['customer_id']; ?>
          &ImageName=<?php echo $val['Upload_image']; ?>">Delete</a>
    </td>
    </tr>
    
  
  <?php $id++; }?>
  </tbody>
</table>
<?php }?>

</body>
</html>
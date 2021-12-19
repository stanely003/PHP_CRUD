<?php
include 'connection.php';


//Add Details;
If(isset($_POST['submit'])){

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$imagename = $_FILES['image']['name'];
$imagepath = "./images/".$imagename;
$tempPath = $_FILES['image']['tmp_name'];
move_uploaded_file($tempPath, $imagepath);
$gender = $_POST['gender'];
$department = implode(',' , $_POST['department']);
$college = $_POST['college'];
$description = $_POST['description'];

$sql = " insert into student (First_name, Last_name, Upload_image, Gender, Department, College,	Description)
        values ('$firstname', '$lastname', '$imagename', '$gender', '$department', '$college', '$description')";
$result = $con->query($sql);

}

//List all student details


$sql = "select * from student";
$result = $con->query($sql);
$fetchall = $result->fetch_all(MYSQLI_ASSOC);
$row= $result->num_rows;

//Edit Students

if(isset($_GET['edit'])){

    $geteditvalue= $fetchall[$_GET['edit']];
    
    $departments = $geteditvalue['Department'];

    $getdeptvalue = explode(',', $departments);

    $ece='';
    $cse='';
    $eee='';
    if(count($getdeptvalue)>0){
            foreach ($getdeptvalue as $dept){
                   if($dept=='ece'){
                       $ece=$dept;
                   }else if($dept=='cse'){
                       $cse=$dept;
                   }else{
                       $eee=$dept;
                   }
            }
    }

}

//Update register form

If(isset($_POST['update'])){
    $studentid= $_POST['studentid'];
    $oldimage = $_POST['oldimage'];

    $imagecolum ='';
    //oldimage delete
    if($_FILES['image']['name'] !='')

    {
         unlink("./images/$oldimage");

     $imagename = $_FILES['image']['name'];     
     $imagepath = "./images/".$imagename;
     $tempname = $_FILES['image']['tmp_name'];    
     move_uploaded_file($tempname, $imagepath);     
     $imagecolum = "Upload_image='$imagename',";
    }


    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $gender = $_POST['gender'];
    $department = implode(' ,' , $_POST['department']);
    $college = $_POST['college'];
    $description = $_POST['description'];
    
      $sql = "update student set First_name='$firstname', Last_name = '$lastname', ".$imagecolum."     
    Gender =  '$gender', Department='$department', College = '$college', Description = '$description'";

    $result = $con->query($sql);
    header('location:registerform.php');
}
//Delete Student Lists

if(isset($_GET['delete']))
{

    $deleteid = $_GET['delete'];
    $imagename = $_GET['imagename'];
    unlink("./images/$imagename");
    $sql = "delete from student where student_id = $deleteid";

    $result = $con ->query($sql);
    header('location:registerform.php');
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
<div class="container">
    <div class="row mt-4">
    </div>

    <?php  if(isset($_GET['edit']))  {  ?>

    <form method="post" enctype="multipart/form-data" id="regiterform">

        <div class="form-group">
            <label>FIRST NAME</label>
            <input type="text" class="form-control" name="firstname" value="<?php echo $geteditvalue['First_name'];?>">

        </div>
        <div class="form-group">
            <label>LAST NAME</label>
            <input type="text" class="form-control" name="lastname" value="<?php echo $geteditvalue['Last_name'];?>">

        </div>
        <div class="form-group">
            <label>UPLOAD YOUR IMAGE</label>

            <input type="file" class="form-control" name="image">
            <div>
                <img style="max-width:10%" ; src="./images/<?php echo $geteditvalue['Upload_image']; ?>">
            </div>

        </div>
        <div class="form-group">
            <label>GENDER</label><br><br>
            Male:<input type="radio" name="gender" <?php if($geteditvalue['Gender'] == 'Male') { echo 'checked';} ?>
                class="form-control" value="Male">
            Female:<input type="radio" name="gender" <?php if($geteditvalue['Gender'] == 'Female') { echo 'checked'; }?>
                class="form-control" value="Female">

        </div>
        <div class="form-group">
            <label>DEPARTMENT</label><br><br>
            CSE <input type="checkbox" <?php if($cse=='cse'){ echo 'checked';} ?> class="form-control"
                name="department[]" value="cse">
            ECE <input type="checkbox" <?php if($ece=='ece'){ echo 'checked';} ?> class="form-control"
                name="department[]" value="ece">
            EEE <input type="checkbox" <?php if($eee=='eee'){ echo 'checked';} ?> class="form-control"
                name="department[]" value="eee">

        </div>
        <div class="form-group">
            <label>COLLEGE</label><br><br>
            <select name="college">
                <option value="">--Select college--</option>
                <option class="form-control" <?php if($geteditvalue['College']=='SKP'){ echo 'selected';} ?> value="SKP"
                    name="college">SKP ENGINEERING COLLEGE</option>
                <option class="form-control" <?php if($geteditvalue['College']=='ARUNAI'){ echo 'selected';} ?>
                    value="ARUNAI" name="college">ARUNAI ENGINEERING COLLEGE</option>
            </select>

        </div>
        <div class="form-group">
            <label>DESCRIPTION</label>
            <textarea name="description" class="form-control"
                placeholder="Enter description..."><?php echo $geteditvalue['Description'];?></textarea>
        </div>
        <input type="hidden" name="studentid" value="<?php echo $geteditvalue['student_id']; ?>">
        <input type="hidden" name="oldimage" value="<?php echo $geteditvalue['Upload_image']; ?>">
        <button type="submit" class="btn btn-primary" value="update" name="update">UPDATE</button><br><br>
    </form>
    <?php } else { ?>
    <form method="post" enctype="multipart/form-data" id="registerform">
        <div class="form-group">
            <label>FIRST NAME</label>
            <input type="text" class="form-control" name="firstname">
        </div>
        <div class="form-group">
            <label>LAST NAME</label>
            <input type="text" class="form-control" name="lastname">
        </div>
        <div class="form-group">
            <label>UPLOAD YOUR IMAGE</label>

            <input type="file" class="form-control" name="image">
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
            <select name="college">
                <option value="">--Select college--</option>
                <option class="form-control" value="SKP" name="college">SKP ENGINEERING COLLEGE</option>
                <option class="form-control" value="ARUNAI" name="college">ARUNAI ENGINEERING COLLEGE</option>
            </select>
        </div>
        <div class="form-group">
            <label>DESCRIPTION</label>
            <textarea name="description" class="form-control" placeholder="Enter description..."></textarea>
        </div>
        <button type="submit" class="btn btn-primary" value="submit" name="submit">Add</button><br><br>
    </form>
    <?php } ?>
    <?php if($row>0) {?>
    <table border=2 style="text-align:center">
        <thead>
            <tr>
                <td>sl.No</td>
                <td>First Name</td>
                <td>Last Name</td>
                <td>Image</td>
                <td>Gender</td>
                <td>Department</td>
                <td>College</td>
                <td>Description</td>
                <td>Action</td>
            </tr>
        </thead>
        <?php $id=1; foreach($fetchall as $val) { ?>
        <tbody>
            <tr>
                <td><?php echo $id ?></td>
                <td><?php echo $val['First_name']; ?></td>
                <td><?php echo $val['Last_name']; ?></td>
                <td>
                    <?php if($val['Upload_image'] != '') { ?>
                    <img style="max-width :10%" ; src="./images/<?php echo $val['Upload_image'];?>">
                    <?php } else  {?>
                    <label>Image not Available</label>
                    <?php } ?>
                </td>
                <td><?php echo $val['Gender'];  ?></td>
                <td><?php echo $val['Department']; ?></td>
                <td><?php echo $val['College'] ?></td>
                <td><?php echo $val['Description'] ?></td>
                <td>
                    <a href="?edit=<?php echo $id-1; ?>">Edit</a>
                    <a href="?delete=<?php echo $val['student_id'];?>&imagename=<?php echo $val['Upload_image']; ?>">Delete</a>
                </td>
            </tr>
        </tbody>
        <?php $id++; }?>
    </table>
 <?php } ?>
    </div>
</body>

</html>
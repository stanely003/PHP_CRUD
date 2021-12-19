<?php

include 'connection.php';


//list customers tables

$sql = "select customer_id, name from customers";

$result = $con->query($sql);

$Getallcustomers = $result->fetch_all(MYSQLI_ASSOC);

//add vehicles

if(isset($_POST['submit']))
{

    $customerid = $_POST['customername'];
    $vehiclename = $_POST['vehiclename'];
    $model = $_POST['model'];
    $vin = $_POST['vin'];
    $sql = "insert into vehicle (customer_id, vehicle_name, vehicle_model,vehicle_vin) 
    values ('$customerid','$vehiclename','$model','$vin')";
    $result = $con->query($sql);
}

//list all vehicles in Vehicle table

$sql = "select v.*, c.name from vehicle as v inner join customers as c on c.customer_id = v.customer_id";
$result= $con->query($sql);
$fetchall = $result->fetch_all(MYSQLI_ASSOC);
$row = $result->num_rows;


//edit vehicle table

if(isset($_GET['edit']))
{

    $editid = $_GET['edit'];
    $sql = "select * from vehicle where vehicle_id=$editid";
    $result = $con->query($sql);
    $fetch = $result->fetch_array(MYSQLI_ASSOC);
    $row = $result->num_rows;
}

//Update customer

if(isset($_POST['Update']))
{
   
  $vehicleid=$_POST['vehID'];
  $customerid =$_POST['customername'];
  $vehiclename = $_POST['vehiclename'];
  $model = $_POST['model'];
  $vin = $_POST['vin'];
  $sql= "update vehicle set customer_id=$customerid, vehicle_name='$vehiclename', 
  vehicle_model='$model', vehicle_vin='$vin' where vehicle_id=$vehicleid";
  
  $result = $con->query($sql);
  header('location:vehicle.php');
}

//Delete customer

if(isset($_GET['delete']))
{
    $deleteid = $_GET['delete'];    
    echo $sql= "delete from vehicle where vehicle_id=$deleteid";
    $result = $con->query($sql);
    header('location:vehicle.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Document</title>
</head>
<body class ="container, text-center">
<?php if(isset($_GET['edit'])) { ?>
<form method='post' >
    
<div class="form-group">
<label>Customer Name</label>
<select name="customername">
 <option value="">-- Select Customer Name--</option>
    <?php foreach($Getallcustomers as $val) {?>

        <option value="<?php echo $val['customer_id'];?>"
        <?php if($val['customer_id']==$fetch['customer_id']) { echo  "selected";    } ?> >
        <?php echo $val['name']; ?> </option>

       <?php } ?> 

  </select>
</div>
<div class="form-group">
<label>VEHICLE NAME</label>
    <input type="text" class="form-control" name="vehiclename" value ="<?php echo $fetch['vehicle_name']; ?>" >

</div>
<div class="form-group">
<label>VEHICLE MODEL</label>
    <input type="text" class="form-control" name="model" value ="<?php echo $fetch['vehicle_model']; ?>">

</div>
<div class="form-group">
<label>VEHICLE VIN</label>
    <input type="text" class="form-control" name="vin" value ="<?php echo $fetch['vehicle_vin']; ?>">

</div>

    <input type = "hidden" name = "vehID"  value ="<?php echo $fetch['vehicle_id']; ?>">

    <input type = "submit" name="Update" value="Update"><br><br>

</form>

<?php } else { ?>
<form method='post' >
    
<div class="form-group">
<label>Customer Name</label>
<select name="customername">
 <option value="">-- Select Customer Name--</option>
    <?php foreach($Getallcustomers as $val) {?>

        <option value="<?php echo $val['customer_id'];?>"><?php echo $val['name']; ?> </option>

       <?php } ?> 

  </select>
</div>
<div class="form-group">
<label>VEHICLE NAME</label>
    <input type="text" class="form-control" name="vehiclename" >

</div>
<div class="form-group">
<label>VEHICLE MODEL</label>
    <input type="text" class="form-control" name="model">

</div>
<div class="form-group">
<label>VEHICLE VIN</label>
    <input type="text" class="form-control" name="vin" >

</div>
<button type="submit" class="btn btn-primary" name = "submit">Add</button><br><br>
</form>

<?php } ?>
<?php if($row>0) { ?>
<table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Vehicle Id</th>
      <th scope="col">Customer Name</th>
      <th scope="col">Vehicle name</th>
      <th scope="col">Vehicle Model</th>
      <th scope="col">Vehicle Vin</th>
      <th scope="col">Action</th>
    
    </tr>
  </thead>
  <tbody>
  <?php $id=1; foreach ($fetchall as $val){?>
    <tr>
    <td><?php $id?></td>
      <td><?php echo $val['name']; ?></td>
      <td><?php echo $val['vehicle_name']; ?></td>
      <td><?php echo $val['vehicle_model']; ?></td>
      <td><?php echo $val['vehicle_vin']; ?></td>
      <td> 
          <a href="?edit=<?php echo $val['vehicle_id']; ?>">Edit</a>
          <a href="?delete=<?php echo $val['vehicle_id']; ?>">Delete</a>
    </td>
    </tr>
      
  <?php $id++; }?>
  </tbody>
</table>
<?php }?>
</body>
</html>
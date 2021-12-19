<?php
include 'connection.php';

//extract user name from customer table

$sql = "select customer_id, name from customers";
$result = $con->query($sql);
$fetch = $result->fetch_all(MYSQLI_ASSOC);
$row= $result->num_rows;


//Add Vehicles in table

if(isset($_POST['add']))
{
   $customerid = $_POST['customername'];
   $vehiclename=$_POST['vehiclename'];
   $model = $_POST['model'];
   $vin = $_POST['vin'];
   
   $sql = "insert into vehicles (customer_id, Vehicle_Name,	Vehicle_Model, Vehicle_Vin) 
   values('$customerid', '$vehiclename',  '$model', '$vin')";
   $result= $con->query($sql);
}


//List all Vehicles;

$sql="select v.*, c.name from vehicles as v inner join customers as c on c.customer_id=v.customer_id";
$result=$con->query($sql);
$fetchall = $result->fetch_all(MYSQLI_ASSOC);
$row = $result->num_rows;


//Edit vehicle table

if(isset($_GET['edit'])){

    $editid=$_GET['edit'];
    $sql = "select * from vehicles where Vehicle_ID= $editid";
    $result = $con->query($sql);
    $fetchalls = $result->fetch_array(MYSQLI_ASSOC);
   
    $row = $result->num_rows;
}


//Update table

if(isset($_POST['update']))
{
$vehid = $_POST['vehid'];
$customerid = $_POST['customername'];
$Vehiclename = $_POST['vehiclename'];
$model = $_POST['model'];
$vin = $_POST['vin'];
$sql = "update vehicles set customer_id = '$customerid', Vehicle_Name = '$Vehiclename', Vehicle_Model = '$model',
          Vehicle_Vin = '$vin' where Vehicle_ID = '$vehid'"; 
       
$result = $con->query($sql);

//header('location:customer.php');

}

//Delete customer

if(isset($_GET['delete']))
{
    $deleteid = $_GET['delete'];    
    echo $sql= "delete from vehicles where Vehicle_ID=$deleteid";
    $result = $con->query($sql);
    //header('location:vehicle.php');

}


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

<?php if(isset($_GET['edit'])) { ?>
    <form style="text-align:center" method="post">
        <label>Welcome:<?php echo $_SESSION['loginUsers'];?></label><label><a href='location: login.php'></a>Logout</label><br><br>


        <label>Customer Name</label>
        <select name=customername>
            <option val="">--Select Customer Name</option>
            <?php foreach($fetch as $val) {?>
            <option value="<?php echo $val['customer_id']; ?>"
            <?php if($val['customer_id']==$fetchalls['customer_id']){ echo "selected"; } ?>> 
            <?php echo $val['name'] ?></option>
            <?php } ?>
        </select><br><br>
        <label>Vehicle Name</label>
        <input type="text" name="vehiclename" value="<?php echo $fetchalls['Vehicle_Name'];?>" ><br><br>
        <label>Vehicle Model</label>
        <input type="text" name="model" value="<?php echo $fetchalls['Vehicle_Model'];?>"><br><br>
        <label>Vehicle Vin</label>
        <input type="text" name="vin" value="<?php echo $fetchalls['Vehicle_Vin'];?>"><br><br>
        <input type="hidden" name="vehid" value="<?php echo $fetchalls['Vehicle_ID'];?>">
        <input type="submit" value="update" name="update">


    </form>
<?php } else { ?>
    <form style="text-align:center" method="post">
        <label>Welcome:<?php echo $_SESSION['loginUsers'];?></label>
        <a href= "header('location:login.php');">Logout</a><br><br>

        <label>Customer Name</label>
        <select name=customername>
            <option val="">--Select Customer Name</option>
            <?php foreach($fetch as $val) {?>
            <option value="<?php echo $val['customer_id']; ?>"><?php echo $val['name'] ?></option>

            <?php } ?>
        </select><br><br>
        <label>Vehicle Name</label>
        <input type="text" name="vehiclename"><br><br>
        <label>Vehicle Model</label>
        <input type="text" name="model"><br><br>
        <label>Vehicle Vin</label>
        <input type="text" name="vin"><br><br>
        <input type="submit" value="submit" name="add">

    </form>
    <?php } ?>
    <?php if($row>0) {?>
    <table>
<thead>
    <tr>

<td>Vehicle Id</td>
<td>Customer Name</td>
<td>Vehicle Name</td>
<td>Vehicle Model</td>
<td>Vehicle Vin</td>
<td>Action</td>
</tr>
</thead>
<tbody>
    <?php $id=1; foreach($fetchall as $val) { ?>
    <tr>
        <td><?php $id ?></td>
        <td><?php echo $val['name']; ?></td>
        <td><?php echo $val['Vehicle_Name']; ?></td>
        <td><?php echo $val['Vehicle_Model'];?></td>
        <td><?php echo $val['Vehicle_Vin']; ?></td>
        <td>
<a href="?edit=<?php  echo $val['Vehicle_ID']; ?>">Edit</a>
<a href="?delete=<?php  echo $val['Vehicle_ID']; ?>">Delete</a>
</td>
</tr>
<?php $id++; } ?>
    </tbody>
    </table>
    <?php } ?>
</body>

</html>
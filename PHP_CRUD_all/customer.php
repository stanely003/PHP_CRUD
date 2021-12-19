<?php
include 'connection.php';

if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $sql ="insert into customers (name, email, phone, address) values('$name','$email', '$phone','$address')";
    $result= $con->query($sql);

}
 //List all customers   

$sql = "select * from customers"; 
$result = $con->query($sql);
$fetchall = $result->fetch_all(MYSQLI_ASSOC);
$row = $result->num_rows; 


//edit customers

if(isset($_GET['edit'])){
    $editid = $_GET['edit'];
    $sql = "select * from customers where customer_id = '$editid'";
    $result = $con->query($sql);
    $fetch= $result->fetch_array(MYSQLI_ASSOC);
    $row = $result->num_rows;

}


//update customers

if(isset($_POST['update'])){

    $customerid = $_POST['custID'];
    $name =$_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $sql = "update customers set name='$name', email='$email', phone='$phone', address='$address' where customer_id='$customerid'";
    $result = $con->query($sql);
    header('location:customer.php');
}

//Delete customers

if(isset($_GET['delete'])){

    $deleteid = $_GET['delete'];
    $sql = "delete from customers where customer_id=$deleteid";
    $result = $con->query($sql);
    header('location:customer.php');
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


<?php if(isset($_GET['edit'])){?>

    <form method='post' >


<label>Welcome:<?php echo $_SESSION['loginUsers']; ?> </label>  <a href="login.php">logout</a>
    
<div class="form-group">
<label>Customer Name</label>
    <input type="text" class="form-control" name="name" value = <?php echo $fetch['name'];?> >

</div>
<div class="form-group">
<label>Customer Email</label>
    <input type="email" class="form-control" name="email" value = <?php echo $fetch['email'];?> >

</div>
<div class="form-group">
<label>Customer Phone</label>
    <input type="text" class="form-control" name="phone" value = <?php echo $fetch['phone'];?>>

</div>
<div class="form-group">
<label>Customer Address</label>
    <input type="text" class="form-control" name="address" value = <?php echo $fetch['address'];?> >

</div>
<input type="hidden" name = "custID" value = "<?php echo $fetch['customer_id'];?>">
<button type="submit" class="btn btn-primary" name = "update">Update</button><br><br>
<button><a href="vehicle.php" >Vehicle page</button>
</form>

<?php } else{ ?>


<form method='post' >


<label>Welcome : <?php echo $_SESSION['loginUsers']; ?> </label>  <a href="login.php">logout</a>
    
<div class="form-group">
<label>Customer Name</label>
    <input type="text" class="form-control" name="name" >

</div>
<div class="form-group">
<label>Customer Email</label>
    <input type="email" class="form-control" name="email" >

</div>
<div class="form-group">
<label>Customer Phone</label>
    <input type="text" class="form-control" name="phone">

</div>
<div class="form-group">
<label>Customer Address</label>
    <input type="text" class="form-control" name="address" >

</div>
<button type="submit" class="btn btn-primary" name = "submit">Add</button><br><br>
<button class="btn btn-primary, text-light" ><a href="vehicle.php">Vehicle page</button><br><br>


</form>
<?php } ?>
<?php if($row>0) { ?>
<table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Customer Id </th>
      <th scope="col">Customer Name</th>
      <th scope="col">Customer Email</th>
      <th scope="col">Customer Phone</th>
      <th scope="col">Customer Address</th>
      <th scope="col">Action</th>
    
    </tr>
  </thead>
  <tbody>
  <?php $id=1; foreach ($fetchall as $val){?>
    <tr>
    <td><?php echo $id ?></td>
      <td><?php echo $val['name']; ?></td>
      <td><?php echo $val['email']; ?></td>
      <td><?php echo $val['phone']; ?></td>
      <td><?php echo $val['address']; ?></td>
      <td> 
          <a href="?edit=<?php echo $val['customer_id']; ?>">Edit</a>
          <a href="?delete=<?php echo $val['customer_id']; ?>">Delete</a>
    </td>
    </tr>
    
  
  <?php $id++; }?>
  </tbody>
</table>
<?php }?>



</body>
</html>




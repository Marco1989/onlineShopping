<?php
require_once ('session_check.php');
require_once('mysqlclass.php');
require_once ('db_info.php');
$cid = $_GET[id];
if(isset($_POST['cid'])){
    $cid = $_POST['cid'];
}
if (isset($_POST['addsubmit'])){
    $sql = "update Product set
	Pname='".$_POST['pname']."', 
	Price='".$_POST['price']."', 
	Category='".$_POST['category']."',
	Status='".$_POST['status']."', 
	Description='".$_POST['description']."'
	where PID=".$cid;
    echo $sql;
    $result = $db->query($sql);
    echo "updated product info<br>";
    //header("Location: customer.php");
}
if (isset($_POST['cansubmit'])){
    header("Location: product.php");
}
$sql = "select * from Product p inner join ProductStore ps where p.PID=".$cid;
$result = $db->query($sql);
$row = $result->fetch();
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="cid" value="<?php echo $row['PID'];?>">
                Product Name: <input type="text" name="pname" maxlength="30" size="40" value="<?php echo $row['Pname'];?>"><br/>
                Price: <input type="text" name="price" maxlength="50" size="60" value="<?php echo $row['Price'];?>"><br/>
                <select name="category">
                    <option value="c1" <?php if (($_POST['category']) == "c1"){echo 'selected="selected"';} ?>>Tablet</option>
                    <option value="c2" <?php if (($_POST['category']) == "c2"){echo 'selected="selected"';} ?>>Laptop</option>
                    <option value="c3" <?php if (($_POST['category']) == "c3"){echo 'selected="selected"';} ?>>Desktop</option>
                    <option value="c4" <?php if (($_POST['category']) == "c4"){echo 'selected="selected"';} ?>>Media Device</option>
                    <option value="c5" <?php if (($_POST['category']) == "c5"){echo 'selected="selected"';} ?>>Phone</option>
                    <option value="c6" <?php if (($_POST['category']) == "c6"){echo 'selected="selected"';} ?>>Camera</option>
                </select><br />
                Status: <input type="text" name="status" maxlength="1" size="5" value="<?php echo $row['Status'];?>"><br/>
                Quantity: <input type="text" name="quantity" maxlength="10" size="20" value="<?php echo $row['Quantity'];?>"><br/>
                Description: <input type="text" name="description" maxlength="50" size="60" value="<?php echo $row['Description'];?>"><br/>
                <input type="submit" name="addsubmit" value="Modify"/>
                <input type="submit" name="cansubmit" value="Return"/>
</form>
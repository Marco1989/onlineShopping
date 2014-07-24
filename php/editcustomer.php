<?php
require_once ('session_check.php');
require_once('mysqlclass.php');
require_once ('db_info.php');
$type = $_GET[type];
$cid = $_GET[id];
if (!mysql_connect($db_host, $db_user, $db_pwd))
    die("Can't connect to database");
if (!mysql_select_db($db_name))
    die("Can't select database");
if(isset($_POST['cid'])){
    $cid = $_POST['cid'];
}
if (isset($_POST['addsubmit'])){
    $sql = "update Customer set
	Name='".$_POST['fname']."', 
	Email='".$_POST['email']."', 
	Street='".$_POST['street']."',
	City='".$_POST['city']."', 
	State='".$_POST['state']."', 
	ZIP='".$_POST['zip']."' where CID=".$cid;
    $result = mysql_query($sql) or die ( mysql_error() );
    if (isset ($_POST['gender'])){
        $sql = "update HomeCustomer set
	Gender='".$_POST['gender']."',
	Age='".$_POST['age']."',
	MaritalStatus='".$_POST['mstatus']."',
	Income='".$_POST['income']."' where CID=".$cid;
        $result = mysql_query($sql) or die ( mysql_error() );
    }else{
        $sql = "update BusinessCustomer set
	CompanyName='".$_POST['cname']."', 
        Category='".$_POST['category']."', 
	GrossIncome='".$_POST['income']."', 
	AcctNumber='".$_POST['anumber']."' where CID=".$cid;
        $result = mysql_query($sql) or die ( mysql_error() );
    }
    echo "updated customer info<br>";
    //header("Location: customer.php");
}
if (isset($_POST['cansubmit'])){
    header("Location: customer.php");
}
if (($type == "home") || (isset ($_POST['gender']))){
	$sql = "select * from HomeCustomer hc inner join Customer c where hc.CID=". $cid ." and hc.CID=c.CID";
}else{
	$sql = "select * from BusinessCustomer bc inner join Customer c where bc.CID=". $cid ." and bc.CID=c.CID";
}
$result = mysql_query($sql) or die ( mysql_error() );
$row = mysql_fetch_array($result);
mysql_close();
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="cid" value="<?php echo $row['CID'];?>">
                Name: <input type="text" name="fname" maxlength="50" size="60" value="<?php echo $row['Name'];?>"><br/>
                Email: <input type="text" name="email" maxlength="50" size="60" value="<?php echo $row['Email'];?>"><br/>
                Street: <input type="text" name="street" maxlength="30" size="40" value="<?php echo $row['Street'];?>"><br/>
                City: <input type="text" name="city" maxlength="20" size="30" value="<?php echo $row['City'];?>"><br/>
                State: <input type="text" name="state" maxlength="2" size="5" value="<?php echo $row['State'];?>"><br/>
                Zip: <input type="text" name="zip" maxlength="5" size="10" value="<?php echo $row['ZIP'];?>"><br/>
            <?php if (($type == "home") || (isset ($_POST['gender']))){ ?>
                <input type="radio" name="gender" value="M" <?php if(($row['Gender'])=='M'){echo "checked";}?>>Male
                <input type="radio" name="gender" value="F" <?php if(($row['Gender'])=='F'){echo "checked";}?>>Female<br/>
                Age: <input type="text" name="age" maxlength="3" size="5" value="<?php echo $row['Age'];?>"><br/>
                <input type="radio" name="mstatus" value="S" <?php if(($row['MaritalStatus'])=='S'){echo "checked";}?>>Single
                <input type="radio" name="mstatus" value="M" <?php if(($row['MaritalStatus'])=='M'){echo "checked";}?>>Married<br/>
                Income:<input type="text" name="income" maxlength="50" size="60" value="<?php echo $row['Income'];?>"><br/>
            <?php }else{ ?>
                Company: <input type="text" name="cname" maxlength="20" size="30" value="<?php echo $row['CompanyName'];?>"><br/>
                Category: <input type="text" name="category" maxlength="20" size="30" value="<?php echo $row['Category'];?>"><br/>
                Yearly Income: <input type="text" name="income" maxlength="50" size="60" value="<?php echo $row['GrossIncome'];?>"><br/>
                Account Number: <input type="text" name="anumber" maxlength="10" size="20" value="<?php echo $row['AcctNumber'];?>"><br/>
            <?php } ?>
                <input type="submit" name="addsubmit" value="Modify"/>
                <input type="submit" name="cansubmit" value="Return"/>
            </form>



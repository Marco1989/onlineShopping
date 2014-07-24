<?php
require_once('mysqlclass.php');
require_once ('db_info.php');
require_once('cartfunctions.php');
session_start(); // Start a new session
if(isset($_POST['submit']))
{
        // Get the data passed from the form
	$username = $_POST['user'];
	$password = $_POST['pass'];
        //if (!mysql_connect($db_host, $db_user, $db_pwd))
        //    die("Can't connect to database");
        //if (!mysql_select_db($db_name))
         //   die("Can't select database");
        // sending query 
	$sql = "select se.Role, se.SID, se.EID, s.City from SalesEmployees se, Store s where se.Login = '$username' and se.Password = '$password' and se.SID=s.SID";
	$result = $db->query($sql);
	$row = $result->fetch();
	//$sql = "select * from SalesEmployees where Login = '$username' and Password = '$password'";
	//$result = mysql_query($sql) or die ( mysql_error() );
        //$row = mysql_fetch_array($result);
        //$count = mysql_num_rows($result);
	if (isset($row['SID'])) {
	    $_SESSION['loggedIn'] = $_POST['user'];
            $_SESSION['role'] = $row['Role'];
            $_SESSION['store'] = $row['SID'];
            $_SESSION['eid'] = $row['EID'];
            $_SESSION['city'] = $row['City'];
	    //header("Location: employee.php"); // This is wherever you want to redirect the user to
	} else {
	      // Wherever you want the user to go when they fail the login
            ?><p>Login incorrect</p><?php
	}
        
}
if(isset($_POST['searchsubmit']))
{
    $p_name = $_POST['search'];
    $query1="select 
    p.PID, s.City, p.Pname, p.Category, ps.Quantity, p.Price, p.Description
    from 
    Store s, Product p, ProductStore ps
    where
    p.PID=ps.PID and ps.SID=s.SID and p.Status='1' and p.Pname like '%".$p_name."%'
    order by 
    s.City";
}elseif(isset($_POST['cityfilter']))
{
    $s_sid = $_POST['city'];  
    $query1="select 
    p.PID, s.City, p.Pname, p.Category, ps.Quantity, p.Price, p.Description
    from 
    Store s, Product p, ProductStore ps
    where
    p.PID=ps.PID and ps.SID=s.SID and p.Status='1' and s.SID=".$s_sid."
    order by 
    s.City";
}elseif(isset($_POST['emailsubmit']))
{
    $s_email = $_POST['emailsearch'];
    $query1="SELECT 
        Product.Pname AS 'Product Name'
        FROM 
        Transactions, Customer, Product
        WHERE 
        Transactions.CID = Customer.CID AND Product.PID = Transactions.PID AND Customer.email = '". $s_email."'";
}elseif(isset($_SESSION['loggedIn'])){
    $query1="select 
    p.PID, s.City, p.Pname, p.Category, ps.Quantity, p.Price, p.Description
    from 
    Store s, Product p, ProductStore ps, SalesEmployees se
    where
    p.PID=ps.PID and ps.SID=s.SID and p.Status='1' and s.SID=se.SID
    and se.EID=".$_SESSION['eid']."
    order by
    p.Category";   
}else{
    $query1="select 
    p.PID, s.City, p.Pname, p.Category, ps.Quantity, p.Price, p.Description
    from 
    Store s, Product p, ProductStore ps
    where
    p.PID=ps.PID and ps.SID=s.SID and p.Status='1'
    order by 
    s.City";
}
?>
<html>
<head>
    <title>Store Front-end</title>
</head>
<body>
 <?php  if (!isset($_SESSION['loggedIn']))
   { ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	     Username: <input type="text" name="user" />
	     Password: <input type="password" name="pass" />
             <input type="submit" name="submit" value="Login"/>
    </form>
 <?php }else{ ?>
    <div>
     <span>Logged In: <?php echo $_SESSION['loggedIn']; ?></span>
     <span><a href="logout.php">[logout]</a></span>
    </div>
    <div>
        <span><a href="customer.php">Customer Management</a> |</span>
        <span><a href="product.php">Product Management</a></span>
    <?php if (($_SESSION['role']) >= 1)
        { ?>
        <span>
            | <a href="employee.php">Admin Portal</a>
        </span>
 <?php } 
       }?>
    </div>
    
<?php
//if (!mysql_connect($db_host, $db_user, $db_pwd))
//    die("Can't connect to database");

//if (!mysql_select_db($db_name))
//    die("Can't select database");?><br />

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<?php 
$sql= "select * from Store";
$result = $db->query($sql);
//$sql= "select * from Store";
//$result = mysql_query($sql) or die ( mysql_error() );
?>
<select name="city">
<?php 
    //while($row = mysql_fetch_array($result)){ 
    while($row = $result->fetch()){
?>
<option value="<?php echo $row['SID']; ?>" <?php if ($_POST['city'] == $row['SID']){echo 'selected="selected"';} ?>><?php echo $row['City']; ?></option><?php
} ?>
</select>
<input type="submit" name="cityfilter" value="Filter City" />
<input type="submit" name="rcityfilter" value="Remove Filter" />
</form>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    Search All Products: <input type="text" name="search" />
    <input type="submit" name="searchsubmit" value="Search"/>
</form>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    Search Purchases by Email: <input type="text" name="emailsearch" />
    <input type="submit" name="emailsubmit" value="Search"/>
</form>
<?php
if (isset($_SESSION['loggedIn'])){
    echo writeShoppingCart();
}
//mysql_free_result($result);
// sending query
$result = mysql_query($query1) or die ( mysql_error() );
$fields_num = mysql_num_fields($result);
$count = mysql_num_rows($result);

    echo "<h1>Products</h1>";
    echo $count." Products listed<br />";

echo "<table border='1'><tr>";
// printing table headers
for($i=0; $i<$fields_num; $i++)
{
    $field = mysql_fetch_field($result);
    echo "<td>{$field->name}</td>";
}
if ((isset($_SESSION['loggedIn'])) and (!isset($_POST['emailsubmit']))){
        echo "<td>Add to Cart</td>";
        //<a href="cart.php?action=add&id='.$row['id'].'">Add to cart</a>
    } 
echo "</tr>\n";
// printing table rows
while($row = mysql_fetch_row($result))
{
    echo "<tr>";
    // $row is array... foreach( .. ) puts every element
    // of $row to $cell variable
    foreach($row as $cell)
        echo "<td>".$cell."</td>";
    //echo $row[1]." ".$_SESSION['city']."\n";
    if ((isset($_SESSION['loggedIn'])) and (!isset($_POST['emailsubmit'])) and ($row[4]>0) and ($row[1]==$_SESSION['city'])){
        echo '<td><a href="cart.php?action=add&id='.$row[0].'">Add to cart</a></td>';
    }elseif ((isset($_SESSION['loggedIn'])) and (!isset($_POST['emailsubmit'])) and ($row[4]==0) and ($row[1]==$_SESSION['city'])){
        echo "<td>Sold Out</td>";
    }
    echo "</tr>\n";
}
mysql_free_result($result);
mysql_close();
?>
</body>
</html>
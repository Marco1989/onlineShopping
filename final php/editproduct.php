<?php
require_once ('session_check.php');
require_once('mysqlclass.php');
require_once ('db_info.php');    
require_once 'cartfunctions.php';
$cid = $_GET[id];
$storeid = $_GET[sid1];
$oldqty = $_GET[qty];
$storename = $_GET[sname];
if(isset($_POST['cid'])){
    $cid = $_POST['cid'];
}
if(isset($_POST['qtysubmit'])){
    $sql = "update ProductStore set
	Quantity='".$_POST['newqty']."'
	where PID=".$cid." and SID=".$_POST['sid'];
    $qtyupdated = "QTY updated for ".$_POST['sid'];
    $result = $db->query($sql);
}
if (isset($_POST['addsubmit'])){
    $sql = "update Product set
	Pname='".$_POST['pname']."', 
	Price='".$_POST['price']."', 
	Category='".$_POST['category']."',
	Status='".$_POST['status']."', 
	Description='".$_POST['description']."'
	where PID=".$cid;
    //echo $sql;
    $result = $db->query($sql);
}
if (isset($_POST['cansubmit'])){
    header("Location: product.php");
}
$sql = "select p.PID, p.Pname, p.Price, p.Category, p.Status, p.Description from Product p where p.PID=".$cid;
$result = $db->query($sql);
$row = $result->fetch();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Database final project</title>
<link href="templatemo_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="dom1.js"></script>
<script language="javascript" type="text/javascript">
function clearText(field)
{
    if (field.defaultValue == field.value) field.value = '';
    else if (field.value == '') field.value = field.defaultValue;
}
</script>
<style type="text/css">
#templatemo_container_wrapper #templatemo_container #templatemo_banner #templatemo_menu ul li a {
	font-size: 16px;
}
</style>
</head>
<body>
<div id="templatemo_container_wrapper">
<div id="templatemo_container">
	<div id="templatemo_banner">
  <div id="site_title">
            <h1><a href="" target="_parent">Database Final</a></h1>
            <p>Class NO. 2710 </p>
                                             <p id="socialicons3"><a href="http://www.facebook.com"><img src="images/facebook.png" alt="" width="24" height="24" /></a><a href="http://www.linkedin.com"><img src="images/in.png" alt="" width="24" height="24" /></a><a href="http://www.twitter.com"><img src="images/twitter.png" alt="" width="24" height="24" /></a><a href="http://www.google.com/reader"><img src="images/rss.png" alt="" width="24" height="24" /></a></p>
  </div>
        
        <div id="templatemo_menu">
            
            
            
             <ul>
                <li><a href="index.php" >Home</a></li>
                <li><a href="">Services</a></li>
                <li><a href="">About</a></li>
                <li><a href="">Contact</a></li>
                </ul>
		</div> <!-- end of menu -->
    
    </div> <!-- end of banner -->
    
    <div id="templatemo_content">
    	
        <div id="side_column">
                        <div class="side_column_box">
                            <div class="side_column_box">
                            <h2>NAV</h2>
                            <img src="images/al.png" width="250" height="143" />
                        </div>
                            <div>
                                <span>Logged In: <?php echo $_SESSION['loggedIn']; ?></span>
                            </div>
                            <div>
                                <span><a href="logout.php">[logout]</a></span>
                            </div>
                            <div>
                                <br />
                                <span><a href="index.php">Home Page</a></span>
                            </div>
                            <div><br />
                                <span><a href="customer.php">Customer Management</a></span>
                            </div>
                            <div>
                                <span><a href="product.php">Product Management</a></span>
                            </div>
                      <?php if (($_SESSION['role']) >= 1) { ?>
                                <div>
                                    <span><a href="employee.php">Admin Portal</a></span>
                                </div>
                      <?php } ?>
                            <br/><br/>
                            <p><img src="images/shopping_cart.png" width="35" height="32" />Your Cart</p>
                      <?php echo writeShoppingCart(); ?>
                        </div>
                        <div class="bottom">
                            
                        </div>
            </div>
        </div> <!-- end of side column -->
        
        <div id="main_column">
          <div class="main_column_section">       
            
            <h2><span></span>Products Management</h2>
                <?php if (isset($_POST['addsubmit'])){ echo "Updated Product Info<br>"; } ?>
                <?php if (isset($qtyupdated)){ echo "Updated QTY to ".$_POST['newqty']."<br/>"; } ?>
                
            <div class="main_column_section_content">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="cid" value="<?php echo $row['PID'];?>">
                Product Name: <input type="text" name="pname" maxlength="30" size="40" value="<?php echo $row['Pname'];?>"><br/>
                Price: <input type="text" name="price" maxlength="50" size="60" value="<?php echo $row['Price'];?>"onkeyup="filter2(this)" onblur="checkObj(this,'price')" ><br/>
          <?php 
                $sql2= "select distinct Category from Product";
                $result2 = $db->query($sql2);
           ?>
                Category: <select name="category">
           <?php    
                    while($row2 = $result2->fetch()){
                    ?>
                        <option value="<?php echo $row2['Category']; ?>" <?php if ($_POST['category'] == $row2['Category']){echo 'selected="selected"';} ?>><?php echo $row2['Category']; ?></option><?php
                    } ?>
                </select>
                <br />
                Description: <input type="text" name="description" maxlength="50" size="60" value="<?php echo $row['Description'];?>"><br/>
                Status: <input type="text" name="status" maxlength="1" size="5" value="<?php echo $row['Status'];?>" onkeyup="filter2(this)" onblur="checkObj(this,'status')"><br/>
                <input type="submit" name="addsubmit" value="Modify"/>
                <input type="submit" name="cansubmit" value="Return"/>
</form>
                <?php
                $sql= "select s.SID, s.City, ps.Quantity from Store s, Product p, ProductStore ps where p.PID=ps.PID and ps.SID=s.SID and p.PID=".$cid;
                $result = $db->query($sql);
                echo "<table border='1'><tr>";
                          
                            echo "<td>SID</td><td>Store</td><td>Quantity</td><td>Modify QTY</td>";
                            echo "</tr>\n";
                            while($row = $result->fetch()) {
                                echo "<tr>";
                                foreach($row as $cell){
                                    echo "<td>$cell</td>";
                                }
                                $sid = $row['SID'];
                                $qty = $row['Quantity'];
                                $sname = $row['City'];
                                echo '<td><a href="editproduct.php?id=', urlencode($cid), '&sid1=', urlencode($sid), '&qty=', urlencode($qty), '&sname=', urlencode($sname), '">Modify</a></td>';
                                echo "</tr>\n";
                            }
                
if (isset($oldqty)){ ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                Store: <input type="text" name="store" maxlength="30" size="40" value="<?php echo $storename; ?>" disabled><br/>
                                Old QTY: <input type="text" name="oldqty" maxlength="10" size="11" value="<?php echo $oldqty; ?>" disabled><br/>
                                New QTY: <input type="text" name="newqty" maxlength="10" size="11" value="<?php echo $oldqty; ?>"onkeyup="filter2(this)" onblur="checkObj(this,'nq')" ><br/>
                                <input name="sid" value="<?php echo $storeid; ?>" type="hidden">
                                <input name="cid" value="<?php echo $cid; ?>" type="hidden">
                                <input type="submit" name="qtysubmit" value="Update"/>
                                <input type="submit" name="qtycancel" value="Cancel"/>
    </form> 
<?php } ?>
            </div>
          </div>
        
        	
            <div class="cleaner"></div>
        </div> <!-- end of main column -->
    
   	 
    </div> <!-- end of content --><!-- end of footer -->

</div> <!-- end of container -->
</body>
</html>
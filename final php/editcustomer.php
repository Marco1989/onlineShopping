<?php
    require_once ('session_check.php');
    require_once('mysqlclass.php');
    require_once ('db_info.php');
    require_once 'cartfunctions.php';
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Database final project</title>
        <link href="templatemo_style.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="dom1.js"></script>
        <script language="javascript" type="text/javascript">
            function clearText(field){
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
                 <!-- end of side column -->
                <div id="main_column">
                    <div class="main_column_section">       
                        <h2><span></span>Customer Management</h2>
                        <div class="main_column_section_content">
                            <?php 
                            if (isset($_POST['addsubmit'])){
                                echo "Updated Customer Information<br /><br />";
                            }?>
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                <input type="hidden" name="cid" value="<?php echo $row['CID']; ?>" />
                                Name: <input type="text" name="fname" maxlength="50" size="60" value="<?php echo $row['Name'];?>" onkeyup="filter1(this)" onblur="checkObj(this,'name')"><br/>
                                Email: <input type="text" name="email" maxlength="50" size="60" value="<?php echo $row['Email'];?>"><br/>
                                Street: <input type="text" name="street" maxlength="30" size="40" value="<?php echo $row['Street'];?>"><br/>
                                City: <input type="text" name="city" maxlength="20" size="30" value="<?php echo $row['City'];?>" onkeyup="filter1(this)" onblur="checkObj(this,'city')"><br/>
                                State: <input type="text" name="state" maxlength="2" size="5" value="<?php echo $row['State'];?>" onkeyup="filter1(this)" onblur="checkObj(this,'state')"><br/>
                                Zip: <input type="text" name="zip" maxlength="5" size="10" value="<?php echo $row['ZIP'];?>" onkeyup="filter2(this)" onblur="checkObj(this,'zip')"><br/>
            <?php               if (($type == "home") || (isset ($_POST['gender']))){ ?>
                                    <input type="radio" name="gender" value="M" <?php if(($row['Gender'])=='M'){echo "checked";}?>>Male
                                    <input type="radio" name="gender" value="F" <?php if(($row['Gender'])=='F'){echo "checked";}?>>Female<br/>
                                    Age: <input type="text" name="age" maxlength="3" size="5" value="<?php echo $row['Age'];?>" onkeyup="filter2(this)" onblur="checkObj(this,'age')"><br/>
                                    <input type="radio" name="mstatus" value="S" <?php if(($row['MaritalStatus'])=='S'){echo "checked";}?>>Single
                                    <input type="radio" name="mstatus" value="M" <?php if(($row['MaritalStatus'])=='M'){echo "checked";}?>>Married<br/>
                                    Income:<input type="text" name="income" maxlength="50" size="60" value="<?php echo $row['Income'];?>" onkeyup="filter2(this)" onblur="checkObj(this,'income')"><br/>
            <?php               }else{ ?>
                                    Company: <input type="text" name="cname" maxlength="20" size="30" value="<?php echo $row['CompanyName'];?>"><br/>
                                    Category: <input type="text" name="category" maxlength="20" size="30" value="<?php echo $row['Category'];?>"><br/>
                                    Yearly Income: <input type="text" name="income" maxlength="50" size="60" value="<?php echo $row['GrossIncome'];?>" onkeyup="filter2(this)" onblur="checkObj(this,'yincome')"><br/>
                                    Account Number: <input type="text" name="anumber" maxlength="10" size="20" value="<?php echo $row['AcctNumber'];?>" onkeyup="filter2(this)" onblur="checkObj(this,'anum')"><br/>
            <?php               } ?>
                                <input type="submit" name="addsubmit" value="Modify"/>
                                <input type="submit" name="cansubmit" value="Return"/>
                            </form>
                        </div>
                  </div>
                    <div class="cleaner">
                        
                    </div>
                </div> <!-- end of main column -->
                </div>
            </div> <!-- end of content --><!-- end of footer -->
        </div> <!-- end of container -->
    </body>
</html>
<?php
    require_once('mysqlclass.php');
    require_once ('db_info.php');
    require_once('cartfunctions.php');
    session_start(); // Start a new session
    if(isset($_POST['submit']))
    {
        $username = $_POST['user'];
	$password = $_POST['pass'];
        $sql = "select se.Role, se.SID, se.EID, s.City from SalesEmployees se, Store s where se.Login = '$username' and se.Password = '$password' and se.SID=s.SID";
	$result = $db->query($sql);
	$row = $result->fetch();
	if (isset($row['SID'])) {
	    $_SESSION['loggedIn'] = $_POST['user'];
            $_SESSION['role'] = $row['Role'];
            $_SESSION['store'] = $row['SID'];
            $_SESSION['eid'] = $row['EID'];
            $_SESSION['city'] = $row['City'];
	} else {
            echo "Login incorrect";
        }
    }
    if(isset($_POST['searchsubmit'])) {
        $p_name = $_POST['search'];
        $query1="select 
            p.PID, s.City, p.Pname, p.Category, ps.Quantity, p.Price, p.Description
            from 
            Store s, Product p, ProductStore ps
            where
            p.PID=ps.PID and ps.SID=s.SID and p.Status='1' and p.Pname like '%".$p_name."%'
            order by 
            s.City";
    }elseif(isset($_POST['cityfilter'])) {
        $s_sid = $_POST['city'];  
        $query1="select 
            p.PID, s.City, p.Pname, p.Category, ps.Quantity, p.Price, p.Description
            from 
            Store s, Product p, ProductStore ps
            where
            p.PID=ps.PID and ps.SID=s.SID and p.Status='1' and s.SID=".$s_sid."
            order by 
            s.City";
    }elseif(isset($_POST['emailsubmit'])) {
        $s_email = $_POST['emailsearch'];
        $query1="SELECT 
            Product.Pname AS 'Product Name', Transactions.TDate as 'Purchase Date', Transactions.Quantity as 'Number Purchased'
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Database final project</title>
        <link href="templatemo_style.css" rel="stylesheet" type="text/css" />
        <script language="javascript" type="text/javascript">
            function clearText(field) {
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
                            
                              <?php if (!isset($_SESSION['loggedIn'])) { ?>
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                            Username: <input type="text" name="user" /><br/><br/>
                                            Password: <input type="password" name="pass" />
                                            <input type="submit" name="submit" value="Login"/>
                                        </form>
                              <?php }else { ?>
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
                                        <div>
                                            <br />
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
                                  <?php echo writeShoppingCart();
                                  }?>
                        </div>         
                       
                    <div class="bottom">
                    
                    </div>
                  </div>
                </div>
             <!-- end of side column -->
            <div id="main_column">
                <div class="main_column_section">       
                    <h2>Products</h2>
                    <div class="main_column_section_content">
                        <br />
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                          <?php 
                            $sql= "select * from Store";
                            $result = $db->query($sql);
                          ?>
                            <select name="city">
                          <?php 
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
                        $result = mysql_query($query1) or die ( mysql_error() );
                        $fields_num = mysql_num_fields($result);
                        $count = mysql_num_rows($result);
                        echo "<h1>Products</h1>";
                        echo $count." Products listed<br />";
                        echo "<table border='1'><tr>";
                        for($i=0; $i<$fields_num; $i++) {
                            $field = mysql_fetch_field($result);
                            echo "<td>{$field->name}</td>";
                        }
                        if ((isset($_SESSION['loggedIn'])) and (!isset($_POST['emailsubmit']))) {
                            echo "<td>Add to Cart</td>";
                        } 
                        echo "</tr>\n";
                        while($row = mysql_fetch_row($result))
                        {
                            echo "<tr>";
                            foreach($row as $cell)
                                echo "<td>".$cell."</td>";
                            if ((isset($_SESSION['loggedIn'])) and (!isset($_POST['emailsubmit'])) and ($row[4]>0) and ($row[1]==$_SESSION['city'])) {
                                echo '<td><a href="cart.php?action=add&id='.$row[0].'">Add to cart</a></td>';
                            }elseif ((isset($_SESSION['loggedIn'])) and (!isset($_POST['emailsubmit'])) and ($row[4]==0) and ($row[1]==$_SESSION['city'])) {
                                echo "<td>Sold Out</td>";
                            }
                            echo "</tr>\n";
                        }
                        mysql_free_result($result);
                        mysql_close();
                      ?>           
                    </div>
                </div>
            </div>
                <div class="cleaner">
                </div>
            </div> <!-- end of main column -->
        </div> <!-- end of content --><!-- end of footer -->
    </body>
</html>
<?php
    require_once ('session_check.php');
    require_once('mysqlclass.php');
    require_once ('db_info.php');
    require_once('cartfunctions.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Database final project</title>
        <link href="templatemo_style.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="dom1.js"></script>
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
                      <?php echo writeShoppingCart(); ?>
                        </div>
                        <div class="bottom">
                            
                        </div>
                    </div>
                </div> <!-- end of side column -->
                <div id="main_column">
                    <div class="main_column_section">       
                        <h2><span></span>Products Management</h2>
                        <div class="main_column_section_content">
                  <?php if(isset($_POST['addsubmit'])){
                            $sql = "Insert into Product (Pname, Price, Category, Status, Description)
                                    values(
                                            '".$_POST['pname']."', 
                                            '".$_POST['price']."', 
                                            '".$_POST['category']."',
                                            '".$_POST['status']."', 
                                            '".$_POST['description']."')";
                            $result = $db->query($sql);
                            echo "Added New Product: ".$_POST['pname'];
                        } 
                        if(isset($_POST['addqty'])){
                            $sql = "Insert into ProductStore (PID, SID, Quantity)
                                    values(
                                            ".$_POST['product'].",
                                            ".$_POST['city'].", 
                                            ".$_POST['quantity']." )";
                           // echo $sql;
                            $result = $db->query($sql);
                            echo "Updated Product Quantity to ".$_POST['quantity'];
                        }
                        if(isset($_POST['createsubmit'])) { ?>
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                Product Name: <input type="text" name="pname" maxlength="30" size="40" value=""><br/>
                                Price: <input type="text" name="price" maxlength="50" size="60" value=""onkeyup="filter2(this)" onblur="checkObj(this,'price')" ><br/>
                                Category: <select name="category">
                            <?php 
                                    $i=0;
                                    $sql= "select distinct Category from Product";
                                    $result = $db->query($sql);
                                    while($row = $result->fetch()){ ?>
                                        <option value="<?php echo $row['Category']; ?>"><?php echo $row['Category']; ?></option><?php
                                    } ?>
                                </select><br />
                                Status: <input type="text" name="status" maxlength="1" size="5" value="" onkeyup="filter2(this)" onblur="checkObj(this,'status')"><br/>
                                Description: <input type="text" name="description" maxlength="50" size="60" value=""><br/>
                                <input type="submit" name="addsubmit" value="Add"/>
                                <input type="submit" name="cansubmit" value="Cancel"/>
                            </form>
                  <?php }elseif(($_POST['modifyproduct'])) {
                            $sql= "select SID, City from Store";
                            $result = $db->query($sql);
                            ?>
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                City: <select name="city">
                            <?php 
                                while($row = $result->fetch()){
                            ?>
                                    <option value="<?php echo $row['SID']; ?>"><?php echo $row['City']; ?></option><?php
                                } ?>
                                </select><br />
                                Product: <select name="product">
                            <?php 
                                $sql= "select PID, Pname from Product";
                                $result = $db->query($sql);
                                while($row = $result->fetch()){
                            ?>
                                    <option value="<?php echo $row['PID']; ?>"><?php echo $row['Pname']; ?></option><?php
                                } ?>
                                </select><br />
                                Quantity: <input type="text" name="quantity" maxlength="10" size="20" value=""onkeyup="filter2(this)" onblur="checkObj(this,'q')" /><br/>
                                <input type="submit" name="addqty" value="Add"/>
                                <input type="submit" name="cansubmit" value="Cancel"/>
                            </form>
                            <br /> <?php
                        }else { ?>
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                <input type="submit" name="createsubmit" value="Create a New Product"/><br/>
                                <input type="submit" name="modifyproduct" value="Add Existing Product to New Store" /><br/>
                                <input type="submit" name="modifysubmit" value="List and Modify Products by Store"/><br/><br/>
                            </form>
    <?php               } 
                        if((isset($_POST['modifysubmit'])) || (isset($_POST['catfilter'])) || (isset($_POST['rcatfilter']))) {
                          ?><form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                          <?php 
                                $sql= "select distinct Category from Product";
                                $result = $db->query($sql);
                          ?>
                                <select name="cat">
                          <?php 
                                    while($row = $result->fetch()){
                          ?>
                                        <option value="<?php echo $row['Category']; ?>" <?php if ($_POST['cat'] == $row['Category']){echo 'selected="selected"';} ?>><?php echo $row['Category']; ?></option><?php
                                    } ?>
                                </select>
                                <input type="submit" name="catfilter" value="Filter Category" />
                                <input type="submit" name="rcatfilter" value="Remove Filter" />
                            </form> <?php
                            if(isset($_POST['catfilter'])){
                                $query1 = "select distinct p.PID, p.Pname, p.Price, p.Category from Product p where p.Category='".$_POST['cat']."'";
                            }else{
                                $query1 = "select distinct p.PID, p.Pname, p.Price, p.Category from Product p";
                            }
                            if (!mysql_connect($db_host, $db_user, $db_pwd))
                                die("Can't connect to database");
                            if (!mysql_select_db($db_name))
                                die("Can't select database");
                            $result = mysql_query($query1) or die ( mysql_error() );
                            $fields_num = mysql_num_fields($result);
                            $count = mysql_num_rows($result);
                            echo $count." Products listed<br />";
                            echo "<table border='1'><tr>";
                            for($i=0; $i<$fields_num; $i++) {
                                $field = mysql_fetch_field($result);
                                echo "<td>{$field->name}</td>";
                            }
                            echo "<td>Modify</td>";
                            echo "</tr>\n";
                            while($row = mysql_fetch_row($result)) {
                                echo "<tr>";
                                foreach($row as $cell){
                                    echo "<td>$cell</td>";
                                }
                                $cid = $row[0];
                                echo '<td><a href="editproduct.php?id=', urlencode($cid), '">Modify</a></td>';
                                echo "</tr>\n";
                            }
                            mysql_free_result($result);
                            mysql_close();
                        } ?>
                </div>
                </div>
                </div>
                <div class="cleaner">
                </div>
            </div> <!-- end of content --><!-- end of footer -->
        </div> <!-- end of container -->
    </body>
</html>
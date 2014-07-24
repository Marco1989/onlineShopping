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
                </div>   
                </div>
                <div class="bottom">
                    
                </div>
            </div>
         <!-- end of side column -->
        <div id="main_column">
            <div class="main_column_section">       
                <h2><span></span>Customer Management</h2>
                <div class="main_column_section_content">
              <?php if(isset($_POST['addsubmit'])) {
                        $sql = "insert into Customer
                            (Name, Email, Street, City, State, ZIP)
                            values
                            ('".$_POST['fname']."', 
                            '".$_POST['email']."', 
                            '".$_POST['street']."',
                            '".$_POST['city']."', 
                            '".$_POST['state']."', 
                            '".$_POST['zip']."')";
                        $result = $db->query($sql);
                        //echo $sql;
                        $custid = $result->insertID ();
                        if (isset ($_POST['gender'])) {
                            $sql = "insert into HomeCustomer
                                (CID, Gender, Age, MaritalStatus, Income)
                                values
                                (   ".$custid.",
                                    '".$_POST['gender']."',
                                    ".$_POST['age'].",
                                    '".$_POST['mstatus']."',
                                    ".$_POST['income'].")";
                            $result = $db->query($sql);
                           // echo $sql;
                        }else{
                            $sql = "insert into BusinessCustomer
                                (CID, CompanyName, Category, GrossIncome, AcctNumber)
                                values
                                (   ".$custid.",
                                    '".$_POST['cname']."', 
                                   '".$_POST['category']."', 
                                    ".$_POST['income'].", 
                                    '".$_POST['anumber']."')";
                            $result = $db->query($sql);
                            //echo $sql;
                        }
                        echo "Added New Customer: ".$_POST['fname'];
                    } ?>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                  <?php if(isset($_POST['createsubmit'])) { ?>
                        <?php if (($_POST['ctype']) == "home"){ ?>
                            <input type="radio" name="ctype" value="home" disabled checked>Home<br/>
                        <?php }else{ ?>
                            <input type="radio" name="ctype" value="business" disabled checked>Business<br/>
                        <?php }
                        }else{ ?>
                            <input type="radio" name="ctype" value="home" checked>Home<br/>
                            <input type="radio" name="ctype" value="business">Business<br/>
                            <input type="submit" name="createsubmit" value="Create"/>
                            <input type="submit" name="modifysubmit" value="List"/>
                  <?php } ?>
                    </form>
              <?php if(isset($_POST['createsubmit'])) { ?>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            Name: <input type="text" name="fname" maxlength="50" size="60"onkeyup="filter1(this)" onblur="checkObj(this,'name')" ><br/>
                            Email: <input type="text" name="email" maxlength="50" size="60"><br/>
                            Street: <input type="text" name="street" maxlength="30" size="40"><br/>
                            City: <input type="text" name="city" maxlength="20" size="30" onkeyup="filter1(this)" onblur="checkObj(this,'city')"><br/>
                            State: <input type="text" name="state" maxlength="2" size="5" onkeyup="filter1(this)" onblur="checkObj(this,'state')"><br/>
                            Zip: <input type="text" name="zip" maxlength="5" size="10" onkeyup="filter2(this)" onblur="checkObj(this,'zip')"><br/>
                      <?php if (($_POST['ctype']) == "home") { ?>
                                Gender:<input type="radio" name="gender" value="M">Male
                                <input type="radio" name="gender" value="F">Female<br/>
                                Age: <input type="text" name="age" maxlength="3" size="5" onkeyup="filter2(this)" onblur="checkObj(this,'age')"><br/>
                                Marital Status:<input type="radio" name="mstatus" value="S">Single
                                <input type="radio" name="mstatus" value="M">Married<br/>
                                Income:<input type="text" name="income" maxlength="50" size="60" onkeyup="filter2(this)" onblur="checkObj(this,'income')"><br/>
                      <?php }else{ ?>
                                Company: <input type="text" name="cname" maxlength="20" size="30"><br/>
                                Category: <input type="text" name="category" maxlength="20" size="30"><br/>
                                Yearly Income: <input type="text" name="income" maxlength="50" size="60" onkeyup="filter2(this)" onblur="checkObj(this,'yincome')"><br/>
                                Account Number: <input type="text" name="anumber" maxlength="10" size="20" onkeyup="filter2(this)" onblur="checkObj(this,'anum')"><br/>
                      <?php } ?>
                            <input type="submit" name="addsubmit" value="Add"/>
                            <input type="submit" name="cansubmit" value="Cancel"/>
                        </form>
              <?php } 
                    if(isset($_POST['modifysubmit'])) {
                        if(($_POST['ctype'] == "home")) {
                            $query1 = "select * from Customer c, HomeCustomer hc where c.CID=hc.CID";
                        }else{
                            $query1 = "select * from Customer c, BusinessCustomer bc where c.CID=bc.CID";
                        }
                        if (!mysql_connect($db_host, $db_user, $db_pwd))
                            die("Can't connect to database");
                        if (!mysql_select_db($db_name))
                            die("Can't select database");
                        $result = mysql_query($query1) or die ( mysql_error() );
                        $fields_num = mysql_num_fields($result);
                        $count = mysql_num_rows($result);
                        echo $count." Customers listed<br />";
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
                            $type = $_POST['ctype'];
                            echo '<td><a href="editcustomer.php?id=', urlencode($cid), '&type=', urlencode($type), '">Modify</a></td>';
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
    </div>
    </div> <!-- end of main column -->
</body>
</html>
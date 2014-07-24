<?php
    require_once ('session_check.php');
    require_once('mysqlclass.php');
    require_once ('db_info.php');
    require_once ('reportsql.php');
    require_once 'cartfunctions.php';
    if(isset($_POST['report2submit'])){
        $ptype = $_POST['r9'];
        $sql = "SELECT C.CompanyName 'Company Name', SUM(C.Quantity) 'Number Sold'
FROM (SELECT TID, BusinessCustomer.CID, CompanyName, Product.Pname, Quantity
FROM Transactions, BusinessCustomer, Product
WHERE Transactions.CID = BusinessCustomer.CID AND Product.PID = Transactions.PID AND Product.Pname LIKE '%".$ptype."%') C				
GROUP BY C.CompanyName
HAVING SUM(C.Quantity) >= ALL (SELECT SUM(Quantity)
			FROM (SELECT TID, BusinessCustomer.CID, CompanyName, Product.Pname, Quantity
			      FROM Transactions, BusinessCustomer, Product
			      WHERE Transactions.CID = BusinessCustomer.CID AND Product.PID = Transactions.PID AND Product.Pname LIKE '%".$ptype."%') B
			GROUP BY B.CompanyName);";
    }elseif(isset($_POST['reportsubmit'])){
        switch($_POST['report']) {
            case "r1":
                $sql=$sql1;
                break;
            case "r2":
                $sql=$sql2;
                break;
            case "r3":
                $sql=$sql3;
                break;
            case "r4":
                $sql=$sql4;
                break;
            case "r5":
                $sql=$sql5;
                break;
            case "r6":
                $sql=$sql6;
                break;
            case "r7":
                $sql=$sql7;
                break;
            case "r8":
                $sql=$sql8;
                break;
        }
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
                                <h2>Navigation</h2>
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
                    </div>
                        <div class="bottom">
                            
                        </div>
                    </div> <!-- end of side column -->
                <div id="main_column">
                    <div class="main_column_section">       
                        <h2><span></span>Administration</h2>
                        <div class="main_column_section_content">
                                 <h3>What Business Buying Given Product the Most</h3><br/>  
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                <select name="report">
                                    <option value="r1" <?php if (($_POST['report']) == "r1"){echo 'selected="selected"';} ?>>Sales By Product</option>
                                    <option value="r2" <?php if (($_POST['report']) == "r2"){echo 'selected="selected"';} ?>>Top Product Category</option>
                                    <option value="r3" <?php if (($_POST['report']) == "r3"){echo 'selected="selected"';} ?>>Top Product</option>
                                    <option value="r4" <?php if (($_POST['report']) == "r4"){echo 'selected="selected"';} ?>>Products Sorted by Popularity</option>
                                    <option value="r5" <?php if (($_POST['report']) == "r5"){echo 'selected="selected"';} ?>>Product Category by Popularity</option>
                                    <option value="r6" <?php if (($_POST['report']) == "r6"){echo 'selected="selected"';} ?>>Top Region by Transactions</option>
                                    <option value="r7" <?php if (($_POST['report']) == "r7"){echo 'selected="selected"';} ?>>Region Ordered by Transactions</option>
                                    <option value="r8" <?php if (($_POST['report']) == "r8"){echo 'selected="selected"';} ?>>Sales by Region</option>
                                </select>
                               
                            <input type="submit" name="reportsubmit" value="Generate Report"/>
                            </form>
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                               
                                Input product: <input type="text" name="r9" <?php if (($_POST['r9']) == "r9"){echo "value=".$_POST['r9'];} ?> />
                                <input type="submit" name="report2submit" value="Generate Report"/>
                            </form>
                            
  <?php
                                if ((isset($_POST['reportsubmit'])) || (isset($_POST['report2submit']))) {
                                    if (!mysql_connect($db_host, $db_user, $db_pwd))
                                        die("Can't connect to database");
                                    if (!mysql_select_db($db_name))
                                        die("Can't select database");
                                    $result = mysql_query($sql) or die ( mysql_error() );
                                    $fields_num = mysql_num_fields($result);
                                    echo "<h1>Report</h1>";
                                    echo "<table border='1'><tr>";
                                    for($i=0; $i<$fields_num; $i++) {
                                        $field = mysql_fetch_field($result);
                                        echo "<td>{$field->name}</td>";
                                    }
                                    while($row = mysql_fetch_row($result)) {
                                        echo "<tr>";
                                        foreach($row as $cell)
                                            echo "<td>$cell</td>";
                                        echo "</tr>\n";
                                    }
                                    mysql_free_result($result);
                                    mysql_close(); 
                                }
  ?>
                            
                        </div>
                    </div>
                </div>
                    <div class="cleaner">
                    </div>
             </div> <!-- end of content --><!-- end of footer -->
        </div> <!-- end of container -->
    </body>
</html>
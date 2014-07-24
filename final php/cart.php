<?php
    require_once ('session_check.php');
    require_once 'mysqlclass.php';
    require_once 'db_info.php';
    require_once 'cartfunctions.php';
    session_start();
    $cart = $_SESSION['cart'];
    $action = $_GET['action'];
    $cid = $_POST['customer'];
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
                    
            <div id="main_column">
                <div class="main_column_section">       
                    <h2><span></span>Shopping Cart</h2>
                    <div class="main_column_section_content">
                <?php
                        switch ($action) {
                            case 'add':
                                if ($cart) {
                                    $cart .= ','.$_GET['id'];
                                } else {
                                    $cart = $_GET['id'];
                                }
                            break;
                            case 'delete':
                                if ($cart) {
                                    $items = explode(',',$cart);
                                    $newcart = '';
                                    foreach ($items as $item) {
                                        if ($_GET['id'] != $item) {
                                            if ($newcart != '') {
                                                    $newcart .= ','.$item;
                                            } else {
                                                    $newcart = $item;
                                            }
                                    }
                                    }
                                    $cart = $newcart;
                                }
                            break;
                            case 'update':
                                if ($cart) {
                                    $newcart=$cart;
                                    if (isset($_POST['checkout'])){
                                        echo "<p>Please Verify Your Order</p>";
                                        echo showCheckout();
                                    }else{
                                        foreach ($_POST as $key=>$value) {
                                            if (stristr($key,'qty')) {
                                                $id = str_replace('qty','',$key);
                                                if($value>$maxqty){
                                                    echo "<script langauge=\"javascript\">alert(\"Not Enough Inventory, You requested ".$value." but only ".$maxqty." are available\");</script>";
                                                }else{
                                                    $newcart = '';
                                                    $items = ($newcart != '') ? explode(',',$newcart) : explode(',',$cart);
                                                    $newcart = '';
                                                    foreach ($items as $item) {
                                                        if ($id != $item) {
                                                            if ($newcart != '') {
                                                                $newcart .= ','.$item;
                                                            } else {
                                                                $newcart = $item;
                                                            }
                                                        }
                                                    }
                                                    for ($i=1;$i<=$value;$i++) {
                                                        if ($newcart != '') {
                                                            $newcart .= ','.$id;
                                                        } else {
                                                            $newcart = $id;
                                                        }
                                                    }
                                                }
                                            }elseif (stristr ($key, 'max')) {
                                                $maxqty=$value;
                                            }
                                        }
                                    }
                                }
                                $cart = $newcart;
                            break;
                        case 'confirmed':
                            if (isset($_POST['confirm'])) {
                             if($cart){
                                echo "Checkout Completed!\nYou Bought the Following:";
                                echo showReceipt();
                                $items = explode(',',$cart);
                                $contents = array();
                                $tdate = date('Y-m-d H:i:s') ;
                                foreach ($items as $item) {
                                    $contents[$item] = (isset($contents[$item])) ? $contents[$item] + 1 : 1;
                                }
                                foreach ($contents as $id=>$qty) {
                                    $sql = "INSERT INTO Transactions
                                        (PID, CID, EID, TDate, Quantity)
                                        VALUES
                                        (".$id.", ".$cid.", ".$_SESSION['eid'].", '".$tdate."', ".$qty.")";
                                    $result = $db->query($sql);
                                    $tid = $result->insertID();
                                    $sqlp = "Select Price from Product where PID=".$id;
                                    $result = $db->query($sqlp);
                                    $row= $result->fetch();
                                    $price = $row['Price'];
                                    $sqlt = "INSERT INTO PriceRecord
                                        VALUES
                                        (".$tid.", ".$id.", ".$price.")";
                                    $result = $db->query($sqlt);
                                    $sql1 = "select 
                                        Quantity from ProductStore 
                                        where 
                                        pid=".$id." and sid=".$_SESSION['store'];
                                    $result = $db->query($sql1);
                                    $row = $result->fetch();
                                    $sql2 = "Update ProductStore
                                        Set Quantity = ".$row['Quantity']."-".$qty." where PID=".$id." and SID=".$_SESSION['store'];
                                    $result = $db->query($sql2);
                                }
                               $cart = '';
                             }else{
                                 echo "Cart Already empty";
                             }
                        }else{
                            echo "purchase aborted";
                        }
                        break;
                    }
                    $_SESSION['cart'] = $cart;
                        if ((isset($_POST['checkout'])) || (isset($_POST['confirm']))) {
   
                        }else{ ?>
                            <p>Please check quantities</p>
                    <?php
                            echo showCart();
                        }
                    ?>
                    </div>
                </div>
                <div class="cleaner">
                    
                </div>
            </div> <!-- end of main column -->
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
                      <?php }
                            //if (isset($_POST['confirm'])) {
                                
                            //}else{
?>
                                <br/><br/>
                                <p><img src="images/shopping_cart.png" width="35" height="32" />Your Cart</p>
                                <?php echo writeShoppingCart();
                           // }?>
                       </div>         
                       </div>
                    <div class="bottom">
                    </div>
             <!-- end of side column -->
            </div>
           </div>
        </div> <!-- end of content --><!-- end of footer -->
    </body>
</html>
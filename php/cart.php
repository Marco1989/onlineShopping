<?php
require_once ('session_check.php');
require_once 'mysqlclass.php';
require_once 'db_info.php';
require_once 'cartfunctions.php';
// Start the session
session_start();
// Process actions
$cart = $_SESSION['cart'];
$action = $_GET['action'];
$cid = $_POST['customer'];
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
                        //echo $key." ".$value."\n";
                        if (stristr($key,'qty')) {
			    $id = str_replace('qty','',$key);
                            if($value>$maxqty){
                                //echo "too much really";
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
                        }//else{
                         //   echo "Checked out";
                         //   $cart = '';   
                        //}
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
                            (PID, CID, EID, TDate)
                            VALUES
                            (".$id.", ".$cid.", ".$_SESSION['eid'].", ".$tdate.")";
                    $sql1 = "select 
                            Quantity from ProductStore 
                            where 
                            pid=".$id." and sid=".$_SESSION['store'];
                    $result = $db->query($sql1);
                    $row = $result->fetch();
                    $sql2 = "Update ProductStore
                                Set Quantity = ".$row['Quantity']." - ".$qty." where PID=".$id." and SID=".$_SESSION['store'];
                    //$result = $db->query($sql);
                    echo $sql."\n".$sql2."\n";
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
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Cart</title>
</head>

<body>
<?php if ((isset($_POST['checkout'])) || (isset($_POST['confirm']))) {
   
}else{ ?>
<h1>Your Shopping Cart</h1>

<?php
echo writeShoppingCart();
?>

<h1>Please check quantities...</h1>

<?php
echo showCart();
}
?>
<div> 
<a href="index.php">Back to Browsing...</a> | <a href="logout.php">logout</a>
        </div>
</body>
</html>
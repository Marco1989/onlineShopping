<?php
function writeShoppingCart() {
	$cart = $_SESSION['cart'];
	if (!$cart) {
		return '<p>No items in cart</p>';
	} else {
		// Parse the cart session variable
		$items = explode(',',$cart);
		$s = (count($items) > 1) ? 's':'';
		return '<p><a href="cart.php">'.count($items).' item'.$s.' in cart</a></p>';
	}
}

function showCart() {
	global $db;
	$cart = $_SESSION['cart'];
	if ($cart) {
		$items = explode(',',$cart);
		$contents = array();
		foreach ($items as $item) {
			$contents[$item] = (isset($contents[$item])) ? $contents[$item] + 1 : 1;
		}
		$output[] = '<form action="cart.php?action=update" method="post" id="cart">';
		$output[] = '<table border="1">';
                $output[] = '<tr><td></td><td>Product</td><td>Price</td><td>In Stock</td><td>Requested</td><td>Total</td></tr>';
		foreach ($contents as $id=>$qty) {
			$sql = 'SELECT * FROM Product p INNER JOIN ProductStore ps on p.PID=ps.PID WHERE ps.SID='.$_SESSION['store'].' AND p.PID = '.$id;
			$result = $db->query($sql);
			$row = $result->fetch();
			extract($row);
			$output[] = '<tr>';
			$output[] = '<td><a href="cart.php?action=delete&id='.$id.'" class="r">Remove</a></td>';
			$output[] = '<td>'.$Pname.'</td>';
			$output[] = '<td>&#36;'.$Price.'</td>';
                        //$output[] = '<td>'.$Quantity.'</td>';
                        $output[] = '<td><input type="text" name="max'.$id.'" value="'.$Quantity.'" size="5" maxlength="5" readonly /></td>';
			$output[] = '<td><input type="text" name="qty'.$id.'" value="'.$qty.'" size="5" maxlength="5"  /></td>';
			$output[] = '<td>&#36;'.($Price * $qty).'</td>';
			$total += $Price * $qty;
			$output[] = '</tr>';
		}
		$output[] = '</table>';
		$output[] = '<p>Grand total: <strong>&#36;'.$total.'</strong></p>';
		$output[] = '<div><button type="submit">Update cart</button>';
		//$output[] = '</form>';
            	//$output[] = '<form action="cart.php?action=checkout" method="post" id="cart">';
                $output[] = '<button type="submit" name="checkout" value="yes">Check Out</button></div>';
                $output[] = '</form>';
                

                                
	} else {
		$output[] = '<p>You shopping cart is empty.</p>';
	}
	return join('',$output);
}
function showCheckout() {
	global $db;
	$cart = $_SESSION['cart'];
	if ($cart) {
		$items = explode(',',$cart);
		$contents = array();
		foreach ($items as $item) {
			$contents[$item] = (isset($contents[$item])) ? $contents[$item] + 1 : 1;
		}
		$output[] = '<form action="cart.php?action=confirmed" method="post" id="cart">';
		$output[] = '<table border="1">';
                $output[] = '<tr><td>Product</td><td>Price</td><td>Requested</td><td>Total</td></tr>';
		foreach ($contents as $id=>$qty) {
			$sql = 'SELECT * FROM Product p INNER JOIN ProductStore ps on p.PID=ps.PID WHERE ps.SID='.$_SESSION['store'].' AND p.PID = '.$id;
			$result = $db->query($sql);
			$row = $result->fetch();
			extract($row);
			$output[] = '<tr>';
			//$output[] = '<td><a href="cart.php?action=delete&id='.$id.'" class="r">Remove</a></td>';
			$output[] = '<td>'.$Pname.'</td>';
			$output[] = '<td>&#36;'.$Price.'</td>';
                        //$output[] = '<td>'.$Quantity.'</td>';
                        //$output[] = '<td><input type="text" name="max'.$id.'" value="'.$Quantity.'" size="5" maxlength="5" readonly /></td>';
			$output[] = '<td><input type="text" name="qty'.$id.'" value="'.$qty.'" size="5" maxlength="5" readonly /></td>';
			$output[] = '<td>&#36;'.($Price * $qty).'</td>';
			$total += $Price * $qty;
			$output[] = '</tr>';
		}
		$output[] = '</table>';
		$output[] = '<p>Grand total: <strong>&#36;'.$total.'</strong></p>';
                $sql= "select CID, Name from Customer";
                $result = $db->query($sql);
                $output[] = 'Select Customer: ';
                $output[] = '<select name="customer">';
                while($row = $result->fetch()){
                    $output[] = '<option value="'.$row['CID'].'">'.$row['Name'].'</option>';
                }
                $output[] = '</select><br />';
                $output[] = '<a href="customer.php">Add/Edit a Customer</a><br />';
                $output[] = '<div><button type="submit" name="confirm" value="1">Confirm</button>';
      		$output[] = '<button type="submit" name="cancel" value="1">Cancel</button></div>';
		//$output[] = '</form>';
            	//$output[] = '<form action="cart.php?action=checkout" method="post" id="cart">';
                //$output[] = '<div><button type="submit" name="checkout" value="yes">Check Out</button></div>';
                $output[] = '</form>';
                

                                
	} else {
		$output[] = '<p>You shopping cart is empty.</p>';
	}
	return join('',$output);
}
function showReceipt() {
	global $db;
	$cart = $_SESSION['cart'];
	if ($cart) {
		$items = explode(',',$cart);
		$contents = array();
		foreach ($items as $item) {
			$contents[$item] = (isset($contents[$item])) ? $contents[$item] + 1 : 1;
		}
		$output[] = '<table border="1">';
                $output[] = '<tr><td>Product</td><td>Price</td><td>Requested</td><td>Total</td></tr>';
		foreach ($contents as $id=>$qty) {
			$sql = 'SELECT * FROM Product p INNER JOIN ProductStore ps on p.PID=ps.PID WHERE ps.SID='.$_SESSION['store'].' AND p.PID = '.$id;
			$result = $db->query($sql);
			$row = $result->fetch();
			extract($row);
			$output[] = '<tr>';
			$output[] = '<td>'.$Pname.'</td>';
			$output[] = '<td>&#36;'.$Price.'</td>';
                        $output[] = '<td><input type="text" readonly name="qty'.$id.'" value="'.$qty.'" size="5" maxlength="5" /></td>';
			$output[] = '<td>&#36;'.($Price * $qty).'</td>';
			$total += $Price * $qty;
			$output[] = '</tr>';
		}
		$output[] = '</table>';
		$output[] = '<p>Grand total: <strong>&#36;'.$total.'</strong></p>';
        } else {
		$output[] = '<p>You shopping cart is empty.</p>';
	}
	return join('',$output);
}
?>
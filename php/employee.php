<?php
require_once ('session_check.php');
require_once('mysqlclass.php');
require_once ('db_info.php');
require_once ('reportsql.php');
if(isset($_POST['report2submit'])){
    $ptype = $_POST['r9'];
    //What Business Buying Given Product the Most (MUST SPECIFY Pname!!)

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
<html>
    <head>
        <title>Admin Portal</title>
    </head>
    <body>
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
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
        What Business Buying Given Product the Most<br/>
        Input product: <input type="text" name="r9" <?php if (($_POST['r9']) == "r9"){echo "value=".$_POST['r9'];} ?> />
        <input type="submit" name="report2submit" value="Generate Report"/>
    </form>
        <div>
  <?php
  if ((isset($_POST['reportsubmit'])) || (isset($_POST['report2submit']))) {
    if (!mysql_connect($db_host, $db_user, $db_pwd))
        die("Can't connect to database");
    if (!mysql_select_db($db_name))
        die("Can't select database");
    $result = mysql_query($sql) or die ( mysql_error() );
    //$row = mysql_fetch_array($result);
    $fields_num = mysql_num_fields($result);
    
    //echo $sql;
    echo "<h1>Report</h1>";
    echo "<table border='1'><tr>";
    for($i=0; $i<$fields_num; $i++)
    {
        $field = mysql_fetch_field($result);
        echo "<td>{$field->name}</td>";
    }
    while($row = mysql_fetch_row($result))
    {
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
        <a href="logout.php">Logout</a>&nbsp;|&nbsp;
        <a href="index.php">Home</a>
    </body>
</html>

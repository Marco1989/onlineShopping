<?php
require_once ('session_check.php');
require_once('mysqlclass.php');
require_once ('db_info.php');
require_once('cartfunctions.php');
?>
<html>
    <head>
        <title>Product Management</title>
    </head>
    <body>
        <?php if(isset($_POST['addsubmit'])){
            $sql = "Insert into Product (Pname, Price, Category, Status, Description)
                    values(
                    Pname='".$_POST['pname']."', 
                    Price='".$_POST['price']."', 
                    Category='".$_POST['category']."',
                    Status='".$_POST['status']."', 
                    Description='".$_POST['description']."')";
            echo $sql;
            //$result = $db->query($sql);
            $sql2 = "Insert into ProductStore (SID, Quantity)
                    values(
                    SID=".$_POST['city'].", 
                    Quantity=".$_POST['quantity']." )";
            echo $sql2;
            echo "Added New Product: ".$_POST['pname'];
        } 
        if(isset($_POST['createsubmit']))
          { ?>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                Product Name: <input type="text" name="pname" maxlength="30" size="40" value="<?php echo $row['Pname'];?>"><br/>
                Price: <input type="text" name="price" maxlength="50" size="60" value="<?php echo $row['Price'];?>"><br/>
                <select name="category">
                 <?php 
                 $i=0;
                 $sql= "select distinct Category from Product";
                 $result = $db->query($sql);
                 while($row = $result->fetch()){
                 ?>
                    <option value="c<?php echo $i++; ?>" <?php if ($_POST['category'] == "c".$i){echo 'selected="selected"';} ?>><?php echo $row['Category']; ?></option><?php
                 } ?>
                </select><br />
                Status: <input type="text" name="status" maxlength="1" size="5" value="<?php echo $row['Status'];?>"><br/>
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
                </select><br />
                Quantity: <input type="text" name="quantity" maxlength="10" size="20" value="<?php echo $row['Quantity'];?>"><br/>
                Description: <input type="text" name="description" maxlength="50" size="60" value="<?php echo $row['Description'];?>"><br/>
                <input type="submit" name="addsubmit" value="Add"/>
                <input type="submit" name="cansubmit" value="Cancel"/>
            </form><div>
    <?php }else { ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="submit" name="createsubmit" value="Create"/>
            <input type="submit" name="modifysubmit" value="List"/>
        </form>
    <?php } 
        if(isset($_POST['modifysubmit']))
        {
            //$query1 = "select * from Product";
            $query1 = "select p.PID, p.Pname, p.Price, p.Category, ps.Quantity, s.City, p.Status from Product p, ProductStore ps, Store s where p.PID=ps.PID and ps.SID=s.SID";
            if (!mysql_connect($db_host, $db_user, $db_pwd))
                die("Can't connect to database");
            if (!mysql_select_db($db_name))
                die("Can't select database");
            $result = mysql_query($query1) or die ( mysql_error() );
            $fields_num = mysql_num_fields($result);
            $count = mysql_num_rows($result);
            echo $count." Products listed<br />";
            echo "<table border='1'><tr>";
            // printing table headers
            for($i=0; $i<$fields_num; $i++)
            {
                $field = mysql_fetch_field($result);
                echo "<td>{$field->name}</td>";
            }
            echo "<td>Modify</td>";
            echo "</tr>\n";
            // printing table rows
            while($row = mysql_fetch_row($result))
            {
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
            </div><div>
          <?php echo writeShoppingCart(); ?><br />
            <a href="index.php">home</a> | <a href="logout.php">logout</a>
        </div>
    </body>
</html>

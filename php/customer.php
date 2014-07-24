<?php
require_once ('session_check.php');
require_once('mysqlclass.php');
require_once ('db_info.php');
require_once('cartfunctions.php');
?>
<html>
    <head>
        <title>Customer Management</title>
    </head>
    <body>
  <?php if(isset($_POST['addsubmit'])){
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
            $row = $result->fetch();
            if (isset ($_POST['gender'])){
                $sql = "insert into HomeCustomer
                        (Gender, Age, MaritalStatus, Income)
                        values
                        ('".$_POST['gender']."',
                         ".$_POST['age'].",
                         '".$_POST['mstatus']."',
                         ".$_POST['income'].")";
                $result = $db->query($sql);
                $row = $result->fetch();
            }else{
                $sql = "insert into BusinessCustomer
                        (CompanyName, Category, GrossIncome, AcctNumber)
                        values
                        ('".$_POST['cname']."', 
                         '".$_POST['category']."', 
                         ".$_POST['income'].", 
                         '".$_POST['anumber']."')";
                $result = $db->query($sql);
                $row = $result->fetch();
            }
            echo "Added New Customer: ".$_POST['fname'];
        } ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <?php if(isset($_POST['createsubmit']))
          { ?>
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
        <?php if(isset($_POST['createsubmit']))
          { ?>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                Name: <input type="text" name="fname" maxlength="50" size="60"><br/>
                Email: <input type="text" name="email" maxlength="50" size="60"><br/>
                Street: <input type="text" name="street" maxlength="30" size="40"><br/>
                City: <input type="text" name="city" maxlength="20" size="30"><br/>
                State: <input type="text" name="state" maxlength="2" size="5"><br/>
                Zip: <input type="text" name="zip" maxlength="5" size="10"><br/>
            <?php if (($_POST['ctype']) == "home"){ ?>
                <input type="radio" name="gender" value="M">Male
                <input type="radio" name="gender" value="F">Female<br/>
                Age: <input type="text" name="age" maxlength="3" size="5"><br/>
                <input type="radio" name="mstatus" value="S">Single
                <input type="radio" name="mstatus" value="M">Married<br/>
                Income:<input type="text" name="income" maxlength="50" size="60"><br/>
            <?php }else{ ?>
                Company: <input type="text" name="cname" maxlength="20" size="30"><br/>
                Category: <input type="text" name="category" maxlength="20" size="30"><br/>
                Yearly Income: <input type="text" name="income" maxlength="50" size="60"><br/>
                Account Number: <input type="text" name="anumber" maxlength="10" size="20"><br/>
            <?php } ?>
                <input type="submit" name="addsubmit" value="Add"/>
                <input type="submit" name="cansubmit" value="Cancel"/>
            </form><div>
    <?php } 
        if(isset($_POST['modifysubmit']))
        {
            if(($_POST['ctype'] == "home"))
            {
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
                $type = $_POST['ctype'];
                echo '<td><a href="editcustomer.php?id=', urlencode($cid), '&type=', urlencode($type), '">Modify</a></td>';
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

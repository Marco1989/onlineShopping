<?php
//Sales By Product
$sql1 = "SELECT Product.Pname 'Product', S.Sales
FROM Product, (SELECT PriceRecord.PID, HPrice * T.CTR Sales
FROM PriceRecord, (SELECT PID, SUM(Quantity) CTR
FROM Transactions
GROUP BY PID) T
WHERE PriceRecord.PID = T.PID
GROUP BY PID) S
WHERE Product.PID = S.PID
ORDER BY S.Sales DESC";


//Top Product Category

$sql2 = "SELECT Product.Category 'Product Category', SUM(Transactions.Quantity) 'Number Sold'
FROM Transactions, Product
WHERE Product.PID = Transactions.PID
GROUP BY Product.Category
HAVING SUM(Quantity) >= ALL (SELECT SUM(Transactions.Quantity)
			FROM Transactions, Product
			WHERE Product.PID = Transactions.PID
			GROUP BY Product.Category)";


//Top Product

$sql3 = "SELECT Product.Pname Product, SUM(Quantity) 'Number Sold'
FROM Transactions, Product
WHERE Product.PID = Transactions.PID
GROUP BY Product.Pname
HAVING SUM(Quantity) >= ALL (SELECT SUM(Quantity)
			FROM Transactions, Product
			WHERE Product.PID = Transactions.PID
			GROUP BY Product.Pname)";


//Products Sorted by Popularity 

$sql4 = "SELECT Product.Pname Product, SUM(Quantity) NumberSold
FROM Transactions, Product
WHERE Product.PID = Transactions.PID
GROUP BY Product.Pname
ORDER BY NumberSold DESC";


//Product Category by Popularity 

$sql5 = "SELECT Product.Category, SUM(Quantity) as NumberSold
FROM Transactions, Product
WHERE Product.PID = Transactions.PID
GROUP BY Product.Category
ORDER BY NumberSold DESC";


//Top Region by Transactions

$sql6 = "SELECT RegionName
FROM Region, (SELECT A.RID, COUNT(*)
FROM (SELECT Transactions.*, SalesEmployees.SID, RID
	FROM Transactions, SalesEmployees, Store
	WHERE Transactions.EID = SalesEmployees.EID AND Store.SID = SalesEmployees.SID) A
GROUP BY A.RID
HAVING COUNT(*) >= ALL (SELECT COUNT(*)
			FROM (SELECT Transactions.*, SalesEmployees.SID, RID
			      FROM Transactions, SalesEmployees, Store
			      WHERE Transactions.EID = SalesEmployees.EID AND Store.SID = SalesEmployees.SID) B
			GROUP BY B.RID)) C
WHERE C.RID = Region.RID";

//Region Ordered by Transactions

$sql7 = "SELECT RegionName 'Region Name', B.CTR Transactions
FROM Region, (SELECT A.RID, COUNT(*) CTR
FROM (SELECT Transactions.*, SalesEmployees.SID, RID
	FROM Transactions, SalesEmployees, Store
	WHERE Transactions.EID = SalesEmployees.EID AND Store.SID = SalesEmployees.SID) A
GROUP BY A.RID) B
WHERE Region.RID = B.RID
ORDER BY B.CTR DESC";


//Sales by Region

$sql8 = "SELECT RegionName, SUM(Sales) Sales
FROM Region, (SELECT Price * C.ProductCount Sales, RID
FROM Product, (SELECT PID, RID, COUNT(*) ProductCount
FROM (SELECT A.TID, A.PID, A.SID, Store.RID
FROM Store, (SELECT Transactions.*, SID
FROM Transactions, SalesEmployees
WHERE Transactions.EID = SalesEmployees.EID) A
WHERE Store.SID = A.SID) B
GROUP BY PID, RID) C
WHERE Product.PID = C.PID) D
WHERE D.RID = Region.RID
GROUP BY Region.RID";

?>

//Sales by Product
SELECT Product.Pname 'Product', S.Sales
FROM Product, (SELECT PriceRecord.PID, HPrice * T.CTR Sales
FROM PriceRecord, (SELECT PID, SUM(Quantity) CTR
FROM Transactions
GROUP BY PID) T
WHERE PriceRecord.PID = T.PID
GROUP BY PID) S
WHERE Product.PID = S.PID
ORDER BY S.Sales DESC;




//Top Product Category
			
SELECT Product.Category 'Product Category', SUM(Transactions.Quantity) 'Number Sold'
FROM Transactions, Product
WHERE Product.PID = Transactions.PID
GROUP BY Product.Category
HAVING SUM(Quantity) >= ALL (SELECT SUM(Transactions.Quantity)
			FROM Transactions, Product
			WHERE Product.PID = Transactions.PID
			GROUP BY Product.Category);
			
//Top Product

SELECT Product.Pname Product, SUM(Quantity) 'Number Sold'
FROM Transactions, Product
WHERE Product.PID = Transactions.PID
GROUP BY Product.Pname
HAVING SUM(Quantity) >= ALL (SELECT SUM(Quantity)
			FROM Transactions, Product
			WHERE Product.PID = Transactions.PID
			GROUP BY Product.Pname);


//Products Sorted by Popularity 

SELECT Product.Pname Product, SUM(Quantity) NumberSold
FROM Transactions, Product
WHERE Product.PID = Transactions.PID
GROUP BY Product.Pname
ORDER BY NumberSold DESC;


//Product Category by Popularity 

SELECT Product.Category, SUM(Quantity) as NumberSold
FROM Transactions, Product
WHERE Product.PID = Transactions.PID
GROUP BY Product.Category
ORDER BY NumberSold DESC;


//Top Region by Transactions 

SELECT RegionName
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
WHERE C.RID = Region.RID;

//Region Ordered by Transactions

SELECT RegionName 'Region Name', B.CTR Transactions
FROM Region, (SELECT A.RID, COUNT(*) CTR
FROM (SELECT Transactions.*, SalesEmployees.SID, RID
	FROM Transactions, SalesEmployees, Store
	WHERE Transactions.EID = SalesEmployees.EID AND Store.SID = SalesEmployees.SID) A
GROUP BY A.RID) B
WHERE Region.RID = B.RID
ORDER BY B.CTR DESC;


//Sales by Region

SELECT RegionName, SUM(Sales) Sales
FROM Region, (SELECT C.PID, C.RID, PriceRecord.HPrice * C.ProductCount Sales
FROM PriceRecord, (SELECT PID, RID, SUM(B.Quantity) ProductCount
FROM (SELECT A.TID, A.PID, A.SID, A.Quantity, Store.RID
FROM Store, (SELECT Transactions.*, SID
FROM Transactions, SalesEmployees
WHERE Transactions.EID = SalesEmployees.EID) A
WHERE Store.SID = A.SID) B
GROUP BY PID, RID) C
WHERE C.PID = PriceRecord.PID
GROUP BY PID, RID) D
WHERE D.RID = Region.RID
GROUP BY Region.RID;



//What Business Buying Given Product the Most (MUST SPECIFY Pname!!)

SELECT C.CompanyName 'Company Name', SUM(C.Quantity) 'Number Sold'
FROM (SELECT TID, BusinessCustomer.CID, CompanyName, Product.Pname, Quantity
FROM Transactions, BusinessCustomer, Product
WHERE Transactions.CID = BusinessCustomer.CID AND Product.PID = Transactions.PID AND Product.Pname LIKE '%".$ptype."%') C				
GROUP BY C.CompanyName
HAVING SUM(C.Quantity) >= ALL (SELECT SUM(Quantity)
			FROM (SELECT TID, BusinessCustomer.CID, CompanyName, Product.Pname, Quantity
			      FROM Transactions, BusinessCustomer, Product
			      WHERE Transactions.CID = BusinessCustomer.CID AND Product.PID = Transactions.PID AND Product.Pname LIKE '%".$ptype."%') B
			GROUP BY B.CompanyName);
			
			
SELECT COUNT(DISTINCT Transactions.CID) CTR
FROM Customer, Transactions
WHERE Customer.CID = Transactions.CID
UNION
SELECT COUNT(Customer.CID) CTR
FROM Customer
WHERE Customer.CID NOT IN (SELECT CID FROM Transactions)
UNION
SELECT COUNT(*) CTR
FROM Customer;

SELECT E.RegionName, MAX(E.Sales)
FROM (SELECT RegionName, SUM(Sales) Sales
FROM Region, (SELECT C.PID, C.RID, PriceRecord.HPrice * C.ProductCount Sales
FROM PriceRecord, (SELECT PID, RID, SUM(B.Quantity) ProductCount
FROM (SELECT A.TID, A.PID, A.SID, A.Quantity, Store.RID
FROM Store, (SELECT Transactions.*, SID
FROM Transactions, SalesEmployees
WHERE Transactions.EID = SalesEmployees.EID) A
WHERE Store.SID = A.SID) B
GROUP BY PID, RID) C
WHERE C.PID = PriceRecord.PID
GROUP BY PID, RID) D
WHERE D.RID = Region.RID
GROUP BY Region.RID) E
GROUP BY RegionName;


SELECT E.RegionName, MAX(E.Sales)
FROM Region, (SELECT RegionName, SUM(Sales) Sales
FROM Region, (SELECT C.PID, C.RID, PriceRecord.HPrice * C.ProductCount Sales
FROM PriceRecord, (SELECT PID, RID, SUM(B.Quantity) ProductCount
FROM (SELECT A.TID, A.PID, A.SID, A.Quantity, Store.RID
FROM Store, (SELECT Transactions.*, SID
FROM Transactions, SalesEmployees
WHERE Transactions.EID = SalesEmployees.EID) A
WHERE Store.SID = A.SID) B
GROUP BY PID, RID) C
WHERE C.PID = PriceRecord.PID
GROUP BY PID, RID) D
WHERE D.RID = Region.RID
GROUP BY Region.RID) E
WHERE E.RegionName = Region.RegionName
GROUP BY Region.RegionName;



SELECT RegionName, SUM(Sales) Sales
FROM Region, (SELECT C.PID, C.RID, PriceRecord.HPrice * C.ProductCount Sales
FROM PriceRecord, (SELECT PID, RID, SUM(B.Quantity) ProductCount
FROM (SELECT A.TID, A.PID, A.SID, A.Quantity, Store.RID
FROM Store, (SELECT Transactions.*, SID
FROM Transactions, SalesEmployees
WHERE Transactions.EID = SalesEmployees.EID) A
WHERE Store.SID = A.SID) B
GROUP BY PID, RID) C
WHERE C.PID = PriceRecord.PID
GROUP BY PID, RID) D
WHERE D.RID = Region.RID
GROUP BY Region.RID
HAVING Sales >= All (SELECT SUM(Sales) Sales
FROM Region, (SELECT C.PID, C.RID, PriceRecord.HPrice * C.ProductCount Sales
FROM PriceRecord, (SELECT PID, RID, SUM(B.Quantity) ProductCount
FROM (SELECT A.TID, A.PID, A.SID, A.Quantity, Store.RID
FROM Store, (SELECT Transactions.*, SID
FROM Transactions, SalesEmployees
WHERE Transactions.EID = SalesEmployees.EID) A
WHERE Store.SID = A.SID) B
GROUP BY PID, RID) C
WHERE C.PID = PriceRecord.PID
GROUP BY PID, RID) D
WHERE D.RID = Region.RID
GROUP BY Region.RID);
			






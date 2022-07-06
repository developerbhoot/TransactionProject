<html>
	<head>
		<title>Demo Transaction Task </title>
		<?php
			$servername="localhost";
			$username="root";
			$password="";
			$runningBalance=0;
			try{
				//first of all need to connect with the database so thus i can connect with the table
				$conn=new PDO("mysql:host=$servername;dbname=dbtransaction",$username,$password);
				$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
				
				//here now need to fetch the record from the table accroding to the query
				$stmt=$conn->prepare("select * from tblTransaction ORDER BY sDate DESC");
				
				//this code is used to execute the query
				$stmt->execute();
				
				//with the help of this function we can pass the data to the users arrray
				$Record=$stmt->fetchAll();
				$count=0;
				$display="<table border='2px'><tr><td>Date</td><td>Description</td><td>Credit</td><td>Debit</td><td>Running Balance </td></tr>";
				foreach($Record as  $data){
					$count++;
					if($count==1){
						$runningBalance=$data["RunningBalance"];	
					}
					$display=$display."<tr><td>".$data["sDate"]."</td>";
					$display=$display."<td>".$data["Description"]."</td>";
					$display=$display."<td>".$data["Credit"]."</td>";
					$display=$display."<td>".$data["Debit"]."</td>";
					$display=$display."<td>".$data["RunningBalance"]."</td></tr>";
					
				}
				$display=$display."</table>";
			}catch(PDOException $e){
				echo $e->getMessage();
			}
			
			if(isset($_POST["btnSave"])){
				$dt=$_POST["dt"];
				$typ=$_POST["transType"];
				
				$amt=$_POST["amtValue"];
				$credit=0;
				$debit=0;
				$des=$_POST["desValue"];
				//here now i need to create the logic to add or substract 
				if($typ=="Credit"){
					$credit=$amt;
					$runningBalance=$runningBalance+$amt;
				}else{
					$debit=$amt;
					$runningBalance=$runningBalance-$amt;
				}
				echo $typ;
				$conn->exec("insert into tblTransaction(sDate,Description,Credit,Debit,RunningBalance)values('".$dt."','".$des."',".$credit.",".$debit.",".$runningBalance.")");
				header("Location: index.php");
				
			}
		?>
		<style>
			.btn{
				border:1px solid red;
				height:50px;
				width:200px;
				background-color:blue;
				padding:10px;
			}
			.ok{
				border:1px solid red;
				height:auto;
				width:80%;
				margin:0 auto;
				display:none;
				padding:10px;
				font-size:25px;
			}
			.box{
				border:1px solid grey;
				border-radius:5px;
				height:20px;
				width:100%;
				padding:20px;
				margin:10px;
			}
		</style>
		
	</head>
		
	<body>
		
		<input type="button" value="add Transaction"  onclick="viewBox();" class="btn"/>
		<div class="Record">
			<?php echo $display; ?>
		</div>
		<div class="ok" id="transBox">
			<form method="POST" action="index.php">
				
				<label>Enter Date</label><br/>
				<input type="datetime-local" name="dt" class="box"/><br/>
				
				<label>Select Transaction Type</label><br/>
				<select name="transType" class="box">
					<option value="Credit">Credit</option>
					<option value="Debit">Debit</option>
				</select><br/>
				
				<label>Enter Amount</label><br/>
				<input type="Number" name="amtValue" class="box"/><br/>
				
				<label>Enter Description</label><br/>
				<input type="text" name="desValue" class="box"/><br/>
				<input type="submit" value="save" name="btnSave" class="btn"/>
			</form>
		</div>
		<script>
			
			function viewBox(){
				document.getElementById("transBox").style.display="block";
				
			}
		</script>
	</body>
</html>
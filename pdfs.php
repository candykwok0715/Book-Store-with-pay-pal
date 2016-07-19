<?php
	require ('config.inc.php');
	redirect_invalid_user();

	include ('header.html');	
	require('forms.inc.php');
?>
<h3>PDF Books</h3>
<?php
	require('mysql.inc.php');
	$q = "SELECT date_expires FROM users WHERE id={$_SESSION['user_id']}";	
	$r = mysqli_query($dbc, $q);
	$row = mysqli_fetch_array($r,MYSQLI_ASSOC);
	//echo $row['date_expires']."<br>";
	//echo date("Y-m-d");
	if ($row['date_expires']<=date("Y-m-d")){   //check has been expired or not 
		echo "Your membership has been expired. If you want to see Books, Please renew your membership.";
	}
	else{
		$q = "SELECT * FROM pdfs";	
		$r = mysqli_query($dbc, $q);
//		$row = mysqli_fetch_array($r,MYSQLI_ASSOC);
		echo '<form action="downloadFile.php" method="post">';
		echo '<table border="1" width="700"><tr><th>Book Title</th><th>Download this Book</th></tr>';
		while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
		echo "<tr><td>".$row["title"]."</td>";
		echo '<td><input type="submit" name="file_name" value="'.$row["file_path"].'" id="submit_button"></td>';		
		}
		echo '</table></form>';
	}
	include('footer.html');
?>
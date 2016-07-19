<?php
	require ('config.inc.php');
	redirect_invalid_user();

	// Include the header file:
	require ('mysql.inc.php');
	$q = "SELECT type FROM users WHERE id={$_SESSION['user_id']}";	
	$r = mysqli_query($dbc, $q);
	$row = mysqli_fetch_array($r,MYSQLI_ASSOC);
		if($row['type']!='admin'){  //make sure only administrator can enter this page
			$destination = 'index.php';
			$protocol = 'http://';
			$url = $protocol . BASE_URL . $destination;
			header("Location: $url");
			exit();
		}
	include ('header.html');	
	require('forms.inc.php');
	?>
    <h3>Add pdf</h3>
	<?php
		if ($_SERVER['REQUEST_METHOD'] == 'POST') 
		{
		$f = mysqli_real_escape_string($dbc, $_FILES["file"]["name"]);
		$t = mysqli_real_escape_string($dbc, $_POST['title']);		
		$allowedExts = array("pdf");
		$temp = explode(".", $f);
		$extension = end($temp);
		$FileName=$f;
		$FileType=$_FILES["file"]["type"];
		$FileSize=($_FILES["file"]["size"]/1024/1024);  //bytes->MB
		$FileTemp=$_FILES["file"]["tmp_name"];
		//echo strlen($FileName);
		
		if (strlen($FileName)>0 && strlen($FileName)<=104 && strlen($t)>0 &&  strlen($t)<=30 ){			

			if ( ($FileType == "application/pdf")	&& ($FileSize <= 10) && in_array($extension, $allowedExts)) 
			{
				if ($_FILES["file"]["error"] > 0)
				{
					//echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
					echo "Upload file has the error. Please contact System administrator.<br><br>";
				}
				else
				{		
						move_uploaded_file($FileTemp ,"pdfs/$FileName");
						$q = "INSERT INTO pdfs (title, file_path) VALUES ('$t', '$FileName')";	
						$r = mysqli_query($dbc, $q);
						//echo $t;
						echo "Upload successfully.<br><br>";
//						echo "Stored in: " . "/pdfs/" . $FileName;
				}
			}
			else
			{
				echo "Invalid file. System only support the PDF file and the file size is smaller than 10MB.<br><br>";
			}
		}else echo "Title and File Path can't be null. Besides title and file name are smaller than 100 chars.<br><br>";
		}
	?>
	<form enctype="multipart/form-data" action="add_pdf.php" method="post">
	<label for="title"><strong>A title of the book: (Only 30 chars) </strong></label><br>	
	<input name="title" type="text" maxlength="30"><br><br>
	<label for="file"><strong>File Path: (File name Only 100 chars)</strong></label><br>
	<input type="file" name="file" id="file"><br><br>	
	<input type="submit" name="submit_button" value="Upload" id="submit_button">
	</form>
<?php
include('footer.html');
?>
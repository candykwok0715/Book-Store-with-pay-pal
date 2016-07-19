<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$file = $_POST['file_name'];
		header('Content-Type: application/pdf');
		header("Content-Disposition: attachment; filename=$file");
		readfile("pdfs/".$file);
}
?>

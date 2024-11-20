<?php 
	$con = mysqli_connect("localhost", "root","", "dct-ccs-finals");

	if ($con == false) {
		die("ERROR: Could not connect" . mysqli_connect_error());
	}
?>
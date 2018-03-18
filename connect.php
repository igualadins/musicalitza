<?php

	// Localhost
	$dbConnection = @new PDO("mysql:dbname=musicalitza;host=localhost", "root", "");

	// Hosting online
	//$dbConnection = @new PDO("mysql:dbname=aledislf_musicalitza;host=localhost", "aledislf_musical", "IOC2018admin");

?>
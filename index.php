<!DOCTYPE HTML>
<html>

<head>
    <title>Web en construcción</title>
    <link rel="shortcut icon" href="images/favicon.ico">
    <meta title='Web en construcci&oacute;n'/>
    <meta name="description" content="Web en construcci&oacute;n" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <style type="text/css">
        body
        {
            margin: 0;
            padding: 0;
            background-color: #FFF;
        }
        #imagen 
        {
            margin-left: 35%;
			margin-right: 35%;
			position: relative;
			top: 100px;
			width: 30%;
        }
		
		@media only screen and (max-width: 767px) {
        
			#imagen 
			{	
				margin-left: 20%;
				margin-right: 20%;
				position: relative;
				top: 100px;
				width: 60%;
			}
		
		}
    </style>
</head>
<body>
	<?php 
		$link = mysql_connect('localhost','root','');
		if (!$link) { 
			die('Could not connect to MySQL: ' . mysql_error()); 
		} 
		echo 'Connection OK'; mysql_close($link); 
	?> 
    <img id="imagen" src="images/construccion.png">
</body>
</html>

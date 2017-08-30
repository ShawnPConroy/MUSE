<?php include "./config.php";

if( $_REQUEST['login'] ) {
	userAccountLogin();	
} else if( $_REQUEST['create'] ) {
	userAccountCreate();
} else if( !$_SESSION['loggedIn'] ) {
	// User is not logged in. Direct them to the login page.
	header("Location: " . $muse['APP_URI'] . "index.php");
} 

$skin = $_REQUEST['skin'] ? $_REQUEST['skin'] : "ModernWhite";
?>

<html>

<head>
	<title>MUSE</title>
	<script type="text/javascript" src="javascript/userInterface.js"></script>
	<script type="text/javascript" src="javascript/ajax.js"></script>
	<link type="text/css" rel="stylesheet" href="css/style.css" />
	<link type="text/css" rel="stylesheet" href="css/<?php echo $skin; ?>.css" />
	<link href='http://fonts.googleapis.com/css?family=Patrick+Hand|Alike|Cinzel:700' rel='stylesheet' type='text/css'>
</head>

<body onLoad="initialize();">


	<div id="contains">
		<p id="location" class="header location">nowhere</p>

		<div class="helperList">
			<div class="header">People</div>
			<ul id="users" class="helperList">
			</ul>
		</div>
		
		<div class="helperList">
			<div class="header">Objects</div>
			<ul id="objects" class="helperList">
			</ul>
		</div>
		
		<div class="helperList">
			<div class="header">Exits</div>
			<ul id="exits" class="helperList">
			</ul>
		</div>
		
		
	</div>
	
	<div id="inventoryDiv" class="helperList">
		<div class="header">Invetory</div>
		<ul id="inventory" class="helperList">
		</ul>
	</div>
	
	<div id="log">
	You wake up suddenly and look around.<br>
	</div>
	
	<div id="actionBar">
		<!-- <button type="button" onclick="action('full-update')">Full-update</button>
		<button type="button" onclick="action('look')">look</button>
		<button type="button" onclick="action('take')">take</button>
		<button type="button" onclick="action('ping')">Ping</button>
		<button type="button" onclick="action('info')">info</button>
		---
		<button type="button" onclick="action('turbo lift')">Turbo Lift</button>
		<button type="button" onclick="action('bridge')">Bridge</button>
		---
		<button type="button" onclick="action('QUIT')">QUIT</button>
		<br/> -->
		<input id="actionInput" type="text" onkeypress="actionInput(event)"/>
	</div>

	<div id="appHeader">MUSE v0.1.0 (Alpha 1)</div>
	<div id="appMenu">Skins (<a href="?skin=ModernWhite">Modern White<a/> - <a href="?skin=ClassicBlack">Classic Black</a> - <a href="?skin=UglyAdventure">Ugly Adventure</a>) - About - Help</div>
	
</body>
<!DOCTYPE html>
<html>
<head>
	<meta name="autor" value="Marko Briški" />
	<meta name="datum" content="24.11.2018"/>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="marko.css">
</head>
<body>
<?php
if(session_id()=="")session_start();
$page=basename($_SERVER["SCRIPT_NAME"]);
$path=$_SERVER['REQUEST_URI'];
$activeUser=0;
$activeUserId=0;
$activeUserType=-1;
?>
<?php
if(isset($_SESSION['activeUser']))
{
	$activeUserId=$_SESSION['activeUserId'];
	$activeUser=$_SESSION['activeUser'];
	$activeUserName=$_SESSION['activeUserName'];
	$activeUserType=$_SESSION['activeUserType'];
}
?>
<nav class="navbar navbar-expand-lg navbar-light bg-primary">
  <a class="navbar-brand" href="index.php">Korisnička podrška IWA 2017/2018</a>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
      	 <?php if($activeUserType==-1) { ?>
        <a class="nav-link" href="loginkorisnika.php">Prijava</a>
         <?php } ?>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="popistvrtki.php">Popis tvrtki</a>
      </li>
      <li class="nav-item">
      	 <?php if($activeUserType==0) { ?>
        <a class="nav-link" href="unosPregledTvrtki.php">Unos i pregled tvrtki </a>
        <?php } ?>
      </li>
      <li class="nav-item">
      	<?php if($activeUserType==0){ ?>
      		<a class="nav-link" href="unosPregledKorisnika.php">Unos i pregled korisnika</a>
      	<?php } ?>
      </li>
      <li class="nav-item">
      	 <?php 
		if(isset($_SESSION ['zaposlenik_id']) || isset($_SESSION['modID']))
			{ ?>

				<?php if($activeUserType==2 && !empty($_SESSION['zaposlenik_id']))
				{ ?>
        <a class="nav-link" href="mojaTvrtka.php">Moja tvrtka</a>
        <?php } ?>
      </li>
      <li class="nav-item">
      	<?php if($activeUserType==1) { ?>
        <a class="nav-link" href="mojaTvrtka.php">Moja tvrtka</a>
        	<?php } ?>
	  <?php } ?>
      </li>
      <li class="nav-item">
      	<?php if($activeUserType!=-1) { ?>
        <a class="nav-link" href="odjava.php">Odjava</a>
        <?php } ?>
      </li>
    </ul>
     <span class="navbar-text" id="tekstHeader">
      <?php if(isset($_SESSION['activeUser']))
      {
      	echo "<h5>Prijavljeni ste kao: ".$_SESSION['activeUser']."</h5>";
		echo "<h5>Prava: ".$_SESSION['tipNaziv']."</h5>";
		if(isset($_SESSION ['zaposlenik_id']) || isset($_SESSION['modID']))
			{
				echo "<h5>Preostali odgovori: ".$_SESSION['preostaliOdgovori']."</h5>";
			}
	  }
	  else
	  {
	   echo "<h5>Niste prijavljeni!</h5>";
	  }
	 ?>
    </span>
  </div>
</nav>
    

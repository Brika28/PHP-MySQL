<?php
if(session_id()=="")session_start();
$page=basename($_SERVER["SCRIPT_NAME"]);
$path=$_SERVER['REQUEST_URI'];
$activeUser=0;
$activeUserId=0;
$activeUserType=-1;

if(isset($_SESSION['activeUser']))
{
	$activeUser=$_SESSION['activeUser'];
	$activeUserName=$_SESSION['activeUserName'];
	$activeUserType=$_SESSION['activeUserType'];
	$activeUserId=$_SESSION['activeUserId'];

}



if(isset($_SESSION['activeUser']))
	{

		echo "<div style='float: right;'>";
		echo "<h3>Prijavljeni ste kao: ".$_SESSION['activeUser']."</h3>";
		echo "<h3>Prava: ".$_SESSION['tipNaziv']."</h3>";
		echo"</div>"; 
	}
	else
	{
		echo "<h3 style='float: right;'>Niste prijavljeni!</h3>";
	}

?>
<a href="index.php"> Home </a>
<?php if($activeUserType==-1) 
{ ?>
	<a href="loginkorisnika.php"> Prijava </a>
<?php 
}
 if($activeUserType!=-1) { ?>
	<a href="odjava.php"> Odjava </a>
<?php } ?>
<a href="popistvrtki.php"> Tvrtke </a>
<?php if($activeUserType==0)
{ ?>
	<a href="dodajTvrtku.php"> Dodaj tvrtku </a>
<?php 
} ?>

<?php if($activeUserType==2)
{ ?>
	<a href="mojaTvrtka.php"> Moja tvrtka </a>
<?php } ?>


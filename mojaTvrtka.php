<?php
include("header.php");
include("baza.php");

$activeUserId=$_SESSION['activeUserId'];

if (isset($_GET['activeUserId'])) 
{              
	$_SESSION['activeUserId'] = $_GET['activeUserId']; 
}

$connect=connectDB();
$checkEmployee="SELECT zaposlenik.korisnik_id, korisnik.korisnik_id, korisnik.ime
					FROM zaposlenik
					INNER JOIN korisnik ON zaposlenik.korisnik_id = korisnik.korisnik_id
					WHERE zaposlenik.korisnik_id = ".$_SESSION['activeUserId'];

$resultEmployee=queryDb($connect,$checkEmployee);

if($resultEmployee)
{
	if(mysqli_num_rows($resultEmployee) !=0)
	{
		list($employed) = mysqli_fetch_array($resultEmployee);
		$_SESSION['employed']=$employed;
	}
}


if($_SESSION['activeUserType']==2)
{
	$connect = connectDB();
	$query ="SELECT pitanje.pitanje_id,pitanje.naslov
				FROM pitanje 
        		INNER JOIN tvrtka ON tvrtka.tvrtka_id = pitanje.tvrtka_id
       			INNER JOIN zaposlenik ON zaposlenik.tvrtka_id = pitanje.tvrtka_id
       			WHERE korisnik_id = '$activeUserId'";

	$result=queryDB($connect,$query);


	if(mysqli_num_rows($result) >= 0)
		{

			echo "<h2> Popis pitanja</h2>";
			echo "<table border ='1'>";
			echo "<thead>";
			echo "<tr>";
			echo "<th>Naslov</th>";
			echo "</tr>";
			echo "</thead>";
			echo "<tbody>";

				while(list($pitanjeId,$naslov)=mysqli_fetch_row($result))
				{
					echo "<tr>";
					echo "<td><a href='detaljiPitanja.php?id=$pitanjeId'>".$naslov."</a></td>";
					echo "</tr>";
				}
				echo "</tbody>";
				echo "</table>";
		}
disconnectDB($connect);
}
else if($_SESSION['activeUserType']==1)
{
	$connect=connectDB();
	$query="SELECT pitanje.pitanje_id,pitanje.naslov, tvrtka.moderator_id
		FROM pitanje 
        INNER JOIN tvrtka ON tvrtka.tvrtka_id = pitanje.tvrtka_id
       	WHERE tvrtka.moderator_id = '$activeUserId'";


	$result=queryDB($connect,$query);


	if(mysqli_num_rows($result) >= 0)
		{

			echo "<h2> Popis pitanja</h2>";
			echo "<table border ='1'>";
			echo "<thead>";
			echo "<tr>";
			echo "<th>Naslov</th>";
			echo "</tr>";
			echo "</thead>";
			echo "<tbody>";

				while(list($pitanjeId,$naslov)=mysqli_fetch_row($result))
				{
					echo "<tr>";
					echo "<td><a href='detaljiPitanja.php?id=$pitanjeId'>".$naslov."</a></td>";
					echo "</tr>";
				}
				echo "</tbody>";
				echo "</table>";
		}

disconnectDB($connect);

}
?>

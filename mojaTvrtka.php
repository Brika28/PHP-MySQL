<?php 
include("header.php");
include("baza.php");

$activeUserId=$_SESSION['activeUserId'];

if (isset($_GET['activeUserId'])) 
{              
$_SESSION['activeUserId'] = $_GET['activeUserId']; 
}

$connect = connectDB();
$query ="SELECT pitanje.pitanje_id,naslov,datum_vrijeme_pitanja,pitanje.tekst,slika,video,pitanje.tvrtka_id, tvrtka.tvrtka_id,preostaliOdgovori,
		zaposlenik.tvrtka_id,korisnik_id
		FROM pitanje 
        INNER JOIN tvrtka ON tvrtka.tvrtka_id = pitanje.tvrtka_id
       	INNER JOIN zaposlenik ON zaposlenik.tvrtka_id = pitanje.tvrtka_id
       	WHERE korisnik_id = '$activeUserId'";


$result1=queryDB($connect,$query);

if(mysqli_num_rows($result1) >= 0)
{


	echo "<h2> Popis pitanja</h2>";
	echo "<table border ='1'>";
	echo "<thead>";
	echo "<tr>";
	echo "<th>Naslov</th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";

		while(list($pitanjeId,$naslov)=mysqli_fetch_row($result1))
		{
			echo "<tr>";
			echo "<td><a href='detaljiPitanja.php?id=$pitanjeId'>".$naslov."</a></td>";
			echo "</tr>";
		}
		echo "</tbody>";
		echo "</table>";
}

disconnectDB($connect);
?>
